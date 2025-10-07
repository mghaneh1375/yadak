@extends('layouts.structure')

@section('header')
    @parent

    <link href="{{URL::asset('global/plugins/datatables/datatables.min.css')}}" rel="stylesheet" type="text/css"
          xmlns="http://www.w3.org/1999/html"/>
    <link href="{{URL::asset('global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.css')}}" rel="stylesheet"
          type="text/css"/>

    <link href="{{URL::asset('pages/css/login-2.min.css')}}" rel="stylesheet" type="text/css"/>

    <style>

        * {
            box-sizing: border-box;
        }

        label {
            display: inline-block;
            min-width: 100px;
        }

        input, select {
            min-width: 200px;
        }

        .col-xs-12 {
            padding: 10px;
        }

        .column {
            float: left;
            width: 33.33%;
            padding: 5px;
            height: 300px;
            max-height: 300px;

        }

        /* Clearfix (clear floats) */
        .row::after {
            content: "";
            clear: both;
            display: table;
        }

        .overlay {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            background-color: #008CBA;
            overflow: hidden;
            width: 100%;
            height: 100%;
            -webkit-transform: scale(0);
            -ms-transform: scale(0);
            transform: scale(0);
            -webkit-transition: .3s ease;
            transition: .3s ease;
        }

        .container:hover .overlay {
            -webkit-transform: scale(1);
            -ms-transform: scale(1);
            transform: scale(1);
        }

        .text {
            color: white;
            font-size: 20px;
            position: absolute;
            top: 50%;
            left: 50%;
            -webkit-transform: translate(-50%, -50%);
            -ms-transform: translate(-50%, -50%);
            transform: translate(-50%, -50%);
            text-align: center;
        }

        .container {
            position: relative;
        }

        .modal {
            display: none; /* Hidden by default */
            position: fixed; /* Stay in place */
            z-index: 1; /* Sit on top */
            padding-top: 100px; /* Location of the box */
            left: 0;
            top: 0;
            width: 100%; /* Full width */
            height: 100%; /* Full height */
            overflow: auto; /* Enable scroll if needed */
            background-color: rgb(0,0,0); /* Fallback color */
            background-color: rgba(0,0,0,0.4); /* Black w/ opacity */
        }

        /* Modal Content */
        .modal-content {
            position: relative;
            background-color: #fefefe;
            margin: auto;
            padding: 0;
            border: 1px solid #888;
            width: 30%;
            direction: rtl;
            box-shadow: 0 4px 8px 0 rgba(0,0,0,0.2),0 6px 20px 0 rgba(0,0,0,0.19);
            -webkit-animation-name: animatetop;
            -webkit-animation-duration: 0.4s;
            animation-name: animatetop;
            animation-duration: 0.4s
        }

        @-webkit-keyframes animatetop {
            from {top:-300px; opacity:0}
            to {top:0; opacity:1}
        }

        @keyframes animatetop {
            from {top:-300px; opacity:0}
            to {top:0; opacity:1}
        }

    </style>

    <script src="//cdn.ckeditor.com/4.10.1/full/ckeditor.js"></script>    <!-- END PAGE LEVEL STYLES -->
@stop

@section('content')

<!-- BEGIN CONTENT BODY -->
<!-- BEGIN PAGE HEAD-->
<div class="page-head">
    <div class="container">
        <!-- BEGIN PAGE TOOLBAR -->
        <div class="page-toolbar">
            <!-- BEGIN THEME PANEL -->
            <div class="btn-group btn-theme-panel">
                <div class="dropdown-menu theme-panel pull-right dropdown-custom hold-on-click">
                    <div class="row">
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <h3>THEME COLORS</h3>
                            <div class="row">
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <ul class="theme-colors">
                                        <li class="theme-color theme-color-default" data-theme="default">
                                            <span class="theme-color-view"></span>
                                            <span class="theme-color-name">Default</span>
                                        </li>
                                        <li class="theme-color theme-color-blue-hoki" data-theme="blue-hoki">
                                            <span class="theme-color-view"></span>
                                            <span class="theme-color-name">Blue Hoki</span>
                                        </li>
                                        <li class="theme-color theme-color-blue-steel" data-theme="blue-steel">
                                            <span class="theme-color-view"></span>
                                            <span class="theme-color-name">Blue Steel</span>
                                        </li>
                                        <li class="theme-color theme-color-yellow-orange" data-theme="yellow-orange">
                                            <span class="theme-color-view"></span>
                                            <span class="theme-color-name">Orange</span>
                                        </li>
                                        <li class="theme-color theme-color-yellow-crusta" data-theme="yellow-crusta">
                                            <span class="theme-color-view"></span>
                                            <span class="theme-color-name">Yellow Crusta</span>
                                        </li>
                                    </ul>
                                </div>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <ul class="theme-colors">
                                        <li class="theme-color theme-color-green-haze" data-theme="green-haze">
                                            <span class="theme-color-view"></span>
                                            <span class="theme-color-name">Green Haze</span>
                                        </li>
                                        <li class="theme-color theme-color-red-sunglo" data-theme="red-sunglo">
                                            <span class="theme-color-view"></span>
                                            <span class="theme-color-name">Red Sunglo</span>
                                        </li>
                                        <li class="theme-color theme-color-red-intense" data-theme="red-intense">
                                            <span class="theme-color-view"></span>
                                            <span class="theme-color-name">Red Intense</span>
                                        </li>
                                        <li class="theme-color theme-color-purple-plum" data-theme="purple-plum">
                                            <span class="theme-color-view"></span>
                                            <span class="theme-color-name">Purple Plum</span>
                                        </li>
                                        <li class="theme-color theme-color-purple-studio" data-theme="purple-studio">
                                            <span class="theme-color-view"></span>
                                            <span class="theme-color-name">Purple Studio</span>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 col-sm-6 col-xs-12 seperator">
                            <h3>LAYOUT</h3>
                            <ul class="theme-settings">
                                <li> Theme Style
                                    <select class="theme-setting theme-setting-style form-control input-sm input-small input-inline tooltips" data-original-title="Change theme style" data-container="body" data-placement="left">
                                        <option value="boxed" selected="selected">Square corners</option>
                                        <option value="rounded">Rounded corners</option>
                                    </select>
                                </li>
                                <li> Layout
                                    <select class="theme-setting theme-setting-layout form-control input-sm input-small input-inline tooltips" data-original-title="Change layout type" data-container="body" data-placement="left">
                                        <option value="boxed" selected="selected">Boxed</option>
                                        <option value="fluid">Fluid</option>
                                    </select>
                                </li>
                                <li> Top Menu Style
                                    <select class="theme-setting theme-setting-top-menu-style form-control input-sm input-small input-inline tooltips" data-original-title="Change top menu dropdowns style" data-container="body"
                                            data-placement="left">
                                        <option value="dark" selected="selected">Dark</option>
                                        <option value="light">Light</option>
                                    </select>
                                </li>
                                <li> Top Menu Mode
                                    <select class="theme-setting theme-setting-top-menu-mode form-control input-sm input-small input-inline tooltips" data-original-title="Enable fixed(sticky) top menu" data-container="body"
                                            data-placement="left">
                                        <option value="fixed">Fixed</option>
                                        <option value="not-fixed" selected="selected">Not Fixed</option>
                                    </select>
                                </li>
                                <li> Mega Menu Style
                                    <select class="theme-setting theme-setting-mega-menu-style form-control input-sm input-small input-inline tooltips" data-original-title="Change mega menu dropdowns style" data-container="body"
                                            data-placement="left">
                                        <option value="dark" selected="selected">Dark</option>
                                        <option value="light">Light</option>
                                    </select>
                                </li>
                                <li> Mega Menu Mode
                                    <select class="theme-setting theme-setting-mega-menu-mode form-control input-sm input-small input-inline tooltips" data-original-title="Enable fixed(sticky) mega menu" data-container="body"
                                            data-placement="left">
                                        <option value="fixed" selected="selected">Fixed</option>
                                        <option value="not-fixed">Not Fixed</option>
                                    </select>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <!-- END THEME PANEL -->
        </div>
        <!-- END PAGE TOOLBAR -->
    </div>
</div>
<!-- END PAGE HEAD-->
<!-- BEGIN PAGE CONTENT BODY -->
<div class="page-content" style="margin-top: 50px">
    <div class="container">

        <!-- BEGIN PAGE CONTENT INNER -->
        <div class="page-content-inner">

            <div class="row">

                @foreach($categories as $category)

                    <div style="direction: rtl" class="col-lg-6">
                        <div class="portlet light portlet-fit ">
                            <div class="portlet-body">
                                <div class="mt-element-list">
                                    <div class="mt-list-head list-default green-seagreen">
                                        <div class="list-head-title-container">
                                            <h3 class="list-title uppercase sbold">{{$category->name}}</h3>
                                        </div>
                                    </div>
                                    <div class="mt-list-container list-default ext-1 group">
                                        <div class="mt-list-title uppercase">افزودن سوال جدید
                                            <span style="margin-left: 5px" class="badge badge-default pull-right bg-hover-green-jungle">
                                                <span style="cursor: pointer" onclick="addNew('{{$category->id}}')" class="font-white">
                                                    <i class="fa fa-plus"></i>
                                                </span>
                                            </span>
                                        </div>
                                        <a class="list-toggle-container" data-toggle="collapse" href="#completed" aria-expanded="true">
                                            <div class="list-toggle done uppercase"> سوالات موجود
                                                <span style="width: 20px; height: 20px; padding-top: 6px; padding-right: 7px; margin-left: 5px;" class="badge badge-default pull-right bg-white font-green bold">{{count($category->questions)}}</span>
                                            </div>
                                        </a>
                                        <div class="panel-collapse collapse in" id="completed">
                                            <ul>
                                                @foreach($category->questions as $itr)
                                                    <li class="mt-list-item done">
                                                        <div class="list-icon-container">
                                                            <span style="cursor: pointer" onclick="deleteQuestion('{{$itr->id}}')">
                                                                <i class="icon-trash"></i>
                                                            </span>
                                                        </div>
                                                        <div class="list-item-content">
                                                            <h3 class="uppercase">
                                                                <span>{!! $itr->question !!}</span>
                                                            </h3>
                                                            <p>{!! $itr->answer !!}</p>
                                                        </div>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                @endforeach
            </div>

        </div>
    </div>
</div>

<div id="myModal" class="modal">
    <form action="{{route('addCommonQuestion')}}" method="post">
        {{ csrf_field() }}
        <div class="modal-content" style="width: 75%">
            <input type="hidden" value="" id="catId" name="catId">
            <h2 style="padding-right: 5%;">افزودن سوال جدید</h2>

            <div id="editor" style="margin-top: 30px">
                <center><h3>متن سوال</h3></center>
                <textarea id="editor1" cols="80" name="question" required></textarea>
            </div>

            <div style="margin-top: 30px">
                <center><h3>متن جواب</h3></center>
                <textarea id="editor2" cols="80" name="answer" required></textarea>
            </div>

            <center style="margin-top: 20px">
                <input type="submit" value="افزودن" class="btn green"  style="margin-right: 5%; margin-bottom: 3%">
                <input type="button" value="انصراف" class="btn green"  style="margin-bottom: 3%; margin-left: 5%;" onclick="document.getElementById('myModal').style.display = 'none'">
            </center>
        </div>
    </form>
</div>

<div id="myModalDel" class="modal">
    <form action="{{route('deleteCommonQuestion')}}" method="post">
        {{ csrf_field() }}
        <div class="modal-content">
            <input type="hidden" value="" id="slideId" name="id">
            <h2 style="padding-right: 5%;">ایا از حذف اطیمنان دارید؟</h2>
            <input type="submit" value="بله" class="btn green"  style="margin-right: 5%; margin-bottom: 3%">
            <input type="button" value="خیر" class="btn green"  style="float: left; margin-bottom: 3%; margin-left: 5%;" onclick="document.getElementById('myModalDel').style.display = 'none'">
        </div>
    </form>
</div>

@stop

@section('moreJS')

    <script>

        var modal = document.getElementById('myModal');
        window.onclick = function(event) {
            if (event.target == modal) {
                modal.style.display = "none";
            }
        }

        CKEDITOR.replace('editor1');
        CKEDITOR.replace('editor2');

        function addNew(catId) {
            document.getElementById('myModal').style.display = 'block';
            document.getElementById('catId').value = catId;
        }

        function deleteQuestion(id) {
            document.getElementById('myModalDel').style.display = 'block';
            document.getElementById('slideId').value = id;
        }

    </script>

@stop