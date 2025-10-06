<!DOCTYPE html>

<!--[if IE 8]> <html lang="en" class="ie8 no-js"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9 no-js"> <![endif]-->
<!--[if !IE]><!-->
<html lang="en" dir="rtl">
<!--<![endif]-->
<!-- BEGIN HEAD -->

<head>
    @include('layouts.common')
    <link href="{{\Illuminate\Support\Facades\URL::asset('pages/css/error-rtl.min.css')}}" rel="stylesheet" type="text/css" />
</head>

<body class=" page-404-3">
<div class="page-inner">
    <img src="{{\Illuminate\Support\Facades\URL::asset('pages/media/pages/earth-rtl.jpg')}}" class="img-responsive" alt="">
</div>
<div class="container error-404">
    <h1>404</h1>
    <h2>صفحه مورد نظر یافت نشد</h2>
    <p>
        <a href="{{route('home')}}" class="btn red btn-outline"> بازگشت به خانه </a>
        <br>
    </p>
</div>

@include('layouts.jsLibraries')

</body>

</html>