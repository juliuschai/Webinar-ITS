<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Webinar ITS</title>

    <!-- Font -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Nunito+Sans:300,400,400i,600,700&display=swap" rel="stylesheet" >

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">

    <!-- Bootstrap -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.1/css/bootstrap.min.css" integrity="sha384-VCmXjywReHh4PwowAiWNagnWcLhlEJLA5buUprzK8rxFgeH0kww/aWY76TfkUoSX" crossorigin="anonymous">

</head>
<body>
    <div class="email">
        <div style="background-color:#f2f3f5;padding:20px">
        <table cellpadding="0" cellspacing="0" width="100%">
            <tr>
                <td bgcolor="#ffffff" style="border-top: 4px solid #013880; border-bottom:1px solid #f2f3f5;">
                    <h1>WEBINAR</h1>
                </td>
            </tr>
            <tr>
                <td bgcolor="#ffffff" style="padding: 40px 30px 40px 30px;"> 
                    <table cellpadding="0" cellspacing="0" width="100%">
                        <tr>
                            <td>
                                <center>
                                    <p class="col-md-5 col-sm-5" style="font-size: 14px; text-align: center; margin-bottom: 10px;">Selamat ! </br> Webinar anda dengan Topik {{ $datas[0]['topic'] }} sudah disetujui.</p>
                                </center>
                            </td>
                        </tr>
                        @foreach ($datas as $data)
                        <tr>
                            <td style="font-size: 14px; border-bottom:1px solid #f2f3f5;">
                                <center>
                                    <h3>{{ $data['index'] }}</h3>
                                    <p class="col-md-5 col-sm-5" style="font-size: 14px; text-align: center; margin-bottom: 10px;">Waktu: {{ $data['start_time'] }} WIB</p>
                                    <p class="col-md-5 col-sm-5" style="font-size: 14px; text-align: center; margin-bottom: 10px;">Link Webinar : <a href="{{ $data['join_url'] }}">{{ $data['join_url'] }}</a> </p>
                                    <p class="col-md-5 col-sm-5" style="font-size: 14px; text-align: center; margin-bottom: 10px;">Webinar ID: {{ $data['webinar_id'] }}</p>
                                    <p class="col-md-5 col-sm-5" style="font-size: 14px; text-align: center; margin-bottom: 10px;">Password: {{ $data['password'] }}</p>
                                </center>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <center>
                                    <p class="col-md-5 col-sm-5" style="font-size: 14px; text-align: center; margin-bottom: 10px;">Mohon berhati-hati saat mengubah setting di Zoom webinar acara anda!<br>Pastikan bahwa Webinar yang anda ubah bukan webinar orang lain!</p>
                                </center>
                            </td>
                        </tr>
                        @endforeach
                    </table>
                </td>
            </tr>

        </table>
        </div>
    </div>
</body>
</html>
