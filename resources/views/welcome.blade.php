<!DOCTYPE html>

<html>

<head>

    <title>Laravel WebSocket 示例</title>

</head>

<body>

<div id="div-data">
    <table class="table table-striped table-bordered table-hover" id="sample_1" name="dataTable">
        <thead>
        <tr class="text-center-row">
            <th> ID</th>
            <th> Name</th>
            <th> Email</th>
            <th> Image</th>
        </tr>
        </thead>
        <tbody id="div-body">
        @foreach($Users as $element)
            <tr class="text-center-row">
                <td>{{$element->id}}</td>
                <td>{{$element->name}}</td>
                <td>{{$element->email}}</td>
                <td><img src="{{$element->image}}" style="width: 100px"></td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
<script src="{{ asset('js/app.js') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/mustache.js/3.0.0/mustache.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script type="text/x-mustache" id="template">
<tr class="text-center-row">
        <td>@{{ name }}</td>
        <td>@{{ email }}</td>
        <!-- <td>
            <a href="#" class="btn dark  btn-outline sbold uppercase  ">
                <i class="fa fa-edit"></i> 編輯部門名稱 </a>
        </td> -->
</tr>

</script>
<script>
    window.Echo.channel('EventTriggered')
        .listen('GetRequestEvent', (e) => {
            console.log(e);
            let template = $("#template").html();
            document.querySelector('#div-body').innerHTML += Mustache.render(template, {
                name: e.message.name,
                email: e.message.email
            });
        })
</script>

</body>


</html>
