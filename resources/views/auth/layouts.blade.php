<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Teacher</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta2/css/all.min.css"
          integrity="sha512-YWzhKL2whUzgiheMoBFwW8CKV4qpHQAEuvilg9FAn5VJUDwKZZxkJNuGM4XkWuk94WCrrwslk8yWNGmY1EduTA=="
          crossorigin="anonymous" referrerpolicy="no-referrer"/>

    <style>
        .navbar-scroll .nav-link,
        .navbar-scroll .navbar-toggler-icon,
        .navbar-scroll .navbar-brand {
            color: #262626;
        }

        .navbar-scroll {
            background-color: #FFC017;
        }

        .navbar-scrolled .nav-link,
        .navbar-scrolled .navbar-toggler-icon,
        .navbar-scroll .navbar-brand {
            color: #262626;
        }

        .navbar-scrolled {
            background-color: #fff;
        }

        .navbar-brand {
            font-size: unset;
            height: 3.5rem;
        }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg bg-light">
    <div class="container">
        <a class="navbar-brand" href="{{ URL('/') }}">Пользовательский вход</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown"
                aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNavDropdown">
            <ul class="navbar-nav ms-auto">
                @guest
                    <li class="nav-item">
                        <a class="nav-link {{ (request()->is('login')) ? 'active' : '' }}" href="{{ route('login') }}">Login</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ (request()->is('register')) ? 'active' : '' }}"
                           href="{{ route('register') }}">Register</a>
                    </li>
                @else
                    <li class="nav-item">
                        <a class="nav-link active d-flex flex-column text-center" aria-current="page"
                           href="{{route('groups.index')}}"><i class="fas fa-group fa-lg"></i> <br>
                            <span class="small">Группы</span></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link d-flex flex-column text-center" aria-current="page"
                           href="{{route('schedule.index')}}">
                            <i class="fas fa-calendar fa-lg"></i> <br><span class="small">Расписание</span></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link d-flex flex-column text-center" aria-current="page"
                           href="{{route('homeworks.index')}}">
                            <i class="fas fa-tasks fa-lg"></i> <br><span class="small">Задание</span></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link d-flex flex-column text-center" aria-current="page"
                           href="{{route('message.index')}}">
                            <i class="fas fa-comment-dots fa-lg"></i> <br><span class="small">СМС</span></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link d-flex flex-column text-center" aria-current="page"
                           href="{{route('test.index')}}">
                            <i class="fas fa-pencil-alt fa-lg"></i> <br><span class="small">Тесты</span></a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown"
                           aria-expanded="false">
                            {{ Auth::user()->name }}
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="{{ route('logout') }}"
                                   onclick="event.preventDefault();
                            document.getElementById('logout-form').submit();"
                                >Logout</a>
                                <form id="logout-form" action="{{ route('logout') }}" method="POST">
                                    @csrf
                                </form>
                            </li>
                        </ul>
                    </li>
                @endguest
            </ul>
        </div>
    </div>
</nav>

<div class="container">
    @include('includes.partials.messages')
    @yield('content')
</div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4"
        crossorigin="anonymous"></script>
</body>
</html>
