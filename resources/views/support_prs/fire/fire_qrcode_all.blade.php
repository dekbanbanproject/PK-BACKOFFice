<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>QrCode All</title>

    <link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css' />
    <link href='https://fonts.googleapis.com/css?family=Kanit&subset=thai,latin' rel='stylesheet' type='text/css'>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
</head>
<style>
    body {
        font-family: 'Kanit', sans-serif;
        font-size: 14px;
    }

    /* border-radius: 2em 2em 2em 2em;
     box-shadow: 0 0 10px rgb(250, 128, 124);
     border:solid 1px #80acfd; */
</style>
<?php
use SimpleSoftwareIO\QrCode\Facades\QrCode;
?>

<body>

    <div class="container-fluid text-center">

        <div class="grid text-center">
            <?php $i = 1; ?>
            @foreach ($dataprint as $item)
                <?php $i++; ?>

                <div class="g-col-4">
                    <div class="card mt-4"
                        style="max-width:10rem;border-color:rgb(150, 236, 20);background-color:rgb(250, 241, 210);border-radius: 2em 2em 2em 2em">
                        <div class="card-body">
                            <?php $qrcode = base64_encode(
                                QrCode::format('svg')
                                    ->size(90)
                                    ->generate($item->fire_id),
                            ); ?>
                            {{-- {!!QrCode::size(112)->generate(" $item->fire_id ")!!}   --}}
                            <img src="data:image/png;base64, {!! $qrcode !!}">
                            <hr style="color:rgb(6, 172, 108)">
                            <p style="font-size: 17px;color:rgb(6, 172, 108)"> รหัส {{ $item->fire_num }} <br>
                                แสกนตรวจสอบ<br>
                                สำหรับเจ้าหน้าที่</p>
                        </div>
                    </div>
                </div>
            @endforeach
     
        </div>

    </div>


    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js"
        integrity="sha384-7+zCNj/IqJ95wo16oMtfsKbZ9ccEh31eOz1HGyDuCQ6wgnyJNSYdrPa03rtR1zdB" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js"
        integrity="sha384-QJHtvGhmr9XOIpI6YVutG+2QOK9T+ZnN4kzFN1RtK3zEFEIsxhlmWl5/YESvpZ13" crossorigin="anonymous">
    </script>


</body>

</html>
