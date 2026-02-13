<div>
    <article>
        <div class="container px-4 px-lg-5">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h2>留言區</h2>
                <div class="d-flex align-items-center">
                    <!-- 搜尋框 -->
                    <div class="input-group input-group-sm me-2" style="width: 200px;">
                        <input 
                            type="text" 
                            class="form-control" 
                            placeholder="搜尋留言..." 
                            wire:model.debounce.300ms="search"
                        >
                        <button 
                            class="btn btn-outline-secondary" 
                            type="button" 
                            wire:click="clearSearch"
                            wire:loading.attr="disabled"
                        >
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                    
                    <!-- 排序選項 -->
                    <select class="form-select form-select-sm" wire:model="sortOrder" style="width: 120px;">
                        <option value="newest">最新</option>
                        <option value="oldest">最舊</option>
                        <option value="popular">熱門</option>
                    </select>
                </div>
            </div>
        </div>
        
        <div class="comment" id="comments">
            @if(empty($comments))
                <div class="container px-4 px-lg-5">
                    <p class="post-meta">
                        @if(!empty($search))
                            找不到符合 "{{ $search }}" 的留言
                        @else
                            No Comment! <br>
                            Join Us Discuss
                        @endif
                    </p>
                </div>
            @else
                @foreach($comments as $comment)
                    <?php $votes = $this->getCommentVotes($comment->id); ?>
                    <div class="container px-4 px-lg-5 comment-element">
                        <div class="post-preview">
                            <div class="d-flex justify-content-between align-items-start">
                                <div class="flex-grow-1">
                                    <span class="post-subtitle">{!! $comment->content !!}</span>
                                    <p class="post-meta mb-1">
                                        Comment by
                                        <span style="color: #1a1e21">{{is_null($comment->users)?'':$comment->users->name}}</span>
                                        At {{$comment->updated_at}}
                                    </p>
                                </div>
                                
                                <!-- 投票按鈕 -->
                                <div class="d-flex flex-column align-items-center ms-3">
                                    <button 
                                        wire:click="voteComment({{ $comment->id }}, 'up')"
                                        class="btn btn-sm {{ $votes['user_vote'] === 'up' ? 'btn-success' : 'btn-outline-success' }} mb-1"
                                        wire:loading.attr="disabled"
                                    >
                                        <i class="fas fa-thumbs-up"></i>
                                    </button>
                                    <span class="badge bg-secondary">{{ $votes['total'] > 0 ? '+' : '' }}{{ $votes['total'] }}</span>
                                    <button 
                                        wire:click="voteComment({{ $comment->id }}, 'down')"
                                        class="btn btn-sm {{ $votes['user_vote'] === 'down' ? 'btn-danger' : 'btn-outline-danger' }} mt-1"
                                        wire:loading.attr="disabled"
                                    >
                                        <i class="fas fa-thumbs-down"></i>
                                    </button>
                                </div>
                            </div>
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
            <h2 class="subheading">發表留言</h2>
            <div class="form-floating" style="padding-bottom: 1rem">
                <textarea wire:model.debounce.500ms="content" class="form-control" id="content" name="content"
                          placeholder="Enter your content here..." style="height: 10rem"
                          data-sb-validations="required">{{$content}}</textarea>
            </div>
            <div class="d-flex justify-content-center align-items-center">
                @error('content') <span class="text-danger">{{ $message }}</span> @enderror
            </div>
            <input type="hidden" name="member_token" value="{{$member_token}}">
            <input type="hidden" name="article_id" value="{{$article_id}}">
            <button style="padding-top: 1rem;" class="btn btn-primary text-uppercase" id="submitButton"
                    data-action="submit"
                    type="button" wire:click="store">Send
            </button>
        </div>
    @else
        <div class="container px-4 px-lg-5 text-center" style="padding-bottom: 1rem">
            <p class="text-muted">請先登入才能發表留言</p>
        </div>
    @endif
</div>
