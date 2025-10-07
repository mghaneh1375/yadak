@extends('layouts.structure')

@section('header')
    @parent

    <style>
        th, td {
            text-align: center;
            padding: 8px;
            border: 1px solid #444;
        }

        table {
            direction: rtl;
        }

        .modal-title {
            float: right;
        }

    </style>

@stop

@section('content')

    <script>
        var selected = -1;
    </script>

    <center style="margin-top: 100px" class="col-xs-12">

        @if(count($orders) == 0)
            <center style="margin-top: 30px">
                <h3 style="direction: rtl; color: #812639">سفارشی موجود نیست!!!</h3>
            </center>

        @else

            <table>
                <tr>
                    <td>نام سفارش دهنده</td>
                    <td>شماره تماس</td>
                    <td style="width: 200px">آدرس</td>
                    <td>تاریخ سفارش</td>
                    <td>ارزش سفارش</td>
                    <td>نوع پرداخت</td>
                    <td>کد تخفیف</td>
                    <td>عملیات</td>
                </tr>
                @foreach($orders as $order)
                    <tr id="tr_{{$order->id}}">
                        <td>{{$order->user->first_name . ' ' . $order->user->last_name}}</td>
                        <td>{{$order->user->phone}}</td>
                        <td style="width: 200px">{{$order->user->address}}</td>
                        <td>{{$order->submit_date}}</td>
                        <td>{{number_format($order->total)}}</td>
                        <td>{{($order->payment_kind == 1) ? "آپلود فیش واریزی" : "پرداخت با چک"}}</td>
                        <td>
                            @if($order->offer == null)
                                استفاده نشده
                            @else
                                <p>
                                    <span>{{$order->offer[0]}}</span>
                                    <span> - </span>
                                    <span> ارزش کد تخفیف </span>
                                    @if($order->offer[3] == 1)
                                        <span>{{$order->offer[1]}} ریال</span>
                                    @else
                                        <span>{{$order->offer[1]}} درصد</span>
                                    @endif
                                </p>
                            @endif
                        </td>
                        <td>

                            <a data-toggle="modal" onclick="getItems('{{json_encode($order->items)}}')" href="#products" class="btn btn-info">مشاهده اقلام سفارش</a>

                            @if($mode != "accepts")
                                <a data-toggle="modal" href="#accept" onclick="selected = '{{$order->id}}'" class="btn btn-success">تایید سفارش</a>
                            @endif

                            @if($mode != "rejects")
                                <a data-toggle="modal" href="#reject" onclick="selected = '{{$order->id}}'" class="btn btn-danger">رد سفارش</a>
                            @endif

                            @if($order->payment_kind == 1)
                                <a onclick="$('#pic').attr('src', '{{\Illuminate\Support\Facades\URL::asset('payments/' . $order->pic)}}')" data-toggle="modal" href="#responsive" class="btn btn-default">مشاهده فیش واریزی</a>
                            @endif

                        </td>
                    </tr>
                @endforeach
            </table>

        @endif

    </center>

    <div id="responsive" class="modal fade" tabindex="-1" aria-hidden="true" style="display: none;">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <span style="float: left" class="close" data-dismiss="modal" aria-hidden="true"></span>
                    <h4 style="float: right" class="modal-title">مشاهده فیش واریزی</h4>
                </div>
                <div class="modal-body">
                    <div class="slimScrollDiv" style="position: relative; overflow: hidden; width: auto;">
                        <div class="scroller" style="width: auto;" data-always-visible="1" data-rail-visible1="1" data-initialized="1">
                            <center class="row">
                                <img width="350px" id="pic">
                            </center>
                        </div>
                        <div class="slimScrollBar" style="background: rgb(187, 187, 187); width: 7px; position: absolute; top: 0px; opacity: 0.4; display: none; border-radius: 7px; z-index: 99; left: 1px; height: 300px;"></div><div class="slimScrollRail" style="width: 7px; height: 100%; position: absolute; top: 0px; display: none; border-radius: 7px; background: rgb(234, 234, 234); opacity: 0.2; z-index: 90; left: 1px;"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <span data-dismiss="modal" class="btn dark btn-outline">بستن</span>
                </div>
            </div>
        </div>
    </div>

    <div id="products" class="modal fade" tabindex="-1" aria-hidden="true" style="display: none;">
        <div class="modal-dialog">

            <div class="modal-content">
                <div class="modal-header">
                    <span style="float: left" class="close" data-dismiss="modal" aria-hidden="true"></span>
                    <h4 class="modal-title">اقلام سفارش</h4>
                </div>
                <div class="modal-body">
                    <div class="slimScrollDiv" style="position: relative; overflow: hidden; width: auto;">
                        <div class="scroller" style="width: auto;" data-always-visible="1" data-rail-visible1="1" data-initialized="1">
                            <div class="row" id="items">
                            </div>
                        </div>
                        <div class="slimScrollBar" style="background: rgb(187, 187, 187); width: 7px; position: absolute; top: 0px; opacity: 0.4; display: none; border-radius: 7px; z-index: 99; left: 1px; height: 300px;"></div><div class="slimScrollRail" style="width: 7px; height: 100%; position: absolute; top: 0px; display: none; border-radius: 7px; background: rgb(234, 234, 234); opacity: 0.2; z-index: 90; left: 1px;"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <span data-dismiss="modal" class="btn dark btn-outline">بستن</span>
                </div>
            </div>
        </div>
    </div>

    <div id="reject" class="modal fade" tabindex="-1" aria-hidden="true" style="display: none;">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <span style="float: left" class="close" data-dismiss="modal" aria-hidden="true"></span>
                    <h4 style="float: right" class="modal-title">رد سفارش</h4>
                </div>
                <div class="modal-body">
                    <div class="slimScrollDiv" style="position: relative; overflow: hidden; width: auto;">
                        <div class="scroller" style="width: auto;" data-always-visible="1" data-rail-visible1="1" data-initialized="1">
                            <center class="row">
                                <div class="col-xs-12">
                                    <textarea id="desc1" style="width: 400px; direction: rtl; height: 250px; overflow: auto" placeholder="لطفا دلیل رد سفارش را بنویسید."></textarea>
                                </div>
                            </center>
                        </div>
                        <div class="slimScrollBar" style="background: rgb(187, 187, 187); width: 7px; position: absolute; top: 0; opacity: 0.4; display: none; border-radius: 7px; z-index: 99; left: 1px; height: 300px;"></div><div class="slimScrollRail" style="width: 7px; height: 100%; position: absolute; top: 0px; display: none; border-radius: 7px; background: rgb(234, 234, 234); opacity: 0.2; z-index: 90; left: 1px;"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <span id="closeReject" data-dismiss="modal" class="btn dark btn-outline">بستن</span>
                    <span onclick="doReject()" class="btn green">رد سفارش</span>
                </div>
            </div>
        </div>
    </div>

    <div id="accept" class="modal fade" tabindex="-1" aria-hidden="true" style="display: none;">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <span style="float: left" class="close" data-dismiss="modal" aria-hidden="true"></span>
                    <h4 style="float: right" class="modal-title">رد سفارش</h4>
                </div>
                <div class="modal-body">
                    <div class="slimScrollDiv" style="position: relative; overflow: hidden; width: auto;">
                        <div class="scroller" style="width: auto;" data-always-visible="1" data-rail-visible1="1" data-initialized="1">
                            <center class="row">

                                <div class="col-xs-12" style="direction: rtl">
                                    <label for="arrivalDate">تاریخ رسیدن اقلام به مشتری</label>
                                    <input placeholder="____ / __ / __" style="direction: ltr" type="text" class="date" id="arrivalDate">
                                </div>

                                <div class="col-xs-12">
                                    <textarea id="desc2" style="width: 400px; direction: rtl; height: 200px; margin-top: 10px; padding: 7px; overflow: auto" placeholder="توضیحات(در صورت وجود)"></textarea>
                                </div>
                            </center>
                        </div>
                        <div class="slimScrollBar" style="background: rgb(187, 187, 187); width: 7px; position: absolute; top: 0; opacity: 0.4; display: none; border-radius: 7px; z-index: 99; left: 1px; height: 300px;"></div><div class="slimScrollRail" style="width: 7px; height: 100%; position: absolute; top: 0px; display: none; border-radius: 7px; background: rgb(234, 234, 234); opacity: 0.2; z-index: 90; left: 1px;"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <span id="closeAccept" data-dismiss="modal" class="btn dark btn-outline">بستن</span>
                    <span onclick="doConfirm()" class="btn green">تایید سفارش</span>
                </div>
            </div>
        </div>
    </div>

    <script>


        function getItems(res) {

            res = JSON.parse(res);

            var newElem = "<table style='display: block; width: 90%'><tr><td>نام محصول</td><td>دسته</td><td style='direction: ltr'>(ریال)قیمت هر واحد</td><td>تعداد</td></tr>";

            for(var i = 0; i < res.length; i++) {
                newElem += "<tr><td>" + res[i].name + "</td><td>" + res[i].super_category + " / " + res[i].category +  " / " + res[i].brand + "</td><td>" + res[i].price + "</td><td>" + res[i].num + "</td></tr>";
            }

            newElem += "</table>";
            // newElem += "<p><span>جمع کل: </span><span>" + totalSum + "</span><span> تومان </span></p>"

            $("#items").empty().append(newElem);

            $("#orderAgainBtn").click();
        }

        $(".date").on('keypress', function () {
            return changeArrival(event);
        }).on('paste', function () {
            return false;
        }).on('keydown', function () {

            var key = event.keyCode || event.charCode;

            if( key == 8 || key == 46 ) {

                var oldVal = $("#arrivalDate").val();
                var newVal = "";
                var counter = 0;
                for (var i = 0; i < oldVal.length; i++) {

                    if (oldVal[i] != "_" && oldVal[i] != ' ' && oldVal[i] != '/') {
                        counter++;
                        if(counter === arrivalDate.length) {
                            arrivalDate = arrivalDate.substr(0, arrivalDate.length - 1);
                            newVal += "_";
                        }
                        else
                            newVal += oldVal[i];
                    }
                    else {
                        newVal += oldVal[i];
                    }
                }

                $("#arrivalDate").val(newVal);
                return false;
            }
        });

        function changeArrival(evt) {

            evt = (evt) ? evt : window.event;
            var charCode = (evt.which) ? evt.which : evt.keyCode;

            if (charCode > 31 && (charCode < 48 || charCode > 57))
                return false;

            var input = charCode - 48;
            var oldVal = $("#arrivalDate").val();

            if(oldVal.length === 0) {
                oldVal = input + "___ / __ / __";
                $("#arrivalDate").val(oldVal);
                arrivalDate = input + "";
                return false;
            }

            var newVal = "";

            for(var i = 0; i < oldVal.length; i++) {

                if(oldVal[i] == "_") {
                    newVal += input;
                    newVal += oldVal.substr(i + 1);
                    arrivalDate += input + "";
                    $("#arrivalDate").val(newVal);
                    return false;
                }
                else
                    newVal += oldVal[i];
            }

            return false;
        }
        
        function doReject() {

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                }
            });

            $.ajax({
                type: 'post',
                url: '{{route('rejectOrder')}}',
                data: {
                    'desc': $("#desc1").val(),
                    'id': selected
                },
                success: function (res) {
                    if(res === "ok") {
                        $("#tr_" + selected).remove();
                        $("#closeReject").click();
                    }
                    else if(res === "nok")
                        alert("لطفا دلیل رد سفارش را بیان کنید");
                }
            });
        }

        function doConfirm() {

            var arrivalDate = $("#arrivalDate").val();

            if(arrivalDate.length != 14) {
                alert("لطفا تاریخ را به صورت صحیح وارد نمایید.");
                return;
            }

            for(var i = 0; i < arrivalDate.length; i++) {
                if(arrivalDate[i] == '_') {
                    alert("لطفا تاریخ را به صورت صحیح وارد نمایید.");
                    return;
                }
            }

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                }
            });

            $.ajax({
                type: 'post',
                url: '{{route('confirmOrder')}}',
                data: {
                    'desc': $("#desc2").val(),
                    'arrival': arrivalDate,
                    'id': selected
                },
                success: function (res) {
                    if(res === "ok")
                        $("#tr_" + selected).remove();
                }
            });

            $("#closeAccept").click();
        }

    </script>

@stop