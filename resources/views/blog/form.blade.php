@extends("blog.main")
@section("content")
    <!-- Page Header-->
    <header class="masthead" style="background-image: url('../assets/img/home-bg.jpg')">
        <div class="container position-relative px-4 px-lg-5">
            <div class="row gx-4 gx-lg-5 justify-content-center">
                <div class="col-md-10 col-lg-8 col-xl-7">
                    <div class="page-heading">
                        <h1>{{$Html->heading}}</h1>
                        <span class="subheading">Have idea? Let's talk everyone.</span>
                    </div>
                </div>
            </div>
        </div>
    </header>
    <main class="mb-4">
        <div class="container px-4 px-lg-5">
            <div class="row gx-4 gx-lg-5 justify-content-center">
                <div class="col-md-10 col-lg-8 col-xl-7">
                    <div class="my-5">
                        <form id="form" action="{{$Html->action}}" method="{{$Html->method}}">
                            @method($Html->method)
                            <div class="form-floating">
                                <input class="form-control" id="title" name="title" type="text"
                                       placeholder="Enter your title..." value="{{$Html->title}}">
                                <label for="title">Title</label>
                            </div>
                            <div class="form-floating">
                                <input class="form-control" id="description" name="description" type="text"
                                       placeholder="Enter your description..." value="{{$Html->description}}">
                                <label for="description">seo-description</label>
                            </div>
                            <div class="form-floating">
                                <input class="form-control" id="keyword" name="keyword" type="text"
                                       placeholder="Enter your keyword..." value="{{$Html->keyword}}">
                                <label for="keyword">seo-keyword(ex:laravel,backend)</label>
                            </div>
                            <div class="form-floating">
                                <label for="content">Content</label>
                                <textarea class="form-control" id="content" name="content"
                                          placeholder="Enter your content here..." style="height: 12rem"
                                          data-sb-validations="required">{{$Html->content}}</textarea>
                            </div>
                            <input type="hidden" name="member_token" value="{{$Html->member_token}}">
                            <br/>
                            <div class="d-none" id="submitErrorMessage">
                                <div class="text-center text-danger mb-3">Error sending message!</div>
                            </div>
                            <button class="btn btn-primary text-uppercase" id="submitButton" data-action="submit"
                                    type="button">Send
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </main>
    <script src="https://cdn.tiny.cloud/1/ma5vlvghjz93fthi8wglhda2j2x1jc9jsit28g74jalc6z2z/tinymce/5/tinymce.min.js"
            referrerpolicy="origin"></script>
    <script src="{{asset('/js/tinymce.js')}}"></script>
    <script>
        $(function () {
            let FormElement = $("#form");
            tinymceInit();
            $("button[data-action='submit']").click(function () {
                ajaxLoadingOpen();
                $.post(FormElement.attr('action'), FormElement.serialize(), function (Obj) {
                    ajaxLoadingClose();
                    if (Obj.status !== true) {
                        $.each(Obj.message, function (key, value) {
                            alert(value.join(','));
                        });
                    } else {
                        alert('{{$Html->success_msg}}');
                    }
                    if (Obj.redirect != '' && Obj.redirect != undefined) {
                        location.href = Obj.redirect;
                    }
                });
            });
        });
    </script>
@endsection
