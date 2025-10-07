@extends('layouts.siteStructure')

@section('header')
    @parent
@stop

@section('content')

    <center>
        <h3>
            عملیات ثبت درخواست شما با موفقیت انجام شد و باید منتظر تایید ادمین باشید.
        </h3>

        <h3>
            کد پیگیری شما: {{$follow_code}} می باشد.
        </h3>

        <p style="font-size: 16px">
            جهت پیگیری سفارش خود از قسمت منو بر روی پیگیری سفارشات کلیک کرده و در صورتی که تا 48 ساعت وضعیت خرید شما مشخص نشد با شماره 09133518607 تماس بگیرید.
        </p>

    </center>

@stop