@extends('layouts.structure')

@section('header')
    @parent

    <style>
        th, td {
            text-align: right;
        }

    </style>

@stop

@section('content')

    <center style="margin-top: 100px">

        <div class="portlet box purple">
            <div class="portlet-title">
                <div class="caption" style="float: right">
                    <i style="float: right" class="fa fa-cogs"></i>
                    <span style="margin-right: 10px">کد های تخفیف استفاده شده</span>
                </div>
                <div class="tools" style="float: left">
                    <a href="javascript:;" class="collapse" data-original-title="" title=""> </a>
                    <a href="#portlet-config" data-toggle="modal" class="config" data-original-title="" title=""> </a>
                    <a href="javascript:;" class="reload" data-original-title="" title=""> </a>
                    <a href="javascript:;" class="remove" data-original-title="" title=""> </a>
                </div>
            </div>
            <div class="portlet-body">
                <div class="table-scrollable">
                    <table class="table table-striped table-bordered table-hover">
                        <thead>
                        <tr>
                            <th scope="col">تاریخ استفاده</th>
                            <th scope="col">کد مورد نظر</th>
                            <th scope="col">مقدار تخفیف</th>
                            <th scope="col" style="width:450px !important">نام کاربری استفاده کننده</th>
                            <th scope="col">شماره همراه استفاده کننده</th>
                        </tr>
                        </thead>

                        <tbody>
                            @foreach($used as $itr)
                                <tr>
                                    <td>{{$itr->submit_date}}</td>
                                    <td>{{$itr->offcode[0]}}</td>
                                    <td>{{$itr->offcode[1]}}</td>
                                    <td>{{$itr->username}}</td>
                                    <td>{{$itr->phone}}</td>
                                </tr>
                            @endforeach
                        </tbody>

                    </table>
                </div>
            </div>
        </div>

        <div class="portlet box yellow">
            <div class="portlet-title">
                <div class="caption" style="float: right">
                    <i style="float: right" class="fa fa-cogs"></i>
                    <span style="margin-right: 10px">کد های تخفیف موجود</span>
                </div>
                <div class="tools" style="float: left">
                    <a href="javascript:;" class="collapse" data-original-title="" title=""> </a>
                    <a href="#portlet-config" data-toggle="modal" class="config" data-original-title="" title=""> </a>
                    <a href="javascript:;" class="reload" data-original-title="" title=""> </a>
                    <a href="javascript:;" class="remove" data-original-title="" title=""> </a>
                </div>
            </div>
            <div class="portlet-body">
                <div class="table-scrollable">
                    <table class="table table-striped table-bordered table-hover">
                        <thead>
                        <tr>
                            <th scope="col">عملیات</th>
                            <th scope="col">مهلت استفاده</th>
                            <th scope="col">کد مورد نظر</th>
                            <th scope="col">مقدار تخفیف</th>
                            <th scope="col">نوع کد</th>
                        </tr>
                        </thead>

                        <tbody>
                            @foreach($codes as $itr)
                                <tr id="tr_{{$itr->id}}">
                                    <td><i onclick="removeTakhfif({{$itr->id}})" style="cursor: pointer" class="fa fa-remove"></i></td>
                                    <td>{{$itr->expire}}</td>
                                    <td>{{$itr->code}}</td>
                                    <td>{{number_format($itr->amount)}}</td>
                                    <td>{{$itr->kind}}</td>
                                </tr>
                            @endforeach
                        </tbody>

                    </table>
                </div>
            </div>
        </div>

    </center>

    <script>

        function removeTakhfif(id) {

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                }
            });

            $.ajax({
                type: 'post',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                },
                url: '{{route('deleteOffer')}}',
                data: {
                    id: id
                },
                success: function (res) {

                    if(res === "ok")
                        $("#tr_" + id).remove();

                }
            });

        }

    </script>

@stop