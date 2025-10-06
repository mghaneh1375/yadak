<?php

    $offers = \App\models\Product::whereHide(false)->where("secondary_price", "<>", "-1")->where("secondary_price", "<>", "money")->where('number', ">", 0)->get();

    foreach ($offers as $offer) {

        $tmp = \App\models\ProductPic::whereProductId($offer->id)->first();
        if($tmp != null) {
            $offer->pic = \Illuminate\Support\Facades\URL::asset('productPic/' . $tmp->name);
        }
        else {
            $offer->pic = \Illuminate\Support\Facades\URL::asset('productPic/1.jpg');
        }

    }

?>

<div class="takhfif" style="width: 32%; float: right; margin-right: 25px">
    <div class="portlet light portlet-fit bordered" style="height: 550px; overflow: auto; max-height: 550px">
        <div class="portlet-body">
            <div class="mt-element-list">
                <div class="mt-list-head list-news ext-1 font-white bg-yellow-crusta">
                    <center class="list-head-title-container">
                        <h3 class="list-title">محصولات تخفیف دار</h3>
                    </center>
                    <div class="list-count pull-right bg-yellow-saffron">{{count($offers)}}</div>
                </div>
                <div class="mt-list-container list-news ext-2">
                    <ul>
                        @foreach($offers as $offer)
                            <li style="cursor: pointer" onclick="document.location.href = '{{route('product', ['id' => $offer->id])}}'" class="mt-list-item">

                                <div class="list-thumb">
                                    <a>
                                        <img src="{{$offer->pic}}">
                                    </a>
                                </div>

                                <div class="list-item-content">
                                    <h2 class="uppercase bold">
                                        <a>{{$offer->name}}</a>
                                    </h2>

                                    <h4>
                                        <span>قیمت اصلی</span>
                                        <span style="text-decoration: line-through;">{{number_format($offer->money)}}</span>
                                        <span>ریال</span>
                                    </h4>

                                    <h4>
                                        <span>قیمت کنونی</span>
                                        <span>{{number_format($offer->secondary_price)}}</span>
                                        <span>ریال</span>
                                    </h4>

                                </div>

                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<div style="clear: both"></div>