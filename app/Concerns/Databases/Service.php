<?php

namespace App\Concerns\Databases;

use App\Concerns\Databases\Contracts\Cache;
use App\Concerns\Databases\Contracts\Repository;
use App\Concerns\Databases\Contracts\Request;
use App\Concerns\Databases\Contracts\Constants\RootStatus;
use App\Concerns\Databases\Contracts\Services\Service as ServiceContracts;
use App\Concerns\Databases\Contracts\Services\ServiceCache;
use App\Concerns\Databases\Contracts\Services\ServiceCreatedBy;
use App\Concerns\Databases\Contracts\Services\ServiceDraft;
use App\Concerns\Databases\Contracts\Services\ServiceLog;
use App\Concerns\Databases\Contracts\Services\ServicePaginate;
use App\Concerns\Databases\Contracts\Constants\Status;
use App\Concerns\Databases\Requests\DatabaseLogRequest;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use InvalidArgumentException;

/**
 * Class Service
 *
 * @package App\Databases
 * @Author  : daniel
 * @DateTime: 2019-03-13 14:50
 */
abstract class Service implements ServiceContracts
{
    /**
     * @var
     * @Author  : daniel
     * @DateTime: 2019-03-18 11:27
     */
    protected $id;

    /**
     * @var null
     * @Author  : ljs
     * @DateTime: 2019/9/19 上午 10:00
     */
    protected $admin_id = null;
    /**
     * @var
     * @Author  : daniel
     * @DateTime: 2019-03-18 11:27
     */
    protected $page_count = 30;

    /**
     * @param int $id
     *
     * @return \App\Concerns\Databases\Service
     * @Author  : daniel
     * @DateTime: 2019-12-24 11:02
     */
    protected function setId(int $id = 0): self
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return mixed
     * @Author  : daniel
     * @DateTime: 2019-03-18 10:18
     */
    protected function getId()
    {
        return $this->id;
    }

    /**
     * @return mixed
     * @Author  : daniel
     * @DateTime: 2019-03-20 14:20
     */
    protected function getAdminId()
    {
        return $this->admin_id;
    }

    /**
     * @param int $admin_id
     *
     * @return $this
     * @Author  : daniel
     * @DateTime: 2019-03-20 14:23
     */
    public function setAdminId(int $admin_id)
    {
        $this->admin_id = $admin_id;
        return $this;
    }

    /**
     * @return \App\Concerns\Databases\Contracts\Repository
     * @Author  : daniel
     * @DateTime: 2020-05-08 14:47
     */
    abstract protected function getRepository(): Repository;

    /**
     * @return \App\Concerns\Databases\Contracts\Cache
     * @Author  : daniel
     * @DateTime: 2019-07-08 18:44
     */
    abstract protected function getCache(): Cache;

    /**
     * @return \App\Concerns\Databases\Contracts\Services\Service
     * @Author  : daniel
     * @DateTime: 2020-06-17 11:45
     */
    public static function getInstance(): ServiceContracts
    {
        if (app()->has(static::class) === false) {
            app()->singleton(static::class);
        }

        return app(static::class);
    }

    /**
     * @param \App\Concerns\Databases\Contracts\Request|array $Request
     *
     * @return \Illuminate\Database\Eloquent\Model|\Illuminate\Database\Eloquent\Relations\BelongsToMany
     * @Author  : daniel
     * @DateTime: 2019-03-18 11:27
     */
    public function create($Request): Model
    {

        if ($this instanceof ServiceCreatedBy && $this->getAdminId() < 1) {
            throw new InvalidArgumentException('請調用setAdminId傳入管理者Id');
        }

        if ($Request instanceof Request) {
            $Request = $Request->toArray();
        }

        //如果有使用草稿就自動更新草稿
        if ($this instanceof ServiceDraft) {
            $Entity = $this->getDraft();
            $Entity->update($Request);
        } else {
            if ($this instanceof ServiceCreatedBy) {
                $Request = array_merge($Request, [
                    'created_by' => $this->getAdminId(),
                ]);
            }

            $Entity = $this->getRepository()->getEntity()->create(
                $Request
            );
        }

        return $Entity;
    }

    /**
     * [取出沒有包含任何where條件的entity]
     *
     * @param int $id
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     * @Author  : daniel
     * @DateTime: 2019-03-18 11:27
     */
    public function find(int $id)
    {
        return $this->getRepository()->getEntity()->find($id);
    }

    /**
     * @param int $id
     *
     * @return mixed
     * @Author  : daniel
     * @DateTime: 2019-12-24 11:14
     */
    public function findWithRepository(int $id)
    {
        //先設定id
        $this->setId($id);

        if ($this instanceof ServiceCache && app()->runningInConsole() == false) {
            if ($this->getCache()->has()) {
                return $this->getCache()->get();
            }
        }

        $Entity = $this->getRepository()->find($id);

        if ($this instanceof ServiceCache) {
            $this->getCache()->put($Entity);
        }

        return $Entity;
    }

    /**
     * @return \Illuminate\Support\Collection
     * @Author  : daniel
     * @DateTime: 2019-07-16 11:59
     */
    public function get(): Collection
    {
        if ($this instanceof ServiceCache && app()->runningInConsole() == false) {
            // 有就直接回傳
            if ($this->getCache()->has()) {
                return $this->getCache()->get();
            }
        }

        $Collection = $this->getRepository()->get();

        if ($this instanceof ServiceCache) {
            $this->getCache()->put($Collection);
        }

        return $Collection;
    }

    /**
     * @param int $page_count
     *
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     *
     * @Author  : daniel
     * @DateTime: 2019-03-20 15:16
     */
    public function paginate(int $page_count = null): LengthAwarePaginator
    {
        if ($this instanceof ServicePaginate == false) {
            throw new InvalidArgumentException('調用此方法必須implement ServicePaginate');
        }

        if (is_null($page_count)) {
            $page_count = $this->page_count;
        }

//        if ($this instanceof ServiceCache) {
        //            // 有就直接回傳
        //            if ($this->getCache()->has()) {
        //                return $this->getCache()->get();
        //            }
        //        }

        $Collection = $this->getRepository()->getBuilder()->paginate($page_count);

//        if ($this instanceof ServiceCache) {
        //            $this->getCache()->put($Collection);
        //        }

        return $Collection;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Model|\Illuminate\Database\Eloquent\Relations\BelongsToMany
     * @Author  : daniel
     * @DateTime: 2019-07-16 11:54
     */
    public function getDraft()
    {
        if ($this instanceof ServiceDraft == false) {
            throw new InvalidArgumentException('調用此方法必須implement ServiceDraft');
        }

        if ($this instanceof ServiceCreatedBy && $this->getAdminId() < 1) {
            throw new InvalidArgumentException('請調用setAdminId傳入管理者Id');
        }

        $Entity = $this->getRepository()->getEntity();

        $CreateData = [
            'status'      => Status::STATUS_DRAFT,
            'created_by'  => $this->getAdminId(),
        ];

//        if ($this instanceof RootStatus == true) {
//            $Entity = $Entity->RootStatus();
//            $CreateData['root_status'] = RootStatus::ROOT_STATUS_DISABLE;
//        }

        $Entity = $Entity->Draft();

        if ($this instanceof ServiceCreatedBy) {
            $Entity = $Entity->where('created_by', $this->getAdminId());
        }

        $Entity = $Entity
            ->limit(1)
            ->first();

        if (is_null($Entity)) {
            $Entity = $this->getRepository()->getEntity()->create($CreateData);
            $Entity      = $this->find($Entity->id);
            $Entity->new = true;
        }

        return $Entity;
    }

    /**
     * @param int                                     $id
     * @param \App\Concerns\Databases\Contracts\Request|array  $Request
     * @param null                                    $LogRequest
     *
     * @return \Illuminate\Database\Eloquent\Model
     * @Author  : daniel
     * @DateTime: 2019-07-09 15:37
     */
    public function update(int $id, $Request, $LogRequest = null): Model
    {
        $Entity = $this->getRepository()->getEntity()->find($id);

        if ($Entity === null) {
            throw new InvalidArgumentException('你輸入的id找不到任何對應的資料');
        }

        if ($Request instanceof Request) {
            $Request = $Request->toArray();
        }

        //儲存
        $Entity->update($Request);
        //如果有宣告要記錄log
        if ($this instanceof ServiceLog) {

            if ($this instanceof ServiceCreatedBy && $this->getAdminId() < 1) {
                throw new InvalidArgumentException('請調用setAdminId傳入管理者Id');
            }

            if ($Entity->getChanges() != null) {
                if ($LogRequest instanceof Request) {
                    $LogRequest = $LogRequest->toArray();
                }

                if ($LogRequest === null) {
                    $LogRequest = (new DatabaseLogRequest(['updated_by' => $this->getAdminId()]))->toArray();
                }

                $LogRequest['changes'] = $Entity->getChanges();

                $Entity->logs()->create($LogRequest);
            }
        }

        //回傳
        return $Entity;
    }

    /**
     * @param int $id
     *
     * @return bool|null
     * @throws \Exception
     * @Author  : daniel
     * @DateTime: 2020-05-21 11:21
     */
    public function delete(int $id)
    {
        $Entity = $this->getRepository()->getEntity()->find($id);

        if ($Entity === null) {
            throw new InvalidArgumentException('你輸入的id找不到任何對應的資料');
        }

        $Entity->status = Status::STATUS_ARCHIVE;

        if ($this instanceof ServiceCreatedBy) {
            if ($this->getAdminId() < 1){
                throw new InvalidArgumentException('請調用setAdminId傳入管理者Id');
            }
            $Entity->deleted_by = $this->getAdminId();
        }

        //儲存刪除資料
        $Entity->save();

        //執行刪除
        return $Entity->delete();
    }

    /**
     * @param \App\Concerns\Databases\Contracts\Request|array $Where
     * @param \App\Concerns\Databases\Contracts\Request|array $Update
     *
     * @return \Illuminate\Database\Eloquent\Model
     * @Author  : daniel
     * @DateTime: 2019-07-12 15:40
     */
    public function updateOrCreate($Where, $Update)
    {
        if ($Where instanceof Request) {
            $Where = $Where->toArray();
        }

        if ($Update instanceof Request) {
            $Update = $Update->toArray();
        }
        return $this->getRepository()->getEntity()->updateOrCreate(
            $Where,
            $Update
        );
    }

    /**
     * @param string $filed
     *
     * @return mixed
     * @Author  : daniel
     * @DateTime: 2019-08-06 15:46
     */
    public function max(string $filed)
    {
        return $this->getRepository()->getBuilder()->max($filed);
    }

    /**
     * @param array $ids
     *
     * @return \Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Relations\BelongsToMany[]
     * @Author  : daniel
     * @DateTime: 2019-08-06 15:57
     */
    public function getByIds(array $ids)
    {
        return $this->getRepository()
            ->setOrderByRaw(sprintf("field(id,%s)", join($ids, ',')))
            ->getBuilder()
            ->whereIn('id', $ids)->get();
    }

    /**
     * @param array $ids
     *
     * @return \Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Relations\BelongsToMany[]|\Illuminate\Database\Query\Builder[]|\Illuminate\Support\Collection
     * @Author  : ljs
     * @DateTime: 2019/8/29 下午 4:44
     */
    public function getWithoutIds(array $ids)
    {
        return $this->getRepository()
            ->getBuilder()
            ->whereNotIn('id', $ids)
            ->get();
    }
}
