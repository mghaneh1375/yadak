@extends('layouts.siteStructure')

@section('header')
    @parent

    <style>

        @media (max-width:767px) {

            .takhfif {
                display: block !important;
                width: 100% !important;
            }
        }


    </style>

@stop

@section('content')

    <div style="margin-right: 100px">
        @include('layouts.slider')
    </div>

    <div>
        <center class="page-head">

            <div style="color: #b82725; padding: 0; margin: 60px 0 0;">
                <h1>محصولات منتخب</h1>
            </div>

        </center>

        <div class="portfolio-content portfolio-1">
        <div id="js-filters-juicy-projects" class="cbp-l-filters-button">
            <div data-filter="*" class="cbp-filter-item-active cbp-filter-item btn dark btn-outline uppercase"> همه
                <div class="cbp-filter-counter"></div>
            </div>
            @foreach($categories as $category)
                <div data-filter=".{{$category->id}}" class="cbp-filter-item btn dark btn-outline uppercase"> {{$category->name}}
                    <div class="cbp-filter-counter"></div>
                </div>
            @endforeach
        </div>

        <div id="js-grid-juicy-projects" class="cbp">
            @foreach($products as $product)
                <div class="cbp-item {{$product->superCategId}}">
                    <div class="cbp-caption">
                        <div class="cbp-caption-defaultWrap">
                            <img src="{{$product->img}}" style="min-height: 250px; max-height: 250px">
                        </div>
                        <div class="cbp-caption-activeWrap">
                            <div class="cbp-l-caption-alignCenter">
                                <div class="cbp-l-caption-body">
                                    <a href="{{route('product', ['id' => $product->id])}}" class="cbp-l-caption-buttonLeft btn red uppercase btn red uppercase" rel="nofollow">مشاهده کالا</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="cbp-l-grid-projects-title uppercase text-center uppercase text-center">{{$product->name}}</div>
                    <div class="cbp-l-grid-projects-desc uppercase text-center uppercase text-center">{{$product->superCateg}} / {{$product->categ}} / {{$product->brand}}</div>

                    @if($product->secondary_price != $product->money && $product->secondary_price != -1)

                        <div class="cbp-l-grid-projects-desc text-center" style="text-decoration: line-through;">{{number_format($product->money)}} ریال</div>

                        <div class="cbp-l-grid-projects-desc text-center">{{number_format($product->secondary_price)}} ریال</div>

                    @else

                        <div class="cbp-l-grid-projects-desc uppercase text-center uppercase text-center">{{number_format($product->money)}} ریال</div>

                    @endif

                    @if($product->number > 0)
                        <div style="color: #28829d; font-size: 16px" class="cbp-l-grid-projects-desc uppercase text-center uppercase text-center">موجود</div>
                    @else
                        <div style="color: #9d3819; font-size: 16px" class="cbp-l-grid-projects-desc uppercase text-center uppercase text-center">ناموجود</div>
                    @endif
                </div>
            @endforeach
        </div>

    </div>
    </div>

@stop