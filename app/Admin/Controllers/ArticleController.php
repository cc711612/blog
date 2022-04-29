<?php

namespace App\Admin\Controllers;

use App\Models\Entities\ArticleEntity;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Carbon\Carbon;
class ArticleController extends AdminController
{
    /**
     * @var string
     */
    protected $title = '文章';

    /**
     * @return \Encore\Admin\Grid
     * @Author: Roy
     * @DateTime: 2022/4/29 下午 09:50
     */
    protected function grid()
    {
        $grid = new Grid(new ArticleEntity());
        $grid->model()->orderByDesc('id');
        $grid->column('id', __('id'));
        $grid->column('user_id', __('uuid'));
        $grid->column('title', __('Title'));
        $grid->column('content', '內容')->display(function ($content) {
            return Str::limit(strip_tags($content),50);
        });
        $grid->column('status', '上下架')->display(function ($status) {
            return $status ? '上架' : '下架';
        });
        $grid->column('created_at', 'Created at')->display(function ($created_at) {
            return Carbon::parse($created_at)->toDateTimeString();
        });
        $grid->column('updated_at', 'Updated at')->display(function ($updated_at) {
            return Carbon::parse($updated_at)->toDateTimeString();
        });
        $grid->filter(function ($filter) {
            // 设置created_at字段的范围查询
//            $filter->between('created_at', 'Created Time')->datetime();
        });
        return $grid;
    }

    /**
     * @param $id
     *
     * @return \Encore\Admin\Show
     * @Author: Roy
     * @DateTime: 2022/4/29 下午 09:50
     */
    protected function detail($id)
    {
        $show = new Show(ArticleEntity::findOrFail($id));
        $show->field('id', __('Id'));
        $show->field('title', __('Title'));
        $show->content()->unescape();

        return $show;
    }

    /**
     * @return \Encore\Admin\Form
     * @Author: Roy
     * @DateTime: 2022/4/29 下午 09:50
     */
    protected function form()
    {
        $form = new Form(new ArticleEntity());

        $form->hidden('user_id')->default(32)->rules('required');
        $form->textarea('title', __('Title'))->rules('required');
        $form->ckeditor('content', __('Content'));
        $form->switch('status', '上架');
        $form->embeds('seo', function ($form) {
            $form->textarea('keyword', __('Keyword'));
            $form->textarea('description', __('Description'));
        });
        $form->tools(function (Form\Tools $tools) {
            // 去掉`列表`按钮
            $tools->disableList();

            // 去掉`删除`按钮
            $tools->disableDelete();

            // 去掉`查看`按钮
//            $tools->disableView();
        });
        $form->footer(function ($footer) {
            // 去掉`重置`按钮
            $footer->disableReset();
            // 去掉`查看`checkbox
            $footer->disableViewCheck();
            // 去掉`继续编辑`checkbox
            $footer->disableEditingCheck();
            // 去掉`继续创建`checkbox
            $footer->disableCreatingCheck();
//            // 去掉`提交`按钮
//            $footer->disableSubmit();
        });


        return $form;
    }

}
