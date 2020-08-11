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
                                <p style="margin-bottom: 10px;">Webinar anda akan dimulai, silahkan login ke akun webinar :</p>
                            </td>
                        </tr>
                        <tr>
                            <td style="border-bottom:1px solid #f2f3f5;">
                                <p>Email : {{ $email }}</p>
                                <p style="margin-bottom: 20px;">Password : {{ $password }}</p>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <p style="margin-bottom: 10px;">Mohon berhati-hati saat mengubah setting Zoom Webinar acara anda!<br>Pastikan bahwa Webinar yang anda ubah bukan Webinar acara lain!</p>
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

