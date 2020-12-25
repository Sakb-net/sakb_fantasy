<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<!-- Tell the browser to be responsive to screen width -->
<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">

<!-- Meta -->
<meta name="description" content="{{ $description }} " />
<meta name="title" content="{{ $title }} " />
<meta name="keywords" content="{{ $keywords }}" />
<meta name="reply-to" content="{{ $email }}">
<meta name="author" content="GAMEFANTASY">
<meta name="designer" content="GAMEFANTASY">
<meta name="owner" content="{{ config('app.name', 'GAMEFANTASY') }}">
<meta name="revisit-after" content="7 days">

<!-- image -->
<link href="{{ $share_image  }}" />
<meta name="medium" content="image" />
<meta property="og:type" content="instapp:photo" />

<!-- for Facebook, Pinterest, LinkedIn, Google+ --> 
<meta property="og:image" content="{{ $share_image  }}">
<meta property="og:url" content="{{ Request::url() }}">
<meta property="og:title" content="{{ $title  }}">
<meta property="og:site_name" content="{{ config('app.name', 'GAMEFANTASY') }}">
<meta property="fb:app_id" content="485726318457824">
<meta property="og:image:width" content="476"/>
<meta property="og:image:height" content="249"/>

<!-- for Twitter -->
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:site" content="{{ config('app.name', 'GAMEFANTASY') }}">
<meta name="twitter:title" content="{{ $title  }}">
<meta name="twitter:description" content="{{ $description }}">
<meta name="twitter:image" content="{{ $share_image }}">  

<!-- CSRF Token -->
<meta name="_token" content="{{ csrf_token() }}"/>

<!-- Title -->
<title>{{ $title }} </title>
<!-- CSS -->
<!-- Favicon -->
<link rel="icon" type="image/png" sizes="56x56" href="{{ asset('images/fav-icon/icon.png') }}">
<!-- Main style sheet -->
@if($cuRRlocal=='ar')
<link rel="stylesheet" type="text/css" href="{{ asset('css/site/css/style.css?v='.config('version.version_script')) }}">
@else
<link rel="stylesheet" type="text/css" href="{{ asset('css/site/css/style-en.css?v='.config('version.version_script')) }}">
@endif
<link rel="stylesheet" type="text/css" href="{{ asset('css/site/css/custom.css?v='.config('version.version_script')) }}">

<!--[if lt IE 9]>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/html5shiv/3.7.3/html5shiv.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/respond.js/1.4.2/respond.min.js"></script>
<![endif]-->