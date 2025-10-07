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

        p, h3 {
            margin-top: 5px !important;
        }

        td {
            padding: 7px;
            border: 1px solid #444;
            direction: ltr;
            text-align: center;
        }

    </style>

@stop

@section('content')

    <center class="col-lg-1"></center>

    <div class="col-xs-12 col-lg-10" style="padding-left: 60px; padding-right: 60px">

        @if(count($orders) == 0)
            <center style="margin-top: 30px">
                <h3 style="color: #812639">سفارشی موجود نیست!!!</h3>
            </center>
        @endif

        @foreach($orders as $order)

            <div class="col-lg-12 box" style="background-color: {{($order->confirm) ? 'powderblue' : (($order->reject) ? 'lightpink' : 'aliceblue')}}" >

                <div style="margin-right: 30px; padding-top: 10px" class="detail">

                    <p style="float: right">
                        <span>تاریخ ثبت سفارش: </span>
                    </p>

                    <p style="float: right; margin-right: 10px">
                        <span>{{$order->submit_date}}</span>
                    </p>

                    <p style="float: right; margin-right: 50px">
                        <span>کد پیگیری: </span>
                    </p>

                    <p style="float: right; margin-right: 10px">
                        <span>{{$order->follow_code}}</span>
                    </p>

                    <p style="float: right; margin-right: 50px">
                        <span>وضعیت : </span>
                        @if($order->confirm)
                            <span style="color: #0b4d3f; font-weight: 500">تایید شده</span>
                        @elseif($order->reject)
                            <span style="color: red; font-weight: 500">رد شده</span>
                        @else
                            <span style="color: red; font-weight: 500">منتظر اعلام نظر ادمین</span>
                        @endif
                    </p>

                    @if($order->confirm || $order->reject)

                        <p style="float: right; margin-right: 50px">
                            <span>تاریخ اعلام نظر ادمین : </span>
                        </p>

                        <p style="float: right; ">
                            <span>{{$order->confirm_date}}</span>
                        </p>
                    @endif

                    @if($order->confirm || $order->reject)
                        <a class="btn btn-success" onclick="getItems('{{$order->id}}')" style="float: right; border-radius: 7px !important; margin-right: 50px">سفارش مجدد</a>
                        <a id="orderAgainBtn" data-toggle="modal" href="#responsive" class="hidden"></a>
                    @endif

                    <div style="clear: both"></div>

                    @if(($order->confirm || $order->reject) && $order->description != null && !empty($order->description))
                        <p style="color: red">نظر ادمین: {{$order->description}}</p>
                    @endif

                    @foreach($order->items as $item)

                        <div style="padding-bottom: 5px; margin: 20px 10px 10px 10px; border-bottom: 1px dashed #444">

                            <p>
                                <span>نام محصول: </span>
                                <span>&nbsp;</span>
                                <span>{{$item["name"]}}</span>
                                <span>&nbsp;&nbsp;&nbsp;</span>
                                <span>&nbsp;&nbsp;&nbsp;</span>
                                <span>&nbsp;&nbsp;&nbsp;</span>
                                <span>دسته محصول: </span>
                                <span>&nbsp;</span>
                                <span>{{$item["super_category"]}}</span>
                                <span> / </span>
                                <span>{{$item["category"]}}</span>
                                <span> / </span>
                                <span>{{$item["brand"]}}</span>
                            </p>

                            <h4>
                                <span>قیمت هر واحد</span>
                                <span>{{number_format((int)$item["price"])}}</span>
                                <span>ریال</span>

                                <span>&nbsp;&nbsp;</span>
                                <span>&nbsp;&nbsp;</span>
                                <span>&nbsp;&nbsp;</span>

                                <span>تعداد: </span>
                                <span>{{$item["num"]}}</span>

                            </h4>
                        </div>
                    @endforeach



                </div>

            </div>
        @endforeach

    </div>

    <div class="col-lg-1"></div>

    <div id="responsive" class="modal fade" tabindex="-1" aria-hidden="true" style="display: none;">
        <div class="modal-dialog">
            <form id="buyAgainForm" method="post" action="{{route('buyAgain')}}" enctype="multipart/form-data">

                {{csrf_field()}}

                <input type="hidden" id="basket_id" name="basket_id">

                <div class="modal-content">
                    <div class="modal-header">
                        <span style="float: left" class="close" data-dismiss="modal" aria-hidden="true"></span>
                        <h4 class="modal-title">سفارش مجدد</h4>
                    </div>
                    <div class="modal-body">
                        <div class="slimScrollDiv" style="position: relative; overflow: hidden; width: auto;">
                            <div class="scroller" style="width: auto;" data-always-visible="1" data-rail-visible1="1" data-initialized="1">
                                <div class="row">

                                    <center class="col-xs-12" id="items"></center>

                                    <div class="col-md-9">
                                        <select onchange="changePaymentKind(this.value)" id="payment_kind" name="payment_kind" style="margin-top: 8px">
                                            <option value="-1">انتخاب کنید</option>
                                            <option value="1">ارسال عکس واریز وجه</option>
                                            <option value="2">پرداخت با چک(مخصوص کاربران ویژه)</option>
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <h4>روش پرداخت</h4>
                                    </div>

                                    <div class="col-xs-12">
                                        <div id="picDiv" class="hidden">
                                            <span>فایل عکس واریز وجه</span>
                                            <input name="pic" id="pic" type="file" style="display: inline-block; font-size: 14px; margin: 10px">
                                        </div>
                                    </div>

                                    <center class="col-xs-12" style="margin-top: 20px">
                                        <p>اگر کد تخفیف دارید، وارد نمایید</p>
                                        <input type="text" name="offcode" id="offcode" style="font-size: 14px; width: 100px; margin: 10px">
                                        <span onclick="checkOffCode()" style="font-size: 11px; padding: 4px !important;" class="btn btn-primary">چک کردن کد تخفیف</span>
                                    </center>
                                    
                                </div>
                            </div>
                            <div class="slimScrollBar" style="background: rgb(187, 187, 187); width: 7px; position: absolute; top: 0px; opacity: 0.4; display: none; border-radius: 7px; z-index: 99; left: 1px; height: 300px;"></div><div class="slimScrollRail" style="width: 7px; height: 100%; position: absolute; top: 0px; display: none; border-radius: 7px; background: rgb(234, 234, 234); opacity: 0.2; z-index: 90; left: 1px;"></div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <span data-dismiss="modal" class="btn dark btn-outline">انصراف</span>
                        <span onclick="checkPaymentKind()" class="btn green">تایید و ثبت سفارش</span>
                        <p id="err" style="color: red; font-weight: 500"></p>

                    </div>
                </div>
            </form>
        </div>
    </div>

    <script>

        var special = '{{\Illuminate\Support\Facades\Auth::user()->special}}';

        function checkOffCode() {

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                }
            });

            $.ajax({
                type: 'post',
                url: '{{route('checkOffCode')}}',
                data: {
                    offcode: $("#offcode").val()
                },
                success: function (res) {

                    res = JSON.parse(res);

                    if(res.status === "ok")
                        alert("کد وارد شده معتبر است و از مبلغ سفارش شما " + res.amount + " کم خواهد شد.");
                    else if(res.status === "nok1")
                        alert("کد تخفیف وارد شده نامعتبر است.");
                    else if(res.status === "nok2")
                        alert("کد تخفیف وارد شده منقضی شده است.");

                }
            });
        }
        
        function getItems(val) {

            $('#basket_id').val(val);

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                }
            });

            $.ajax({
                type: 'post',
                url: '{{route('getItems')}}',
                data: {
                    id: val
                },
                success: function (res) {

                    res = JSON.parse(res);

                    var totalSum = res.totalSum;
                    res = res.items;

                    var newElem = "<table><tr><td>نام محصول</td><td>(ریال)قیمت هر واحد</td><td>تعداد</td><td>(ریال)جمع کل</td></tr>";

                    for(var i = 0; i < res.length; i++) {
                        newElem += "<tr><td>" + res[i].name + "</td><td>" + res[i].price + "</td><td>" + res[i].num + "</td><td>" + res[i].sum + "</td></tr>";
                    }

                    newElem += "</table><p><span>جمع کل: </span><span>" + totalSum + "</span><span> ریال </span></p>";
                    $("#items").empty().append(newElem);

                    $("#orderAgainBtn").click();
                }
            });

        }

        function checkPaymentKind() {

            var payment_kind = $("#payment_kind").val();

            if(payment_kind == "2" && special == "0") {
                alert("پرداخت با چک فقط مخصوص کاربران ویژه است. برای کاربر ویژه شدن با مدیریت سایت (09133518607) تماس بگیرید.");
                return;
            }

            $("#buyAgainForm").submit();
        }

        function changePaymentKind(val) {

            if(val == 1)
                $("#picDiv").removeClass('hidden');
            else
                $("#picDiv").addClass('hidden');

        }

    </script>

@stop