<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Laravel</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">
    <link rel="stylesheet" href="{{asset('plugins/bootstrap4/css/bootstrap.min.css')}}" >

    <!-- Styles -->
    <style>
        html, body {
            background-color: #333;
            color: #636b6f;
            font-family: 'Nunito', sans-serif;
            font-weight: 200;
            height: 100%;
            margin: 0;
        }

        .full-height {
            height: 100%;
        }

        .flex-center {
            align-items: center;
            display: flex;
            justify-content: center;
        }

        .position-ref {
            position: relative;
        }

        .top-right {
            position: absolute;
            right: 10px;
            top: 18px;
        }

        .content {
            text-align: center;
        }

        .title {
            font-size: 84px;
        }

        .links > a {
            color: #636b6f;
            padding: 0 25px;
            font-size: 13px;
            font-weight: 600;
            letter-spacing: .1rem;
            text-decoration: none;
            text-transform: uppercase;
        }

        .m-b-md {
            margin-bottom: 30px;
        }

        .header-banner {
            background-color: #333;
            background-image: url("{{asset('img/backgroundlarge.jpg')}}");

            background-repeat: no-repeat;
            background-size: auto;
            background-position: center;
            width: 100%;
            height: 450px;
        }

    </style>
    <style>
        /*-------------*/
        .VPcrop{
            width: 300px;
            height: 300px;
            border: 2px dashed #dcdee3;
            margin: 3px;
            position: relative;
            display: inline-block;
        }
        .VPcimg{
            width: calc(100% + 4px);
            height: calc(100% + 4px);
            top:-2px;
            left: -2px;
            position: absolute;
            z-index: 2;
        }
        .VPcropint{
            width: 100%;
            height: 100%;
            position: absolute;
            top:0;
            left: 0;
            text-indent: -10000;
            opacity: 0;
            z-index: 3;
        }
        .VPcropspan{
            position: absolute;
            right: 5px;
            top: 1px;
            padding: 3px 5px;
            color: white;
            transition: all .2s;
            opacity: 0;
            transform: scale(0);
            cursor: pointer;
            z-index: 4;
        }
        .VPcrop:hover .VPcropspan{
            opacity: 1;
            transform: scale(1);
        }
        .VPcrop:before{
            content: "+";
            position: absolute;
            top: calc(50% - 22.5px);
            left: calc(50% - 11.5px);
            font-size: 40px;
            opacity: 0.3;
            color: #000;
            font-weight: bold;
            z-index: 1;
        }
    </style>
</head>
<body>
<header>
    <div class="header-banner">
    </div>
</header>
<div class="container">
    <div class='row '>
        <div class='col-6 align-self-center'>
            <div class='VPcrop mt-5'>
                <input class='VPcropint' id='image' style="cursor: pointer" onchange="previewFileimg('image')" name='input' type='file' accept="image/x-png, image/gif, image/jpeg"/>
            </div>
            {{--<div class="progress" style="width: 350px">
                <div class="progress-bar progress-bar-striped bg-success progress-bar-animated" role="progressbar" style="width: 25%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
            </div>--}}
        </div>
        <div class="col-6 align-self-center">
            <div class="col-12">
                <div class="row">
                    <div class="col-3">
                        <img src="{{asset('img/casco.png')}}">
                    </div>
                    <div class="col-3">
                        <img id="casco" src="{{asset('img/positivo.png')}}" style="display: none">
                    </div>
                </div>

            </div>
            <div class="col-12 mt-4">
                <div class="row">
                    <div class="col-3">
                        <img src="{{asset('img/humano.png')}}">
                    </div>
                    <div class="col-3 mt-2">
                        <img id="persona" src="{{asset('img/negativo.png')}}" style="display: none">
                    </div>
                </div>
            </div>
        </div>
        <input type="hidden" id="positivo" value="{{asset('img/positivo.png')}}">
        <input type="hidden" id="negativo" value="{{asset('img/negativo.png')}}">
    </div>
</div>
</body>
<script src="{{asset('plugins/jquery-3.4.1.min.js')}}" ></script>
<script src="{{asset('plugins/bootstrap4/js/bootstrap.min.js')}}" ></script>
<script>
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        statusCode : {
            401 : function () {
                window.location = "/";
            }
        }
    });

    function previewFileimg(x){
        var file = document.getElementById(x).files[0];
        var reader  = new FileReader();
        $('#casco').hide();
        $('#persona').hide();
        reader.onloadend = function () {
            VPcropremove = document.getElementById(x).parentNode;
            groupcrop = VPcropremove.parentNode;
            //
            var t='t';
            VPcrimg = document.createElement('img');
            VPcrimg.className ='VPcimg';
            VPcrimg.src = "";
            VPcrimg.id ='VPcrimg'+t;
            VPcropremove.appendChild(VPcrimg);
            VPcrimg.src = reader.result;
            subirImagen();
        }
        if (file) {
            reader.readAsDataURL(file); //reads the data as a URL
        } else {
            PoundNote('Thông Báo','Bạn chưa chọn hình ảnh',1);
        }
    }

    function subirImagen(){
        var file_data = $('#image').prop('files')[0];
        var form_data = new FormData();
        form_data.append('image', file_data);
        $.ajax({
            url: '{{route('upload')}}', // point to server-side controller method
            dataType: 'text', // what to expect back from the server
            cache: false,
            contentType: false,
            processData: false,
            data: form_data,
            type: 'post',
            success: function (response) {
                var respuesta = JSON.parse(response);
                console.log(respuesta);
                procesarRespuesta(respuesta)
            },
            error: function (response) {
                //$('#msg').html(response); // display error response from the server
            }
        });
    }

    function procesarRespuesta(payload) {
        if(payload.casco === 1){
            $('#casco').attr('src',$('#positivo').val());
        }else{
            $('#casco').attr('src',$('#negativo').val());
        }
        if(payload.persona === 1){
            $('#persona').attr('src',$('#positivo').val());
        }else{
            $('#persona').attr('src',$('#negativo').val());
        }
        $('#casco').show();
        $('#persona').show();
    }
</script>
</html>
