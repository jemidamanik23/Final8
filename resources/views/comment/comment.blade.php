<div class="comment" id="comment-{{ $comment->id }}">
    <div class="pull-left">
        <?php
        if ($comment->user_id) {
            $href = route('user.show', $comment->username);
        } else {
            $href = $comment->site ? httpUrl($comment->site) : 'javascript:void(0);';
        }
        $imgSrc = $comment->user ? $comment->user->avatar : config('app.avatar');
        $imgSrc = processImageViewUrl($imgSrc, 40, 40);
        $commentFragment = "comment-$comment->id"
        ?>
        <a name="{{ $commentFragment }}" href="{{ $href }}">
            <img class="comment-avatar" src="{{ $imgSrc }}">
        </a>
    </div>
    <div class="comment-info">
        <div class="comment-head">
                <span class="name">
                    <a href="{{ $href }}"{{ !$comment->isVerified()?" style=color:red":'' }}>{{ $comment->username }}</a>
                    @if(isAdminById($comment->user_id))
                        <label class="role-label">BLOGGER</label>
                    @endif
                    @if(!$comment->isVerified())
                        <label class="role-label">NOT_VERIFIED</label>
                    @endif
                </span>
            <span class="comment-operation pull-right">
                    <a href="#{{ $commentFragment }}"
                       style="color: #ccc;font-size: 12px">#{{ $comment->id }}</a>
            </span>
        </div>
        <div class="comment-time">
            <span>{{ $comment->created_at }}</span>
        </div>
        <div class="comment-content">
            {!! $comment->html_content !!}
            <div class="comment-operation">
                @can('manager',$comment)
                    <a class="comment-operation-item"
                       href="javascript:void (0)"
                       onclick="deleteComment('{{ $comment->id }}')">
                        REMOVE
                    </a>
                    <a class="comment-operation-item"
                       title="EDIT2"
                       href="{{ route('comment.edit',[$comment->id,'redirect'=>(isset($redirect) ? $redirect.'#'.$commentFragment : request()->fullUrl().'#'.$commentFragment)]) }}">
                        EDIT2
                    </a>
                @endcan
               
                @if(isAdminById(auth()->id()) && !$comment->isVerified())
                    <a class="comment-operation-item"
                       title="Verify"
                       href="{{ route('comment.verify',$comment->id) }}">
                        Verify
                    </a>
                @endif
            </div>
            @foreach($comment->comments as $reply_comment)
                @include('comment.comment',['comment'=>$reply_comment])
            @endforeach
        </div>
    </div>
</div>