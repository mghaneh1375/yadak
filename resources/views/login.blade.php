<!DOCTYPE html>

<!--[if IE 8]> <html lang="en" class="ie8 no-js"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9 no-js"> <![endif]-->
<!--[if !IE]><!-->
<html lang="en" dir="rtl">
<!--<![endif]-->
<!-- BEGIN HEAD -->

<head>
    @include('layouts.common')
    <link href="{{\Illuminate\Support\Facades\URL::asset('global/plugins/select2/css/select2.min.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{\Illuminate\Support\Facades\URL::asset('global/plugins/select2/css/select2-bootstrap.min.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{\Illuminate\Support\Facades\URL::asset('pages/css/login-4-rtl.min.css')}}" rel="stylesheet" type="text/css" />
</head>

<body class=" login">

<div class="logo">
    <a href="{{route('home')}}">
        <img width="70px" src="{{\Illuminate\Support\Facades\URL::asset('layouts/layout/img/logo.png')}}" alt="" />
    </a>
</div>

<div class="content">

    <form class="login-form myForm" action="{{route('doLogin')}}" method="post">

        {{csrf_field()}}

        <h3 class="form-title">ورود</h3>
        <div class="alert alert-danger display-hide">
            <button class="close" data-close="alert"></button>
            <span> کد ملی و رمزعبور خود را وارد نمایید. </span>
        </div>
        <div class="form-group">

            <!--ie8, ie9 does not support html5 placeholder, so we just show field title for that-->
            <label class="control-label visible-ie8 visible-ie9">کد ملی</label>
            <div class="input-icon">
                <i class="fa fa-user"></i>
                <input class="form-control placeholder-no-fix" type="text" autocomplete="off" placeholder="کد ملی" name="username" />
            </div>
        </div>
        <div class="form-group">
            <label class="control-label visible-ie8 visible-ie9">رمزعبور</label>
            <div class="input-icon">
                <i class="fa fa-lock"></i>
                <input class="form-control placeholder-no-fix" type="password" autocomplete="off" placeholder="رمزعبور" name="password" />
            </div>
        </div>

        <div class="form-actions">
            @if(isset($loginErr) && !empty($loginErr))
                <p>{{$loginErr}}</p>
            @endif
            <button type="submit" class="btn green pull-right">ورود</button>
        </div>

        <div class="forget-password">
            <h4>رمزعبور خود را فراموش کرده اید؟</h4>
            <p> نگران نباشید
                <a onclick="$('.myForm').addClass('hidden'); $('.forget-form').removeClass('hidden').css('display', 'block')" id="forget-password"> اینجا </a> را کلیک کنید </p>
        </div>
        <div class="create-account">
            <p> حساب کاربری ندارید؟
                <a onclick="$('.myForm').addClass('hidden'); $('.register-form').removeClass('hidden').css('display', 'block')" id="register-btn">حساب کاربری بسازید</a>
            </p>
        </div>
    </form>

    <form class="register-form myForm" action="{{route('registry')}}" method="post">

        {{csrf_field()}}

        <h3>ساخت حساب کاربری</h3>
        <p> لطفا اطلاعات زیر را پر نمایید </p>

        <div class="form-group">
            <label class="control-label visible-ie8 visible-ie9">نام</label>
            <div class="input-icon">
                <i class="fa fa-font"></i>
                <input class="form-control placeholder-no-fix" value="{{(isset($firstname) ? $firstname : '')}}" type="text" placeholder="نام" name="firstname" />
            </div>
        </div>

        <div class="form-group">
            <label class="control-label visible-ie8 visible-ie9">نام خانوادگی</label>
            <div class="input-icon">
                <i class="fa fa-font"></i>
                <input class="form-control placeholder-no-fix" value="{{isset($lastname) ? $lastname : ''}}" type="text" placeholder="نام خانوادگی" name="lastname" />
            </div>
        </div>

        <div class="form-group">
            <!--ie8, ie9 does not support html5 placeholder, so we just show field title for that-->
            <label class="control-label visible-ie8 visible-ie9">کد ملی</label>
            <div class="input-icon">
                <i class="fa fa-font"></i>
                <input class="form-control placeholder-no-fix" type="text" value="{{isset($username) ? $username : ''}}" placeholder="کد ملی" name="username" />
            </div>
        </div>

        <div class="form-group">
            <label class="control-label visible-ie8 visible-ie9">شماره همراه</label>
            <div class="input-icon">
                <i class="fa fa-phone"></i>
                <input class="form-control placeholder-no-fix" value="{{isset($phone) ? $phone : ''}}" type="text" placeholder="شماره همراه" name="phone" />
            </div>
        </div>

        <div class="form-group">
            <label class="control-label visible-ie8 visible-ie9">نشانی</label>
            <div class="input-icon">
                <i class="fa fa-location-arrow"></i>
                <textarea name="address" class="form-control placeholder-no-fix" placeholder="نشانی">{{isset($address) ? $address : ''}}</textarea>
            </div>
        </div>

        <div class="form-group">
            <label class="control-label visible-ie8 visible-ie9">رمزعبور</label>
            <div class="input-icon">
                <i class="fa fa-lock"></i>
                <input class="form-control placeholder-no-fix" type="password" autocomplete="off" id="register_password" placeholder="رمزعبور" name="password" />
            </div>
        </div>

        <div class="form-group">
            <label class="control-label visible-ie8 visible-ie9">تکرار رمزعبور</label>
            <div class="controls">
                <div class="input-icon">
                    <i class="fa fa-lock"></i>
                    <input class="form-control placeholder-no-fix" type="password" autocomplete="off" placeholder="تکرار رمزعبور" name="rpassword" />
                </div>
            </div>
        </div>

        <div class="form-actions">
            <p id="err"></p>
            <button onclick="$('.myForm').addClass('hidden'); $('.login-form').removeClass('hidden').css('display', 'block')" id="register-back-btn" type="button" class="btn red btn-outline"> بازگشت </button>
            <button type="submit" id="register-submit-btn" class="btn green pull-right"> ثبت نام </button>
        </div>
    </form>

    <form class="activation myForm hidden" action="{{route('doActive')}}" method="post">

        {{csrf_field()}}

        <input name="uId" id="uId" type="hidden">
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
            <button type="submit" class="btn green pull-right"> تایید </button>
        </div>

        <p>
            <span id="reminder"></span>
            <span>&nbsp;</span>
            <span>تا امکان ارسال مجدد کد فعال سازی</span>
        </p>

        <center>
            <button type="submit" formaction="{{route('resendActivation')}}" id="resendBtn" disabled class="btn red"> ارسال مجدد کد فعال سازی </button>
        </center>
    </form>

    <form class="forget-form myForm" action="{{route('forgetPass')}}" method="post">

        {{csrf_field()}}

        <h4>رمزعبور خود را فراموش کرده اید؟</h4>
        <p> کد ملی و شماره همراه خود را وارد نمایید </p>
        <div class="form-group">
            <!--ie8, ie9 does not support html5 placeholder, so we just show field title for that-->
            <label class="control-label visible-ie8 visible-ie9">کد ملی</label>
            <div class="input-icon">
                <i class="fa fa-user"></i>
                <input class="form-control placeholder-no-fix" type="text" autocomplete="off" placeholder="کد ملی" name="nid" />
            </div>
        </div>
        <div class="form-group">
            <div class="input-icon">
                <i class="fa fa-phone"></i>
                <input class="form-control placeholder-no-fix" type="text" autocomplete="off" placeholder="شماره همراه" name="phone" />
            </div>
        </div>
        <div class="form-actions">
            @if(isset($err) && !empty($err))
                <p>{{$err}}</p>
            @endif
            <button onclick="$('.myForm').addClass('hidden'); $('.login-form').removeClass('hidden').css('display', 'block')" type="button" id="back-btn" class="btn red btn-outline">بازگشت </button>
            <button type="submit" class="btn green pull-right"> تایید </button>
        </div>
    </form>

</div>


<!--[if lt IE 9]>
<script src="{{URL::asset('global/plugins/respond.min.js')}}"></script>
<script src="{{URL::asset('global/plugins/excanvas.min.js')}}"></script>
<script src="{{URL::asset('global/plugins/ie8.fix.min.js')}}"></script>
<![endif]-->

<!-- BEGIN CORE PLUGINS -->
<script src="{{URL::asset('global/plugins/jquery.min.js')}}" type="text/javascript"></script>
<script src="{{URL::asset('global/plugins/bootstrap/js/bootstrap.min.js')}}" type="text/javascript"></script>
<script src="{{URL::asset('global/plugins/js.cookie.min.js')}}" type="text/javascript"></script>
<script src="{{URL::asset('global/plugins/jquery-slimscroll/jquery.slimscroll.min.js')}}" type="text/javascript"></script>
<script src="{{URL::asset('global/plugins/jquery.blockui.min.js')}}" type="text/javascript"></script>
<script src="{{URL::asset('global/plugins/bootstrap-switch/js/bootstrap-switch.min.js')}}" type="text/javascript"></script>
<!-- END CORE PLUGINS -->

<!-- BEGIN PAGE LEVEL PLUGINS -->
<script src="{{\Illuminate\Support\Facades\URL::asset('global/plugins/jquery-validation/js/jquery.validate.min.js')}}" type="text/javascript"></script>
<script src="{{\Illuminate\Support\Facades\URL::asset('global/plugins/jquery-validation/js/additional-methods.min.js')}}" type="text/javascript"></script>
<script src="{{\Illuminate\Support\Facades\URL::asset('global/plugins/select2/js/select2.full.min.js')}}" type="text/javascript"></script>
<script src="{{\Illuminate\Support\Facades\URL::asset('global/plugins/backstretch/jquery.backstretch.min.js')}}" type="text/javascript"></script>
<!-- END PAGE LEVEL PLUGINS -->


<!-- BEGIN THEME GLOBAL SCRIPTS -->
<script src="{{URL::asset('global/scripts/app.min.js')}}" type="text/javascript"></script>
<!-- END THEME GLOBAL SCRIPTS -->

<!-- BEGIN PAGE LEVEL SCRIPTS -->
<script src="{{\Illuminate\Support\Facades\URL::asset('pages/scripts/login-4.min.js')}}" type="text/javascript"></script>
<!-- END PAGE LEVEL SCRIPTS -->

<script>

    @if(isset($status) && $status == "err")
        $("#err").empty().append('{{$err}}');
        $(".myForm").addClass('hidden');
        $(".register-form").removeClass('hidden').css('display', 'block');
    @elseif(isset($status) && $status == "step")
        $(".myForm").addClass('hidden');
        $("#uId").val('{{$uId}}');
        $("#phone").val('{{$phone}}');
        startTimer('{{$reminder}}');
        $(".activation").removeClass('hidden').css('display', 'block');
    @elseif(isset($status) && $status == "forget")
        $(".myForm").addClass('hidden');
        $(".forget-form").removeClass('hidden').css('display', 'block');
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

</body>

</html>