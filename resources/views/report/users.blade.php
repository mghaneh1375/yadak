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
                    <td>عملیات</td>
                    <td>ارزش کل خریدها(ریال)</td>
                    <td>تعداد خرید</td>
                    <td>وضعیت</td>
                    <td>نوع کاربری</td>
                    <td>آدرس</td>
                    <td>شماره تماس</td>
                    <td>نام</td>
                </tr>
                @foreach($users as $user)
                    <tr>
                        <td>
                            @if($user->status == 3)
                                <button id="toggle_{{$user->id}}" onclick="toggleStatus('{{$user->id}}')" class="btn btn-danger col-xs-6">غیرفعال کردن کاربر</button>
                            @else
                                <button id="toggle_{{$user->id}}" onclick="toggleStatus('{{$user->id}}')" class="btn btn-success col-xs-6">فعال کردن کاربر</button>
                            @endif

                            <button onclick="document.location.href = '{{route('userBookmarks', ['uId' => $user->id])}}'" class="btn btn-info col-xs-6">اقلام مورد علاقه کاربر</button>
                            <button onclick="document.location.href = '{{route('userBuys', ['uId' => $user->id])}}'" class="btn btn-default col-xs-6">اقلام خریداری شده کاربر</button>
                            <button id="toggleSpecial_{{$user->id}}" onclick="toggleSpecial('{{$user->id}}')" class="btn btn-warning col-xs-6">تغییر وضعیت کاربری</button>
                        </td>
                        <td>{{$user->sum}}</td>
                        <td>{{$user->buys}}</td>
                        <td>{{($user->status == 3) ? "فعال" : "غیرفعال"}}</td>
                        <td>{{($user->special == 1) ? "ویژه" : "عادی"}}</td>
                        <td>{{$user->address}}</td>
                        <td>{{$user->phone}}</td>
                        <td>{{$user->first_name . ' ' . $user->last_name}}</td>
                    </tr>
                @endforeach
            </table>
        </center>

    </div>

    <script>

        function toggleSpecial(id) {

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                }
            });

            $.ajax({
                type: 'post',
                url: '{{route('toggleSpecial')}}',
                data: {
                    id: id
                },
                success: function (res) {
                    if(res === "ok")
                        window.location.reload();
                }
            });

        }

        function toggleStatus(id) {

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                }
            });

            $.ajax({
                type: 'post',
                url: '{{route('toggleStatusUser')}}',
                data: {
                    id: id
                },
                success: function (res) {
                    if(res === "ok")
                        $("#toggle_" + id).remove();
                }
            });

        }

    </script>

@stop