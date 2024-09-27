<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Calculador de costos">
    <meta name="author" content="SENA CIDM">
    <meta name="keyword" content="Sistema Calculador de costos">
    <link rel="shortcut icon" href="img/favicon.png">
    <title>H.A.C.E.M - SENA CIDM</title>
    <!-- Icons -->
    <link href="css/simple-line-icons.min.css" rel="stylesheet">
    <link href="css/font-awesome.min.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">

</head>

<body class="app header-fixed sidebar-fixed aside-menu-fixed aside-menu-hidden">

    <div id="app"><!-- /Apertura div app que permite usar vue -->

        <header class="app-header navbar">
        <button class="navbar-toggler mobile-sidebar-toggler d-lg-none mr-auto" type="button">
          <span class="navbar-toggler-icon"></span>
        </button>
        <a class="navbar-brand" href="#"></a>
        <button class="navbar-toggler sidebar-toggler d-md-down-none" type="button">
          <span class="navbar-toggler-icon"></span>
        </button>
        <ul class="nav navbar-nav d-md-down-none">
            <li class="nav-item px-3">
                <a class="nav-link" href="#">
                    @foreach (Auth::user()->empresas as $empresa)
                    <b>{{$empresa['nombre']}}</b>
                @endforeach
                </a>
            </li>
            <li class="nav-item px-3">
                <a class="nav-link" href="#">Escritorio</a>
            </li>
            <li class="nav-item px-3">
                <a class="nav-link" href="#">Configuraciones</a>
            </li>
        </ul>
        <ul class="nav navbar-nav ml-auto">
            <li class="nav-item d-md-down-none">
                <a class="nav-link" href="#" data-toggle="dropdown">
                    <i class="icon-bell"></i>
                    <span class="badge badge-pill badge-danger">5</span>
                </a>
                <div class="dropdown-menu dropdown-menu-right">
                    <div class="dropdown-header text-center">
                        <strong>Notificaciones</strong>
                    </div>
                    <a class="dropdown-item" href="#">
                        <i class="fa fa-envelope-o"></i> Empresa
                        <span class="badge badge-success">
                            @foreach (Auth::user()->empresas as $empresa)
                                {{$empresa['nombre']}}
                            @endforeach
                        </span>
                    </a>
                    <a class="dropdown-item" href="#">
                        <i class="fa fa-tasks"></i> Id Empresa
                        <span class="badge badge-danger">
                            @foreach (Auth::user()->empresas as $empresa)
                                {{$empresa['id']}}
                            @endforeach
                        </span>
                    </a>
                    {{--
                    <a class="dropdown-item" href="#">
                        <i class="fa fa-tasks"></i> Escritorio
                        <span class="badge badge-danger">
                            {{Auth::user()->vistas['escritorio']}}
                        </span>
                    </a>
                    --}}
                    <a class="dropdown-item" href="#">
                        <i class="fa fa-tasks"></i>
                        <span class="badge badge-danger">
                            @foreach (Auth::user()->roles as $rol)
                                {{$rol['rol']}}
                            @endforeach
                        </span>
                    </a>
                </div>
            </li>
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle nav-link" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">
                <!-- <img src="img/avatars/{{Auth::user()->id}}.jpg" class="img-avatar" alt="{{Auth::user()->email}}"> -->
                    <img src="img/avatars/1.jpg" class="img-avatar" alt="{{Auth::user()->email}}">
                    <span class="d-md-down-none">{{Auth::user()->name}}</span>
                </a>
                <div class="dropdown-menu dropdown-menu-right">
                    <div class="dropdown-header text-center">
                        <strong>Cuenta</strong>
                    </div>
                <a class="dropdown-item" @click="menu=98" href="#">
                <i class="fa fa-eye" style="color: #ff671b"></i> Vista personalizada</a>

                <a class="dropdown-item" @click="menu=100" href="#">
                <i class="fa fa-lock" style="color: #ff671b"></i>Cambiar Contraseña</a>

                <a class="dropdown-item" href="{{route('logout')}}"
                onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                <i class="fa fa-sign-out" style="color: #ff671b"></i> Cerrar sesión</a>

                <form id="logout-form" action="{{route('logout')}}" method="post" style="display: none;">
                {{csrf_field()}}
                </form>

            </div>
            </li>
            <li><p>&nbsp;&nbsp;&nbsp;</p></li>
        </ul>
    </header>

    <div class="app-body">

        @foreach (Auth::user()->roles as $rol)

            @if (($rol->id)==1)
                @include('contenido.sidebarsa')
            @elseif (($rol->id)==2)
                @include('contenido.sidebarem')
            @else
                @include('contenido.sidebarvacio')
            @endif

        @endforeach

        <!-- Contenido Principal -->
        @yield('contenido')
        <!-- /Fin del contenido principal -->
    </div>

    </div><!-- /Cierre div app que permite usar vue -->

    <footer class="app-footer">
        <span><a href="#">SENA CIDM</a> &copy; 2020 - 2021</span>
        <span class="ml-auto">Desarrollado por <a href="#">GRUPO SENNOVA</a></span>
    </footer>

    <!-- Bootstrap and necessary plugins -->
    <script src="js/app.js"></script>
    <script src="js/popper.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/Chart.min.js"></script>
    <script src="js/pace.min.js"></script>
    <script src="js/template.js"></script>
    <script src="js/sweetalert2.all.js"></script>

</body>

</html>

<style>
        
    body, html{
        height: 100%;
        margin: 0;
        display: flex;
        flex-direction: column;
        min-height: 100vh;
    }

    footer{
        margin-top: auto;
    }

</style>
