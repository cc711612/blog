<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requesters\Api\ImageStoreRequester;
use App\Http\Validators\Api\ImageStoreValidator;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;



class ImageController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // 圖片檔案過大
        if ($request->file('uploadedFile')->getError() == 1) {
            return response()->json([
                'status'  => false,
                'message' => '檔案最大為 3000 KB。',
            ]);
        }
        $Requester = new ImageStoreRequester($request);

        // 進行資料整理與資料驗證
        $Validate = (new ImageStoreValidator($Requester))->validate();

        if ($Validate->fails() === true) {
            return response()->json([
                'status'   => false,
                'messages' => $Validate->errors(),
            ]);
        }

        $imageName = Arr::get($Requester,'file_name');
        #存檔
        $image = request()->uploadedFile->move($this->getSavePath(), $imageName);
        $image_info = $this->getImageSizeFormat(sprintf('%s/%s',$this->getSavePath(),$imageName));
        //檢查
        if (empty($image)) {
            return response()->json([
                'status'  => false,
                'message' => '上傳發生錯誤，請黎落管理員',
            ]);
        }
        return response()->json(
            [
                'status' => true,
                'url'    => $this->getImagePath($imageName),
                'width'  => Arr::get($image_info,'width'),
                'height' => Arr::get($image_info,'height'),
            ]
        );
    }

    /**
     * @return string
     * @Author: Roy
     * @DateTime: 2021/8/7 下午 01:53
     */
    public function getSaveDatePath()
    {
        return  sprintf('\%s\%s\%s',date("Y"),date("m"),date('d'));
    }

    /**
     * @return string
     * @Author: Roy
     * @DateTime: 2021/8/7 下午 01:54
     */
    public function getUrlDatePath()
    {
        return  sprintf('%s/%s/%s/',date("Y"),date("m"),date('d'));
    }

    /**
     * @param  string  $imageName
     *
     * @return string
     * @Author: Roy
     * @DateTime: 2021/8/7 下午 01:53
     */
    public function getImagePath(string $imageName)
    {
        return sprintf('%s%s%s%s',$_SERVER["HTTP_HOST"],config('filesystems.disks.images.url'),$this->getUrlDatePath(),$imageName);
    }

    /**
     * @param  string  $file_path
     *
     * @return array
     * @Author: Roy
     * @DateTime: 2021/8/7 下午 02:08
     */
    private function getImageSizeFormat(string $file_path)
    {
        [$width, $height] = getimagesize($file_path);
        return compact('width', 'height');
    }

    /**
     * @return string
     * @Author: Roy
     * @DateTime: 2021/8/7 下午 02:28
     */
    private function getSavePath()
    {
        return sprintf('%s%s',config('filesystems.disks.images.root'),$this->getSaveDatePath());
    }
}
