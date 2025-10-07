@extends('layouts.structure')

@section('header')
    @parent

@stop

@section('content')
    <style>
        * {
            box-sizing: border-box;
        }

        .column {
            float: left;
            width: 33.33%;
            padding: 5px;
            height: 300px;
            max-height: 300px;

        }

        /* Clearfix (clear floats) */
        .row::after {
            content: "";
            clear: both;
            display: table;
        }

        .overlay {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            background-color: #008CBA;
            overflow: hidden;
            width: 100%;
            height: 100%;
            -webkit-transform: scale(0);
            -ms-transform: scale(0);
            transform: scale(0);
            -webkit-transition: .3s ease;
            transition: .3s ease;
        }

        .container:hover .overlay {
            -webkit-transform: scale(1);
            -ms-transform: scale(1);
            transform: scale(1);
        }

        .text {
            color: white;
            font-size: 20px;
            position: absolute;
            top: 50%;
            left: 50%;
            -webkit-transform: translate(-50%, -50%);
            -ms-transform: translate(-50%, -50%);
            transform: translate(-50%, -50%);
            text-align: center;
        }

        .container {
            position: relative;
        }

        .modal {
            display: none; /* Hidden by default */
            position: fixed; /* Stay in place */
            z-index: 1; /* Sit on top */
            padding-top: 100px; /* Location of the box */
            left: 0;
            top: 0;
            width: 100%; /* Full width */
            height: 100%; /* Full height */
            overflow: auto; /* Enable scroll if needed */
            background-color: rgb(0,0,0); /* Fallback color */
            background-color: rgba(0,0,0,0.4); /* Black w/ opacity */
        }

        /* Modal Content */
        .modal-content {
            position: relative;
            background-color: #fefefe;
            margin: auto;
            padding: 0;
            border: 1px solid #888;
            width: 30%;
            direction: rtl;
            box-shadow: 0 4px 8px 0 rgba(0,0,0,0.2),0 6px 20px 0 rgba(0,0,0,0.19);
            -webkit-animation-name: animatetop;
            -webkit-animation-duration: 0.4s;
            animation-name: animatetop;
            animation-duration: 0.4s
        }

        @-webkit-keyframes animatetop {
            from {top:-300px; opacity:0}
            to {top:0; opacity:1}
        }

        @keyframes animatetop {
            from {top:-300px; opacity:0}
            to {top:0; opacity:1}
        }
    </style>
    <!-- BEGIN CONTENT BODY -->
    <div id="mainContainer" class="page-content" style="margin-top: 5%; direction: rtl">
        <div class="row">
            @for($i = 0; $i < count($slide); $i++)
                <div class="column container">
                    <img src="{{URL::asset('slideBar/'.$slide[$i]->pic)}}" alt="Snow" style="width:100%; height: 100%">
                    <center class="overlay">
                        <input type="submit" value="حذف" class="btn green" onclick="deleteSlideQuest({{$slide[$i]->id}})"  style="margin-top: 50px; background-color: rebeccapurple">
                    </center>
                </div>
            @endfor
            <div class="column" style="border: solid;">
                <div>
                    <center>
                        <img id="blah" src="{{URL::asset('slideBar/12.svg')}}"  alt="your image" style="width:100%; height: 165px;">
                    </center>
                </div>
                <center style="margin-top: 5%;">
                    <form id="add_slide_show" action="{{route('saveSlideShow')}}" method="post"
                          enctype="multipart/form-data">
                        {{ csrf_field() }}
                        <input type="hidden" name="kind" value="save">
                        <input type="file" name="pic" id="imgInp">
                        <input type="submit" value="ذخیره" class="btn green" style="margin-top: 10px">
                    </form>
                </center>
            </div>
        </div>
    </div>

    <div id="myModal" class="modal">
        <form action="{{route('saveSlideShow')}}" method="post">
            {{ csrf_field() }}
            <div class="modal-content">
                <input type="hidden" value="" id="slideId" name="id">
                <input type="hidden" value="delete" name="kind">
                <h2 style="padding-right: 5%;">ایا اطیمنان دارید؟</h2>
                <input type="submit" value="بله" class="btn green"  style="margin-right: 5%; margin-bottom: 3%">
                <input type="button" value="خیر" class="btn green"  style="float: left; margin-bottom: 3%; margin-left: 5%;" onclick="document.getElementById('myModal').style.display = 'none'">
            </div>
        </form>
    </div>

    <style>
        .register-form {
            width: 400px;
            background-color: white;
            padding: 20px;
            position: absolute;
            top: 50px;
            left: 35%;
            right: auto;
        }

        input, textarea, select {
            text-align: -webkit-right;
            direction: rtl;
        }
    </style>
@stop

@section('moreJS')


    <script>
        var modal = document.getElementById('myModal');
        window.onclick = function(event) {
            if (event.target == modal) {
                modal.style.display = "none";
            }
        }
    </script>

    <script>
        $.ajaxSetup(
            {
                headers:
                    {
                        'X-CSRF-Token': $('input[name="_token"]').val()
                    }
            });
    </script>

    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
    </script>

    <script>
        function readURL(input) {

            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function(e) {
                    $('#blah').attr('src', e.target.result);
                }

                reader.readAsDataURL(input.files[0]);
            }
        }

        $("#imgInp").change(function() {
            readURL(this);
        });
    </script>

    <script>

        function deleteSlideQuest(id) {
            document.getElementById('myModal').style.display = 'block';
            document.getElementById('slideId').value = id;
        }
    </script>

@stop