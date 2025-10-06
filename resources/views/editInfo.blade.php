@extends('layouts.siteStructure')

@section('header')
    @parent

    <style>
        .form-group {
            width: 300px;
        }
    </style>

@stop

@section('content')

    <center style="margin-top: 100px" class="content">

        <form class="register-form myForm" action="{{route('doEditInfo')}}" method="post">

            {{csrf_field()}}

            <div class="form-group">
                <label class="control-label visible-ie8 visible-ie9">نام</label>
                <div class="input-icon">
                    <i class="fa fa-font"></i>
                    <input class="form-control placeholder-no-fix" value="{{(\Illuminate\Support\Facades\Auth::user()->first_name)}}" type="text" placeholder="نام" name="firstname" />
                </div>
            </div>

            <div class="form-group">
                <label class="control-label visible-ie8 visible-ie9">نام خانوادگی</label>
                <div class="input-icon">
                    <i class="fa fa-font"></i>
                    <input class="form-control placeholder-no-fix" value="{{\Illuminate\Support\Facades\Auth::user()->last_name}}" type="text" placeholder="نام خانوادگی" name="lastname" />
                </div>
            </div>

            <div class="form-group">
                <label class="control-label visible-ie8 visible-ie9">شماره همراه</label>
                <div class="input-icon">
                    <i class="fa fa-phone"></i>
                    <input class="form-control placeholder-no-fix" value="{{\Illuminate\Support\Facades\Auth::user()->phone}}" type="text" placeholder="شماره همراه" name="phone" />
                </div>
            </div>

            <div class="form-group">
                <label class="control-label visible-ie8 visible-ie9">نشانی</label>
                <div class="input-icon">
                    <i class="fa fa-location-arrow"></i>
                    <textarea name="address" class="form-control placeholder-no-fix" placeholder="نشانی">{{\Illuminate\Support\Facades\Auth::user()->address}}</textarea>
                </div>
            </div>

            <div class="form-actions">
                <p id="err"></p>
                <button type="submit" id="register-submit-btn" class="btn green"> ویرایش اطلاعات </button>
            </div>
        </form>

        <form class="activation myForm hidden" action="{{route('doActiveAgain')}}" method="post">

            {{csrf_field()}}

            <input name="phone" id="phone" type="hidden">

            <p>کد فعال سازی برای شماره وارد شده پیامک شده است. (در صورت عدم رسید پیامک می توانید چند دقیقه بعد مجددا امتحان کنید)</p>

            <div class="form-group">
                <label class="control-label visible-ie8 visible-ie9">کد فعال سازی</label>
                <div class="input-icon">
                    <i class="fa fa-user"></i>
                    <input class="form-control placeholder-no-fix" type="text" autocomplete="off" placeholder="کد فعال سازی" name="code" />
                </div>
            </div>

            <div class="form-actions">
                @if(isset($err) && !empty($err))
                    <p>{{$err}}</p>
                @endif
                <button type="submit" class="btn green"> تایید </button>
            </div>

            <p>
                <span id="reminder"></span>
                <span>&nbsp;</span>
                <span>تا امکان ارسال مجدد کد فعال سازی</span>
            </p>

            <center>
                <button type="submit" formaction="{{route('resendActivationAgain')}}" id="resendBtn" disabled class="btn red"> ارسال مجدد کد فعال سازی </button>
            </center>
        </form>

    </center>

    <script src="{{\Illuminate\Support\Facades\URL::asset('pages/scripts/login-4.min.js')}}" type="text/javascript"></script>

    <script>

        @if(isset($status) && $status == "err")
        $("#err").empty().append('{{$err}}');
        $(".myForm").addClass('hidden');
        $(".register-form").removeClass('hidden').css('display', 'block');
        @elseif(isset($status) && $status == "step")
        $(".myForm").addClass('hidden');
        $("#phone").val('{{$phone}}');
        startTimer('{{$reminder}}');
        $(".activation").removeClass('hidden').css('display', 'block');
        @endif

        var c_minutes;
        var c_seconds;
        var total_time;

        function startTimer(val) {

            total_time = val;
            c_minutes = parseInt(total_time / 60);
            c_seconds = parseInt(total_time % 60);

            if (total_time > 0)
                setTimeout("checkTime()", 1);
            else
                showResendBtn();
        }

        function showResendBtn() {
            $("#resendBtn").removeAttr('disabled');
        }

        function checkTime() {
            document.getElementById("reminder").innerHTML =  c_seconds + " : " + c_minutes;
            if (total_time <= 0)
                setTimeout("showResendBtn()", 1);
            else {
                total_time--;
                c_minutes = parseInt(total_time / 60);
                c_seconds = parseInt(total_time % 60);
                setTimeout("checkTime()", 1000);
            }
        }

    </script>
@stop