@foreach($data as $keypost=>$val_data)
            <div class="col-md-4 col-sm-6">
                <div class="post-slide">
                    <div class="post-img">
                        <a href="{{ route('news.single',$val_data->link) }}">
                            @if(!empty($val_data->image))
                            <img  src="{{$val_data->image}}" />
                            @else
                            <img  src="{{ asset('images/news-img/1.jpg') }}" alt="">
                            @endif
                        </a>
                    </div>
                    <div class="post-content">
                        <div class="post-date">
                            <span class="month">{{arabic_Value_month($val_data->created_at->format('m'))}}</span>
                            <span class="date">{{$val_data->created_at->format('d')}}</span>
                        </div>
                        <h5 class="post-title">
                            <a href="{{ route('news.single',$val_data->link) }}">{{$val_data->name}}</a>
                        </h5>
                        <p class="post-description">{{\Illuminate\Support\Str::limit($val_data->content, $limit = 100, $end = '...')}}</p>
                    </div>
                </div>
            </div>
            @endforeach