@extends('layouts.siteStructure')

@section('header')
    @parent

    <style>
        p {
            margin: 8px;
        }
    </style>

@stop

@section('content')

    <center class="col-xs-12">
        <img src="{{URL::asset('images/contactUs.png')}}" width="500px" style="margin-top: 40px">
    </center>

    <center class="col-xs-12">
        <p style="margin-top: 40px; font-size: 20px; line-height: 1.7em; width: 500px !important; text-align: justify">نشانی: یزد سه راه حکیمیان، ابتدای بلوار پاکنژاد، فروشگاه اکبرپور، پلاک 920</p>
        <p style="font-size: 20px; width: 500px !important; text-align: justify">تلفن تماس: 03535221107 - 09133518607</p>
        <h3>شبکه های اجتماعی ما</h3>

        <div style="font-size: 20px; width: 500px !important; text-align: justify; clear: both">
            <div style="width: 45px; height: 45px; float: right; background: url('{{\Illuminate\Support\Facades\URL::asset('images/social.png')}}'); background-position-x: -11px; background-position-y: -13px"></div>
            <p style="float: right">تلگرام</p>
            <p style="float: left; direction: ltr">@DAmotor</p>
        </div>

        <div style="font-size: 20px; width: 500px !important; text-align: justify; clear: both">
            <div style="width: 45px; height: 45px; float: right; background: url('{{\Illuminate\Support\Facades\URL::asset('images/whatsapp.png')}}'); background-size: contain"></div>
            <p style="float: right">واتس آپ:</p>
            <p style="float: left; direction: ltr">09133518607</p>
        </div>

        <div style="font-size: 20px; width: 500px !important; text-align: justify; clear: both">
            <div style="width: 45px; height: 45px; float: right; background: url('{{\Illuminate\Support\Facades\URL::asset('images/social.png')}}'); background-position-x: -130px; background-position-y: -13px"></div>
            <p style="float: right">اینستاگرام:</p>
            <p style="float: left; direction: ltr">davoodakbarpoor86078607</p>
        </div>


    </center>

@stop