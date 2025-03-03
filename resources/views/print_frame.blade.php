<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="{{ asset('css/idcard_style.css') }}">
    <title>Document</title>
    <style>
        body {
            width: 100%;
            margin: 0%;
            padding: 0%;

        }
        .print{
            width: 100% !important;
        }

        .it-parent {
            width: 100%;
            position: relative;
            background-color: #fff;
            overflow: hidden;
            text-align: center;
            font-size: 40px;
            color: #000;
        }
    </style>
</head>

<body>
    <div class="print" id="print">
        <div class="it-parent" id="it-parent">
            <div class="bg-template" id="bg-template">
                <img class="it-icon" alt="" src="{{ asset('assets/img/template_idcard_staffup.png') }}">
            </div>
            <div class="photo-parent">
                <div class="preview" id="preview">
                    <img class="photo-icon" alt="" src="{{ asset('assets/img/picture_icon.png') }}">
                </div>
                <div class="fullname-parent">
                    <b class="fullname" id="fullname">FULLNAME</b>
                    <div class="department" id="department">DEPARTMENT</div>
                    <div class="joblevel" id="joblevel">LEVEL</div>
                    <div class="nikid" id="nikid">NIK ID</div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>
