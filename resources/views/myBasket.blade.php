@extends('layouts.siteStructure')

@section('header')
    @parent

    <style>
        .zoomable:hover {
            transform: scale(1.1);
        }

        .box {
            padding: 8px;
            border: 1px solid #444;
            border-radius: 7px !important;
            background-color: white;
            margin: 10px;
        }

        select {
            font-size: 14px;
        }

    </style>

@stop

@section('content')

    <center class="col-lg-3">

        <div class="col-xs-12" style="margin: 10px; background-color: white; border: 1px solid #444; border-radius: 7px !important; font-size: 20px; font-weight: bolder">
            <p>جمع کل</p>

            <p>
                <span id="total_price"></span>
                <span>ریال</span>
            </p>

            <select id="paymentKind" onchange="changePaymentKind(this.value)" style="width: 100%">
                <option value="-1">نوع پرداخت (انتخاب کنید)</option>
                <option value="1">ارسال عکس واریز وجه</option>
                <option value="2">پرداخت با چک(مخصوص کاربران ویژه)</option>
            </select>

            <div id="picDiv" class="hidden">
                <p>فایل عکس واریز وجه</p>
                <input id="pic" type="file" style="font-size: 14px; margin: 10px">
            </div>

            <div>
                <p>اگر کد تخفیف دارید، وارد نمایید</p>
                <input type="text" id="offcode" style="font-size: 14px; width: 100px; margin: 10px">
                <span onclick="checkOffCode()" style="font-size: 11px; padding: 4px !important;" class="btn btn-primary">چک کردن کد تخفیف</span>
            </div>

            <button onclick="finishBuy()" style="color: white; border-radius: 7px !important; margin: 10px" class="btn badge-success zoomable">تکمیل خرید</button>
            <p style="color: red; text-align: justify; font-weight: 500" id="err"></p>
        </div>

    </center>

    <div class="col-xs-12 col-lg-6" style="max-height: 70vh; overflow: auto">

        @foreach($products as $product)

            <div class="col-lg-12 box" id="box_{{$product->id}}" data-id="{{$product->id}}" data-price="{{$product->price}}" data-num="1">

                <img width="200px" style="float: right" src="{{$product->pic}}">

                <div style="float: right; margin-right: 30px" class="detail">

                    <h3>{{$product->name}}</h3>

                    <p>
                        <span>{{$product->super_category}}</span>
                        <span> / </span>
                        <span>{{$product->category}}</span>
                        <span> / </span>
                        <span>{{$product->brand}}</span>
                    </p>

                    @if($product->secondary_price != $product->money && $product->secondary_price != null && $product->secondary_price > 0)
                        <h4>
                            <span>قیمت اصلی</span>
                            <span style="text-decoration: line-through;">{{number_format($product->money)}}</span>
                            <span>ریال</span>
                        </h4>

                        <h4>
                            <span>قیمت کنونی</span>
                            <span>{{number_format($product->secondary_price)}}</span>
                            <span>ریال</span>
                        </h4>
                    @else
                        <h4>
                            <span>قیمت</span>
                            <span>{{number_format($product->money)}}</span>
                            <span>ریال</span>
                        </h4>
                    @endif

                    <div>
                        <p>
                            <span>تعداد: </span>
                            <span>&nbsp;&nbsp;&nbsp;</span>
                            <span onclick="inc({{$product->id}})" style="cursor: pointer; font-weight: bolder; color: #38ad94; font-size: 20px"> + </span>
                            <input id="nums_{{$product->id}}" type="text" style="width: 100px; text-align: center" value="1">
                            <span onclick="dec({{$product->id}})" style="cursor: pointer; font-weight: bolder; color: red; font-size: 25px"> - </span>
                        </p>
                    </div>


                    <span onclick="removeBox({{$product->id}})" class="glyphicon glyphicon-remove" style="color: red; font-family: 'Glyphicons Halflings' !important; position: absolute; cursor: pointer; top: 20px; left: 30px;"></span>

                </div>

            </div>
        @endforeach

    </div>

    <div class="col-lg-3">

        <div class="col-xs-12" style="margin: 10px; background-color: white; border: 1px solid #444; border-radius: 7px !important; font-size: 20px; font-weight: bolder">

            <center>
                <h3>اطلاعات تماس</h3>
            </center>

            <p style="font-size: 16px; font-weight: 300">
                <span>آدرس ارسال:</span>
                <span>{{\Illuminate\Support\Facades\Auth::user()->address}}</span>
            </p>

            <p style="font-size: 16px; font-weight: 300">
                <span>شماره تماس:</span>
                <span>{{\Illuminate\Support\Facades\Auth::user()->phone}}</span>
            </p>

            <p style="font-size: 16px; font-weight: 300">
                <span>نام گیرنده:</span>
                <span>{{\Illuminate\Support\Facades\Auth::user()->first_name . ' ' . \Illuminate\Support\Facades\Auth::user()->last_name}}</span>
            </p>

            <center>
                <a target="_blank" href="{{route('editInfo')}}" style="color: white; border-radius: 7px !important; margin: 10px; background-color: #38ad94" class="btn zoomable">ویرایش اطلاعات تماس</a>
            </center>

        </div>

    </div>

    <script>

        $(document).ready(function () {
            calcPrice();
        });

        function formatNum(val) {
            tmp = (val).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,');
            return tmp.substr(0, tmp.length - 3);
        }

        function changePaymentKind(val) {

            if(val == 1)
                $("#picDiv").removeClass('hidden');
            else
                $("#picDiv").addClass('hidden');

        }

        function calcPrice() {

            var sum = 0;

            $(".box").each(function () {
                sum += $(this).attr('data-price') * $(this).attr('data-num');
            });

            $("#total_price").empty().append(formatNum(sum));
        }

        function removeBox(id) {

            $("#box_" + id).fadeOut("slow");

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                }
            });

            $.ajax({
                type: 'post',
                url: '{{route('removeFromBasket')}}',
                data: {
                    'product_id': id
                },
                success: function (res) {
                    $("#box_" + id).remove();
                    calcPrice();
                }
            });
        }

        function inc(id) {
            var x = parseInt($("#nums_" + id).val());
            $("#nums_" + id).val(x + 1);
            $("#box_" + id).attr('data-num', x + 1);
            calcPrice();
        }
        
        function dec(id) {

            var x = parseInt($("#nums_" + id).val());

            if(x > 1) {
                $("#nums_" + id).val(x - 1);
                $("#box_" + id).attr('data-num', x - 1);
                calcPrice();
            }
        }

        function checkOffCode() {

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                }
            });

            $("#err").text("");

            $.ajax({
                type: 'post',
                url: '{{route('checkOffCode')}}',
                data: {
                    offcode: $("#offcode").val()
                },
                success: function (res) {

                    res = JSON.parse(res);

                    if(res.status === "ok")
                        $("#err").text("کد وارد شده معتبر است و از مبلغ سفارش شما " + res.amount + " کم خواهد شد.");
                    else if(res.status === "nok1")
                        $("#err").text("کد تخفیف وارد شده نامعتبر است.");
                    else if(res.status === "nok2")
                        $("#err").text("کد تخفیف وارد شده منقضی شده است.");

                }
            });
        }

        function finishBuy() {

            $("#err").text("");
            var payment_kind = $("#paymentKind").val();

            if(payment_kind == -1) {
                $("#err").text("لطفا نوع پرداخت خود را مشخص نمایید.");
            }

            var products = [];

            $(".box").each(function () {

                products.push({
                    "id": $(this).attr('data-id'),
                    "num": $(this).attr('data-num'),
                })

            });

            if(payment_kind == 1 && $('#pic')[0].files.length === 0) {
                $("#err").text("لطفا فایل عکس واریز وجه خود را آپلود نمایید.");
                return;
            }

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                }
            });

            $.ajax({
                type: 'post',
                url: '{{route('finishBuy')}}',
                data: {
                    payment_kind: payment_kind,
                    products: products,
                    offcode: $("#offcode").val()
                },
                success: function (res) {

                    res = JSON.parse(res);

                    if(res.status === "nok1") {
                        $("#err").text("پرداخت با چک فقط مخصوص کاربران ویژه است. برای کاربر ویژه شدن با مدیریت سایت (09133518607) تماس بگیرید.");
                    }
                    else if(res.status === "ok") {

                        let code = res.follow_code;

                        if(payment_kind == 1) {

                            var formData = new FormData();

                            formData.append('pic', $('#pic')[0].files[0]);

                            $.ajax({
                                url : '{{route('addPic')}}' + "/" + res.follow_code,
                                type : 'POST',
                                data : formData,
                                processData: false,  // tell jQuery not to process the data
                                contentType: false,  // tell jQuery not to set contentType
                                success : function(res) {
                                    if(res === "ok")
                                        document.location.href = "{{route('success')}}" + "/" + code;
                                }
                            });

                        }
                        else
                            document.location.href = "{{route('success')}}" + "/" + code;
                    }
                }
            });

        }
        
    </script>
    
@stop