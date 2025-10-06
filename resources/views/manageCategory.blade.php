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
    <!-- END PAGE LEVEL STYLES -->
    <meta name="_token" content="{{ csrf_token() }}"/>

@stop

@section('content')


    <style>
        .accordion {
            background-color: #eee;
            color: #444;
            cursor: pointer;
            padding: 10px;
            width: 100%;
            border: none;
            text-align: center;
            outline: none;
            font-size: 15px;
            transition: 0.4s;
        }

        .accordion_category {
            background-color: #eee;
            color: #444;
            cursor: pointer;
            padding: 10px;
            width: 100%;
            border: none;
            text-align: center;
            outline: none;
            font-size: 15px;
            transition: 0.4s;
        }

        .active, .accordion:hover {
            background-color: #ccc;
        }

        .panel {
            padding: 0 18px;
            background-color: white;
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.2s ease-out;
        }

        .modal {
            display: none; /* Hidden by default */
            position: fixed; /* Stay in place */
            z-index: 999999; /* Sit on top */
            padding-top: 100px; /* Location of the box */
            left: 0;
            top: 0;
            width: 100%; /* Full width */
            height: 100%; /* Full height */
            overflow: auto; /* Enable scroll if needed */
            background-color: rgb(0, 0, 0); /* Fallback color */
            background-color: rgba(0, 0, 0, 0.4); /* Black w/ opacity */
        }

        /* Modal Content */
        .modal-content {
            position: relative;
            background-color: #fefefe;
            margin: auto;
            padding: 1%;
            border: 1px solid #888;
            direction: rtl;
            width: 30%;
            box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);
            -webkit-animation-name: animatetop;
            -webkit-animation-duration: 0.4s;
            animation-name: animatetop;
            animation-duration: 0.4s
        }

        /* Add Animation */
        @-webkit-keyframes animatetop {
            from {
                top: -300px;
                opacity: 0
            }
            to {
                top: 0;
                opacity: 1
            }
        }

        @keyframes animatetop {
            from {
                top: -300px;
                opacity: 0
            }
            to {
                top: 0;
                opacity: 1
            }
        }

        .modal-body {
            padding: 2px 16px;
        }

        tr {
            border: solid;
            text-align: right;
            padding: 2%;
            width: 100%;
        }
        th{
            text-align: center;
            padding: 3%;
            font-size: 140%;
            background-color: lemonchiffon;
        }
        td{
            padding: 2%;
            background-color: aliceblue;
            padding-right: 10%;
        }
        table{
            margin-top: 3%;
            margin-bottom: 3%;
        }
    </style>
    <!-- BEGIN CONTENT BODY -->
    <div id="mainContainer" class="page-content" style="margin-top: 5%; direction: rtl">
        @for($i = 0; $i < count($superCategory); $i++)


            <button type="button" class="accordion" id="{{$superCategory[$i]->id}}">
                <form id="save_super_category" action="{{route('saveSuperCategory')}}" method="post"
                      enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <input type="hidden" value="edit" name="kind">
                    <input type="hidden" value="{{$superCategory[$i]->id}}" name="superCategoryId">

                    <div style="display: inline-block;">
                        <h4 id="super_category_name_{{$superCategory[$i]->id}}"
                            ondblclick="editSuperCategory({{$superCategory[$i]->id}})"
                            style="display: inline-block; transform: translateX(0%) translateY(20%);">{{$superCategory[$i]->name}}</h4>
                        @if($superCategory[$i]->icon != null)

                        @endif
                        <input id="edit_super_category_name_{{$superCategory[$i]->id}}" type="text"
                               value="{{$superCategory[$i]->name}}" style="display: none;" name="name"
                               onchange="changeSuperCategoryName({{$superCategory[$i]->id}})"><br>
                        <input value="حذف" id="delete_super_category_{{$superCategory[$i]->id}}" type="button"
                               class="btn green" onclick="deleteSuperCategory({{$superCategory[$i]->id}})"
                               style="display: none; background-color: rebeccapurple;">

                    </div>
                    <div style="display: inline-block; float: left;">
                        <input id="edit_super_category_submit_{{$superCategory[$i]->id}}" type="submit"
                               value="ویرایش"
                               class="btn green" style="display: none; float: left">
                        <input id="edit_super_category_back_{{$superCategory[$i]->id}}" type="button" value="بستن"
                               class="btn green" style="display: none; float: right"
                               onclick="checkSuperName({{$superCategory[$i]->id}})">
                    </div>
                </form>
            </button>

            <div class="panel">
                @for($j = 0; $j < count($category); $j++)
                    @if($category[$j]->super_category_id == $superCategory[$i]->id)
                        <div style="display: inline-block;  width: 30%; margin-left: 3%;">
                            <form id="save_category" action="{{route('saveCategory')}}" method="post"
                                  enctype="multipart/form-data">
                                {{ csrf_field() }}
                                <input type="hidden" value="edit" name="kind">
                                <input type="hidden" value="{{$category[$j]->id}}" name="categoryId">

                                <button class="accordion_category" type="button" style="margin-top: 5%">
                                    <div style="display: inline-block;">
                                        <h4 id="category_name_{{$category[$j]->id}}"
                                            ondblclick="editCategory({{$category[$j]->id}})"
                                            style="display: inline-block; transform: translateX(0%) translateY(20%);">{{$category[$j]->name}}</h4>
                                        @if($category[$j]->icon!= null)

                                        @endif
                                        <input id="edit_category_name_{{$category[$j]->id}}" type="text"
                                               value="{{$category[$j]->name}}" style="display: none;" name="name"
                                               onchange="editCategoryName({{$category[$j]->id}})">
                                    </div>
                                    <br>
                                    <div id="edit_category_{{$category[$j]->id}}"
                                         style="display: inline-block; width: 100%; display: none;">

                                        <input id="edit_category_submit_{{$category[$j]->id}}" type="submit"
                                               value="ویرایش"
                                               class="btn green" style="display: none; float: left">
                                        <input value="حذف" id="delete_category_{{$category[$j]->id}}"
                                               type="button" class="btn green"
                                               onclick="deleteCategory({{$category[$j]->id}})"
                                               style="display: inline-block; background-color: rebeccapurple;">

                                        <input id="edit_category_back_{{$category[$j]->id}}" type="button"
                                               value="بستن"
                                               class="btn green" style="display: none; float: right"
                                               onclick="checkCategoryName({{$category[$j]->id}})">
                                    </div>

                                </button>
                                <div class="panel" style="margin-bottom: 3%;">
                                    <table style="width: 100%;">
                                        @for($k = 0; $k < count($item); $k++)
                                            @if($item[$k]->category_id == $category[$j]->id && $item[$k]->base_item_id == 0 )
                                                <tr>
                                                    <th  ondblclick="editItem({{$item[$k]->id}})">
                                                        <h4 id="item_name_{{$item[$k]->id}}">{{$item[$k]->name}}</h4>
                                                        <input id="edit_item_name_{{$item[$k]->id}}" value="{{$item[$k]->name}}" style="display: none; float: right; margin-bottom: 5%;" onchange="changeItem({{$item[$k]->id}})">
                                                        <input id="add_item_{{$item[$k]->id}}" type="button"
                                                               value="افزودن"
                                                               class="btn green" style="display: none; float: right"
                                                               onclick="newItemFunc({{$item[$k]->id}})">
                                                        <input id="edit_item_back_{{$item[$k]->id}}" type="button"
                                                               value="بستن"
                                                               class="btn green" style="display: none; "
                                                               onclick="checkItemName({{$item[$k]->id}})">
                                                        <input id="delete_item_{{$item[$k]->id}}" type="button"
                                                               value="حذف"
                                                               class="btn green" style="display: none; float: left"
                                                               onclick="deleteItem({{$item[$k]->id}})">

                                                    </th>
                                                </tr>
                                                @for($l = 0; $l < count($item); $l++)
                                                    @if( $item[$l]->base_item_id == $item[$k]->id)
                                                        <tr>
                                                            <td ondblclick="editItem({{$item[$l]->id}})">
                                                                <h5 id="item_name_{{$item[$l]->id}}">{{$item[$l]->name}}</h5>
                                                                <input id="edit_item_name_{{$item[$l]->id}}" value="{{$item[$l]->name}}" style="display: none; float: right; margin-bottom: 5%;" onchange="changeItem({{$item[$l]->id}})">
                                                                <input id="edit_item_back_{{$item[$l]->id}}" type="button"
                                                                       value="بستن"
                                                                       class="btn green" style="display: none; float: right;"
                                                                       onclick="checkItemName({{$item[$l]->id}})">
                                                                <input id="delete_item_{{$item[$l]->id}}" type="button"
                                                                       value="حذف"
                                                                       class="btn green" style="display: none; float: left"
                                                                       onclick="deleteItem({{$item[$l]->id}})">
                                                            </td>
                                                        </tr>
                                                    @endif
                                                @endfor
                                            @endif
                                        @endfor
                                    </table>
                                    <input id="myBtn" type="button"
                                           value="افزودن"
                                           class="btn green" style="display: block; float: right"
                                           onclick="openPage({{$category[$j]->id}})">
                                </div>
                            </form>
                        </div>

                    @endif
                @endfor
                <div style="display: inline-block;  width: 30%; margin-left: 3%; margin-top: 2%;">

                    <button class="accordion_category" type="button">افزودن زیربخش</button>

                    <div class="panel">
                        <form id="save_category" action="{{route('saveCategory')}}" method="post"
                              enctype="multipart/form-data">
                            {{ csrf_field() }}
                            <input type="hidden" value="save" name="kind">
                            <input type="hidden" value="{{$superCategory[$i]->id}}" name="superCategoryId">
                            <label>نام زیربخش</label>
                            <input id="new_category_name" type="text"
                                   style="display: inline-block; margin-left:10%; margin-top: 3%;" name="name">

                            <input id="new_category_submit" type="submit" value="ذخیره"
                                   class="btn green" style=" display: inline-block; float: left; margin-top: 3%;">
                        </form>
                    </div>
                </div>

                <div style="display: inline-block; margin-left: 3%; margin-top: 2%;">
                    <button onclick="rmCategory('{{$superCategory[$i]->id}}')" class="rm_category btn btn-danger" type="button">حذف</button>
                </div>

            </div>

        @endfor

        <button class="accordion">افزودن بخش اصلی</button>
        <div class="panel">
            <form id="save_super_category" action="{{route('saveSuperCategory')}}" method="post"
                  enctype="multipart/form-data">
                {{ csrf_field() }}
                <input type="hidden" value="save" name="kind">
                <label>نام سر بخش</label>
                <input id="new_super_category_name" type="text"
                       style="display: inline-block; margin-left:10%; margin-top: 3%;" name="name">

                <input id="new_super_category_submit" type="submit" value="ذخیره" class="btn green"
                       style=" display: inline-block; float: left; margin-top: 3%;">
            </form>
        </div>
    </div>


    <div id="myModal" class="modal">
        <form id="save_item" action="{{route('saveItem')}}" method="post"
              enctype="multipart/form-data">
            {{ csrf_field() }}
            <input type="hidden" value="save" name="kind">
            <input type="hidden" value="" id="category_id_item" name="categoryId">
            <div class="modal-content">
                <center>
                    <div class="modal-body">
                        <div id="base" style="margin-bottom: 5%;">
                            <label>نام ایتم اصلی</label>
                            <input type="text" id="new_baseItem_name" name="baseName">
                        </div>
                        <div id="items">

                        </div>
                        <input value="افزودن ایتم"
                               type="button" class="btn green"
                               onclick="addItem()"
                               style="display: inline-block; background-color: rebeccapurple;">
                        <input value="ثبت"
                               type="submit" class="btn green"
                               style="display: inline-block; float: left;">
                    </div>
                </center>
            </div>
        </form>
    </div>

    <div id="newItems" class="modal">
        <form id="save_item" action="{{route('saveItem')}}" method="post"
              enctype="multipart/form-data">
            {{ csrf_field() }}
            <input type="hidden" value="save" name="kind">
            <input type="hidden" value="" id="base_item" name="baseId">
            <div class="modal-content">
                <center>
                    <div class="modal-body">
                        <div id="base" style="margin-bottom: 5%;">
                            <label>نام ایتم 1</label>
                            <input type="text" id="name" name="name[0]">
                        </div>
                        <div id="items2">

                        </div>
                        <input value="افزودن ایتم"
                               type="button" class="btn green"
                               onclick="addItems()"
                               style="display: inline-block; background-color: rebeccapurple;">
                        <input value="ثبت"
                               type="submit" class="btn green"
                               style="display: inline-block; float: left;">
                    </div>
                </center>
            </div>
        </form>
    </div>

    <style>
        .register-form {
            width: 400px;
            background-color: white;
            padding: 20px;
            position: absolute;
            top: 50px;
            left: 35%;
            right: auto;
        }

        input, textarea, select {
            text-align: -webkit-right;
            direction: rtl;
        }
    </style>
@stop

@section('moreJS')
    <!-- BEGIN PAGE LEVEL PLUGINS -->
    <script src="{{URL::asset('global/scripts/datatable.js')}}" type="text/javascript"></script>
    <script src="{{URL::asset('global/plugins/datatables/datatables.min.js')}}" type="text/javascript"></script>
    <script src="{{URL::asset('global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.js')}}"
            type="text/javascript"></script>
    <!-- END PAGE LEVEL PLUGINS -->

    <!-- BEGIN PAGE LEVEL SCRIPTS -->
    <script src="{{URL::asset('pages/scripts/table-datatables-managed.min.js')}}" type="text/javascript"></script>
    <!-- END PAGE LEVEL SCRIPTS -->

    <script>
        var modal = document.getElementById('myModal');
        var modals = document.getElementById('newItems');
        window.onclick = function (event) {
            if (event.target == modal) {
                modal.style.display = "none";
            }
            if (event.target == modals) {
                modals.style.display = "none";
            }
        }
    </script>

    <script>
        $.ajaxSetup(
            {
                headers:
                    {
                        'X-CSRF-Token': $('input[name="_token"]').val()
                    }
            });
    </script>

    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
    </script>

    <script>
        var SuperCategory = {!! json_encode($superCategory) !!};

        function editSuperCategory(id) {
            document.getElementById('super_category_name_' + id).style.display = 'none';
            document.getElementById('edit_super_category_name_' + id).style.display = 'inline-block';
            document.getElementById('edit_super_category_icon_' + id).style.display = 'inline-block';
            document.getElementById('edit_super_category_submit_' + id).style.display = 'inline-block';
            document.getElementById('edit_super_category_back_' + id).style.display = 'inline-block';
            document.getElementById('delete_super_category_' + id).style.display = 'inline-block';
        }

        function editCategory(id) {
            document.getElementById('category_name_' + id).style.display = 'none';
            document.getElementById('edit_category_name_' + id).style.display = 'inline-block';
            document.getElementById('edit_category_icon_' + id).style.display = 'inline-block';
            document.getElementById('edit_category_submit_' + id).style.display = 'inline-block';
            document.getElementById('edit_category_back_' + id).style.display = 'inline-block';
            document.getElementById('edit_category_' + id).style.display = 'inline-block';
            document.getElementById('delete_category_' + id).style.display = 'inline-block';
        }

        function checkSuperName(id) {
            var disp = document.getElementById('super_category_name_' + id).style.display;
            if (disp == 'none') {
                document.getElementById('super_category_name_' + id).style.display = 'inline-block';
                document.getElementById('edit_super_category_name_' + id).style.display = 'none';
                document.getElementById('edit_super_category_icon_' + id).style.display = 'none';
                document.getElementById('edit_super_category_submit_' + id).style.display = 'none';
                document.getElementById('edit_super_category_back_' + id).style.display = 'none';
                document.getElementById('delete_super_category_' + id).style.display = 'none';
            }
        }

        function checkCategoryName(id) {
            var disp = document.getElementById('category_name_' + id).style.display;
            if (disp == 'none') {
                document.getElementById('category_name_' + id).style.display = 'inline-block';
                document.getElementById('edit_category_name_' + id).style.display = 'none';
                document.getElementById('edit_category_icon_' + id).style.display = 'none';
                document.getElementById('edit_category_submit_' + id).style.display = 'none';
                document.getElementById('edit_category_back_' + id).style.display = 'none';
                document.getElementById('edit_category_' + id).style.display = 'none';
                document.getElementById('delete_category_' + id).style.display = 'none';
            }
        }

        function editItem(id) {
            document.getElementById('item_name_' + id).style.display = 'none';
            document.getElementById('edit_item_name_' + id).style.display = 'inline-block';
            document.getElementById('edit_item_back_' + id).style.display = 'inline-block';
            document.getElementById('delete_item_' + id).style.display = 'inline-block';
            document.getElementById('add_item_' + id).style.display = 'inline-block';
        }

        function checkItemName(id) {
            var disp = document.getElementById('item_name_' + id).style.display;
            if (disp == 'none') {
                document.getElementById('item_name_' + id).style.display = 'inline-block';
                document.getElementById('edit_item_name_' + id).style.display = 'none';
                document.getElementById('edit_item_back_' + id).style.display = 'none';
                document.getElementById('delete_item_' + id).style.display = 'none';
                document.getElementById('add_item_' + id).style.display = 'none';
            }
        }

        function rmCategory(id) {

            $.ajax({
                type: 'post',
                url: '{{route('removeCategory')}}',
                data: {
                    'id': id
                },
                success: function (res) {

                    if(res == "ok") {
                        $("#" + id).remove();
                        $(".panel").css('max-height', '0');
                    }
                }
            });
        }

    </script>

    <script>
        var acc = document.getElementsByClassName("accordion");
        var i;
        var panel;
        var panel_number;

        for (i = 0; i < acc.length; i++) {
            acc[i].addEventListener("click", function () {
                this.classList.toggle("active");
                panel = this.nextElementSibling;
                if (panel.style.maxHeight) {
                    panel.style.maxHeight = null;
                    panel_number = 0;
                } else {
                    panel_number = panel.scrollHeight + 40;
                    panel.style.maxHeight = panel_number + "px";
                }
            });
        }
    </script>

    <script>
        var acc_category = document.getElementsByClassName("accordion_category");
        var i;
        var panel_category;
        var panel_category_number;

        for (i = 0; i < acc_category.length; i++) {
            acc_category[i].addEventListener("click", function () {
                this.classList.toggle("active");
                panel_category = this.nextElementSibling;
                if (panel_category.style.maxHeight) {
                    panel_category.style.maxHeight = null;
                    panel.style.maxHeight = panel_number + "px";
                }
                else {
                    panel_category_number = panel_number + panel_category.scrollHeight;
                    panel_category.style.maxHeight = panel_category.scrollHeight + "px";
                    panel.style.maxHeight = panel_category_number + "px";

                }
            });
        }
    </script>

    <script>
        var numOfItem = 0;

        function changeSuperCategoryName(id) {
            var newSuperName = document.getElementById('edit_super_category_name_' + id).value;
            if (newSuperName != '') {
                $.ajax({
                    type: 'post',
                    url: '{{url('saveSuperCategory')}}',
                    data: {
                        'id': id,
                        'name': newSuperName,
                        'kind': 'editName'
                    },
                    success: function (response) {
                        if (response == 'ok') {
                            document.getElementById('edit_super_category_name_' + id).value = newSuperName;
                            document.getElementById('super_category_name_' + id).innerText = newSuperName;
                        }
                    }
                });
            }
        }

        function deleteSuperCategory(id) {
            $.ajax({
                type: 'post',
                url: '{{url('saveSuperCategory')}}',
                data: {
                    'id': id,
                    'kind': 'delete'
                },
                success: function (response) {
                    if (response == 'ok') {
                        document.location.href = '{{route('manageCategory')}}';
                    }
                }
            });

        }

        function editCategoryName(id) {
            var newCategoryName = document.getElementById('edit_category_name_' + id).value;
            if (newCategoryName != '') {
                $.ajax({
                    type: 'post',
                    url: '{{url('saveCategory')}}',
                    data: {
                        'id': id,
                        'name': newCategoryName,
                        'kind': 'editName'
                    },
                    success: function (response) {
                        if (response == 'ok') {
                            document.getElementById('edit_category_name_' + id).value = newCategoryName;
                            document.getElementById('category_name_' + id).innerText = newCategoryName;
                        }
                    }
                });
            }
        }

        function deleteCategory(id) {
            $.ajax({
                type: 'post',
                url: '{{url('saveCategory')}}',
                data: {
                    'id': id,
                    'kind': 'delete'
                },
                success: function (response) {
                    if (response == 'ok') {
                        document.location.href = '{{route('manageCategory')}}';
                    }
                }
            });
        }

        function openPage(id) {
            document.getElementById('myModal').style.display = 'block';
            document.getElementById('category_id_item').value = id;
            document.getElementById('items').innerHTML = '';
            numOfItem = 0;
        }

        function addItem() {
            var text = '<label>ایتم ' + (numOfItem + 1) + ':</label>\n' +
                '<input type="text" name="name[' + numOfItem + ']" style="margin-bottom: 3%;"><br>';

            $('#items').append(text);
            numOfItem++;
        }

        function changeItem(id) {
            var name = document.getElementById('edit_item_name_' + id).value ;
            if(name != ''){
                $.ajax({
                    type: 'post',
                    url: '{{url("saveItem")}}',
                    data:{
                        'id': id,
                        'name': name,
                        'kind': 'edit'
                    },
                    success: function (response) {
                        if (response == 'ok') {
                            document.getElementById('edit_item_name_' + id).value = name;
                            document.getElementById('item_name_' + id).innerText = name;
                        }
                    }
                });
            }
        }

        function deleteItem(id) {
            $.ajax({
                type: 'post',
                url: '{{url('saveItem')}}',
                data:{
                    'id': id,
                    'kind': 'delete'
                },
                success: function (response) {
                    if (response == 'ok') {
                        document.location.href = '{{route('manageCategory')}}';
                    }
                }
            });
        }

        function newItemFunc(id) {
            document.getElementById('newItems').style.display = 'block';
            document.getElementById('base_item').value = id;
            document.getElementById('items').innerHTML = '';
            numOfItem = 1;
        }

        function addItems() {
            var text = '<label>ایتم ' + (numOfItem + 1) + ':</label>\n' +
                '<input type="text" name="name[' + numOfItem + ']" style="margin-bottom: 3%;"><br>';

            $('#items2').append(text);
            numOfItem++;
        }
    </script>

@stop