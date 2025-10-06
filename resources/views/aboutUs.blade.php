@extends('layouts.siteStructure')

@section('header')
    @parent
@stop

@section('content')

    <center class="col-xs-12">
        <img src="{{URL::asset('images/aboutUs.png')}}" width="300px">
    </center>

    <center class="col-xs-12">
        <p style="margin-top: 40px; font-size: 20px; line-height: 1.7em; width: 400px !important; text-align: justify">این مجموعه دارای بیش از 30 سال سابقه نمایندگی سایپا با کد عاملیت 997 و نمایندگی شرکت هایی همچون جمع ساز، تکلان، افشان، PRx، آیس، فیکس، گوهر قطعه، پارسیان، شمع پارس، الکا موتور، برین ساز(مرسا)، HIC، برنا یدک، CTR، طوس، MBC و فراسلی می باشد.</p>
    </center>

@stop