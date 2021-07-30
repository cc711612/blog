<?php

/**
 * 方便除錯
 */

use App\Helpers\Minify_HTML;

ini_set('xdebug.var_display_max_depth', 10);
ini_set('xdebug.var_display_max_children', 256);
ini_set('xdebug.var_display_max_data', 1024);
// 關閉 var_dump html 高亮度格式化，需要除錯請用 dd or dump
ini_set("xdebug.overload_var_dump", "off");

/**
 * [isJSON 判斷字串是否為 Json]
 *
 * @Author    Boday
 * @DateTime  2017-09-30T15:24:08+0800
 *
 * @param     [type]                    $string  [description]
 *
 * @return    boolean                            [description]
 */
function isJSON($string)
{
    return is_string($string) && is_array(json_decode($string,
        true)) && (json_last_error() == JSON_ERROR_NONE) ? true : false;
}

/**
 * [getCollectDataMatch 判斷Collect裡面第一層的欄位正確性]
 *
 * @Author    Boday
 * @DateTime  2017-08-26T00:49:21+0800
 *
 * @param     [type]                    $Collection  [description]
 * @param     [type]                    $Name        [description]
 * @param     [type]                    $Value       [description]
 *
 * @return    [type]                                 [description]
 */
function getCollectDataMatch($Collection, $Name, $Value)
{
    return (isset($Collection) && $Collection->get($Name) == $Value);
}

/**
 * [get_service_domain_flag 主要用來獲取當前網域 匹配 /config/route.php 裡面的哪一個規則 keyname]
 *
 * @Author    Boday
 * @DateTime  2018-05-24T14:53:06+0800
 * @return    [type]                    [description]
 */
function get_service_domain_flag()
{
    // 判斷是否有建構與設定 Config
    $has_config_route = Config::has('route.list') == false || Config::has('route.default') == false;

    if ($has_config_route) {
        throw new Exception('請檢查你的 /config/route.php 的設定。', 999999);
    }

    // 抓取當前網域的 Service Domain Flag
    $flag = collect(Config::get('route.list'))->where('domain', Request::getHost())->keys()->all();
    if (isset($flag['0'])) {
        return $flag['0'];
    }

    return Config::get('route.default');

}

/**
 * [route_uri description]
 *
 * @Author    Boday
 * @DateTime  2018-03-08T15:32:19+0800
 *
 * @param  string  $name  [description]
 * @param  array|null  $arguments  [description]
 *
 * @return    [type]                                [description]
 */
function route_uri(string $name, array $arguments = null)
{
    $route_arguments = [
    ];

    if ($arguments) {
        $route_arguments = array_merge($route_arguments, $arguments);
    }

    return call_user_func('route', $name, $route_arguments, false);
}

/**
 * [byte_size_to_readable description]
 *
 * @Author    Boday
 * @DateTime  2017-11-28T15:54:27+0800
 *
 * @param     [type]                    $size  [description]
 *
 * @return    [type]                           [description]
 */
function byte_size_to_readable($size)
{
    $base = log($size) / log(1024);
    $suffix = ['', 'KB', 'MB', 'GB', 'TB'];
    $f_base = floor($base);
    return round(pow(1024, $base - floor($base)), 1).$suffix[$f_base];
}

/**
 * [readable_size_to_byte description]
 *
 * @Author    Boday
 * @DateTime  2017-11-28T16:02:43+0800
 *
 * @param     [type]                    $size  [description]
 *
 * @return    [type]                           [description]
 */
function readable_size_to_byte($size)
{
    preg_match('/([0-9]+)([K|M|G|T|P])?/', str_replace(',', '', strtoupper($size)), $matches);
    if (empty($matches)) {
        return 0;
    }
    $pows = ['K' => 1, 'M' => 2, 'G' => 3, 'T' => 4, 'P' => 5];
    return isset($matches['2']) ? ($matches['1'] * pow(1024, $pows[$matches['2']])) : $matches['1'];
}

/**
 * [remove_many_slash 移除多餘的反斜線]
 *
 * @Author    Boday
 * @DateTime  2017-12-04T17:12:54+0800
 *
 * @param  string  $value  [description]
 *
 * @return    [type]                            [description]
 */
function remove_many_slash(string $value)
{
    return preg_replace('/(\/[\/]+)/', '/', $value);
}

/**
 * [get_collaborator description]
 *
 * @Author    Boday
 * @DateTime  2018-01-17T13:58:33+0800
 *
 * @param  string  $route_name  [description]
 *
 * @return    [type]                                 [description]
 */
function get_collaborator(string $route_name)
{
    $namespace = implode('|',
        array_reduce(config('route'), function ($carry, $item) {
            if ($item['name']) {
                $carry[$item['name']] = $item['name'];
            }
            return $carry;
        })
    );

    $route_name = preg_replace(sprintf('/^(%s)\./', $namespace), '', $route_name);
    $route_name = substr($route_name, 0, strrpos($route_name, '.'));

    return $route_name;
}

/**
 * [getSelect description]
 *
 * @Author    Boday
 * @DateTime  2017-10-23T15:16:34+0800
 *
 * @param  int|null  $Selected
 * @param  array|null  $CustomSelectOption
 * @param  array|null  $Option
 *
 * @return array
 */
function getSelect(int $Selected = null, array $CustomSelectOption = null, array $Option = null)
{
    // 設定 Select Option 的內容
    $SelectOptions = (isset($CustomSelectOption) && is_array($CustomSelectOption)) ? $CustomSelectOption : SupportAbstract::getAllStatus();

    $SelectValue = '';

    //額外參數設定
    if (isset($Option)) {
        foreach ($Option as $Key => $Value) {
            switch ($Key) {
                //設定Select的Value欄位
                case 'select_value':
                    $SelectValue = $Value;
                    break;
            }
        }
    }

    /*if (empty($Selected)) {
    return $SelectOptions;
    }*/

    foreach ($SelectOptions as $Key => $Rows) {
        $SelectOptions[$Key]['value'] = ($SelectValue) ? $Rows[$SelectValue] : $Key;
        $SelectOptions[$Key]['selected'] = ($SelectOptions[$Key]['value'] == $Selected) ? 'selected' : '';
        $SelectOptions[$Key]['checked'] = ($SelectOptions[$Key]['value'] == $Selected) ? 'checked' : '';
    }

    return $SelectOptions;
}

/**
 * @param  string  $password
 *
 * @return string
 * @Author  : daniel
 * @DateTime: 2018/7/27 上午11:36
 */
function make_password(string $password)
{

    return hash('sha256', sprintf('%s%s', $password, config('app.key')));
}

/**
 * [驗證 google re captcha 暫時先放這]
 *
 * @param $response
 *
 * @return mixed
 * @Author  : daniel
 * @DateTime: 2018/8/13 上午10:39
 */

function verify_captcha($recaptcha)
{
    $PostData = [
        'secret'   => config('social.google.recaptcha.secret_key'),
        'response' => $recaptcha,
    ];
    $CurlConnect = curl_init();
    $CurlOption = [
        CURLOPT_HEADER         => 'true',
        CURLOPT_RETURNTRANSFER => 1,
        CURLOPT_URL            => 'https://www.google.com/recaptcha/api/siteverify',
        CURLOPT_CUSTOMREQUEST  => 'POST',
        CURLOPT_POSTFIELDS     => $PostData,
    ];
    curl_setopt_array($CurlConnect, $CurlOption);
    $Result = curl_exec($CurlConnect);
    curl_close($CurlConnect);
    return json_decode($Result, true);
}

function Base62Encode($Origin)
{
    // 為符合手機操作 移除大寫
    $Base62Chars = '0123456789abcdefghijklmnopqrstuvwxyz';
    $BaseLength = strlen($Base62Chars);
    //基準值(請勿亂動)
    $BaseValue = '4000000000';
    //偏移值(請勿亂動)
    $BaseDiff = '5000';

    $Result = '';
    $Origin = $BaseValue + ($Origin * $BaseDiff);
    while ($Origin > 0) {
        $iii = $Origin % $BaseLength;
        $Result = $Base62Chars[$iii].$Result;
        $Origin = ($Origin - $iii) / $BaseLength;
    }
    return $Result;
}

function Base62Decode($Origin)
{
    // 為符合手機操作 移除大寫
    $Base62Chars = '0123456789abcdefghijklmnopqrstuvwxyz';
    $BaseLength = strlen($Base62Chars);
    //基準值(請勿亂動)
    $BaseValue = '4000000000';
    //偏移值(請勿亂動)
    $BaseDiff = '5000';

    $OriginLength = strlen($Origin);
    $Result = 0;
    $arr = array_flip(str_split($Base62Chars));
    for ($iii = 0; $iii < $OriginLength; $iii++) {
        if (!isset($arr[$Origin[$iii]])) {
            return -1;
        }
        $Result += $arr[$Origin[$iii]] * pow($BaseLength, $OriginLength - $iii - 1);
    }
    $Result = ($Result - $BaseValue) / $BaseDiff;
    return $Result;
}

/**
 * @param  array  $sources
 * @param  array  $defaults
 *
 * @return array
 * @Author  : boday
 * @DateTime: 2018-12-25 09:03
 */
function array_merge_default(array $sources, array $defaults): array
{
    return array_replace($defaults, array_intersect_key(
            array_filter($sources, function ($value) {
                return !is_null($value);
            }),
            $defaults)
    );
}

/*
 * 取得預設圖片
 */
function getDefaultImage(string $size = '200x200')
{
    return sprintf('https://via.placeholder.com/%s/EFEFEF/AAAAAA&text=%s', $size,$size);
}

/**
 * 移除多餘註解
 *
 * @param  string  $blade_path
 * @param  array  $option
 *
 * @return string
 * @Author  : steatng
 * @DateTime: 2020/5/20 14:25
 */
function mini_view(string $blade_path, array $option = [])
{
    return Minify_HTML::minify(view($blade_path, $option));
}

/**
 * @param  string|null  $filename
 *
 * @return string
 * @Author: Roy
 * @DateTime: 2021/3/2 下午 05:31
 */
#取得音檔路徑
function getAudioPath(string $filename = null)
{
    if (is_null($filename) === true || empty($filename) === true) {
        return '';
    }
    #s3
    if (is_null(env('APP_STORAGE_NAME')) === false && is_null(env('MEDIA_AUDIO_LINK')) === false) {
        return sprintf('%s%s', env('MEDIA_AUDIO_LINK'), $filename);
    }
    return sprintf('%s/%s', config('filesystems.disks.audio.url'), $filename);
}

/**
 * @param  string|null  $filename
 *
 * @return string
 * @Author: Roy
 * @DateTime: 2021/3/2 下午 05:31
 */
#取得教材檔路徑
function getHandoutPath(string $filename = null)
{
    if (is_null($filename) === true || empty($filename) === true) {
        return '';
    }
    #s3
    if (is_null(env('APP_STORAGE_NAME')) === false && is_null(env('MEDIA_HANDOUT_LINK')) === false) {
        return sprintf('%s%s', env('MEDIA_HANDOUT_LINK'), $filename);
    }
    return sprintf('%s/%s', config('filesystems.disks.handout.url'), $filename);
}

/**
 * @param  string|null  $Number
 * @param  string|null  $Area_code
 *
 * @return string|null
 * @Author: Roy
 * @DateTime: 2021/3/17 下午 03:31
 */
function setPhoneNumber(string $Area_code = null, string $Number = null)
{
    #處理區碼塞入db時的function
    if (is_null($Area_code)) {
        return $Number;
    }
    return sprintf("%s-%s", $Area_code, $Number);
}

/**
 * @param  string|null  $Number
 *
 * @return array
 * @Author: Roy
 * @DateTime: 2021/3/17 下午 03:40
 */
function HandlePhoneNumber(string $Number = null)
{
    #預設
    $Result = [
        'area_code' => null,
        'value'     => $Number,
    ];
    if (strstr($Number, '-') === false) {
        #判斷沒有區碼
        return $Result;
    }
    #處理區碼
    $Handle = explode('-', $Number);
    return [
        'area_code' => $Handle[0],
        'value'     => $Handle[1],
    ];
}

/**
 * @param  string|null  $Number
 *
 * @Author: Roy
 * @DateTime: 2021/3/17 下午 03:43
 */
function getPhoneNumber(string $Number = null)
{
    return Arr::get(HandlePhoneNumber($Number), 'value', null);
}

/**
 * @param  string|null  $Number
 *
 * @Author: Roy
 * @DateTime: 2021/3/17 下午 03:43
 */
function getPhoneNumberAreaCode(string $Number = null)
{
    return Arr::get(HandlePhoneNumber($Number), 'area_code', null);
}

/**
 * @return array
 * @Author  : daniel
 * @DateTime: 2021/7/1 下午2:57
 */
function getLastUpdateTime()
{
    $result = [];
    $now = \Carbon\Carbon::now();
    $date = $now->toDateString();
    $hour = $now->hour;
    $minutes = $now->minute;
    //算到五分整
    if($minutes > 0){
        $minutes = floor($minutes/5)*5;
    }

    $result['watch_report'] = \Carbon\Carbon::createFromFormat('Y-m-d H:i',sprintf('%s %s:%02d',$date,$hour, $minutes));

    if($now->minute > $minutes){
        $minutes++;
        $result['daily_report'] = \Carbon\Carbon::createFromFormat('Y-m-d H:i',sprintf('%s %s:%02d',$date,$hour, $minutes));
    }else{
        $result['daily_report'] = \Carbon\Carbon::createFromFormat('Y-m-d H:i',sprintf('%s %s:%02d',$date,$hour, $minutes))->subMinutes(4);
    }

    return $result;
}
