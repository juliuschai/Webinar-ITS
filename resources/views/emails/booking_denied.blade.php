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
                                    <p class="col-md-5 col-sm-5" style="font-size: 14px; text-align: center; margin-bottom: 10px;">Maaf, Webinar anda dengan Topik {{ $topic }} terdapat masalah dan belum disetujui. Silahkan mengecheck di aplikasi booking webinar.</p>
                                </center>
                            </td>
                        </tr>
                        <tr>
                            <td style="font-size: 14px; border-bottom:1px solid #f2f3f5;">
                                <center>
                                    <p class="col-md-5 col-sm-5" style="font-size: 14px; text-align: center; margin-bottom: 10px;">Silahkan edit booking: <a href="https://webinar-book.its.ac.id/booking/edit/{{ $id }}">Link Edit Booking</a></p>
                                </center>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>

        </table>
        </div>
    </div>
</body>
</html>
