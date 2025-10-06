@extends('layouts.structure')

@section('header')
    @parent

    <style>
        th, td {
            text-align: center;
            padding: 7px;
            border: 1px solid #444;
        }

    </style>

    <style type="text/css">
        #container {
            min-width: 310px;
            max-width: 800px;
            height: 400px;
            margin: 0 auto
        }

        select {
            min-width: 100px;
            text-align: center;
        }

        label {
            min-width: 120px;
        }

    </style>
@stop

@section('content')

    <div class="col-md-12" style="margin-top: 100px">

        <div class="portlet light portlet-fit bordered" style="height: 500px !important;">
            <div class="portlet-body">

                <center class="col-lg-2">
                    <button onclick="document.location.href = '{{route('sellReport', ['id' => $id])}}' + '/' + $('#year').val() + '/' + $('#month').val()" class="btn btn-success">اعمال فیلتر</button>
                </center>

                <center class="col-lg-5">
                    <select id="year">
                        @for($i = 2020; $i < 2026; $i++)
                            @if($i == $y)
                                <option selected value="{{$i}}">{{$i}}</option>
                            @else
                                <option value="{{$i}}">{{$i}}</option>
                            @endif
                        @endfor
                    </select>
                    <label>سال مورد نظر</label>
                </center>

                <center class="col-lg-5">
                    <select id="month">
                        @for($i = 1; $i < 13; $i++)
                            @if($m == $i)
                                <option selected value="{{$i}}">{{$i}}</option>
                            @else
                                <option value="{{$i}}">{{$i}}</option>
                            @endif
                        @endfor
                    </select>
                    <label>ماه مورد نظر</label>
                </center>

                <div id="container"></div>
            </div>
        </div>

        <center>
            <table>
                <tr>
                    <td>تعداد خرید</td>
                    <td>تاریخ خرید</td>
                    <td>شماره خریدار</td>
                    <td>نام خریدار</td>
                </tr>
                @foreach($sells as $sell)
                    <tr>
                        <td>{{$sell["num"]}}</td>
                        <td>{{$sell["date"]}}</td>
                        <td>{{$sell["user"]->phone}}</td>
                        <td>{{$sell["user"]->first_name . ' ' . $sell["user"]->last_name}}</td>
                    </tr>
                @endforeach
            </table>
        </center>

    </div>

    <script src="{{\Illuminate\Support\Facades\URL::asset('js/highcharts.js')}}"></script>
    <script src="{{\Illuminate\Support\Facades\URL::asset('js/exporting.js')}}"></script>

    <script type="text/javascript">

        var stats = {!! json_encode($stats) !!};

        var x_vals = [];
        var y_vals = [];

        for (const [key, value] of Object.entries(stats)) {
            x_vals.push(key.split("-")[2]);
            y_vals.push(value);
        }

        Highcharts.chart('container', {

            title: {
                text: '{{$name}}'
            },

            subtitle: {
                text: 'گزارش فروش ماه ' + '{{$month}}'
            },

            yAxis: {
                title: {
                    text: 'تعداد فروش'
                }
            },
            legend: {
                layout: 'vertical',
                align: 'right',
                verticalAlign: 'middle'
            },

            // plotOptions: {
            //     series: {
            //         data: x_vals
            //     }
            // },
            xAxis: {
                categories: x_vals
            },
            series: [{
                name: 'فروش',
                data: y_vals
            }]

        });
    </script>

@stop