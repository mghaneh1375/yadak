<!DOCTYPE html>

<!--[if IE 8]> <html lang="en" class="ie8 no-js"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9 no-js"> <![endif]-->
<!--[if !IE]><!-->
<html lang="en">
<!--<![endif]-->

<head>
    @section('header')
        @include('layouts.common')
    @show
</head>

<body class="page-header-fixed page-sidebar-closed-hide-logo page-content-white" onload="onloadfunction()">
    <div class="page-wrapper">
        <!-- BEGIN HEADER -->
        <div class="page-header navbar navbar-fixed-top">
            <!-- BEGIN HEADER INNER -->
            <div class="page-header-inner ">
                <!-- BEGIN LOGO -->
                <div class="page-logo">
                    <a href="{{route('home')}}">
                        <img width="80px" src="{{URL::asset('layouts/layout/img/logo.png')}}" alt="logo" class="logo-default" />
                    </a>
                    <div class="menu-toggler sidebar-toggler">
                        <span></span>
                    </div>
                </div>

                <a href="javascript:;" class="menu-toggler responsive-toggler" data-toggle="collapse" data-target=".navbar-collapse">
                    <span></span>
                </a>

                <div class="top-menu">
                    <ul class="nav navbar-nav pull-left">

                        <?php
                            \Illuminate\Support\Facades\DB::select('select b.created_at, concat(u.first_name, " ", u.last_name) as name from users u, basket b where b.user_id = u.id and b.confirm = false and b.reject = false');
                            $baskets = \App\Models\Basket::whereConfirm(false)->whereReject(false)->get();
                            $warningProducts = \Illuminate\Support\Facades\DB::select('select p.name, p.number, s.name as super_category, c.name as category, b.name as brand from product p, super_category s, category c, brand b, config co where b.id = p.brand_id and p.category_id = c.id and c.super_category_id = s.id and p.number < co.warning_threshold and p.number >= co.critical_threshold');
                            $criticalProducts = \Illuminate\Support\Facades\DB::select('select p.name, p.number, s.name as super_category, c.name as category, b.name as brand from product p, super_category s, category c, brand b, config co where b.id = p.brand_id and p.category_id = c.id and c.super_category_id = s.id and p.number < co.critical_threshold');
                        ?>

                        <li class="dropdown dropdown-extended dropdown-notification" id="header_notification_bar">
                            <a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-close-others="true">
                                <i class="icon-bell"></i>
                                <span class="badge badge-default"> {{count($baskets) + count($warningProducts) + count($criticalProducts)}} </span>
                            </a>
                            <ul class="dropdown-menu">
                                <li>
                                    <ul class="dropdown-menu-list scroller" style="height: 250px;" data-handle-color="#637283">

                                        @foreach($baskets as $basket)
                                            <li>
                                                <a href="{{route('unConfirmedOrders')}}">
                                                    <span class="time">{{MiladyToShamsi('', explode('-', explode(' ', $basket->created_at)[0]))}}</span>
                                                    <span class="details">
                                                        <span class="label label-sm label-icon label-success" style="font-size: 0.9em">
                                                            <i class="fa fa-bell-o"></i>
                                                            سفارش جدید
                                                        </span>
                                                        {{$basket->name}}
                                                    </span>
                                                </a>
                                            </li>
                                        @endforeach

                                        @foreach($criticalProducts as $product)

                                            <li style="cursor: auto !important;">
                                                <a href="javascript:;">
                                                    <span style="font-size: 0.6em; color: black; font-weight: bolder" class="time">تعداد باقی مانده: {{$product->number}}</span>
                                                    <span class="details" style="font-weight: bolder; font-size: 0.8em">
                                                        {{$product->super_category . ' / ' . $product->category . ' / ' . $product->brand . ' / ' . $product->name}}
                                                        <span class="label label-sm label-icon label-danger" style="font-size: 0.9em">
                                                            <i class="fa fa-bell-o"></i>
                                                            کمتر از حد بحرانی
                                                        </span>
                                                    </span>
                                                </a>
                                            </li>

                                        @endforeach

                                        @foreach($warningProducts as $product)

                                            <li style="cursor: auto !important;">
                                                <a href="javascript:;">
                                                    <span style="font-size: 0.6em; color: black; font-weight: bolder" class="time">تعداد باقی مانده: {{$product->number}}</span>
                                                    <span class="details" style="font-weight: bolder; font-size: 0.8em">
                                                    {{$product->super_category . ' / ' . $product->category . ' / ' . $product->brand . ' / ' . $product->name}}
                                                    <span class="label label-sm label-icon label-warning" style="font-size: 0.9em">
                                                        <i class="fa fa-bell-o"></i>
                                                        کمتر از حد اخطار
                                                    </span>
                                                </span>
                                                </a>
                                            </li>

                                        @endforeach

                                    </ul>
                                </li>
                            </ul>
                        </li>
                        <!-- END NOTIFICATION DROPDOWN -->


                        <!-- BEGIN QUICK SIDEBAR TOGGLER -->
                        <!-- DOC: Apply "dropdown-dark" class after below "dropdown-extended" to change the dropdown styte -->
                        <li class="dropdown dropdown-quick-sidebar-toggler">
                            <a href="{{route('logout')}}" class="dropdown-toggle">
                                <i class="icon-logout"></i>
                            </a>
                        </li>
                        <!-- END QUICK SIDEBAR TOGGLER -->
                    </ul>
                </div>

            </div>

        </div>

        <div class="clearfix"> </div>

        <div class="page-container">

            @if(\Illuminate\Support\Facades\Auth::check() && \Illuminate\Support\Facades\Auth::user()->level == 1)
                <div class="page-sidebar-wrapper">
                    <div class="page-sidebar navbar-collapse collapse" style="display: block !important;">
                        <ul class="page-sidebar-menu  page-header-fixed " data-keep-expanded="false" data-auto-scroll="true" data-slide-speed="200" style="padding-top: 20px">
                            <li class="sidebar-toggler-wrapper hide">
                                <div class="sidebar-toggler">
                                    <span></span>
                                </div>
                            </li>

                            <li class="nav-item">

                                <a href="javascript:;" class="nav-link nav-toggle">
                                    <i class="icon-basket"></i>
                                    <span class="title">پرسش و پاسخ متداول</span>
                                    <span class="arrow open"></span>
                                </a>

                                <ul class="sub-menu" style="display: none;">
                                    <li class="nav-item  ">
                                        <a href="{{route('faqCategories')}}" class="nav-link">
                                            <span class="title">مدیریت دسته ها</span>
                                        </a>
                                    </li>

                                    <li class="nav-item  ">
                                        <a href="{{route('commonQuestionsPanel')}}" class="nav-link">
                                            <span class="title">مدیریت سوالات</span>
                                        </a>
                                    </li>

                                </ul>

                            </li>

                            <li class="nav-item">

                                <a href="{{route('config')}}" class="nav-link nav-toggle">
                                    <i class="icon-settings"></i>
                                    <span class="title">پیکربندی</span>
                                    <span class="arrow open"></span>
                                </a>

                            </li>

                            <li class="nav-item  ">
                                <a href="{{route('manageCategory')}}" class="nav-link nav-toggle">
                                    <i class="icon-diamond"></i>
                                    <span class="title">مدیریت دسته ها</span>
                                    <span class="arrow"></span>
                                </a>
                            </li>

                            <li class="nav-item">
                                <a href="{{route('manageSlideShow')}}" class="nav-link nav-toggle">
                                    <i class="icon-diamond"></i>
                                    <span class="title">مدیریت اسلایدر</span>
                                    <span class="arrow"></span>
                                </a>
                            </li>

                            <li class="nav-item  ">
                                <a href="{{route('addProduct')}}" class="nav-link nav-toggle">
                                    <i class="icon-diamond"></i>
                                    <span class="title">افزودن کالا</span>
                                    <span class="arrow"></span>
                                </a>
                            </li>

{{--                            <li class="nav-item  ">--}}
{{--                                <a href="{{route('addBatchProduct')}}" class="nav-link nav-toggle">--}}
{{--                                    <i class="icon-diamond"></i>--}}
{{--                                    <span class="title">افزودن دسته ای کالا</span>--}}
{{--                                    <span class="arrow"></span>--}}
{{--                                </a>--}}
{{--                            </li>--}}

                            <li class="nav-item">
                                <a href="javascript:;" class="nav-link nav-toggle">
                                    <i class="icon-basket"></i>
                                    <span class="title">گزارشات</span>
                                    <span class="arrow open"></span>
                                </a>

                                <ul class="sub-menu" style="display: none;">
                                    <li class="nav-item  ">
                                        <a href="{{route('productReport')}}" class="nav-link">
                                            <span class="title">محصولات</span>
                                        </a>
                                    </li>

                                    <li class="nav-item  ">
                                        <a href="{{route('most')}}" class="nav-link">
                                            <span class="title">پر طرفدار های ماه</span>
                                        </a>
                                    </li>

                                    <li class="nav-item  ">
                                        <a href="{{route('usersReport')}}" class="nav-link">
                                            <span class="title">کاربران</span>
                                        </a>
                                    </li>

                                </ul>
                            </li>

                            <li class="nav-item">
                                <a href="javascript:;" class="nav-link nav-toggle">
                                    <i class="icon-basket"></i>
                                    <span class="title">مدیریت کد های تخفیف</span>
                                    <span class="arrow open"></span>
                                </a>

                                <ul class="sub-menu" style="display: none;">
                                    <li class="nav-item  ">
                                        <a href="{{route('createOffCode')}}" class="nav-link">
                                            <span class="title">ساخت کد تخفیف</span>
                                        </a>
                                    </li>

                                    <li class="nav-item">
                                        <a href="{{route('offCodeReports')}}" class="nav-link">
                                            <span class="title">گزارش گیری</span>
                                        </a>
                                    </li>

                                </ul>

                            </li>

                            <li class="nav-item">
                                <a href="javascript:;" class="nav-link nav-toggle">
                                    <i class="icon-basket"></i>
                                    <span class="title">مدیریت سفارشات</span>
                                    <span class="arrow open"></span>
                                </a>

                                <ul class="sub-menu" style="display: none;">

                                    <li class="nav-item  ">
                                        <a href="{{route('rejectedOrders')}}" class="nav-link">
                                            <span class="title">سفارشات رد شده</span>
                                        </a>
                                    </li>

                                    <li class="nav-item  ">
                                        <a href="{{route('unConfirmedOrders')}}" class="nav-link">
                                            <span class="title">سفارشات جدید</span>
                                        </a>
                                    </li>

                                    <li class="nav-item">
                                        <a href="{{route('confirmedOrders')}}" class="nav-link">
                                            <span class="title">سفارشات تایید شده</span>
                                        </a>
                                    </li>

                                </ul>

                            </li>
    {{--                        <li class="nav-item  ">--}}
    {{--                            <a href="{{route('editProduct')}}" class="nav-link nav-toggle">--}}
    {{--                                <i class="icon-diamond"></i>--}}
    {{--                                <span class="title">ویرایش کالا</span>--}}
    {{--                                <span class="arrow"></span>--}}
    {{--                            </a>--}}
    {{--                        </li>--}}

                        </ul>

                    </div>
                </div>
            @endif

            <div style="margin-top: -80px; padding: 20px; float: left" id="myContentWrapper" class="page-content-wrapper">
                @yield('content')
            </div>

        </div>
    </div>


    <div class="quick-nav-overlay"></div>

    @include('layouts.jsLibraries')

    @yield('moreJS')

    <script>

        var open = true;

        $(".menu-toggler").on('click', function () {
            if(open) {
                open = false;
                $("#myContentWrapper").css('width', "95%");
            }
            else {
                open = true;
                $("#myContentWrapper").css('width', "80%");
            }
        });

        $(document).ready(function () {
            $("#myContentWrapper").css('width', "80%");
        });

    </script>

</body>

</html>