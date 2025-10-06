@extends('layouts.siteStructure')

@section('header')
    @parent
    <link href="{{URL::asset('pages/css/faq-rtl.min.css')}}" rel="stylesheet" type="text/css"/>
@stop

@section('content')

    <div class="page-content">

        <center class="page-head">
            <center>
                <h1>سوالات رایج</h1>
            </center>
        </center>


        <div class="faq-page faq-content-1" style="direction: rtl">
            <div class="faq-content-container">
                <div class="row">
                    <div class="col-md-4">
                        <?php $i = 0; ?>
                        @foreach($categories as $category)
                            @if($i % 3 == 0)
                                <div class="faq-section bordered">
                                    <h2 class="faq-title uppercase font-blue">{{$category->name}}</h2>
                                    <div class="panel-group accordion faq-content" id="accordion{{$category->id}}">
                                        @foreach($category->items as $itr)
                                            <div class="panel panel-default">
                                                <div class="panel-heading">
                                                    <h4 class="panel-title">
                                                        <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion{{$category->id}}" href="#collapse_{{$itr->id}}"> {!! $itr->question !!}</a>
                                                    </h4>
                                                </div>
                                                <div id="collapse_{{$itr->id}}" class="panel-collapse collapse">
                                                    <div class="panel-body">
                                                        <p>{!! $itr->answer !!}</p>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                            <?php $i++; ?>
                        @endforeach
                    </div>

                    <div class="col-md-4">
                        <?php $i = 0; ?>
                        @foreach($categories as $category)
                            @if($i % 3 == 1)
                                <div class="faq-section bordered">
                                    <h2 class="faq-title uppercase font-blue">{{$category->name}}</h2>
                                    <div class="panel-group accordion faq-content" id="accordion{{$category->id}}">
                                        @foreach($category->items as $itr)
                                            <div class="panel panel-default">
                                                <div class="panel-heading">
                                                    <h4 class="panel-title">
                                                        <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion{{$category->id}}" href="#collapse_{{$itr->id}}"> {!! $itr->question !!}</a>
                                                    </h4>
                                                </div>
                                                <div id="collapse_{{$itr->id}}" class="panel-collapse collapse">
                                                    <div class="panel-body">
                                                        <p>{!! $itr->answer !!}</p>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                            <?php $i++; ?>
                        @endforeach
                    </div>

                    <div class="col-md-4">
                        <?php $i = 0; ?>
                        @foreach($categories as $category)
                            @if($i % 3 == 2)
                                <div class="faq-section bordered">
                                    <h2 class="faq-title uppercase font-blue">{{$category->name}}</h2>
                                    <div class="panel-group accordion faq-content" id="accordion{{$category->id}}">
                                        @foreach($category->items as $itr)
                                            <div class="panel panel-default">
                                                <div class="panel-heading">
                                                    <h4 class="panel-title">
                                                        <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion{{$category->id}}" href="#collapse_{{$itr->id}}"> {!! $itr->question !!}</a>
                                                    </h4>
                                                </div>
                                                <div id="collapse_{{$itr->id}}" class="panel-collapse collapse">
                                                    <div class="panel-body">
                                                        <p>{!! $itr->answer !!}</p>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                            <?php $i++; ?>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

    </div>
@stop