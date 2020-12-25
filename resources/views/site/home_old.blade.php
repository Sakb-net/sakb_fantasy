@extends('site.layouts.app_mobile')
@section('content')

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header" style="font-size: 20px;text-align: center;margin: 200px auto;">
                    <a style="margin-bottom: 40px;" href="{{ route('admin.index') }}">Dashboard</a>
                     <h1>GAMEFANTASY</h1>
                </div>
                <div class="card-body">
<!--                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif-->
                </div>
            </div>
        </div>
    </div>
</div>
@endsection