<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>เข้าสู่ระบบ</title>

    <!-- Font Awesome -->
    <link href="{{ asset('assets/fontawesome/css/all.css') }}" rel="stylesheet">
    <!-- App favicon -->
    <link rel="shortcut icon" href="{{ asset('apkclaim/images/logo150.ico') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Edu+VIC+WA+NT+Beginner&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.2/css/all.min.css" rel="stylesheet">
    <link href="{{ asset('assets/fontawesome/css/all.css') }}" rel="stylesheet">

    {{-- <link href="{{ asset('sky16/css/bootstrap.min.css') }}" rel="stylesheet" /> --}}
    {{-- <link href="{{ asset('sky16/css/bootstrap-extended.css') }}" rel="stylesheet" /> --}}
    <link href="{{ asset('assets/css53/bootstrap.min.css') }}" rel="stylesheet">

    <link href="{{ asset('assets/navbar-fixed.css') }}" rel="stylesheet">
</head>

<style>
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
        font-family: 'Edu VIC WA NT Beginner', cursive;
    }

    body {
        width: 100%;
        height: 100vh;
        background: linear-gradient(rgba(0, 0, 0, 0.75), rgba(0, 0, 0, 0.75)),
            url(/pkbackoffice/public/sky16/images/bgPK.jpg)no-repeat 50%;
        /* url(/sky16/images/bgPK.jpg)no-repeat 50%; */
        /* url(/sky16/images/logo.png)no-repeat 50%; */
        background-size: cover;
        background-attachment: fixed;
        display: flex;
        align-items: center;
        justify-content: center;
        min-height: 75rem;
  padding-top: 4.5rem;
    }
    .container-fluid {
        position: relative;
    }
    .form {
        position: relative;
        z-index: 100;
        margin-top: 35%;
        width: 270px;
        height: 300px;
        background-color: rgba(240, 248, 255, 0.158);
        border-radius: 30px;
        backdrop-filter: blur(2px);
        display: flex;
        align-items: center;
        justify-content: center;
        flex-direction: column;
    }
    .logo {
        width: 100px;
        height: 100px;
        background:
            url(/pkbackoffice/public/sky16/images/logo250.png)no-repeat 50%;
        /* url(/sky16/images/logo250.png)no-repeat 25%; */
        background-size: cover;
        /* background-attachment: fixed; */
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .h1 {
        color: rgb(255, 255, 255);
        font-weight: 500;
        margin-bottom: 20px;
        font-size: 50px;
        margin-top: 20px;
    }
    .username {
        width: 160px;
        background: none;
        outline: none;
        border: none;
        margin: 10px 0px;
        border-bottom: rgba(240, 248, 255, 0.418) 1px solid;
        padding: 5px;
        color: aliceblue;
        font-size: 18px;
        transition: 0.2s ease-in-out;
        margin-top: 30px;
    }
    .password {
        width: 160px;
        background: none;
        outline: none;
        border: none;
        margin: 5px 0px;
        border-bottom: rgba(240, 248, 255, 0.418) 1px solid;
        padding: 5px;
        color: aliceblue;
        font-size: 18px;
        transition: 0.2s ease-in-out;
    }
    ::placeholder {
        color: rgba(255, 255, 255, 0.582);
    }
    ::focus {
        border-bottom: aliceblue 1px solid;
    }
    .fa-solid {
        transition: 0.2s ease-in-out;
        color: rgba(240, 248, 255, 0.59);
        margin-right: 10px;
        /* margin-top: 50px; */
    }
    .btn {
        width: 110px;
        height: 30px;
        margin-top: 10px;
        font-weight: 500;
        color: aliceblue;
        outline: none;
        border: none;
        background: rgba(240, 248, 255, 0.2);
        backdrop-filter: blur(15px);
        border-radius: 20px;
        font-size: 15px;
        transition: 0.2s;
    }
    .footer {
        width: 400px;
        height: 40px;
        margin-top: 100px;
        font-weight: 500;
        color: aliceblue;
        outline: none;
        border: none;
        background: rgba(240, 248, 255, 0.2);
        backdrop-filter: blur(15px);
        border-radius: 20px;
        font-size: 20px;
        transition: 0.2s;
    }
    &::hover {
        background: aliceblue;
        color: gray;
        font-weight: 500;
    }
    .circle1 {
        position: absolute;
        width: 290px;
        height: 290px;
        background: rgba(240, 248, 255, 0.1);
        border-radius: 50%;
        top: 60%;
        left: 70%;
        z-index: -1;
        animation: float 2s 0.5s ease-in-out infinite;
    }
    .circle2 {
        position: absolute;
        width: 170px;
        height: 170px;
        background: rgba(240, 248, 255, 0.1);
        border-radius: 50%;
        top: -30%;
        right: 35%;
        z-index: -1;
        animation: float 2s ease-in-out infinite;
    }
    .circle3 {
        position: absolute;
        width: 220px;
        height: 220px;
        background: rgba(240, 248, 255, 0.1);
        border-radius: 50%;
        top: 50%;
        right: 50%;
        z-index: -1;
        animation: float 2s 0.7s ease-in-out infinite;
    }
    .circle4 {
        position: absolute;
        width: 220px;
        height: 220px;
        background: rgba(240, 248, 255, 0.1);
        border-radius: 50%;
        top: 20%;
        left: 2%;
        z-index: -1;
        animation: float 2s 0.5s ease-in-out infinite;
    }
    .circle5 {
        position: absolute;
        width: 320px;
        height: 320px;
        background: rgba(240, 248, 255, 0.1);
        border-radius: 50%;
        top: 20%;
        left: 28%;
        z-index: -1;
        animation: float 2s 0.5s ease-in-out infinite;
    }
    .po {
        position: relative;
        z-index: 100;
        margin-top: 7%;
        width: 800px;
        height: auto;
        background-color: rgba(240, 248, 255, 0.158);
        border-radius: 20px;
        backdrop-filter: blur(1px);
        /* display: flex; */
        align-items: right;
        justify-content: right;
        flex-direction: column;
    }
    .popic {
        position: absolute;
        width: 300px;
        height: 300px;
        background:
            url(/pkbackoffice/public/images/ponews.png)no-repeat 50%;
        /* url(/sky16/images/logo250.png)no-repeat 25%; */
        background-size: cover;
        top: -35%;
        right: 7%;
        z-index: -1;
        animation: float 2s ease-in-out infinite;
    }


    @keyframes float {
        0% {
            transform: translateY(0);
        }

        50% {
            transform: translateY(-20px);
        }

        100% {
            transform: translateY(0);
        }
    }
</style>

<body>
    <?php
    $datadetail = DB::connection('mysql')->select('select * from orginfo where orginfo_id = 1');
    ?>
    <nav class="navbar navbar-expand-md navbar-dark fixed-top bg-dark">
        <div class="container-fluid">
            @foreach ($datadetail as $item)
            {{-- <label for="" class="mt-2" style="color: white">2023 © {{$item->orginfo_name}}</label> --}}
            <a class="navbar-brand" href="#">{{$item->orginfo_name}}</a>
            @endforeach

          <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
          </button>
          <div class="collapse navbar-collapse" id="navbarCollapse">
            <ul class="navbar-nav me-auto mb-2 mb-md-0">
              <li class="nav-item">
                <a class="nav-link active" aria-current="page" href="#">Home</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="#">Link</a>
              </li>

            </ul>
            {{-- <form class="d-flex" role="search">
              <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search">
              <button class="btn btn-outline-success" type="submit">Search</button>
            </form> --}}
          </div>
        </div>
      </nav>
    <div class="container-fluid">

        <div class="circle1"> </div>
            <div class="circle2"> </div>
            <div class="circle3"> </div>
            <div class="circle4"> </div>
            <div class="circle5"> </div>
            <div class="popic"> </div>
        <div class="row">
            <div class="col-md-1"></div>
            <div class="col-md-4">
                <form method="POST" action="{{ route('login') }}" class="form">
                    @csrf
                    <div class="logo"> </div>
                    <div>
                        <i class="fa-solid fa-user"></i>
                        <input type="text" placeholder="Username" name="username" class="username" required>
                        @error('username')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div>
                        <i class="fa-solid fa-key"></i>
                        <input type="password" placeholder="Password" name="password" class="password">
                        @error('password')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <button type="submit" class="btn btn-info">Login</button>
                </form>
            </div>
            <div class="col-md-4">


            </div>
            <div class="col-md-2">

            </div>
            <div class="col-md-1">
        </div>
        <div class="row ">


            <div class="col-md-4 text-center">
                @foreach ($datadetail as $item)
                <label for="" class="mt-2" style="color: white">2023 © {{$item->orginfo_name}}</label>
                @endforeach
            </div>
            <div class="col-md-8"></div>
        </div>

        <div class="row text-center">
            <div class="col-md-1"></div>
            <div class="col-md-2 ">
                <label for="" class=" ms-2 mt-2" style="color: white"> By ทีมพัฒนา PK-HOS</label>
            </div>
            <div class="col-md-9"></div>
        </div>

        <div class="row text-start">
            <div class="col-md-1 ">

            </div>
            <div class="col-md-4 ">   &nbsp;&nbsp;&nbsp;
                <a href="{{url('report_dashboard')}}" class="btn btn-success btn-sm ms-2" target="_blank">Report </a>
                <a href="{{url('sit_auto')}}" class="btn btn-warning btn-sm ms-2" target="_blank">Auto Systems</a>
            </div>
            <div class="col-md-7 ">

            </div>
        </div>

    </div>

            {{-- <svg xmlns="http://www.w3.org/2000/svg" style="display: none;">
            <symbol id="check2" viewBox="0 0 16 16">
                <path d="M13.854 3.646a.5.5 0 0 1 0 .708l-7 7a.5.5 0 0 1-.708 0l-3.5-3.5a.5.5 0 1 1 .708-.708L6.5 10.293l6.646-6.647a.5.5 0 0 1 .708 0z"/>
            </symbol>
            <symbol id="circle-half" viewBox="0 0 16 16">
                <path d="M8 15A7 7 0 1 0 8 1v14zm0 1A8 8 0 1 1 8 0a8 8 0 0 1 0 16z"/>
            </symbol>
            <symbol id="moon-stars-fill" viewBox="0 0 16 16">
                <path d="M6 .278a.768.768 0 0 1 .08.858 7.208 7.208 0 0 0-.878 3.46c0 4.021 3.278 7.277 7.318 7.277.527 0 1.04-.055 1.533-.16a.787.787 0 0 1 .81.316.733.733 0 0 1-.031.893A8.349 8.349 0 0 1 8.344 16C3.734 16 0 12.286 0 7.71 0 4.266 2.114 1.312 5.124.06A.752.752 0 0 1 6 .278z"/>
                <path d="M10.794 3.148a.217.217 0 0 1 .412 0l.387 1.162c.173.518.579.924 1.097 1.097l1.162.387a.217.217 0 0 1 0 .412l-1.162.387a1.734 1.734 0 0 0-1.097 1.097l-.387 1.162a.217.217 0 0 1-.412 0l-.387-1.162A1.734 1.734 0 0 0 9.31 6.593l-1.162-.387a.217.217 0 0 1 0-.412l1.162-.387a1.734 1.734 0 0 0 1.097-1.097l.387-1.162zM13.863.099a.145.145 0 0 1 .274 0l.258.774c.115.346.386.617.732.732l.774.258a.145.145 0 0 1 0 .274l-.774.258a1.156 1.156 0 0 0-.732.732l-.258.774a.145.145 0 0 1-.274 0l-.258-.774a1.156 1.156 0 0 0-.732-.732l-.774-.258a.145.145 0 0 1 0-.274l.774-.258c.346-.115.617-.386.732-.732L13.863.1z"/>
            </symbol>
            <symbol id="sun-fill" viewBox="0 0 16 16">
                <path d="M8 12a4 4 0 1 0 0-8 4 4 0 0 0 0 8zM8 0a.5.5 0 0 1 .5.5v2a.5.5 0 0 1-1 0v-2A.5.5 0 0 1 8 0zm0 13a.5.5 0 0 1 .5.5v2a.5.5 0 0 1-1 0v-2A.5.5 0 0 1 8 13zm8-5a.5.5 0 0 1-.5.5h-2a.5.5 0 0 1 0-1h2a.5.5 0 0 1 .5.5zM3 8a.5.5 0 0 1-.5.5h-2a.5.5 0 0 1 0-1h2A.5.5 0 0 1 3 8zm10.657-5.657a.5.5 0 0 1 0 .707l-1.414 1.415a.5.5 0 1 1-.707-.708l1.414-1.414a.5.5 0 0 1 .707 0zm-9.193 9.193a.5.5 0 0 1 0 .707L3.05 13.657a.5.5 0 0 1-.707-.707l1.414-1.414a.5.5 0 0 1 .707 0zm9.193 2.121a.5.5 0 0 1-.707 0l-1.414-1.414a.5.5 0 0 1 .707-.707l1.414 1.414a.5.5 0 0 1 0 .707zM4.464 4.465a.5.5 0 0 1-.707 0L2.343 3.05a.5.5 0 1 1 .707-.707l1.414 1.414a.5.5 0 0 1 0 .708z"/>
            </symbol>
            </svg>

            <div class="dropdown position-fixed bottom-0 end-0 mb-3 me-3 bd-mode-toggle">
            <button class="btn btn-bd-primary py-2 dropdown-toggle d-flex align-items-center"
                    id="bd-theme"
                    type="button"
                    aria-expanded="false"
                    data-bs-toggle="dropdown"
                    aria-label="Toggle theme (auto)">
                <svg class="bi my-1 theme-icon-active" width="1em" height="1em"><use href="#circle-half"></use></svg>
                <span class="visually-hidden" id="bd-theme-text">Toggle theme</span>
            </button>
            <ul class="dropdown-menu dropdown-menu-end shadow" aria-labelledby="bd-theme-text">
                <li>
                <button type="button" class="dropdown-item d-flex align-items-center" data-bs-theme-value="light" aria-pressed="false">
                    <svg class="bi me-2 opacity-50 theme-icon" width="1em" height="1em"><use href="#sun-fill"></use></svg>
                    Light
                    <svg class="bi ms-auto d-none" width="1em" height="1em"><use href="#check2"></use></svg>
                </button>
                </li>
                <li>
                <button type="button" class="dropdown-item d-flex align-items-center" data-bs-theme-value="dark" aria-pressed="false">
                    <svg class="bi me-2 opacity-50 theme-icon" width="1em" height="1em"><use href="#moon-stars-fill"></use></svg>
                    Dark
                    <svg class="bi ms-auto d-none" width="1em" height="1em"><use href="#check2"></use></svg>
                </button>
                </li>
                <li>
                <button type="button" class="dropdown-item d-flex align-items-center active" data-bs-theme-value="auto" aria-pressed="true">
                    <svg class="bi me-2 opacity-50 theme-icon" width="1em" height="1em"><use href="#circle-half"></use></svg>
                    Auto
                    <svg class="bi ms-auto d-none" width="1em" height="1em"><use href="#check2"></use></svg>
                </button>
                </li>
            </ul>
            </div>


        <nav class="navbar navbar-expand-md navbar-dark fixed-top bg-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">Fixed navbar</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarCollapse">
            <ul class="navbar-nav me-auto mb-2 mb-md-0">
                <li class="nav-item">
                <a class="nav-link active" aria-current="page" href="#">Home</a>
                </li>
                <li class="nav-item">
                <a class="nav-link" href="#">Link</a>
                </li>
                <li class="nav-item">
                <a class="nav-link disabled">Disabled</a>
                </li>
            </ul>
            <form class="d-flex" role="search">
                <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search">
                <button class="btn btn-outline-success" type="submit">Search</button>
            </form>
            </div>
        </div>
        </nav>

        <main class="container">
        <div class="bg-body-tertiary p-5 rounded">
            <h1>Navbar example</h1>
            <p class="lead">This example is a quick exercise to illustrate how fixed to top navbar works. As you scroll, it will remain fixed to the top of your browser’s viewport.</p>
            <a class="btn btn-lg btn-primary" href="../components/navbar/" role="button">View navbar docs &raquo;</a>
        </div>
        </main> --}}
    <script src="{{ asset('assets/js53/bootstrap.bundle.min.js') }}"></script>
</body>

</html>
