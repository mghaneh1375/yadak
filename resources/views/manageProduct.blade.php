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
            width: 25%;
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
        <h1>افزودن کالا</h1>

        <div id="selectBar" style="width: 100%; padding: 5%;">
            <label>انواع دسته ها:</label>
            <select onchange="getCategory(this.value)" style="margin-left: 10%;">
                <option style="display: none;">دسته مورد نظر خود انتخاب کنید...</option>
                @for($i = 0; $i < count($super); $i++)
                    <option value="{{$super[$i]->id}}">{{$super[$i]->name}}</option>
                @endfor
            </select>

            <div id="category" style="display: none;">
                <label>مدل را انتخاب کنید:</label>
                <select id="categorySelect" onchange="getItem(this.value)">
                </select>
            </div>
        </div>
        <div id="content">
            <form id="newProduct" action="{{route('saveNewProduct')}}" method="post"
                  enctype="multipart/form-data">
                <input type="hidden" id="categoryId11" name="categoryId">
                {{ csrf_field() }}
            </form>
            <div id="mainItem" style="width: 100%; margin-bottom: 3%; display: none; ">
                <label style="width: 10%;">نام محصول:</label>
                <input type="text" id="nameProduct" form="newProduct" name="name">

                <div id="uploadFile" style="float: left; display: inline-block;">
                    <form id="excel" name="excel" action="{{route('saveExcelProduct')}}" method="post" enctype="multipart/form-data">
                        {{ csrf_field() }}
                        <input type="hidden" form="excel" id="categoryId" name="categoryId">
                        <label>آپلود فایل اکسل:</label>
                        <input type="file" form="excel" name="file">
                        <button type="submit"  form="excel">ذخیره</button>
                    </form>
                </div>
                <br>

                <label style="width: 10%;">قیمت محصول:</label>
                <input type="text" id="moneyProduct" name="money" form="newProduct"><br>

                <label style="width: 10%;">تعداد محصول:</label>
                <input type="text" id="numberProduct" name="number" form="newProduct"><br>

                <label style="width: 10%;">برند محصول:</label>
                <select id="brands" name="brandId" form="newProduct"></select>
                <input type="text" id="brandProduct" name="brand" form="newProduct">

                <br>
                <label style="width: 35%;">آیا محصول مورد نظر جزء پیشنهاد های صفحه نخست است؟</label>
                <input type="checkbox" name="best" form="newProduct"><br>

            </div>

            <div id="editor" style="display: none;">
                <textarea id="editor1" cols="80" name="descProduct" form="newProduct"></textarea>
            </div>

            <div id="imgContent" style="width: 100%; display: none; float: left; border: solid;">
                    <div id="images" style="display: inline;">
                        <img id="blah0" src="" alt="your image"
                             style="width:25%; margin: 2%; height: 288px; display: none;">
                    </div>
                <div class="column" style="border: solid;">
                    <div id="inlineImg" class="image-upload">
                        <center>
                            <label id="label" for="imgInp0">
                            <img src ="{{URL::asset('slidebar/12.svg')}}" alt="your image"
                                 style="width:100%; height: 310px;">
                            </label>
                        </center>
                            <input type="file" form="newProduct" name="pic[0]" id="imgInp0" onchange="readURL(this)">
                    </div>
                    <div id="noneImg" style="display: none;">

                    </div>
                </div>
            </div>
            <input name="submit11" type="submit" id="submit1" value="ذخیره" class="btn green" onclick="checkInput()" style="margin-top: 1%; width: 100%; display: none;">
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

    <script src="{{URL::asset('global/scripts/datatable.js')}}" type="text/javascript"></script>
    <script src="{{URL::asset('global/plugins/datatables/datatables.min.js')}}" type="text/javascript"></script>
    <script src="{{URL::asset('global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.js')}}"
            type="text/javascript"></script>

    <script src="{{URL::asset('pages/scripts/table-datatables-managed.min.js')}}" type="text/javascript"></script>

    <script>

        var numOfImg = 0;

        $.ajaxSetup(
            {
                headers:
                    {
                        'X-CSRF-Token': $('input[name="_token"]').val()
                    }
            });

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        function readURL(input) {

            if(numOfImg > 0) {
                numOfImg = 0;
            }

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
                        'style="width:25%; margin: 2%; height: 288px; display: none;">';
                    $('#images').append(text2);
                }

                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>

    <script>
        // Replace the <textarea id="editor1"> with a CKEditor
        // instance, using default configuration.
        CKEDITOR.replace( 'editor1' );

        var token = "{{ csrf_token() }}";
        var category = [];
        var item = [];
        var brand = [];

        function getCategory(id) {
            document.getElementById('categorySelect').innerHTML = ' <option style="display: none;">مدل را انتخاب کنید... </option>\n';

            $.ajax({
                type: 'post',
                url: '{{url('getCategoryForProduct')}}',
                data: {
                    '_token': token,
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
                    $('#categorySelect').append(text);
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
                    '_token': token,
                    'id': id
                },
                success: function (response) {
                    response = JSON.parse(response);
                    item = response.item;
                    brand = response.brands;

                    document.getElementById('categoryId').value = id;
                    document.getElementById('categoryId11').value = id;
                    document.getElementById('mainItem').style.display = 'block';
                    document.getElementById('imgContent').style.display = 'inline-block';
                    document.getElementById('submit1').style.display = 'inline-block';
                    for (var i = 0; i < item.length; i++) {
                        if (item[i].base_item_id == 0) {
                            text += '<tr><th style="width: 100%;"><h3 id="itemName' + item[i].id + '">' + item[i].name + '</h3><input type="checkbox" id="itemInputName' + item[i].id + '" value="' + item[i].name + '"> </th></tr>';
                            for (var j = 0; j < item.length; j++) {
                                if (item[j].base_item_id == item[i].id) {
                                    text += '<tr style="padding: 1px;"><th style="border: solid;"><label id="itemName' + item[j].id + '" style="float: right">' + item[j].name + '</label><input type="checkbox" id="itemInputName' + item[j].id + '"></th>\n' +
                                        '<th style="border: solid;"><input form="newProduct" type="checkbox" style="float: left" name="item[' + numOfItems + ']"></th>\n' +
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
                        text += '<option value="' + brand[i].id + '">' + brand[i].name + '</option>';
                    }
                    $('#brands').append(text);

                }
            });
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