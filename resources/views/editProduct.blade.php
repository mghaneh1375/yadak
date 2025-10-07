@extends('layouts.structure')

@section('header')
    @parent
    <script src="//cdn.ckeditor.com/4.10.1/full/ckeditor.js"></script>
@stop

@section('content')
    <style>
        * {
            box-sizing: border-box;
        }

        .column {
            float: left;
            width: 45%;
            padding: 5px;
            height: 310px;
            max-height: 310px;

        }

        /* Clearfix (clear floats) */
        .row::after {
            content: "";
            clear: both;
            display: table;
        }
        th {
            padding: 2%;
        !important;
        }

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

        .image-upload > input
        {
            display: none;
        }

        .image-upload img
        {
            width: 80px;
            cursor: pointer;
        }
    </style>
    <!-- BEGIN CONTENT BODY -->
    <div id="mainContainer" class="page-content" style="margin-top: 5%; direction: rtl; overflow-y: auto">
        <h1>ویرایش کالا</h1>

        <div id="content">

            <div id="selectBar" style="width: 100%; padding: 10px">
                <label>انواع دسته ها:</label>
                <select onchange="getCategory(this.value)" style="margin-left: 10%;">
                    <option style="display: none;">دسته مورد نظر خود را انتخاب کنید...</option>
                    @for($i = 0; $i < count($super); $i++)
                        @if($super[$i]->id == $product->super_category)
                            <option selected value="{{$super[$i]->id}}">{{$super[$i]->name}}</option>
                        @else
                            <option value="{{$super[$i]->id}}">{{$super[$i]->name}}</option>
                        @endif
                    @endfor
                </select>

                <div id="category" style="display: none;">
                    <label>مدل را انتخاب کنید:</label>
                    <select id="categorySelect" onchange="getItem(this.value)">
                    </select>
                </div>
            </div>

            <form id="newProduct" action="{{route('doEditProduct', ['id' => $id])}}" method="post"
                  enctype="multipart/form-data">

                {{ csrf_field() }}

                <input type="hidden" id="categoryId11" name="categoryId">

                <label style="width: 10%;">نام محصول:</label>
                <input type="text" style="text-align: center" id="nameProduct" value="{{$product->name}}" form="newProduct" name="name"><br>

                <label style="width: 10%;">قیمت محصول:</label>
                <input type="text" style="text-align: center" value="{{$product->money}}" id="moneyProduct" name="money" form="newProduct"><br>

                <label style="width: 10%;">تعداد محصول:</label>
                <input type="text" style="text-align: center" value="{{$product->number}}" id="numberProduct" name="number" form="newProduct"><br>

                <label style="width: 10%;">برند محصول:</label>
                <select id="brands" name="brandId" form="newProduct"></select>

                <input type="text" id="brandProduct" name="brand" form="newProduct"><br/>

                <h4>تصویر فعلی محصول</h4>
                <img src="{{$pic}}" width="200px"><br/><br/>

                <label for="pic">انتخاب تصویر جدید برای محصول</label>
                <input type="file" name="pic" id="pic">

                <br>
                <label style="width: 35%;">آیا محصول مورد نظر جزء پیشنهاد های صفحه نخست است؟</label>
                @if($product->best)
                    <input type="checkbox" name="best" checked form="newProduct"><br>
                @else
                    <input type="checkbox" name="best" form="newProduct"><br>
                @endif

                <div id="editor">
                    <textarea id="editor1" cols="80" name="descProduct" form="newProduct">{{$product->desc}}</textarea>
                </div>

            </form>

            <input name="submit11" type="submit" id="submit1" value="ذخیره" class="btn green" onclick="checkInput()" style="margin-top: 1%; width: 100%;">
        </div>

        <div id="myModal" class="modal">
            <div class="modal-content">

            </div>
        </div>

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

    <script>

        var numOfImg = 0;

        $(document).ready(function () {
            getCategory2('{{$product->super_category}}', '{{$product->category_id}}',  '{{$product->brand_id}}');
        });

        function readURL(input) {

            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    $('#blah'+numOfImg).attr('src', e.target.result);
                    document.getElementById('blah'+numOfImg).style.display = 'inline-block';
                    none = document.getElementById('imgInp'+numOfImg);
                    $('#noneImg').append(none);
                    numOfImg++;
                    var text = '<center>\n' +
                        '<label id="label" for="imgInp' + numOfImg + '">\n' +
                        '<img src ='+ "{{URL::asset("slidebar/12.svg")}}"+' alt="your image"\n' +
                        'style="width:100%; height: 310px;">\n' +
                        '</label>\n' +
                        '</center>\n' +
                        '<input type="file" form="newProduct" name="pic[' + numOfImg + ']" id="imgInp' + numOfImg + '" onchange="readURL(this)">';

                    document.getElementById('inlineImg').innerHTML = text;
                    var text2 = '<img id="blah' + numOfImg + '" src="" alt="your image"\n' +
                        'style="width:45%; margin: 2%; height: 288px; display: none;">';
                    $('#images').append(text2);
                }

                reader.readAsDataURL(input.files[0]);
            }
        }

        CKEDITOR.replace( 'editor1' );

        var category = [];
        var item = [];

        function getCategory2(id, categoryId, brandId) {

            document.getElementById('categorySelect').innerHTML = ' <option style="display: none;">مدل را انتخاب کنید... </option>\n';

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                }
            });

            $.ajax({
                type: 'post',
                url: '{{url('getCategoryForProduct')}}',
                data: {
                    'id': id
                },
                success: function (response) {
                    response = JSON.parse(response);
                    category = response.category;
                    document.getElementById('category').style.display = 'inline-block';
                    var text = '';
                    for (var i = 0; i < category.length; i++) {
                        if(categoryId == category[i].id)
                            text += '<option selected value="' + category[i].id + '" >' + category[i].name + '</option>\n';
                        else
                            text += '<option value="' + category[i].id + '" >' + category[i].name + '</option>\n';
                    }
                    $('#categorySelect').empty().append(text);

                    getItem2(categoryId, brandId);
                }
            });
        }

        function getCategory(id) {

            document.getElementById('categorySelect').innerHTML = ' <option style="display: none;">مدل را انتخاب کنید... </option>\n';

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                }
            });

            $.ajax({
                type: 'post',
                url: '{{url('getCategoryForProduct')}}',
                data: {
                    'id': id
                },
                success: function (response) {
                    response = JSON.parse(response);
                    category = response.category;
                    document.getElementById('category').style.display = 'inline-block';
                    var text = '';
                    for (var i = 0; i < category.length; i++) {
                        text += '<option value="' + category[i].id + '" >' + category[i].name + '</option>\n';
                    }
                    $('#categorySelect').empty().append(text);
                    $('#brands').val(0);
                }
            });
        }

        function getItem2(id, brandId) {
            var numOfItems = 0;
            var text = '';
            $.ajax({
                type: 'post',
                url: '{{url('getItemForProduct')}}',
                data: {
                    'id': id
                },
                success: function (response) {

                    response = JSON.parse(response);
                    item = response.item;
                    brand = response.brands;

                    // document.getElementById('categoryId').value = id;
                    document.getElementById('categoryId11').value = id;

                    for (var i = 0; i < item.length; i++) {
                        if (item[i].base_item_id == 0) {
                            text += '<tr><th style="width: 100%;"><h3 id="itemName' + item[i].id + '" ondblclick="changeName(' + item[i].id + ')">' + item[i].name + '</h3><input type="text" id="itemInputName' + item[i].id + '" value="' + item[i].name + '" style="display: none" onchange="ajaxName(' + item[i].id + ')"> </th></tr>';
                            for (var j = 0; j < item.length; j++) {
                                if (item[j].base_item_id == item[i].id) {
                                    text += '<tr style="padding: 1px;"><th style="border: solid;"><label id="itemName' + item[j].id + '" style="float: right" ondblclick="changeName(' + item[j].id + ')">' + item[j].name + '</label><input type="text" id="itemInputName' + item[j].id + '" value="' + item[j].name + '" style="display: none" onchange="ajaxName(' + item[j].id + ')"></th>\n' +
                                        '<th style="border: solid;"><input form="newProduct" type="text" style="float: left" name="item[' + numOfItems + ']"></th>\n' +
                                        // '<th style="border: solid;"><label>دارد،ندارد</label><input type="checkbox"></th></tr>'+
                                        '<input form="newProduct" type="hidden" name="id[' + numOfItems + ']" value="' + item[j].id + '">';
                                    numOfItems++;
                                }
                            }
                        }
                    }
                    document.getElementById('editor').style.display = 'block';
                    $('#items').append(text);

                    text = '';
                    text += '<option value="0">برند جدید</option>';
                    for(var i = 0; i < brand.length; i++){
                        if(brand[i].id == brandId)
                            text += '<option selected value="' + brand[i].id + '">' + brand[i].name + '</option>';
                        else
                            text += '<option value="' + brand[i].id + '">' + brand[i].name + '</option>';

                    }
                    $('#brands').append(text);

                }
            });
        }

        function getItem(id) {
            var numOfItems = 0;
            var text = '';
            $.ajax({
                type: 'post',
                url: '{{url('getItemForProduct')}}',
                data: {
                    'id': id
                },
                success: function (response) {

                    response = JSON.parse(response);
                    item = response.item;
                    brand = response.brands;

                    // document.getElementById('categoryId').value = id;
                    document.getElementById('categoryId11').value = id;

                    for (var i = 0; i < item.length; i++) {
                        if (item[i].base_item_id == 0) {
                            text += '<tr><th style="width: 100%;"><h3 id="itemName' + item[i].id + '" ondblclick="changeName(' + item[i].id + ')">' + item[i].name + '</h3><input type="text" id="itemInputName' + item[i].id + '" value="' + item[i].name + '" style="display: none" onchange="ajaxName(' + item[i].id + ')"> </th></tr>';
                            for (var j = 0; j < item.length; j++) {
                                if (item[j].base_item_id == item[i].id) {
                                    text += '<tr style="padding: 1px;"><th style="border: solid;"><label id="itemName' + item[j].id + '" style="float: right" ondblclick="changeName(' + item[j].id + ')">' + item[j].name + '</label><input type="text" id="itemInputName' + item[j].id + '" value="' + item[j].name + '" style="display: none" onchange="ajaxName(' + item[j].id + ')"></th>\n' +
                                        '<th style="border: solid;"><input form="newProduct" type="text" style="float: left" name="item[' + numOfItems + ']"></th>\n' +
                                        '<input form="newProduct" type="hidden" name="id[' + numOfItems + ']" value="' + item[j].id + '">';
                                    numOfItems++;
                                }
                            }
                        }
                    }
                    document.getElementById('editor').style.display = 'block';
                    $('#items').empty().append(text);

                    text = '';
                    text += '<option value="0">برند جدید</option>';
                    for(var i = 0; i < brand.length; i++){
                        text += '<option value="' + brand[i].id + '">' + brand[i].name + '</option>';
                    }
                    $('#brands').empty().append(text);

                }
            });
        }

        function changeName(id) {
            document.getElementById('itemName'+id).style.display = 'none';
            document.getElementById('itemInputName'+id).style.display = '';
        }

        function ajaxName(id) {
            document.getElementById('itemInputName'+id).style.display = 'none';
            document.getElementById('itemName'+id).style.display = '';

            var name = document.getElementById('itemInputName' + id).value ;
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
                            document.getElementById('itemInputName' + id).value = name;
                            document.getElementById('itemName' + id).innerText = name;
                        }
                    }
                });
            }

        }

        function checkInput() {
            var nameProduct = document.getElementById('nameProduct').value;
            var brandProduct = document.getElementById('brandProduct').value;
            var brandId = document.getElementById('brands').value;
            var moneyProduct = document.getElementById('moneyProduct').value;
            var numberProduct = document.getElementById('numberProduct').value;
            var text = 'لطفا فیلد های :';
            var errornum = 0;

            if(nameProduct == ''){
                text += ',نام محصول ';
                errornum++;
            }
            if(brandProduct == '' && brandId == 0){
                text += ',برند محصول ';
                errornum++;
            }
            if(moneyProduct == ''){
                text += ',قیمت محصول ';
                errornum++;
            }
            if(numberProduct == ''){
                text += ',تعداد محصول ';
                errornum++;
            }

            text += '  را کامل کنید';

            if(errornum != 0){
                alert(text);
            }
            else{
                $("form:first").submit();
            }

        }
    </script>


@stop