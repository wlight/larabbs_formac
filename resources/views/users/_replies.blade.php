@if(count($replies))
<ul class="list-group">
    @foreach ($replies as $index => $reply)
        <li class="list-group-item">
            <a href="{{ $reply->topic->link(['#reply'.$reply->id]) }} ">
            {{ $reply->topic->title }}
            </a>

            <div class="reply-content" style="margin:6px 0;">
                {!! $reply->content !!}
            </div>

            <div class="meta">
                <span class="glyphicon glyphicon-time" aria-hidden="true"></span>
                回复于{{ $reply->created_at->diffForhumans() }}
            </div>
        </li>
    @endforeach
    
</ul>

@else
<div class="empty-block">暂无回复</div>
@endif

{!! $replies->appends(Request::except('page'))->render() !!}