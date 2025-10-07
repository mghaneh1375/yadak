
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta content="width=device-width, initial-scale=1" name="viewport" />
<meta charset="utf-8" />

<meta name="_token" content="{{ csrf_token() }}"/>

<link href="http://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700&subset=all" rel="stylesheet" type="text/css" />

<link rel="shortcut icon" type="image/png" href="{{\Illuminate\Support\Facades\URL::asset('layouts/layout/img/logo.png')}}"/>


<!-- BEGIN GLOBAL MANDATORY STYLES -->
<link href="{{URL::asset('global/plugins/font-awesome/css/font-awesome.min.css')}}" rel="stylesheet" type="text/css" />
<link href="{{URL::asset('global/plugins/simple-line-icons/simple-line-icons.min.css')}}" rel="stylesheet" type="text/css" />
<link href="{{URL::asset('global/plugins/bootstrap/css/bootstrap.min.css')}}" rel="stylesheet" type="text/css" />
<link href="{{URL::asset('global/plugins/bootstrap-switch/css/bootstrap-switch.min.css')}}" rel="stylesheet" type="text/css" />
<!-- END GLOBAL MANDATORY STYLES -->

<!-- BEGIN THEME GLOBAL STYLES -->
<link href="{{URL::asset('global/css/components.min.css')}}" rel="stylesheet" id="style_components" type="text/css" />
<link href="{{URL::asset('global/css/plugins.min.css')}}" rel="stylesheet" type="text/css" />
<!-- END THEME GLOBAL STYLES -->

<!-- BEGIN THEME LAYOUT STYLES -->
<link href="{{URL::asset('layouts/layout/css/layout-rtl.min.css?v=1.2')}}" rel="stylesheet" type="text/css" />
<link href="{{URL::asset('layouts/layout/css/themes/default-rtl.min.css')}}" rel="stylesheet" type="text/css" id="style_color" />
<link href="{{URL::asset('layouts/layout/css/custom-rtl.min.css')}}" rel="stylesheet" type="text/css" />
<!-- END THEME LAYOUT STYLES -->

<!-- BEGIN PAGE LEVEL PLUGINS -->
<link href="{{URL::asset('global/plugins/bootstrap-daterangepicker/daterangepicker.min.css')}}" rel="stylesheet" type="text/css" />
<link href="{{URL::asset('global/plugins/morris/morris.css')}}" rel="stylesheet" type="text/css" />
<link href="{{URL::asset('global/plugins/fullcalendar/fullcalendar.min.css')}}" rel="stylesheet" type="text/css" />
<link href="{{URL::asset('global/plugins/jqvmap/jqvmap/jqvmap.css')}}" rel="stylesheet" type="text/css" />
<link href="{{URL::asset('css/myFont.css')}}" rel="stylesheet">
<!-- END PAGE LEVEL PLUGINS -->



<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>


<style>
    .nav-item > a {
        min-height: 40px;
    }
    .nav-item > a > i {
        float: right !important;
    }
    .nav-item > a > .title {
        float: right !important;
    }

    div, p, span, a, li, h1, h2, h3, h4, h5, h6{
        font-family: IRANSans !important;
    }

    .hidden {
        display: none !important;
    }

    .dropdown-menu li {
        display: block;
        float: none !important;
        text-align: right;
    }

    .nav-tabs li {
        float: right;
    }

    .nav-tabs li a {
        color: #625f60 !important;
        font-weight: 600;
    }
    .nav-tabs {
        width: 100%;
        margin-bottom: 0;
        border-bottom: 2px solid black;
    }

    .dropdown-submenu {
        margin-right: 10px;
    }

    .dropdown-submenu li {
        display: block;
        float: none !important;
        text-align: right;
        margin: 7px;
        color: #d33e4d !important;
    }

    .dropdown-submenu li a {
        text-decoration: none;
        color: #d33e4d !important;
    }

    .list-thumb {
        float: right !important;
    }

    .list-item-content {
        padding-right: 90px !important;
        padding-left: 10px !important;
    }

    h1, h2, h3, h4 {
        font-weight: bold !important;
    }

</style>

<script>
    function numberWithCommas(x) {
        return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
    }
</script>