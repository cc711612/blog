<?php

namespace App\Concerns\Commons\Traits;

use App\Caches\Hrs\DashboardCache;
use App\Macros\StoreHrs\Courses\CourseMacro;
use Arr;
use App\Modules\Agreements\Databases\Services\AgreementHrService;
use App\Modules\Clients\Databases\Services\ClientHrService;
use App\Modules\Courses\Databases\Services\CourseHrService;
use App\Concerns\Databases\Contracts\Constants\Status;
use App\Modules\Books\Databases\Services\BookHrService;
use Illuminate\Database\Eloquent\Collection;
use App\Caches\Hrs\DashboardRecourseCache;

/**
 * Trait AgreementDashboardTrait
 *
 * @package App\Concerns\Commons\Traits
 * @Author: Roy
 * @DateTime: 2021/4/23 下午 04:40
 */
trait AgreementDashboardTrait
{
    private $client_id;
    private $AgreementEntity;
    private $Agreement;

    /**
     * @param  int  $client_id
     *
     * @return \App\Caches\Hrs\DashboardCache
     * @Author: Roy
     * @DateTime: 2021/4/23 下午 04:40
     */
    private function getDashboardCache(int $client_id): DashboardCache
    {
        if (app()->has(DashboardCache::class) === false) {
            app()->singleton(DashboardCache::class);
        }

        return app(DashboardCache::class)->setCacheClientId($client_id);
    }

    /**
     * @param  int  $client_id
     *
     * @return \App\Caches\Hrs\DashboardRecourseCache
     * @Author: Roy
     * @DateTime: 2021/4/23 下午 04:40
     */
    private function getDashboardRecourseCache(int $client_id): DashboardRecourseCache
    {
        if (app()->has(DashboardRecourseCache::class) === false) {
            app()->singleton(DashboardRecourseCache::class);
        }

        return app(DashboardRecourseCache::class)->setCacheClientId($client_id);
    }

    /**
     * @param  array  $AgreementEntity
     *
     * @return array|\int[][]
     * @Author  : Roy
     * @DateTime: 2021/3/9 下午 03:08
     */
    public function handleDashboard(array $AgreementEntity)
    {

        if (empty($AgreementEntity)) {
            return [
                'member' => [
                    #合約會員數
                    'default' => 0,
                    #目前會員數
                    'value'   => 0,
                    'percent' => 0,
                ],
                'book'   => [
                    'default' => 0,
                    'value'   => 0,
                    'percent' => 0,
                ],
                'course' => [
                    'default' => 0,
                    'value'   => 0,
                    'percent' => 0,
                ],
            ];
        }

        #全部書籍
        $Books = (new BookHrService())->getAllBookIds();
        #全部課程
        $Courses = (new CourseHrService())->getAllCourseIds();
        #預設值(已上架)
        $AgreementEntity['course']['default'] = $Courses->count();
        $AgreementEntity['book']['default'] = $Books->count();

        #驗證上下架
        $AgreementEntity['book']['value'] = $Books->intersect($AgreementEntity['book']['value'])->count();
        $AgreementEntity['course']['value'] = $Courses->pluck('id')->intersect($AgreementEntity['course']['value'])->count();

        #全訂閱
        if (Arr::get($AgreementEntity, 'book.subscription', false) == true) {
            $AgreementEntity['book']['value'] = $AgreementEntity['book']['default'];
        }
        if (Arr::get($AgreementEntity, 'course.subscription', false) == true) {
            $AgreementEntity['course']['value'] = $AgreementEntity['course']['default'];
        }

        #計算
        $this->AgreementEntity = $AgreementEntity;
        $AgreementEntity['member']['percent'] = $this->getPercent('member');
        $AgreementEntity['course']['percent'] = $this->getPercent('course');
        $AgreementEntity['book']['percent'] = $this->getPercent('book');
        return $AgreementEntity;
    }

    /**
     * @param  string|null  $key
     *
     * @return int
     * @Author  : Roy
     * @DateTime: 2021/3/9 下午 03:05
     */
    private function getPercent(string $key = null): int
    {
        if (arr::get($this->AgreementEntity, sprintf('%s.default', $key), 0) == 0) {
            return 0;
        }
        return round((arr::get($this->AgreementEntity, sprintf('%s.value', $key), 0) / arr::get($this->AgreementEntity,
                    sprintf('%s.default', $key), 0)) * 100);
    }

    /**
     * @param $client_id
     *
     * @return $this
     * @Author  : Roy
     * @DateTime: 2021/3/18 下午 04:58
     */
    public function setClientId($client_id): self
    {
        $this->client_id = $client_id;
        return $this;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Collection
     * @Author  : daniel
     * @DateTime: 2021/4/12 下午2:22
     */
    public function getAgreement(): Collection
    {
        if ($this->Agreement) {
            return $this->Agreement;
        }

        $this->Agreement = (new AgreementHrService())
            ->setRequest(['client_id' => $this->client_id])
            ->getClientAgreement();
//        dd($this->Agreement);
        return $this->Agreement;
    }

    /**
     * @param  int|null  $client_id
     *
     * @return array
     * @Author: Roy
     * @DateTime: 2021/4/13 下午 02:18
     */
    public function getClientAgreement(int $client_id = null): array
    {
//        if ($this->getDashboardCache($client_id)->has()) {
//            return $this->getDashboardCache($client_id)->get();
//        }

        $this->setClientId($client_id);

        $Recourse = $this->handleRecourse();

        $Result = [
            'member'    => (new ClientHrService())->find($client_id)->member_amount,
            'agreement' => $this->getAgreement()->where('status', Status::STATUS_ENABLE)->count(),
            'book'      => count(Arr::get($Recourse, 'book', [])),
            'course'    => count(Arr::get($Recourse, 'course', [])),
        ];

        $this->getDashboardCache($this->client_id)->setParams($Result)->put();

        return $Result;
    }

    /**
     * @param  int|null  $client_id
     *
     * @Author: Roy
     * @DateTime: 2021/6/7 下午 02:30
     */
    private function forgetDashboardCache(int $client_id = null)
    {
        if ($this->getDashboardCache($client_id)->has()) {
            $this->getDashboardCache($client_id)->forget();
        }
        return $this;
    }

    /**
     * @return array|mixed
     * @Author: Roy
     * @DateTime: 2021/4/13 下午 02:18
     */
    private function handleRecourse()
    {

//        if ($this->getDashboardRecourseCache($this->client_id)->has()) {
//            return $this->getDashboardRecourseCache($this->client_id)->get();
//        }

        $Result = [];

        $chapter = $this->getAgreement()->pluck('recourse.chapter')->flatten()->unique()->toArray();
        $course = $this->getAgreement()->pluck('recourse.course')->flatten()->unique()->toArray();
        $book = $this->getAgreement()->pluck('recourse.book')->flatten()->unique()->toArray();

        if ($this->getAgreement()->isEmpty() === false) {
            # 書籍全訂閱
            if ($this->getAgreement()->pluck('recourse')->where('book_subscription', true)->isEmpty() === false) {
                $book = (new BookHrService())->getAllBookIds()->toArray();
            }elseif(!empty($book)){
                $book = (new BookHrService())->checkBookByIds($book)->pluck('id')->toArray();
            }
            # 課程全訂閱
            if ($this->getAgreement()->pluck('recourse')->where('course_subscription', true)->isEmpty() === false) {
                $CourseData = (new CourseMacro())
                    ->getAllCourseAndChapter();
                $course = Arr::get($CourseData, 'course')->toArray();
                $chapter = Arr::get($CourseData, 'chapter')->toArray();
            }elseif(!empty($course)){
                $CourseData = (new CourseMacro())->getCourseAndChapterByIds($course,$chapter);
                $course = Arr::get($CourseData, 'course')->toArray();
                $chapter = Arr::get($CourseData, 'chapter')->toArray();
            }
        }

        $Result['book'] = $book;
        $Result['course'] = $course;
        $Result['chapter'] = $chapter;

        $this->getDashboardRecourseCache($this->client_id)->setParams($Result)->put();

        return $Result;
    }

}
