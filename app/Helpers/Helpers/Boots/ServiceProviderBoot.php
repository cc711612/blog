<?php
/**
 * ServiceProviderBoot.php
 */

/**
 * [throwException description]
 * @Author    Boday
 * @DateTime  2017-09-11T16:09:46+0800
 * @return    [type]                    [description]
 */
function throwException()
{
    call_user_func_array(['PHPTeamException', 'throwException'], func_get_args());
}

/**
 * [get_system description]
 * @Author    Boday
 * @DateTime  2017-08-26T00:50:09+0800
 * @param     string                    $namespace  [description]
 * @return    [type]                                [description]
 */
function get_system(string $namespace = '')
{
    if (empty($namespace)) {
        return;
    }
    return System::get($namespace);
}

/**
 * [flush_system description]
 * @Author    Boday
 * @DateTime  2017-10-17T16:22:16+0800
 * @return    [type]                    [description]
 */
function flush_system()
{
    return System::flush();
}

/**
 * [meta description]
 * @Author    Boday
 * @DateTime  2017-09-04T16:04:16+0800
 * @param     [type]                    $RouteName  [description]
 * define('ROUTE_MAIN', 'main') or  define('ROUTE_ADMIN', 'mag01');
 * @return    [type]                                [description]
 */
function meta($RouteName = null)
{
    $Meta = Meta::init($RouteName);
    // 處理 route-name & csrf_token 找不到合適的地方處理 先放在這裡 @Boday
    if (Request::route()) {
        $Meta->set('route-name', Request::route()->getName());
    }
    $Meta->set('csrf-token', csrf_token());
    return $Meta;
}

/**
 * [trail description]
 * @Author    Boday
 * @DateTime  2017-09-07T13:48:36+0800
 * @param     [type]                    $NameSpace  [description]
 * @return    [type]                                [description]
 */
function trail($NameSpace = null)
{
    return Trail::name($NameSpace ?? '');
}

/**
 * [html_link description]
 * @Author    Boday
 * @DateTime  2017-09-14T16:00:59+0800
 * @return    [type]                    [description]
 */
function html_link()
{
    return Html::link();
}

/**
 * [html_script description]
 * @Author    Boday
 * @DateTime  2017-09-15T09:04:41+0800
 * @return    [type]                    [description]
 */
function html_script()
{
    return Html::script();
}

/**
 * [upload_image description]
 * @Author    Boday
 * @DateTime  2018-04-30T10:08:28+0800
 * @return    [type]                    [description]
 */
function upload_image()
{
    return UploadFile::init('image');
}

/**
 * [upload_file description]
 * @Author    Boday
 * @DateTime  2018-04-30T10:08:31+0800
 * @return    [type]                    [description]
 */
function upload_file()
{
    return UploadFile::init('file');
}

/**
 * @return mixed
 * @Author: Roy
 * @DateTime: 2021/2/18 上午 11:31
 */
function upload_audio()
{
    return UploadFile::init('audio');
}

/**
 * @return mixed
 * @Author: Roy
 * @DateTime: 2021/2/19 下午 05:49
 */
function upload_handout()
{
    return UploadFile::init('handout');
}
/**
 * [upload_video description]
 * @Author    Boday
 * @DateTime  2018-04-30T10:08:34+0800
 * @return    [type]                    [description]
 */
function upload_video()
{
    return UploadFile::init('video');
}
