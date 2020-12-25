@extends('site.layouts.app')
@section('content')
@include('site.layouts.page_title')

<section class="section-padding">
                <div class="container">
                    <div class="row">
                        <!-- awards-->
                        <div class="wow fadeInUp">
                            <div class="featured-content">
                                @foreach ($content_items as $content_item)
                                    <h3 class="block-title mx-width">
                                        <span>{{ trans($content_item->content_value) }} :</span>
                                    </h3>
                                    <ul>
                                    @foreach (explode("\n", $content_item->content_etc) as $part)
                                        <li>{{ trans($part) }}</li>
                                    @endforeach
                                    </ul>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </section>
@include('site.home.sponsers')
@endsection

