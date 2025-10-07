@extends('layouts.structure')

@section('header')
    @parent

    <!-- BEGIN PAGE LEVEL PLUGINS -->
    <link href="{{URL::asset('global/plugins/datatables/datatables.min.css')}}" rel="stylesheet" type="text/css"
          xmlns="http://www.w3.org/1999/html"/>
    <link href="{{URL::asset('global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.css')}}" rel="stylesheet"
          type="text/css"/>
    <!-- END PAGE LEVEL PLUGINS -->

    <!-- BEGIN PAGE LEVEL STYLES -->
    <link href="{{URL::asset('pages/css/login-2.min.css')}}" rel="stylesheet" type="text/css"/>

@stop

@section('content')

    <style>
        * {
            box-sizing: border-box;
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
        .cke_chrome {
            margin-top: 20px;
            border: none !important;
        }
    </style>

    <div class="page-content" style="margin-top: 50px">

        <div class="portlet box red">
            <div class="portlet-title">
                <div class="caption" style="float: right">
دسته بندی ها
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12" style="direction: rtl">
                <div class="portlet light bordered">
                    <center class="portlet-body">

                        @if(count($items) == 0)
                            <p>محتوایی در این بخش وجود ندارد</p>
                        @else
                            <center>
                                @foreach($items as $item)
                                    <div style="margin: 20px">
                                        <p>{{$item->name}}</p>
                                        <div>
                                            <button onclick="deleteItem('{{$item->id}}')" class="btn btn-danger">حذف</button>
                                            <button onclick="editItem('{{$item->id}}', '{{$item->name}}')" class="btn btn-success">ویرایش</button>
                                        </div>
                                    </div>
                                @endforeach
                            </center>
                        @endif

                        <form method="post" action="{{route('addFaqCategory')}}">
                            {{csrf_field()}}

                            <div id="editor" style="margin-top: 30px">
                                <input maxlength="100" name="name" required type="text">
                            </div>

                            <div class="margin-top-10">
                                <input type="submit" class="btn green" value="افزودن دسته جدید">
                                <p style="margin-top: 10px">{{$err}}</p>
                            </div>
                        </form>
                    </center>
                </div>
            </div>

            <div id="myModal" class="modal">
                <form action="{{route('deleteFaqCategory')}}" method="post">
                    {{ csrf_field() }}
                    <div class="modal-content">
                        <input type="hidden" value="" id="slideId" name="categoryId">
                        <h2 style="padding-right: 5%;">ایا اطیمنان دارید؟</h2>
                        <input type="submit" value="بله" class="btn green"  style="margin-right: 5%; margin-bottom: 3%">
                        <input type="button" value="خیر" class="btn green"  style="float: left; margin-bottom: 3%; margin-left: 5%;" onclick="document.getElementById('myModal').style.display = 'none'">
                    </div>
                </form>
            </div>

            <div id="myEditModal" class="modal">
                <form action="{{route('editFaqCategory')}}" method="post">
                    {{ csrf_field() }}
                    <div class="modal-content">
                        <input type="hidden" value="" id="categoryId" name="categoryId">
                        <h2 style="padding-right: 5%;">نام جدید دسته</h2>
                        <input type="text" id="oldName" name="newName" required maxlength="100">
                        <input type="submit" value="ویرایش" class="btn green"  style="margin-right: 5%; margin-bottom: 3%">
                        <input type="button" value="انصراف" class="btn green"  style="float: left; margin-bottom: 3%; margin-left: 5%;" onclick="document.getElementById('myEditModal').style.display = 'none'">
                    </div>
                </form>
            </div>

        </div>
    </div>

    <script>

        var selectedId = -1;
        
        function deleteItem(id) {
            document.getElementById('myModal').style.display = 'block';
            document.getElementById('slideId').value = id;
        }

        function editItem(id, name) {
            document.getElementById('myEditModal').style.display = 'block';
            document.getElementById('categoryId').value = id;
            document.getElementById('oldName').value = name;
        }


    </script>

@stop