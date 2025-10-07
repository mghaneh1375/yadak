@extends('layouts.structure')

@section('header')
    @parent
@stop

@section('content')
    <
    <div id="mainContainer" class="page-content" style="margin-top: 5%; direction: rtl">
        <div class="row">

        </div>
    </div>

    <div id="myModal" class="modal">
            <div class="modal-content">


            </div>
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

@stop