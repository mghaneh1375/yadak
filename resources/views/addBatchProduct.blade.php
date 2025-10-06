@extends('layouts.structure')

@section('header')
    @parent

@stop

@section('content')

    <center class="col-xs-12" style="margin-top: 200px">

        <form method="post" action="{{route('doAddBatchProduct')}}" enctype="multipart/form-data">

            {{csrf_field()}}

            <div class="col-xs-12">
                <input type="file" name="file">
            </div>

            <div class="col-xs-12" style="margin-top: 20px">
                <input class="btn btn-success" type="submit" value="تایید">
            </div>

        </form>

    </center>

@stop