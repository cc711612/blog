<?php

namespace App\Admin\Controllers;

use App\Models\Entities\UserEntity;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;
use Carbon\Carbon;
use Illuminate\Support\Arr;

class UserController extends AdminController
{
    /**
     * @var string
     */
    protected $title = 'UserEntity';

    /**
     * @return \Encore\Admin\Grid
     * @Author: Roy
     * @DateTime: 2022/4/29 下午 09:50
     */
    protected function grid()
    {
        $grid = new Grid(new UserEntity());
        $grid->model()->orderByDesc('id');
        $grid->column('id', __('Id'));
        $grid->column('name', __('Name'));
        $grid->column('email', __('Email'));
        $grid->column('images', 'Images')->display(function ($images) {
            return sprintf('<img src="%s" style="width: 10rem" onerror="default_user(this)">', Arr::get($images, 'cover'));
        });
        $grid->column('updated_at', 'Updated at')->display(function ($updated_at) {
            return Carbon::parse($updated_at)->toDateTimeString();
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
        $show = new Show(UserEntity::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('name', __('Name'));
        $show->field('email', __('Email'));
        $show->field('introduction', __('Introduction'));
        $show->field('images.cover', __('Images'));
        return $show;
    }

    /**
     * @return \Encore\Admin\Form
     * @Author: Roy
     * @DateTime: 2022/4/29 下午 09:50
     */
    protected function form()
    {
        $form = new Form(new UserEntity());

        $form->text('name', __('Name'));
        $form->email('email', __('Email'));
        $form->ckeditor('introduction', __('Introduction'));
        $form->embeds('images', function ($form) {
            $form->image('cover', __('cover'))->removable();
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
