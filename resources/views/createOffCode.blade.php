@extends('layouts.structure')

@section('header')
    @parent

    <style>
        select, input, label {
            width: 200px;
        }
    </style>

@stop

@section('content')

    <center style="margin-top: 200px">

        <form method="post" action="{{route('generateOffCode')}}">

            {{csrf_field()}}

            <div>
                <input id="count" name="count" type="number">
                <label for="count">تعداد</label>
            </div>

            <div>
                <select style="direction: rtl" id="type" name="type">
                    <option value="1">مقداری</option>
                    <option value="2">درصدی</option>
                </select>
                <label for="type">نوع</label>
            </div>

            <div>
                <input id="amount" name="amount" type="number">
                <label for="amount">میزان</label>
            </div>

            <div>
                <input id="date" name="date" type="date">
                <label for="date">تاریخ انقضا</label>
            </div>

            <div style="margin-top: 10px">
                <input type="submit" class="btn btn-success" value="ایجاد">
            </div>
        </form>

    </center>
@stop