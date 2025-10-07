@extends('layouts.siteStructure')

@section('header')
    @parent

    <style>

        .cbp-item {
            width: 250px !important;
            height: 330px;
        }

        .cbp-caption-defaultWrap {
            height: 200px;
        }

        .filter {
            background: url('{{\Illuminate\Support\Facades\URL::asset('images/filter.png')}}');
            position: relative;
            width: 25px;
            height: 25px;
            cursor: pointer;
            background-size: cover !important;
        }

        .filterPane {
            background-color: white;
            width: 180px;
            height: 160px;
            padding: 20px 7px 7px;
            top: 30px;
            position: absolute;
            border-radius: 7px !important;
        }

        .filterPane label {
            font-size: 12px;
        }

        /* Customize the label (the myContainer) */
        .myContainer {
            display: block;
            position: relative;
            margin-bottom: 12px;
            cursor: pointer;
            font-size: 22px;
            -webkit-user-select: none;
            -moz-user-select: none;
            -ms-user-select: none;
            user-select: none;
        }

        /* Hide the browser's default checkbox */
        .myContainer input {
            position: absolute;
            opacity: 0;
            left: 0;
            cursor: pointer;
            height: 0;
            width: 0;
        }

        /* Create a custom checkbox */
        .checkmark {
            position: absolute;
            top: 0;
            left: 0;
            height: 20px;
            width: 20px;
            background-color: #eee;
        }

        /* On mouse-over, add a grey background color */
        .myContainer:hover input ~ .checkmark {
            background-color: #ccc;
        }

        /* When the checkbox is checked, add a blue background */
        .myContainer input:checked ~ .checkmark {
            background-color: #2196F3;
        }

        /* Create the checkmark/indicator (hidden when not checked) */
        .checkmark:after {
            content: "";
            position: absolute;
            display: none;
        }

        /* Show the checkmark when checked */
        .myContainer input:checked ~ .checkmark:after {
            display: block;
        }

        /* Style the checkmark/indicator */
        .myContainer .checkmark:after {
            left: 9px;
            top: 5px;
            width: 5px;
            height: 10px;
            border: solid white;
            border-width: 0 3px 3px 0;
            -webkit-transform: rotate(45deg);
            -ms-transform: rotate(45deg);
            transform: rotate(45deg);
        }
        
    </style>

@stop

@section('content')

    <div class="col-lg-1">
    </div>

    <div class="col-lg-10">

        <div id="js-grid-juicy-projects" class="cbp">
        </div>

    </div>

    <div class="col-lg-1">
        <div style="position: fixed; z-index: 10000;">
            <div class="filter" onclick="if($('#filterPane').hasClass('hidden')) $('#filterPane').removeClass('hidden'); else $('#filterPane').addClass('hidden');">
                <div id="filterPane" class="filterPane hidden">

                    <label class="myContainer" for="asc">نمایش قیمت ها، صعودی
                        <input onchange="filter()" id="asc" name="sort" type="radio" value="1">
                        <span class="checkmark"></span>
                    </label>


                    <label class="myContainer" for="desc">نمایش قیمت ها، نزولی
                        <input onchange="filter()" id="desc" name="sort" type="radio" value="2">
                        <span class="checkmark"></span>
                    </label>


                    <label class="myContainer" for="exist">فقط کالاهای موجود
                        <input id="exist" onchange="filter()" name="exist" type="checkbox">
                        <span class="checkmark"></span>
                    </label>


                    <label class="myContainer" for="offer">فقط کالاهای تخفیف دار
                        <input id="offer" onchange="filter()" name="offer" type="checkbox">
                        <span class="checkmark"></span>
                    </label>


                </div>
            </div>
        </div>

    </div>

    <script>

        var data = {!! json_encode($products) !!};
        var productPath = '{{route('product')}}' + "/";
        var editProductPath = '{{route('editProduct')}}' + "/";

        $(document).ready(function () {
            render(data);
        });

        function compare( a, b ) {

            if ( a.last_price < b.last_price ){
                return -1;
            }
            if ( a.last_price > b.last_price ){
                return 1;
            }
            return 0;
        }

        function compare2( a, b ) {

            if ( a.last_price > b.last_price ){
                return -1;
            }
            if ( a.last_price < b.last_price ){
                return 1;
            }
            return 0;
        }

        function render(data) {

            var newElem = '';

            for(var i = 0; i < data.length; i++) {
                data_exist = (data[i].number > 0) ? 1 : 0;
                data_off = (data[i].takhfif > 0) ? 1 : 0;

                newElem += '<div data-exist="' + data_exist + '" data-off="' + data_off + '" class="cbp-item">';
                newElem += '<div class="cbp-caption">';
                newElem += '<div class="cbp-caption-defaultWrap">';
                newElem += '<img style="max-height: 180px; min-height: 180px" src="' + data[i].img + '">';
                newElem += '</div>';
                newElem += '<div class="cbp-caption-activeWrap">';
                newElem += '<div class="cbp-l-caption-alignCenter">';
                newElem += '<div class="cbp-l-caption-body">';
                newElem += '<a href="' + productPath + data[i].id + '" class="cbp-l-caption-buttonLeft btn red uppercase" rel="nofollow">مشاهده کالا</a>';

                @if(\Illuminate\Support\Facades\Auth::check() && \Illuminate\Support\Facades\Auth::user()->level == 1)
                    newElem += '<a href="' + editProductPath + data[i].id + '" class="cbp-l-caption-buttonLeft btn green uppercase" rel="nofollow">ویرایش کالا</a>';
                @endif

                newElem += '</div>';
                newElem += '</div>';
                newElem += '</div>';
                newElem += '</div>';
                newElem += '<div class="text-center">' + data[i].name + '</div>';
                newElem += '<div class="text-center">' + data[i].super_category + ' / ' + data[i].category + ' / ' + data[i].brand + '</div>';

                if(data_off === 1) {
                    newElem += '<div class="text-center" style="text-decoration: line-through">' + data[i].money + ' ریال</div>';
                    newElem += '<div class="text-center">' + data[i].secondary_price + ' ریال</div>';
                }
                else
                    newElem += '<div class="text-center">' + data[i].money + ' ریال</div>';

                if(data_exist === 1)
                    newElem += '<div style="color: #28829d; font-size: 16px" class="text-center">موجود</div>';
                else
                    newElem += '<div style="color: #9d3819; font-size: 16px" class="text-center">ناموجود</div>';

                newElem += '</div>';
            }

            $("#js-grid-juicy-projects").empty().append(newElem);
            i = 0;

            var left = 290;
            var top = 330;
            var numInRow = Math.floor((window.innerWidth * 0.8) / left);

            $(".cbp-item").each(function () {
                $(this).css('left', (left * (i % numInRow)) + "px").css('top', (top * Math.floor(i / numInRow) + "px"));
                i++;
            });
        }

        var offer = false;
        var exist = false;


        function filter() {

            exist = $("#exist").prop('checked');
            offer = $("#offer").prop('checked');

            newData = [];

            for(var i = 0; i < data.length; i++) {

                data_exist = (data[i].number > 0) ? 1 : 0;
                data_off = (data[i].takhfif > 0) ? 1 : 0;

                if(
                    (exist && data_exist === 0) ||
                    (offer && data_off === 0)
                )
                    continue;

                newData.push(data[i]);
            }

            sort = $("input[name='sort']:checked").val();

            if(sort == 1)
                newData.sort( compare );
            else if(sort == 2)
                newData.sort( compare2 );

            render(newData);
        }

    </script>

@stop