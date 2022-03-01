@extends("blog.main")
@section("content")
    <!-- Page Header-->
    <header class="masthead" style="background-image: url('../assets/img/home-bg.jpg')">
        <div class="container position-relative px-4 px-lg-5">
            <div class="row gx-4 gx-lg-5 justify-content-center">
                <div class="col-md-10 col-lg-8 col-xl-7">
                    <div class="post-heading">
                        <h1>{{$Html->element->title}}</h1>
                        <h2 class="subheading">{{$Html->element->sub_title}}</h2>
                        <span class="meta">
                            Posted by
                            <a href="{{$Html->element->actions->user_uri}}">{{$Html->element->user_name}}</a>
                            on {{$Html->element->updated_at}}
                        </span>
                        <span id="busuanzi_value_page_pv"></span> Views
                    </div>
                </div>
            </div>
        </div>
    </header>
    <!-- Post Content-->
    <article class="mb-4">
        <div class="container px-4 px-lg-5">
            <div class="share" style="text-align: right!important;">
                <a href="https://www.facebook.com/sharer/sharer.php?u={{\Illuminate\Support\Facades\URL::current()}}" target="_blank" title="facebook分享"><i class="fab fa-2x fa-facebook-square"></i></a>
                <a href="https://social-plugins.line.me/lineit/share?url={{\Illuminate\Support\Facades\URL::current()}}&amp;from=line_scheme&amp;" title="Line分享" target="_blank"><i class="fab fa-2x fa-line text-success mr-1"></i></a>
            </div>


            <div class="row gx-4 gx-lg-5 justify-content-center">
                {!! $Html->element->content !!}
            </div>
        </div>
    </article>
    <article class="mb-8">
        <div class="container px-4 px-lg-5">
            <h2>View Comments</h2>
        </div>
        <div class="comment" id="comments">
            @if($Html->element->comments->isEmpty())
                <div class="container px-4 px-lg-5">
                    <p class="post-meta">
                        No comment! <br>
                        Join us discuss
                    </p>
                </div>
            @else
                @foreach($Html->element->comments as $comment)
                    <div class="container px-4 px-lg-5 comment-element">
                        <div class="post-preview">
                            <span class="post-subtitle">{!! $comment->content !!}</span>
                            <p class="post-meta">
                                Comment by
                                <span
                                    style="color: #1a1e21">{{is_null($comment->users)?'':$comment->users->name}}</span>
                                At {{$comment->updated_at}}
                            </p>
                        </div>
                        <!-- Divider-->
                        <hr class="my-4"/>
                    </div>
                @endforeach
            @endif
        </div>
    </article>
    @if(is_null(\Illuminate\Support\Facades\Auth::id()) === false)
        <div class="container px-4 px-lg-5">
            <h2 class="subheading">Comment</h2>
            <form id="form" method="POST" action="{{route('api.comment.store')}}">
                <div class="form-floating" style="padding-bottom: 1rem">
                <textarea class="form-control" id="content" name="content"
                          placeholder="Enter your content here..." style="height: 10rem"
                          data-sb-validations="required" ></textarea>
                </div>
                <input type="hidden" name="member_token" value="{{$Html->member_token}}">
                <input type="hidden" name="article_id" value="{{$Html->element->id}}">
                <button style="padding-top: 1rem" class="btn btn-primary text-uppercase" id="submitButton"
                        data-action="submit"
                        type="button">Send
                </button>
            </form>
        </div>
    @endif
    @push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/mustache.js/3.0.0/mustache.min.js"></script>
    <script type="text/x-mustache" id="comments_template">
        <div class="container px-4 px-lg-5 comment-element">
            <div class="post-preview">
                <span class="post-subtitle">@{{ content }}</span>
                <p class="post-meta">
                    Comment by
                    <span style="color: #1a1e21">@{{ user_name }}</span>
                                At @{{ updated_at }}
            </p>
        </div>
        <!-- Divider-->
        <hr class="my-4"/>
    </div>
    </script>
    <script>
        $(function () {
            @if(is_null(\Illuminate\Support\Facades\Auth::id()) === false)
            let FormElement = $("#form");
            $("button[data-action='submit']").click(function () {
                let content = $("#content");
                if(content.val() == ''){
                    alert('請填寫留言內容');
                    content.focus();
                    return false
                }
                ajaxLoadingOpen();
                $.post(FormElement.attr('action'), FormElement.serialize(), function (Obj) {
                    ajaxLoadingClose();
                    if (Obj.status !== true) {
                        $.each(Obj.message, function (key, value) {
                            alert(value.join(','));
                        });
                        return false;
                    } else {
                        alert('留言成功');
                    }
                    if (Obj.redirect != '' && Obj.redirect != undefined) {
                        location.href = Obj.redirect;
                    }
                    getNewComments();
                    return false;
                });
            });
            @endif
        });

        function getNewComments() {
            ajaxLoadingOpen();
            $.get('{{route('api.comment.index',['article_id'=>$Html->element->id])}}', function (Obj) {
                ajaxLoadingClose();
                let comments = $("#comments");
                let content = $("#content");
                let template = $("#comments_template").html();
                let html = '';

                $(".comment-element").empty();
                if (Obj.status !== true) {
                    $.each(Obj.message, function (key, value) {
                        alert(value.join(','));
                    });
                } else {
                    for (let i = 0; i < Obj.data.length; i++) {
                        html += Mustache.render(template, {
                            content: Obj.data[i].content,
                            user_name: Obj.data[i].user.name,
                            updated_at: Obj.data[i].updated_at
                        });
                    }
                    comments.append(html);
                }
                content.focus();
                content.val('');
            });
            return false;
        }
    </script>
    @endpush
@endsection
