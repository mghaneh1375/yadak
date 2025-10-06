@extends('layouts.structure')

@section('header')
    @parent

    <style>
        th, td {
            text-align: right;
            direction: rtl;
        }

    </style>
@stop

@section('content')


    <center style="margin-top: 100px">

        <center>
            <select onchange="changeSuperCat(this.value)" id="cat" style="text-align: center; direction: rtl">
                <option value="-1">همه</option>
                @foreach($categories as $category)
                    <option value="{{$category->name}}">{{$category->name}}</option>
                @endforeach
            </select>
            <label for="cat">دسته مورد نظر</label>
        </center>

        <div class="portlet box purple">
            <div class="portlet-title">
                <div class="caption" style="float: right">
                    <i style="float: right" class="fa fa-cogs"></i>
                    <span style="margin-right: 10px">گزارش گیری از محصولات</span>
                </div>
                <div class="tools" style="float: left"></div>
            </div>
            <div class="portlet-body">
                <div class="table-scrollable">
                    <table class="table table-striped table-bordered table-hover">
                        <thead>
                        <tr>
                            <th scope="col">عملیات</th>
                            <th scope="col">قیمت با تخفیف</th>
                            <th scope="col">قیمت اصلی</th>
                            <th scope="col">تعداد کل فروش</th>
                            <th scope="col">تعداد موجود</th>
                            <th scope="col">دسته</th>
                            <th scope="col">نام</th>
                        </tr>
                        </thead>

                        <tbody>
                        @foreach($products as $itr)
                            <tr class="tr" data-super-cat="{{$itr->super_category}}" data-brand="">
                                <td>
                                    <a style="font-size: 0.8em" href="{{route('sellReport', ['id' => $itr->id])}}" class="btn btn-default">گزارش فروش</a>
                                    @if($itr->hide)
                                        <button style="font-size: 0.8em" onclick="toShow('{{$itr->id}}')" id="show_{{$itr->id}}" class="btn btn-primary">نمایش به کاربر</button>
                                    @else
                                        <button style="font-size: 0.8em" onclick="toHide('{{$itr->id}}')" id="hide_{{$itr->id}}" class="btn btn-danger">پنهان کردن از کاربر</button>
                                    @endif
                                    <a style="font-size: 0.8em" href="{{route('editProduct', ['id' => $itr->id])}}" class="btn btn-warning">ویرایش کالا</a>
                                </td>
                                <td>
                                    <a style="font-size: 0.8em" onclick="preChangePrice('{{$itr->id}}', '{{$itr->secondary_price}}')" href="#takhfif" data-toggle="modal" class="btn btn-success">تغییر مبلغ قیمت با تخفیف</a>
                                    <span id="takhfif_{{$itr->id}}">{{$itr->secondary_price}}</span>
                                </td>
                                <td>{{$itr->money}}</td>
                                <td>{{$itr->sells}}</td>
                                <td style="background-color: {{$itr->background}}">{{$itr->number}}</td>
                                <td>{{$itr->super_category . ' / ' . $itr->category . ' / ' . $itr->brand}}</td>
                                <td>{{$itr->name}}</td>
                            </tr>
                        @endforeach
                        </tbody>

                    </table>
                </div>
            </div>
        </div>

    </center>

    <div id="takhfif" class="modal fade" tabindex="-1" aria-hidden="true" style="display: none;">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <span style="float: left" class="close" data-dismiss="modal" aria-hidden="true"></span>
                    <h4 style="float: right" class="modal-title">تغییر مبلغ قیمت با تخفیف</h4>
                </div>
                <div class="modal-body">
                    <div class="slimScrollDiv" style="position: relative; overflow: hidden; width: auto;">
                        <div class="scroller" style="width: auto;" data-always-visible="1" data-rail-visible1="1" data-initialized="1">
                            <center class="row">
                                <input type="number" id="price">
                            </center>
                        </div>
                        <div class="slimScrollBar" style="background: rgb(187, 187, 187); width: 7px; position: absolute; top: 0px; opacity: 0.4; display: none; border-radius: 7px; z-index: 99; left: 1px; height: 300px;"></div><div class="slimScrollRail" style="width: 7px; height: 100%; position: absolute; top: 0px; display: none; border-radius: 7px; background: rgb(234, 234, 234); opacity: 0.2; z-index: 90; left: 1px;"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <span id="closeTakhfifBtn" data-dismiss="modal" class="btn dark btn-outline">بستن</span>
                    <span onclick="changeTakhfif()" class="btn dark btn-outline">تایید</span>
                </div>
            </div>
        </div>
    </div>


    <script>

        var selectedId = -1;

        function changeSuperCat(val) {

            if(val == -1) {
                $(".tr").removeClass('hidden');
                return;
            }

            $(".tr").addClass('hidden').each(function () {

                if($(this).attr('data-super-cat') === val)
                    $(this).removeClass('hidden');

            });

        }

        function preChangePrice(id, price) {
            selectedId = id;
            $('#price').val(price.replace(",", ""));
        }

        function toHide(id) {

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                }
            });

            $.ajax({
                type: 'post',
                url: '{{route('toggleShow')}}',
                data: {
                    id: id
                },
                success: function (res) {
                    if(res === "ok")
                        $("#hide_" + id).remove();
                }
            });

        }

        function changeTakhfif() {

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                }
            });

            var val = $("#price").val();

            $.ajax({
                type: 'post',
                url: '{{route('changeTakhfif')}}',
                data: {
                    id: selectedId,
                    price: val
                },
                success: function (res) {
                    if(res === "ok") {
                        $("#takhfif_" + selectedId).empty().append(val);
                        $("#closeTakhfifBtn").click();
                    }
                }
            })
        }

        function toShow(id) {

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                }
            });

            $.ajax({
                type: 'post',
                url: '{{route('toggleShow')}}',
                data: {
                    id: id
                },
                success: function (res) {
                    if(res === "ok")
                        $("#show_" + id).remove();
                }
            });
        }

    </script>

@stop