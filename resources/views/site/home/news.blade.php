<div class="col-md-8">
    <h3 class="block-title">
        <span>{{trans('app.last_news')}}</span>
        <a class="read-more" href="{{ route('news.index') }}">{{trans('app.more_more')}}</a>
    </h3>
    @foreach($news as $keynews=>$val_news)
    <div class="col-md-6">
        <div class="post-slide">
            <div class="post-img">
                <a href="{{ route('news.single',$val_news->link) }}">
                    @if(!empty($val_news->image))
                   <img  src="{{ $val_news->image }}" alt="">
                   @else
                   <img  src="{{ asset('images/news-img/1.jpg') }}" alt="">
                   @endif
                </a>
            </div>
            <div class="post-content">
                <div class="post-date">
                    <span class="month">{{arabic_Value_month($val_news->created_at->format('m'))}}</span>
                    <span class="date">{{$val_news->created_at->format('d')}}</span>
                </div>
                <h5 class="post-title">
                    <a href="{{ route('news.single',$val_news->link) }}">{{$val_news->name}}</a>
                </h5>
                <p class="post-description">{{\Illuminate\Support\Str::limit($val_news->content, $limit = 100, $end = '...')}}</p>
            </div>
        </div>
    </div>
    @endforeach
</div>