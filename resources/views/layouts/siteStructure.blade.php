<!DOCTYPE html>

<!--[if IE 8]> <html lang="en" class="ie8 no-js"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9 no-js"> <![endif]-->
<!--[if !IE]><!-->
<html lang="en" dir="rtl">
<!--<![endif]-->
<!-- BEGIN HEAD -->

<head>
    @section('header')
        @include('layouts.common')
        <link href="{{URL::asset("global/plugins/cubeportfolio/css/cubeportfolio.css")}}" rel="stylesheet" type="text/css" />
        <link href="{{URL::asset("pages/css/portfolio-rtl.min.css")}}" rel="stylesheet" type="text/css" />
    @show

    <style>
        #searchResult {
            margin-top: 6px;
            width: 100%;
            position: absolute;
            left: 0;
            top: 20px;
            z-index: 1000000001 !important;
            background-color: #3B3F51;
            color: white;
            border-radius: 18px;
            max-height: 200px;
            overflow: auto;
        }

        .suggest {
            padding-right: 10px !important;
        }

        @media (max-width:767px) {
            .floatRightOnMobile {
                float: right !important;
            }
        }

        @media (max-width:991px) {
            .hidden-991 {
                display: none !important;
            }
        }

        @media (min-width:991px) {
            .hidden-lg-991 {
                display: none !important;
            }
        }

    </style>

</head>

<?php
$categories = \App\models\SuperCategory::all();

foreach ($categories as $category) {

    $category->sub = \App\models\Category::whereSuperCategoryId($category->id)->get();

    foreach ($category->sub as $itr) {
        $itr->sub = \App\models\CategoryItem::whereCategoryId($itr->id)->get();
    }

}

?>

<body class="page-container-bg-solid page-header-fixed page-sidebar-closed-hide-logo">

<div style="position: fixed; top: 0; left: 0; width: 100%; z-index: 10001; ">

    <div class="page-header navbar navbar-fixed-top">

        <div class="page-header-inner ">

            <div class="page-logo">
                <a href="{{route('home')}}">
                    <img src="{{URL::asset('layouts/layout/img/logo.png')}}" alt="logo" class="logo-default" />
                </a>
            </div>

            <a href="javascript:;" onclick="$('.navbar-collapse').removeClass('hidden')" class="menu-toggler responsive-toggler" data-toggle="collapse" data-target=".navbar-collapse"> </a>

            <div class="floatRightOnMobile" style="display: inline-block !important; float: left">

                <div class="search-form" id="searchDivForScroll">
                    <div class="input-group searchBtn">
                        <input type="text" onkeyup="search(event)" class="form-control input-sm" placeholder="جست و جو کنید" id="query">
                        <span class="input-group-btn">
                            <a class="btn submit">
                                <i style="color: #737a99" class="icon-magnifier"></i>
                            </a>
                        </span>

                        <div id="searchResult"></div>

                    </div>
                </div>

                <div class="top-menu">
                    <ul class="nav navbar-nav pull-right">
                        <li class="separator hide"> </li>

                        @if(\Illuminate\Support\Facades\Auth::check() && \Illuminate\Support\Facades\Auth::user()->level == 1)

                            <?php
                            \Illuminate\Support\Facades\DB::select('select b.created_at, concat(u.first_name, " ", u.last_name) as name from users u, basket b where b.user_id = u.id and b.confirm = false and b.reject = false');
                            $baskets = \App\models\Basket::whereConfirm(false)->whereReject(false)->get();
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

                        @endif

                        @if(\Illuminate\Support\Facades\Auth::check())
                            <li class="dropdown dropdown-user dropdown-dark hidden-xs">
                                <p style="color: white">
                                    <span class="username username-hide-on-mobile"> سلام {{\Illuminate\Support\Facades\Auth::user()->first_name . ' ' . \Illuminate\Support\Facades\Auth::user()->last_name}} عزیز</span>
                                </p>
                            </li>

                        @else
                            <li onclick="document.location.href = '{{route('login')}}'" class="dropdown dropdown-extended dropdown-notification dropdown-dark">
                                <a class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-close-others="true">
                                    <i class="icon-login"></i>
                                    <span class="badge badge-success"> ورود </span>
                                </a>
                            </li>
                        @endif

                    </ul>
                </div>

            </div>

            <div class="page-sidebar navbar-collapse collapse" aria-expanded="false" style="height: 0; width: 100%">

                <ul class="page-sidebar-menu" data-keep-expanded="false" data-auto-scroll="true" data-slide-speed="200">

                    <li class="nav-item start ">
                        <a href="{{route('home')}}" class="nav-link nav-toggle">
                            <i class="icon-home"></i>
                            <span class="title">خانه</span>
                            <span class="arrow"></span>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="javascript:;" class="nav-link nav-toggle">
                            <i class="icon-diamond"></i>
                            <span class="title">دسته بندی کالا ها</span>
                            <span class="arrow"></span>
                        </a>
                        <ul class="sub-menu">
                            @foreach($categories as $category)
                                <li class="nav-item">
                                    <a href="javascript:;" class="nav-link nav-toggle">
                                        <i class="icon-diamond"></i>
                                        <span class="title">{{$category->name}}</span>
                                        <span class="arrow"></span>
                                    </a>
                                    @if($category->sub != null && count($category->sub) > 0)
                                    <ul class="sub-menu">
                                        @foreach($category->sub as $itr)
                                            <li class="nav-item  ">
                                                <a href="{{route('productsInCategory', ['id' => $itr->id])}}" class="nav-link ">
                                                    <span class="title">{{$itr->name}}</span>
                                                </a>
                                            </li>
                                        @endforeach
                                    </ul>
                                    @endif
                                </li>
                            @endforeach
                        </ul>
                    </li>

                    <li class="nav-item">
                        <a href="{{route('faq')}}" class="nav-link nav-toggle">
                            <i class="icon-question"></i>
                            <span class="title">سوالی دارید؟</span>
                            <span class="arrow"></span>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{route('trackOrders')}}" class="nav-link nav-toggle">
                            <i class="icon-basket"></i>
                            <span class="title">پیگیری سفارشات</span>
                            <span class="arrow"></span>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{route('contactUs')}}" class="nav-link nav-toggle">
                            <i class="icon-call-end"></i>
                            <span class="title">تماس با ما</span>
                            <span class="arrow"></span>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{route('aboutUs')}}" class="nav-link nav-toggle">
                            <i class="icon-info"></i>
                            <span class="title">درباره ما</span>
                            <span class="arrow"></span>
                        </a>
                    </li>

                    @if(\Illuminate\Support\Facades\Auth::check() && \Illuminate\Support\Facades\Auth::user()->level == 1)

                        <li class="nav-item">
                            <a href="{{route('profile')}}" class="nav-link nav-toggle">
                                <i class="icon-user"></i>
                                <span class="title">پروفایل</span>
                                <span class="arrow"></span>
                            </a>
                        </li>

                    @endif

                    @if(\Illuminate\Support\Facades\Auth::check())

                        <li class="nav-item">
                            <a href="{{route('bookmarks')}}" class="nav-link nav-toggle">
                                <i class="glyphicon glyphicon-bookmark"></i>
                                <span class="title">محصولات نشان شده</span>
                                <span class="arrow"></span>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="{{route('editInfo')}}" class="nav-link nav-toggle">
                                <i class="icon-user"></i>
                                <span class="title">ویرایش اطلاعات کاربری</span>
                                <span class="arrow"></span>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="{{route('logout')}}" class="nav-link nav-toggle">
                                <i class="icon-logout"></i>
                                <span class="title">خروج</span>
                                <span class="arrow"></span>
                            </a>
                        </li>

                    @endif

                </ul>

            </div>
        </div>

    </div>

    <div class="clearfix"> </div>

    <div class="portlet light bordered hidden-xs hidden-991" style="margin-top: 65px; margin-bottom: 0; padding-bottom: 0">
        <div class="portlet-title" style="border: none; min-height: 40px; margin-bottom: 0">

            <div style="width: 100%">
                @include('layouts.navBar')
            </div>

        </div>
    </div>

    @if(\Illuminate\Support\Facades\Auth::check())
        <div class="portlet light bordered hidden-lg hidden-lg-991" style="margin-bottom: 0; padding-bottom: 0">
            <div class="portlet-title" style="border: none; min-height: 40px; margin-bottom: 0">
                <div style="width: 100%">

                    <?php $myBasket = \Illuminate\Support\Facades\Session::get('products'); $myBasket = ($myBasket == null) ? 0 : count($myBasket) ?>

                    <li style="float: left; margin-left: 40px; position: relative; list-style-type: none">
                        <a target="_blank" href="{{route('myBasket')}}" style=" font-size: 18px">
                            <span data-val="{{$myBasket}}" id="shopping-cart" class="glyphicon glyphicon-shopping-cart" style="cursor: pointer; font-family: 'Glyphicons Halflings' !important;"></span>
                            <span id="basketNum" style="position: absolute; border: 1px; border-radius: 50% !important; width: 17px; background-color: red; height: 17px; top: 5px; color: white; font-size: 0.7em; padding-top: 0; padding-right: 5px; line-height: 16px">{{$myBasket}}</span>
                        </a>
                    </li>

                </div>

            </div>
        </div>
    @endif

</div>

<div class="page-container" style="margin: 120px 0 0;">


    <div class="page-content-wrapper">
        @yield('content')
    </div>

    <a href="javascript:;" class="page-quick-sidebar-toggler">
        <i class="icon-login"></i>
    </a>
    <div class="page-quick-sidebar-wrapper" data-close-on-body-click="false">
        <div class="page-quick-sidebar">
            <ul class="nav nav-tabs">
                <li class="active">
                    <a href="javascript:;" data-target="#quick_sidebar_tab_1" data-toggle="tab"> Users
                        <span class="badge badge-danger">2</span>
                    </a>
                </li>
                <li>
                    <a href="javascript:;" data-target="#quick_sidebar_tab_2" data-toggle="tab"> Alerts
                        <span class="badge badge-success">7</span>
                    </a>
                </li>
                <li class="dropdown">
                    <a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown"> More
                        <i class="fa fa-angle-down"></i>
                    </a>
                    <ul class="dropdown-menu pull-right">
                        <li>
                            <a href="javascript:;" data-target="#quick_sidebar_tab_3" data-toggle="tab">
                                <i class="icon-bell"></i> Alerts </a>
                        </li>
                        <li>
                            <a href="javascript:;" data-target="#quick_sidebar_tab_3" data-toggle="tab">
                                <i class="icon-info"></i> Notifications </a>
                        </li>
                        <li>
                            <a href="javascript:;" data-target="#quick_sidebar_tab_3" data-toggle="tab">
                                <i class="icon-speech"></i> Activities </a>
                        </li>
                        <li class="divider"></li>
                        <li>
                            <a href="javascript:;" data-target="#quick_sidebar_tab_3" data-toggle="tab">
                                <i class="icon-settings"></i> Settings </a>
                        </li>
                    </ul>
                </li>
            </ul>
            <div class="tab-content">
                <div class="tab-pane active page-quick-sidebar-chat" id="quick_sidebar_tab_1">
                    <div class="page-quick-sidebar-chat-users" data-rail-color="#ddd" data-wrapper-class="page-quick-sidebar-list">
                        <h3 class="list-heading">Staff</h3>
                        <ul class="media-list list-items">
{{--                            <li class="media">--}}
{{--                                <div class="media-status">--}}
{{--                                    <span class="badge badge-success">8</span>--}}
{{--                                </div>--}}
{{--                                <img class="media-object" src="../assets/layouts/layout/img/avatar3.jpg" alt="...">--}}
{{--                                <div class="media-body">--}}
{{--                                    <h4 class="media-heading">Bob Nilson</h4>--}}
{{--                                    <div class="media-heading-sub"> Project Manager </div>--}}
{{--                                </div>--}}
{{--                            </li>--}}
{{--                            <li class="media">--}}
{{--                                <img class="media-object" src="../assets/layouts/layout/img/avatar1.jpg" alt="...">--}}
{{--                                <div class="media-body">--}}
{{--                                    <h4 class="media-heading">Nick Larson</h4>--}}
{{--                                    <div class="media-heading-sub"> Art Director </div>--}}
{{--                                </div>--}}
{{--                            </li>--}}
{{--                            <li class="media">--}}
{{--                                <div class="media-status">--}}
{{--                                    <span class="badge badge-danger">3</span>--}}
{{--                                </div>--}}
{{--                                <img class="media-object" src="../assets/layouts/layout/img/avatar4.jpg" alt="...">--}}
{{--                                <div class="media-body">--}}
{{--                                    <h4 class="media-heading">Deon Hubert</h4>--}}
{{--                                    <div class="media-heading-sub"> CTO </div>--}}
{{--                                </div>--}}
{{--                            </li>--}}
{{--                            <li class="media">--}}
{{--                                <img class="media-object" src="../assets/layouts/layout/img/avatar2.jpg" alt="...">--}}
{{--                                <div class="media-body">--}}
{{--                                    <h4 class="media-heading">Ella Wong</h4>--}}
{{--                                    <div class="media-heading-sub"> CEO </div>--}}
{{--                                </div>--}}
{{--                            </li>--}}
                        </ul>
                        <h3 class="list-heading">Customers</h3>
                        <ul class="media-list list-items">
{{--                            <li class="media">--}}
{{--                                <div class="media-status">--}}
{{--                                    <span class="badge badge-warning">2</span>--}}
{{--                                </div>--}}
{{--                                <img class="media-object" src="../assets/layouts/layout/img/avatar6.jpg" alt="...">--}}
{{--                                <div class="media-body">--}}
{{--                                    <h4 class="media-heading">Lara Kunis</h4>--}}
{{--                                    <div class="media-heading-sub"> CEO, Loop Inc </div>--}}
{{--                                    <div class="media-heading-small"> Last seen 03:10 AM </div>--}}
{{--                                </div>--}}
{{--                            </li>--}}
{{--                            <li class="media">--}}
{{--                                <div class="media-status">--}}
{{--                                    <span class="label label-sm label-success">new</span>--}}
{{--                                </div>--}}
{{--                                <img class="media-object" src="../assets/layouts/layout/img/avatar7.jpg" alt="...">--}}
{{--                                <div class="media-body">--}}
{{--                                    <h4 class="media-heading">Ernie Kyllonen</h4>--}}
{{--                                    <div class="media-heading-sub"> Project Manager,--}}
{{--                                        <br> SmartBizz PTL </div>--}}
{{--                                </div>--}}
{{--                            </li>--}}
{{--                            <li class="media">--}}
{{--                                <img class="media-object" src="../assets/layouts/layout/img/avatar8.jpg" alt="...">--}}
{{--                                <div class="media-body">--}}
{{--                                    <h4 class="media-heading">Lisa Stone</h4>--}}
{{--                                    <div class="media-heading-sub"> CTO, Keort Inc </div>--}}
{{--                                    <div class="media-heading-small"> Last seen 13:10 PM </div>--}}
{{--                                </div>--}}
{{--                            </li>--}}
{{--                            <li class="media">--}}
{{--                                <div class="media-status">--}}
{{--                                    <span class="badge badge-success">7</span>--}}
{{--                                </div>--}}
{{--                                <img class="media-object" src="../assets/layouts/layout/img/avatar9.jpg" alt="...">--}}
{{--                                <div class="media-body">--}}
{{--                                    <h4 class="media-heading">Deon Portalatin</h4>--}}
{{--                                    <div class="media-heading-sub"> CFO, H&D LTD </div>--}}
{{--                                </div>--}}
{{--                            </li>--}}
{{--                            <li class="media">--}}
{{--                                <img class="media-object" src="../assets/layouts/layout/img/avatar10.jpg" alt="...">--}}
{{--                                <div class="media-body">--}}
{{--                                    <h4 class="media-heading">Irina Savikova</h4>--}}
{{--                                    <div class="media-heading-sub"> CEO, Tizda Motors Inc </div>--}}
{{--                                </div>--}}
{{--                            </li>--}}
{{--                            <li class="media">--}}
{{--                                <div class="media-status">--}}
{{--                                    <span class="badge badge-danger">4</span>--}}
{{--                                </div>--}}
{{--                                <img class="media-object" src="../assets/layouts/layout/img/avatar11.jpg" alt="...">--}}
{{--                                <div class="media-body">--}}
{{--                                    <h4 class="media-heading">Maria Gomez</h4>--}}
{{--                                    <div class="media-heading-sub"> Manager, Infomatic Inc </div>--}}
{{--                                    <div class="media-heading-small"> Last seen 03:10 AM </div>--}}
{{--                                </div>--}}
{{--                            </li>--}}
                        </ul>
                    </div>
                    <div class="page-quick-sidebar-item">
                        <div class="page-quick-sidebar-chat-user">
                            <div class="page-quick-sidebar-nav">
                                <a href="javascript:;" class="page-quick-sidebar-back-to-list">
                                    <i class="icon-arrow-left"></i>Back</a>
                            </div>
                            <div class="page-quick-sidebar-chat-user-messages">
{{--                                <div class="post out">--}}
{{--                                    <img class="avatar" alt="" src="../assets/layouts/layout/img/avatar3.jpg" />--}}
{{--                                    <div class="message">--}}
{{--                                        <span class="arrow"></span>--}}
{{--                                        <a href="javascript:;" class="name">Bob Nilson</a>--}}
{{--                                        <span class="datetime">20:15</span>--}}
{{--                                        <span class="body"> When could you send me the report ? </span>--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                                <div class="post in">--}}
{{--                                    <img class="avatar" alt="" src="../assets/layouts/layout/img/avatar2.jpg" />--}}
{{--                                    <div class="message">--}}
{{--                                        <span class="arrow"></span>--}}
{{--                                        <a href="javascript:;" class="name">Ella Wong</a>--}}
{{--                                        <span class="datetime">20:15</span>--}}
{{--                                        <span class="body"> Its almost done. I will be sending it shortly </span>--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                                <div class="post out">--}}
{{--                                    <img class="avatar" alt="" src="../assets/layouts/layout/img/avatar3.jpg" />--}}
{{--                                    <div class="message">--}}
{{--                                        <span class="arrow"></span>--}}
{{--                                        <a href="javascript:;" class="name">Bob Nilson</a>--}}
{{--                                        <span class="datetime">20:15</span>--}}
{{--                                        <span class="body"> Alright. Thanks! :) </span>--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                                <div class="post in">--}}
{{--                                    <img class="avatar" alt="" src="../assets/layouts/layout/img/avatar2.jpg" />--}}
{{--                                    <div class="message">--}}
{{--                                        <span class="arrow"></span>--}}
{{--                                        <a href="javascript:;" class="name">Ella Wong</a>--}}
{{--                                        <span class="datetime">20:16</span>--}}
{{--                                        <span class="body"> You are most welcome. Sorry for the delay. </span>--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                                <div class="post out">--}}
{{--                                    <img class="avatar" alt="" src="../assets/layouts/layout/img/avatar3.jpg" />--}}
{{--                                    <div class="message">--}}
{{--                                        <span class="arrow"></span>--}}
{{--                                        <a href="javascript:;" class="name">Bob Nilson</a>--}}
{{--                                        <span class="datetime">20:17</span>--}}
{{--                                        <span class="body"> No probs. Just take your time :) </span>--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                                <div class="post in">--}}
{{--                                    <img class="avatar" alt="" src="../assets/layouts/layout/img/avatar2.jpg" />--}}
{{--                                    <div class="message">--}}
{{--                                        <span class="arrow"></span>--}}
{{--                                        <a href="javascript:;" class="name">Ella Wong</a>--}}
{{--                                        <span class="datetime">20:40</span>--}}
{{--                                        <span class="body"> Alright. I just emailed it to you. </span>--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                                <div class="post out">--}}
{{--                                    <img class="avatar" alt="" src="../assets/layouts/layout/img/avatar3.jpg" />--}}
{{--                                    <div class="message">--}}
{{--                                        <span class="arrow"></span>--}}
{{--                                        <a href="javascript:;" class="name">Bob Nilson</a>--}}
{{--                                        <span class="datetime">20:17</span>--}}
{{--                                        <span class="body"> Great! Thanks. Will check it right away. </span>--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                                <div class="post in">--}}
{{--                                    <img class="avatar" alt="" src="../assets/layouts/layout/img/avatar2.jpg" />--}}
{{--                                    <div class="message">--}}
{{--                                        <span class="arrow"></span>--}}
{{--                                        <a href="javascript:;" class="name">Ella Wong</a>--}}
{{--                                        <span class="datetime">20:40</span>--}}
{{--                                        <span class="body"> Please let me know if you have any comment. </span>--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                                <div class="post out">--}}
{{--                                    <img class="avatar" alt="" src="../assets/layouts/layout/img/avatar3.jpg" />--}}
{{--                                    <div class="message">--}}
{{--                                        <span class="arrow"></span>--}}
{{--                                        <a href="javascript:;" class="name">Bob Nilson</a>--}}
{{--                                        <span class="datetime">20:17</span>--}}
{{--                                        <span class="body"> Sure. I will check and buzz you if anything needs to be corrected. </span>--}}
{{--                                    </div>--}}
{{--                                </div>--}}
                            </div>
                            <div class="page-quick-sidebar-chat-user-form">
                                <div class="input-group">
                                    <input type="text" class="form-control" placeholder="Type a message here...">
                                    <div class="input-group-btn">
                                        <button type="button" class="btn green">
                                            <i class="icon-paper-clip"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="tab-pane page-quick-sidebar-alerts" id="quick_sidebar_tab_2">
                    <div class="page-quick-sidebar-alerts-list">
                        <h3 class="list-heading">General</h3>
                        <ul class="feeds list-items">
                            <li>
                                <div class="col1">
                                    <div class="cont">
                                        <div class="cont-col1">
                                            <div class="label label-sm label-info">
                                                <i class="fa fa-check"></i>
                                            </div>
                                        </div>
                                        <div class="cont-col2">
                                            <div class="desc"> You have 4 pending tasks.
                                                <span class="label label-sm label-warning "> Take action
                                                            <i class="fa fa-share"></i>
                                                        </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col2">
                                    <div class="date"> Just now </div>
                                </div>
                            </li>
                            <li>
                                <a href="javascript:;">
                                    <div class="col1">
                                        <div class="cont">
                                            <div class="cont-col1">
                                                <div class="label label-sm label-success">
                                                    <i class="fa fa-bar-chart-o"></i>
                                                </div>
                                            </div>
                                            <div class="cont-col2">
                                                <div class="desc"> Finance Report for year 2013 has been released. </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col2">
                                        <div class="date"> 20 mins </div>
                                    </div>
                                </a>
                            </li>
                            <li>
                                <div class="col1">
                                    <div class="cont">
                                        <div class="cont-col1">
                                            <div class="label label-sm label-danger">
                                                <i class="fa fa-user"></i>
                                            </div>
                                        </div>
                                        <div class="cont-col2">
                                            <div class="desc"> You have 5 pending membership that requires a quick review. </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col2">
                                    <div class="date"> 24 mins </div>
                                </div>
                            </li>
                            <li>
                                <div class="col1">
                                    <div class="cont">
                                        <div class="cont-col1">
                                            <div class="label label-sm label-info">
                                                <i class="fa fa-shopping-cart"></i>
                                            </div>
                                        </div>
                                        <div class="cont-col2">
                                            <div class="desc"> New order received with
                                                <span class="label label-sm label-success"> Reference Number: DR23923 </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col2">
                                    <div class="date"> 30 mins </div>
                                </div>
                            </li>
                            <li>
                                <div class="col1">
                                    <div class="cont">
                                        <div class="cont-col1">
                                            <div class="label label-sm label-success">
                                                <i class="fa fa-user"></i>
                                            </div>
                                        </div>
                                        <div class="cont-col2">
                                            <div class="desc"> You have 5 pending membership that requires a quick review. </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col2">
                                    <div class="date"> 24 mins </div>
                                </div>
                            </li>
                            <li>
                                <div class="col1">
                                    <div class="cont">
                                        <div class="cont-col1">
                                            <div class="label label-sm label-info">
                                                <i class="fa fa-bell-o"></i>
                                            </div>
                                        </div>
                                        <div class="cont-col2">
                                            <div class="desc"> Web server hardware needs to be upgraded.
                                                <span class="label label-sm label-warning"> Overdue </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col2">
                                    <div class="date"> 2 hours </div>
                                </div>
                            </li>
                            <li>
                                <a href="javascript:;">
                                    <div class="col1">
                                        <div class="cont">
                                            <div class="cont-col1">
                                                <div class="label label-sm label-default">
                                                    <i class="fa fa-briefcase"></i>
                                                </div>
                                            </div>
                                            <div class="cont-col2">
                                                <div class="desc"> IPO Report for year 2013 has been released. </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col2">
                                        <div class="date"> 20 mins </div>
                                    </div>
                                </a>
                            </li>
                        </ul>
                        <h3 class="list-heading">System</h3>
                        <ul class="feeds list-items">
                            <li>
                                <div class="col1">
                                    <div class="cont">
                                        <div class="cont-col1">
                                            <div class="label label-sm label-info">
                                                <i class="fa fa-check"></i>
                                            </div>
                                        </div>
                                        <div class="cont-col2">
                                            <div class="desc"> You have 4 pending tasks.
                                                <span class="label label-sm label-warning "> Take action
                                                            <i class="fa fa-share"></i>
                                                        </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col2">
                                    <div class="date"> Just now </div>
                                </div>
                            </li>
                            <li>
                                <a href="javascript:;">
                                    <div class="col1">
                                        <div class="cont">
                                            <div class="cont-col1">
                                                <div class="label label-sm label-danger">
                                                    <i class="fa fa-bar-chart-o"></i>
                                                </div>
                                            </div>
                                            <div class="cont-col2">
                                                <div class="desc"> Finance Report for year 2013 has been released. </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col2">
                                        <div class="date"> 20 mins </div>
                                    </div>
                                </a>
                            </li>
                            <li>
                                <div class="col1">
                                    <div class="cont">
                                        <div class="cont-col1">
                                            <div class="label label-sm label-default">
                                                <i class="fa fa-user"></i>
                                            </div>
                                        </div>
                                        <div class="cont-col2">
                                            <div class="desc"> You have 5 pending membership that requires a quick review. </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col2">
                                    <div class="date"> 24 mins </div>
                                </div>
                            </li>
                            <li>
                                <div class="col1">
                                    <div class="cont">
                                        <div class="cont-col1">
                                            <div class="label label-sm label-info">
                                                <i class="fa fa-shopping-cart"></i>
                                            </div>
                                        </div>
                                        <div class="cont-col2">
                                            <div class="desc"> New order received with
                                                <span class="label label-sm label-success"> Reference Number: DR23923 </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col2">
                                    <div class="date"> 30 mins </div>
                                </div>
                            </li>
                            <li>
                                <div class="col1">
                                    <div class="cont">
                                        <div class="cont-col1">
                                            <div class="label label-sm label-success">
                                                <i class="fa fa-user"></i>
                                            </div>
                                        </div>
                                        <div class="cont-col2">
                                            <div class="desc"> You have 5 pending membership that requires a quick review. </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col2">
                                    <div class="date"> 24 mins </div>
                                </div>
                            </li>
                            <li>
                                <div class="col1">
                                    <div class="cont">
                                        <div class="cont-col1">
                                            <div class="label label-sm label-warning">
                                                <i class="fa fa-bell-o"></i>
                                            </div>
                                        </div>
                                        <div class="cont-col2">
                                            <div class="desc"> Web server hardware needs to be upgraded.
                                                <span class="label label-sm label-default "> Overdue </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col2">
                                    <div class="date"> 2 hours </div>
                                </div>
                            </li>
                            <li>
                                <a href="javascript:;">
                                    <div class="col1">
                                        <div class="cont">
                                            <div class="cont-col1">
                                                <div class="label label-sm label-info">
                                                    <i class="fa fa-briefcase"></i>
                                                </div>
                                            </div>
                                            <div class="cont-col2">
                                                <div class="desc"> IPO Report for year 2013 has been released. </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col2">
                                        <div class="date"> 20 mins </div>
                                    </div>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="tab-pane page-quick-sidebar-settings" id="quick_sidebar_tab_3">
                    <div class="page-quick-sidebar-settings-list">
                        <h3 class="list-heading">General Settings</h3>
                        <ul class="list-items borderless">
                            <li> Enable Notifications
                                <input type="checkbox" class="make-switch" checked data-size="small" data-on-color="success" data-on-text="ON" data-off-color="default" data-off-text="OFF"> </li>
                            <li> Allow Tracking
                                <input type="checkbox" class="make-switch" data-size="small" data-on-color="info" data-on-text="ON" data-off-color="default" data-off-text="OFF"> </li>
                            <li> Log Errors
                                <input type="checkbox" class="make-switch" checked data-size="small" data-on-color="danger" data-on-text="ON" data-off-color="default" data-off-text="OFF"> </li>
                            <li> Auto Sumbit Issues
                                <input type="checkbox" class="make-switch" data-size="small" data-on-color="warning" data-on-text="ON" data-off-color="default" data-off-text="OFF"> </li>
                            <li> Enable SMS Alerts
                                <input type="checkbox" class="make-switch" checked data-size="small" data-on-color="success" data-on-text="ON" data-off-color="default" data-off-text="OFF"> </li>
                        </ul>
                        <h3 class="list-heading">System Settings</h3>
                        <ul class="list-items borderless">
                            <li> Security Level
                                <select class="form-control input-inline input-sm input-small">
                                    <option value="1">Normal</option>
                                    <option value="2" selected>Medium</option>
                                    <option value="e">High</option>
                                </select>
                            </li>
                            <li> Failed Email Attempts
                                <input class="form-control input-inline input-sm input-small" value="5" /> </li>
                            <li> Secondary SMTP Port
                                <input class="form-control input-inline input-sm input-small" value="3560" /> </li>
                            <li> Notify On System Error
                                <input type="checkbox" class="make-switch" checked data-size="small" data-on-color="danger" data-on-text="ON" data-off-color="default" data-off-text="OFF"> </li>
                            <li> Notify On SMTP Error
                                <input type="checkbox" class="make-switch" checked data-size="small" data-on-color="warning" data-on-text="ON" data-off-color="default" data-off-text="OFF"> </li>
                        </ul>
                        <div class="inner-content">
                            <button class="btn btn-success">
                                <i class="icon-settings"></i> Save Changes</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- END QUICK SIDEBAR -->
</div>
<!-- END CONTAINER -->

@include('layouts.jsLibraries')
<script src="{{URL::asset('global/plugins/cubeportfolio/js/jquery.cubeportfolio.min.js')}}" type="text/javascript"></script>
<script src="{{URL::asset("pages/scripts/portfolio-1.min.js")}}" type="text/javascript"></script>

<script>

    $('.searchBtn').on('click', function () {
        $("#query").val("");

        if($(".search-form").hasClass('open')) {
            $(".search-form").removeClass('open');
        }
        else {
            $(".search-form").addClass('open');
            $(this).find('.form-control').focus();
        }
    });

    var currIdx = -1;
    var suggestions = [];

    function search(e) {

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
            }
        });

        var val = $("#query").val();
        $(".suggest").css("background-color", "transparent").css("padding", "4px").css("border-radius", "0");

        if (null == val || "" === val || val.length < 2)
            $("#searchResult").empty();
        else {

            var scrollVal = $("#searchDivForScroll").scrollTop();

            if (13 === e.keyCode && -1 !== currIdx) {
                document.location.href = suggestions[currIdx].url;
                return;
            }

            if (13 === e.keyCode && -1 === currIdx && suggestions.length > 0) {
                document.location.href = suggestions[0].url;
                return;
            }

            if (40 === e.keyCode) {
                if (currIdx + 1 < suggestions.length) {
                    currIdx++;
                    $("#searchDivForScroll").scrollTop(scrollVal + 25);
                }
                else {
                    currIdx = 0;
                    $("#searchDivForScroll").scrollTop(0);
                }

                if (currIdx >= 0 && currIdx < suggestions.length) {
                    $("#suggest_" + currIdx).css("background-color", "#dcdcdc").css("padding", "10px").css("border-radius", "5px");
                }

                return;
            }

            if (38 === e.keyCode) {
                if (currIdx - 1 >= 0) {
                    currIdx--;
                    $("#searchDivForScroll").scrollTop(scrollVal - 25);
                }
                else {
                    currIdx = suggestions.length - 1;
                    $("#searchDivForScroll").scrollTop(25 * suggestions.length);
                }

                if (currIdx >= 0 && currIdx < suggestions.length)
                    $("#suggest_" + currIdx).css("background-color", "#dcdcdc").css("padding", "10px").css("border-radius", "5px");
                return;
            }

            if ("ا" === val[0]) {

                for (var val2 = "آ", i = 1; i < val.length; i++) val2 += val[i];

                $.ajax({
                    type: "post",
                    url: '{{route('search')}}',
                    data: {
                        'key': val,
                        "key2": val2
                    },
                    success: function (response) {

                        var newElement = "";

                        if (response.length === 0) {
                            newElement = "موردی یافت نشد";
                            $("#searchResult").empty().append(newElement);
                            return;
                        }

                        response = JSON.parse(response);
                        currIdx = -1;
                        suggestions = response;

                        for (i = 0; i < response.length; i++) {
                            newElement += "<p style='cursor: pointer' class='suggest' id='suggest_" + i + "' onclick='document.location.href = \"" + response[i].url + "\"' data-url='" + response[i].url + "'>" + response[i].name + "</p>";
                        }

                        $("#searchResult").empty().append(newElement);
                    }
                })
            }
            else {

                $.ajax({
                    type: "post",
                    url: '{{route('search')}}',
                    data: {
                        "key": val
                    },
                    success: function (response) {

                        var newElement = "";

                        if (response.length === 0) {
                            $("#searchResult").empty().append(newElement);
                            return;
                        }

                        response = JSON.parse(response);
                        currIdx = -1;
                        suggestions = response;

                        for (i = 0; i < response.length; i++) {
                            newElement += "<p style='cursor: pointer' class='suggest' id='suggest_" + i + "' onclick='document.location.href = \"" + response[i].url + "\"'>" + response[i].name + "</p>";
                        }

                        $("#searchResult").empty().append(newElement);
                    }
                });
            }
        }
    }

</script>

</body>

</html>