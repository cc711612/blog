<div>
    <article>
        <div class="container px-4 px-lg-5">
            <h2>View Comments</h2>
        </div>
        <div class="comment" id="comments">
            @if($comments->isEmpty())
                <div class="container px-4 px-lg-5">
                    <p class="post-meta">
                        No Comment! <br>
                        Join Us Discuss
                    </p>
                </div>
            @else
                @foreach($comments as $comment)
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
    @if(is_null($member_token) === false)
        <div class="container px-4 px-lg-5" style="padding-bottom: 1rem">
            <h2 class="subheading">Comment</h2>
            <div class="form-floating" style="padding-bottom: 1rem">
                <textarea wire:model.debounce.500ms="content" class="form-control" id="content" name="content"
                          placeholder="Enter your content here..." style="height: 10rem"
                          data-sb-validations="required">{{$content}}</textarea>
            </div>
            <div class="d-flex justify-content-center align-items-center">
                @error('content') <span class="text-danger">{{ $message }}</span> @enderror
            </div>
            <input type="hidden" name="member_token" value="{{$member_token}}">
            <button style="padding-top: 1rem;" class="btn btn-primary text-uppercase" id="submitButton"
                    data-action="submit"
                    type="button" wire:click="store">Send
            </button>
        </div>
    @endif
</div>
