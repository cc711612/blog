<?php
/**
 * @Author  : steatng
 * @DateTime: 2020/6/4 10:03
 */

namespace App\Concerns\Commons\Traits;

use App\Concerns\Commons\Abstracts\SupportAbstracts;
use App\Modules\Books\Databases\Entities\BookEntity;
use App\Modules\Courses\Databases\Entities\CourseEntity;
use App\Modules\Teachers\Databases\Entities\TeacherEntity;
use Illuminate\Support\Arr;

/**
 * Trait TagBlockTrait
 *
 * @package App\Concerns\Commons\Traits
 * @Author  : steatng
 * @DateTime: 2020/6/4 10:08
 */
trait TagBlockTrait
{
    /**
     * @param  string  $SelectedCollectionName
     * @param  string  $AllTagCollectionName
     *
     * @return array
     * @Author: Roy
     * @DateTime: 2021/5/20 上午 09:54
     */
    public function handleTagBookSelectFormat(string $SelectedCollectionName, string $AllTagCollectionName)
    {
        return SupportAbstracts::getMultipleSelected(
        //指定已選標籤
            $this->getResource($SelectedCollectionName)->tags->pluck('id')->all(),
            //指定所有標籤列表
            $this->getResource($AllTagCollectionName)->map(function ($Tag) {
                return [
                    'id'     => $Tag->id,
                    'title'  => $Tag->title,
                    'status' => SupportAbstracts::getEnableDisableChecked($Tag->status),
                    'count'  => $Tag->books->count(),
                ];
            })->toArray(),
            //指定對應欄位
            [
                'select_value' => 'id',
                'text'         => 'title',
            ]
        );
    }

    /**
     * @param  string  $SelectedCollectionName
     * @param  string  $AllTagCollectionName
     *
     * @return array
     * @Author: Roy
     * @DateTime: 2021/5/31 上午 09:33
     */
    public function handleTagCourseSelectFormat(string $SelectedCollectionName, string $AllTagCollectionName)
    {
        return SupportAbstracts::getMultipleSelected(
        //指定已選標籤
            $this->getResource($SelectedCollectionName)->tags->pluck('id')->all(),
            //指定所有標籤列表
            $this->getResource($AllTagCollectionName)->map(function ($Tag) {
                return [
                    'id'     => $Tag->id,
                    'title'  => $Tag->title,
                    'status' => SupportAbstracts::getEnableDisableChecked($Tag->status),
                    'count'  => $Tag->courses->count(),
                ];
            })->toArray(),
            //指定對應欄位
            [
                'select_value' => 'id',
                'text'         => 'title',
            ]
        );
    }

    /**
     * @param  string  $SelectedCollectionName
     * @param  string  $AllTagCollectionName
     *
     * @return array
     * @Author  : steatng
     * @DateTime: 2020/6/4 10:08
     */
    public function handleTeacherSelectFormat(string $SelectedCollectionName, string $AllTagCollectionName)
    {
        $Teachers = $this->getResource($AllTagCollectionName);

        return SupportAbstracts::getMultipleSelected(
        //指定已選標籤
            $this->getResource($SelectedCollectionName)->teachers->pluck('id')->all(),
            //指定所有標籤列表
            $Teachers->map(function (TeacherEntity $Teacher) {
                return [
                    'id'     => $Teacher->id,
                    'title'  => $Teacher->teacher_profiles->name,
                    'status' => SupportAbstracts::getEnableDisableChecked($Teacher->status),
                ];
            })->toArray(),
            //指定對應欄位
            [
                'select_value' => 'id',
                'text'         => 'title',
            ]
        );
    }

    /**
     * @param  string  $SelectedCollectionName
     * @param  string  $AllTagCollectionName
     *
     * @return array
     * @Author: Roy
     * @DateTime: 2021/5/31 上午 09:53
     */
    public function handleTeacherBookSelectFormat(string $SelectedCollectionName, string $AllTagCollectionName)
    {
        $Teachers = $this->getResource($AllTagCollectionName);

        return SupportAbstracts::getMultipleSelected(
        //指定已選標籤
            $this->getResource($SelectedCollectionName)->teachers->pluck('id')->all(),
            //指定所有標籤列表
            $Teachers->map(function (TeacherEntity $Teacher) {
                return [
                    'id'     => $Teacher->id,
                    'title'  => $Teacher->teacher_profiles->name,
                    'status' => SupportAbstracts::getEnableDisableChecked($Teacher->status),
                    'count'  => $Teacher->books->count(),
                ];
            })->toArray(),
            //指定對應欄位
            [
                'select_value' => 'id',
                'text'         => 'title',
            ]
        );
    }
    /**
     * @param  string  $SelectedCollectionName
     * @param  string  $AllTagCollectionName
     *
     * @return array
     * @Author: Roy
     * @DateTime: 2021/5/31 上午 09:46
     */
    public function handleTeacherCourseSelectFormat(string $SelectedCollectionName, string $AllTagCollectionName)
    {
        $Teachers = $this->getResource($AllTagCollectionName);

        return SupportAbstracts::getMultipleSelected(
        //指定已選標籤
            $this->getResource($SelectedCollectionName)->teachers->pluck('id')->all(),
            //指定所有標籤列表
            $Teachers->map(function (TeacherEntity $Teacher) {
                return [
                    'id'     => $Teacher->id,
                    'title'  => $Teacher->teacher_profiles->name,
                    'status' => SupportAbstracts::getEnableDisableChecked($Teacher->status),
                    'count'  => $Teacher->courses->count(),
                ];
            })->toArray(),
            //指定對應欄位
            [
                'select_value' => 'id',
                'text'         => 'title',
            ]
        );
    }

    /**
     * @param  string  $SelectedCollectionName
     * @param  string  $AllTagCollectionName
     *
     * @return array
     * @Author  : daniel
     * @DateTime: 2020-06-18 09:45
     */
    public function handleBookSelectFormat(string $SelectedCollectionName, string $AllTagCollectionName)
    {
        $Books = $this->getResource($AllTagCollectionName);

        return SupportAbstracts::getMultipleSelected(
        //指定已選標籤
            $this->getResource($SelectedCollectionName)->books->pluck('id')->all(),
            //指定所有標籤列表
            $Books->map(function (BookEntity $Book) {
                return [
                    'id'     => $Book->id,
                    'title'  => $Book->book_profiles->title,
                    'status' => SupportAbstracts::getEnableDisableChecked($Book->status),
                ];
            })->toArray(),
            //指定對應欄位
            [
                'select_value' => 'id',
                'text'         => 'title',
            ]
        );
    }

    /**
     * @param  string  $SelectedCollectionName
     * @param  string  $AllTagCollectionName
     *
     * @return array
     * @Author  : daniel
     * @DateTime: 2020-06-18 09:45
     */
    public function handleCourseSelectFormat(string $SelectedCollectionName, string $AllTagCollectionName)
    {
        $Course = $this->getResource($AllTagCollectionName);

        return SupportAbstracts::getMultipleSelected(
        //指定已選標籤
            $this->getResource($SelectedCollectionName)->courses->pluck('id')->all(),
            //指定所有標籤列表
            $Course->map(function (CourseEntity $Course) {
                return [
                    'id'     => $Course->id,
                    'title'  => $Course->title,
                    'status' => SupportAbstracts::getEnableDisableChecked($Course->status),
                ];
            })->toArray(),
            //指定對應欄位
            [
                'select_value' => 'id',
                'text'         => 'title',
            ]
        );
    }

    public function handleCategorySelectFormat(string $SelectedCollectionName, string $AllTagCollectionName)
    {
        $Categories = $this->getResource($AllTagCollectionName);

        return SupportAbstracts::getMultipleSelected(
        //指定已選標籤
            $this->getResource($SelectedCollectionName)->categories->pluck('id')->all(),
            //指定所有標籤列表
            $Categories->map(function ($Category) {
                return [
                    'id'     => $Category->id,
                    'title'  => $Category->title,
                    'status' => SupportAbstracts::getEnableDisableChecked($Category->status),
                    'count'  => Arr::get($Category, 'books', collect([]))->count(),
                ];
            })->toArray(),
            //指定對應欄位
            [
                'select_value' => 'id',
                'text'         => 'title',
            ]
        );
    }
}
