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

@stop

@section('content')

    <div class="col-md-12" style="margin-top: 100px">

        <center>
            <table>
                <tr>
                    <td>دسته محصول</td>
                    <td>نام محصول</td>
                </tr>

                @foreach($products as $product)
                    <tr>
                        <td>{{$product->super_category . ' / ' . $product->category . ' / ' . $product->brand}}</td>
                        <td>{{$product->product}}</td>
                    </tr>
                @endforeach
            </table>
        </center>

    </div>

@stop