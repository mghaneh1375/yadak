@extends('layouts.structure')

@section('header')
    @parent

    <style>

        label {
            width: 200px;
        }

        input {
            text-align: center;
        }

    </style>

@stop

@section('content')

    <center style="margin-top: 200px">

        <form method="post" action="{{route('doConfig')}}">

            {{csrf_field()}}

            <div>
                <input id="warning_threshold" value="{{$config->warning_threshold}}" name="warning_threshold" type="number">
                <label for="warning_threshold">حد آستانه تعداد اخطار کالا</label>
            </div>

            <div>
                <input value="{{$config->critical_threshold}}" id="critical_threshold" name="critical_threshold" type="number">
                <label for="critical_threshold">حد آستانه تعداد بحرانی کالا</label>
            </div>

            <div style="margin-top: 10px">
                <input type="submit" class="btn btn-success" value="ذخیره">
            </div>
        </form>

    </center>
@stop