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

        @if(count($products) == 0)
            <center style="margin-top: 30px">
                <h3 style="color: #812639">کالایی موجود نیست!!!</h3>
            </center>
        @endif

        @foreach($products as $product)

            <div class="col-lg-12 box">

                <div class="col-xs-12 col-lg-9">

                    <div style="margin-right: 30px; padding-top: 10px" class="detail">

                        <div style="padding-bottom: 5px; margin: 20px 10px 10px 10px">

                            <p>
                                <span>نام محصول: </span>
                                <span>&nbsp;</span>
                                <span>{{$product->name}}</span>
                                <span>&nbsp;&nbsp;&nbsp;</span>
                                <span>&nbsp;&nbsp;&nbsp;</span>
                                <span>&nbsp;&nbsp;&nbsp;</span>
                                <span>دسته محصول: </span>
                                <span>&nbsp;</span>
                                <span>{{$product->super_category}}</span>
                                <span> / </span>
                                <span>{{$product->category}}</span>
                                <span> / </span>
                                <span>{{$product->brand}}</span>
                            </p>

                            <div class="col-xs-12" style="padding: 0; margin-top: 75px">
                                <div style="float: right; display: inline-block">

                                    <h4>
                                        <span>قیمت اصلی</span>
                                        <span>{{number_format($product->money)}}</span>
                                        <span>ریال</span>
                                    </h4>

                                    @if($product->secondary_price != null && $product->secondary_price > 0 && $product->money != $product->secondary_price)

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
                                    @endif

                                </div>

                                <p onclick="addToBasket('{{$product->id}}')" class="zoomable" onmouseenter="$(this).css('background-color', 'rgb(237,187,182)')" onmouseleave="$(this).css('background-color', 'transparent')" style="float: right; cursor: pointer; margin-right: 10px; border: 1px solid brown; color: brown; height: 34px; padding: 0 23px; line-height: 30px; background-color: transparent; border-radius: 30px !important;">
                                    <span>افزودن به سبد خرید</span>
                                    <span id="buy" class="glyphicon glyphicon-shopping-cart" style="margin-right: 4px; cursor: pointer; font-family: 'Glyphicons Halflings' !important;"></span>
                                </p>
                            </div>
                        </div>

                    </div>

                </div>

                <div class="col-xs-12 col-lg-3">
                    <img id="pic" src="{{$product->pic}}" width="100%">
                </div>

            </div>
        @endforeach

    </div>

    <div class="col-lg-1"></div>

    <script>

        function addToBasket(id) {

            var cart = $('#shopping-cart');
            var imgtodrag = $("#pic");

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                }
            });

            $.ajax({
                type: 'post',
                url: '{{route('addToBasket')}}',
                data: {
                    'product_id': id
                },
                success: function (res) {
                    if(res === "ok") {

                        if (imgtodrag) {
                            var imgclone = imgtodrag.clone()
                                .offset({
                                    top: imgtodrag.offset().top,
                                    left: imgtodrag.offset().left
                                })
                                .css({
                                    'opacity': '0.5',
                                    'position': 'absolute',
                                    'height': '150px',
                                    'width': '150px',
                                    'z-index': '100'
                                })
                                .appendTo($('body'))
                                .animate({
                                    'top': cart.offset().top + 10,
                                    'left': cart.offset().left + 10,
                                    'width': 75,
                                    'height': 75
                                }, 1000);

                            setTimeout(function () {
                                shake();
                            }, 1500);

                            imgclone.animate({
                                'width': 0,
                                'height': 0
                            }, function () {
                                $(this).detach()
                            });
                        }

                        tmp = cart.attr('data-val');
                        tmp = parseInt(tmp) + 1;
                        cart.attr('data-val', tmp);
                        $("#basketNum").empty().append(tmp);
                    }
                }
            });
        }

    </script>

@stop