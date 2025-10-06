@extends('layouts.siteStructure')

@section('header')
    @parent

    <style>

        #bookmark:hover:before {
            content: "\e005" !important;
        }

        .zoomable:hover {
            transform: scale(1.1);
        }

    </style>

@stop

@section('content')

    <div class="col-xs-12" style="position: sticky">

        <p style="float: right; margin-right: 40px; font-size: 16px; font-weight: bolder">
            <span>{{$product->super_category}}</span>
            <span> / </span>
            <span>{{$product->category}}</span>
            <span> / </span>
            <span>{{$product->brand}}</span>
        </p>

        <div class="bookmarkDivTotal">
            <p onclick="bookmark()" class="zoomable bookmarkDiv" onmouseenter="$(this).css('background-color', 'rgb(199, 200, 206)')" onmouseleave="$(this).css('background-color', 'transparent')" style="cursor: pointer; float: left; margin-left: 30px; border: 1px solid #202121; height: 34px; padding: 0 23px; line-height: 30px; background-color: transparent; border-radius: 30px !important;">
                <span>نشان کن</span>
                <span id="bookmark" class="glyphicon {{($bookmark) ? "glyphicon-heart" : "glyphicon-heart-empty"}}" style="margin-right: 4px; cursor: pointer; font-family: 'Glyphicons Halflings' !important;"></span>
            </p>

            @if($product->number > 0)
                <p onclick="addToBasket()" class="zoomable" onmouseenter="$(this).css('background-color', 'rgb(237,187,182)')" onmouseleave="$(this).css('background-color', 'transparent')" style="cursor: pointer; float: left; margin-left: 10px; border: 1px solid brown; color: brown; height: 34px; padding: 0 23px; line-height: 30px; background-color: transparent; border-radius: 30px !important;">
                    <span>افزودن به سبد خرید</span>
                    <span id="buy" class="glyphicon glyphicon-shopping-cart" style="margin-right: 4px; cursor: pointer; font-family: 'Glyphicons Halflings' !important;"></span>
                </p>
            @endif
        </div>

    </div>

    <div class="col-xs-12">

        <center class="col-lg-6">
            <img width="70%" id="pic" src="{{$product->pic}}">
        </center>

        <div class="col-lg-6">

            <div style="margin-right: 40px">
                <center>
                    <h3>{{$product->name}}</h3>
                </center>

                <p>{!! html_entity_decode($product->desc) !!}</p>

                @if($product->secondary_price != $product->money && $product->secondary_price != -1)
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

                <center>
                    @if($product->number > 0)
                        <div style="border: 1px solid #28829d; padding: 8px; border-radius: 9px !important; width: 180px; color: #28829d; font-size: 20px">وضعیت: موجود</div>
                    @else
                        <div style="border: 1px solid #9d3819; padding: 8px; border-radius: 9px !important; width: 180px; color: #9d3819; font-size: 20px">وضعیت: ناموجود</div>
                    @endif
                </center>
            </div>

        </div>
        
    </div>

    <script>

        var hasLogin = '{{\Illuminate\Support\Facades\Auth::check() ? "true" : "false"}}';

        function shake() {
            var div = document.getElementById('shopping-cart');
            var interval = 100;
            var distance = 10;
            var times = 4;

            $(div).css('position', 'relative');

            for (var iter = 0; iter < (times + 1) ; iter++) {
                $(div).animate({
                    left: ((iter % 2 == 0 ? distance : distance * -1))
                }, interval);
            }
            $(div).animate({ left: 0 }, interval);
        }


        function addToBasket() {

            if(hasLogin == "false") {
                document.location.href = '{{route('login')}}';
                return;
            }

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
                    'product_id': '{{$product->id}}'
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


        function bookmark() {

            if(hasLogin == "false") {
                document.location.href = '{{route('login')}}';
                return;
            }

            else {

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                    }
                });

                $.ajax({
                    type: 'post',
                    url: '{{route('bookmark')}}',
                    data: {
                        id: '{{$product->id}}'
                    },
                    success: function (res) {
                        if(res === "ok")
                            $("#bookmark").removeClass('glyphicon-heart-empty').addClass('glyphicon-heart');
                        else
                            $("#bookmark").removeClass('glyphicon-heart').addClass('glyphicon-heart-empty');
                    }
                });
            }
        }

    </script>


@stop