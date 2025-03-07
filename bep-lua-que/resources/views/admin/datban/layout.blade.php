<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title')</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/5.0.0-alpha1/css/bootstrap.min.css"
        integrity="sha384-r4NyP46KrjDleawBgD5tp8Y7UzmLA05oM1iAEQ17CSuDqnUK2+k9luXQOfXJCJ4I" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"
        integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous">
    </script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/5.0.0-alpha1/js/bootstrap.min.js"
        integrity="sha384-oesi62hOLfzrys4LxRF63OJCXdXDipiYWBnvTl9Y9/TRlw5xlKIEHpNyvvDShgf/" crossorigin="anonymous">
    </script>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Thêm Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://js.pusher.com/7.2/pusher.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/moment@2.29.1/moment.min.js"></script>







</head>

<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Header -->
            <header class="col-12 bg-primary text-white text-center p-3">
                <h1>Lễ tân</h1>
            </header>
        </div>
        <div class="row">
            <!-- Sidebar -->
            <nav class="col-2 bg-light p-3">
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a href="{{ route('dat-ban.index') }}" class="btn btn-primary">Danh sách bàn đặt</a>
                    </li>
                    <br>
                    <li class="nav-item">
                        <a href="{{ route('datban.danhsach') }}" class="btn btn-primary">Danh sách đặt bàn</a>
                    </li>
                </ul>
            </nav>
            <!-- Main Content -->
            <main class="col-10 p-3">
                @yield('content')
            </main>
        </div>
    </div>

    <script src="https://js.pusher.com/7.0/pusher.min.js"></script>



</body>

</html>
