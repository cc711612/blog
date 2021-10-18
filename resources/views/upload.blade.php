<!DOCTYPE html>

<html>
<meta name="csrf-token" content="{{ csrf_token() }}">
<head>

    <title>Laravel WebSocket 示例</title>

</head>

<body>
<form>
    <input type="file" id="ImageUpload" name="uploadedFile">
    <img src="" id="img">
</form>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="{{ asset('js/app.js') }}"></script>
<script src="{{ asset('js/jquery.html5uploader.min.js') }}"></script>


<script>
    $().ready(function () {
        let ImageUpload = $('#ImageUpload');
        let upload_url = '{{route('api.image.store')}}';
        //上傳
        ImageUpload.html5Uploader({
            // 最大上傳檔案數
            max: 1,
            postUrl: upload_url,
            parameter: {
                'route_name': $('meta[name="route-name"]').attr('content'),
            },
            onClientLoadStart: function (e) {
            },
            onSuccess: function (e, file, Result) {
                Result = JSON.parse(Result);
                console.log(Result);
                if(Result.status){
                    $("#img").attr('src',Result.url)
                }else{
                    alert('error');
                }

                return false;
            }
        });
    })

</script>

</body>


</html>
