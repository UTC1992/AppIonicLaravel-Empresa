<!DOCTYPE html>
<!--
This is a starter template page. Use this page to start your new project from
scratch. This page gets rid of all links and provides the needed markup only.
-->
<html>
    <head>
      <meta charset="utf-8">
      <meta http-equiv="X-UA-Compatible" content="IE=edge">
      <title>JMC</title>
      <!-- Tell the browser to be responsive to screen width -->
      <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
      <link rel="stylesheet" href="{{ asset('libreria_dash/bootstrap/css/bootstrap.min.css')}} ">
      <!-- Font Awesome -->
      <link rel="stylesheet" href=" {{ asset('libreria_dash/css/font-awesome.min.css')}} ">
      <!-- Ionicons -->
      <link rel="stylesheet" href=" {{ asset('libreria_dash/css/ionicons.min.css') }} ">
      <!-- Theme style -->
      <link rel="stylesheet" href=" {{ asset('libreria_dash/css/AdminLTE.min.css') }} ">
      <!-- AdminLTE Skins. We have chosen the skin-blue for this starter
            page. However, you can choose any other skin. Make sure you
            apply the skin class to the body tag so the changes take effect. -->
      <link rel="stylesheet" href=" {{ asset('libreria_dash/css/skin-blue.min.css') }} ">
      <!-- DataTables -->
      <link rel="stylesheet" href="{{ asset('libreria_dash/datatables.net-bs/css/dataTables.bootstrap.min.css') }}">

      <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
      <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
      <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
      <![endif]-->

      <!-- Google Font -->
      <link rel="stylesheet"
            href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
      
    </head>
<!--
BODY TAG OPTIONS:
=================
Apply one or more of the following classes to get the
desired effect
|---------------------------------------------------------|
| SKINS         | skin-blue                               |
|               | skin-black                              |
|               | skin-purple                             |
|               | skin-yellow                             |
|               | skin-red                                |
|               | skin-green                              |
|---------------------------------------------------------|
|LAYOUT OPTIONS | fixed                                   |
|               | layout-boxed                            |
|               | layout-top-nav                          |
|               | sidebar-collapse                        |
|               | sidebar-mini                            |
|---------------------------------------------------------|
-->
<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">

  <!-- Main Header -->
  <header class="main-header" >

    <!-- Logo -->
    <a href="/admin" class="logo" >
      <!-- mini logo for sidebar mini 50x50 pixels -->
      <span class="logo-mini"><b>JMC</b></span>
      <!-- logo for regular state and mobile devices -->
      <span class="logo-lg"><b>JMC</b> Developers</span>
    </a>

    <!-- Header Navbar -->
    <nav class="navbar navbar-static-top" role="navigation">
      <!-- Sidebar toggle button-->
      <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
        <span class="sr-only">Toggle navigation</span>
      </a>
      <!-- Navbar Right Menu -->
      <div class="navbar-custom-menu">
        <ul class="nav navbar-nav">
          <!-- /inicio-menu-mensajes

            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              <i class="fa fa-envelope-o"></i>
              <span class="label label-success">4</span>
            </a>
            <ul class="dropdown-menu">
              <li class="header">You have 4 messages</li>
              <li>
                <ul class="menu">
                  <li>
                    <a href="#">
                      <div class="pull-left">

                        <img src="libreria_dash/dist/img/logoPNG.png" class="img-circle" alt="User Image">
                      </div>

                      <h4>
                        Support Team
                        <small><i class="fa fa-clock-o"></i> 5 mins</small>
                      </h4>

                      <p>Why not buy a new awesome theme?</p>
                    </a>
                  </li>

                </ul>

              </li>
              <li class="footer"><a href="#">See All Messages</a></li>
            </ul>
          </li>
          /fin-menu-mensajes -->

          <!-- Notifications Menu -->
          <li class="dropdown notifications-menu">
            <!-- Menu toggle button -->
          <!--
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              <i class="fa fa-bell-o"></i>
              <span class="label label-warning">10</span>
            </a>
            -->
            <ul class="dropdown-menu">
              <li class="header">Tu tienes 10 notificaciones</li>
              <li>
                <!-- Inner Menu: contains the notifications -->
                <ul class="menu">
                  <li><!-- start notification -->
                    <a href="#">
                      <i class="fa fa-users text-aqua"></i> 5 noticias nuevas de hoy
                    </a>
                  </li>
                  <!-- end notification -->
                </ul>
              </li>
              <li class="footer"><a href="#">Ver más</a></li>
            </ul>
          
          </li>

          <!-- inicio Menu bandera

            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              <i class="fa fa-flag-o"></i>
              <span class="label label-danger">9</span>
            </a>
            <ul class="dropdown-menu">
              <li class="header">You have 9 tasks</li>
              <li>

                <ul class="menu">
                  <li>
                    <a href="#">

                      <h3>
                        Design some buttons
                        <small class="pull-right">20%</small>
                      </h3>

                      <div class="progress xs">

                        <div class="progress-bar progress-bar-aqua" style="width: 20%" role="progressbar"
                             aria-valuenow="20" aria-valuemin="0" aria-valuemax="100">
                          <span class="sr-only">20% Complete</span>
                        </div>
                      </div>
                    </a>
                  </li>

                </ul>
              </li>
              <li class="footer">
                <a href="#">View all tasks</a>
              </li>
            </ul>
          </li>
          <!-- fin Menu bandera -->

          <li class="dropdown user user-menu">
            <!-- Menu Toggle Button -->
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              <!-- The user image in the navbar-->
              <img src=" {{ asset('libreria_dash/dist/img/logoPNG.png')}} " class="user-image" alt="User Image">
              <!-- hidden-xs hides the username on small devices so only the image appears. -->
              <span class="hidden-xs">{{ Auth::user()->nombres }} {{ Auth::user()->apellidos }}</span>
            </a>
            <ul class="dropdown-menu">
              <!-- The user image in the menu -->
              <!--<li class="user-header">
                <img src=" {{ asset('libreria_dash/dist/img/logoPNG.png')}} " class="img-circle" alt="User Image">

                <p style="font-size: 12pt;">
                  {{ Auth::user()->nombres }} {{ Auth::user()->apellidos }}
                </p>
              </li>
            -->
              <!-- Menu Body -->
              <!--
              <li class="user-body">
                <div class="row">
                  <div class="col-xs-4 text-center">
                    <a href="#">Clientes</a>
                  </div>
                  <div class="col-xs-4 text-center">
                    <a href="#">Ventas</a>
                  </div>
                  <div class="col-xs-4 text-center">
                    <a href="#">Equipo</a>
                  </div>
                </div>
              </li>-->
              <!-- Menu Footer-->
              <li class="user-footer">
                <div class="pull-left">
                  <a href="{{ route('admin.perfil') }}" class="btn btn-default btn-flat">Perfil</a>
                </div>
                <div class="pull-right">
                  <a class="btn btn-default btn-flat" href="{{ route('admin.logout') }}">
                      {{ __('Salir') }}
                  </a>
                  <!--<a class="btn btn-default btn-flat" href="{{ route('admin.logout') }}"
                     onclick="event.preventDefault();
                                   document.getElementById('logout-form').submit();">
                      {{ __('Salir') }}
                  </a>

                  <form id="logout-form" action="{{ route('admin.logout') }}" method="post" style="display: none;">
                      @csrf
                  </form>
                -->
                </div>
              </li>
            </ul>
          </li>
          <!-- Control Sidebar Toggle Button -->
          <!--<li>
            <a href="#" data-toggle="control-sidebar"><i class="fa fa-gears"></i></a>
          </li>
        -->
        </ul>
      </div>
    </nav>
  </header>
  <!-- Left side column. contains the logo and sidebar -->
  <aside class="main-sidebar">

    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">

      <!-- Sidebar user panel (optional) -->
      <div class="user-panel">
        <div class="pull-left image">
          <img src=" {{ asset('libreria_dash/dist/img/logoPNG.png')}} " style="height: 36px; width: 36px;" class="img-circle" alt="User Image">
        </div>
        <div class="pull-left info">
          <p>{{ Auth::user()->nombres }}</p>
          <!-- Status -->
          <a href="#" ><i class="fa fa-circle text-success"></i> En línea</a>
        </div>
      </div>

      <!-- search form (Optional) -->
      <form action="#" method="get" class="sidebar-form">
        <div class="input-group">
          <input type="text" name="q" class="form-control" placeholder="Buscar...">
          <span class="input-group-btn">
              <button type="submit" name="search" id="search-btn" class="btn btn-flat"><i class="fa fa-search"></i>
              </button>
            </span>
        </div>
      </form>
      <!-- /.search form -->

      <!-- Sidebar Menu -->
      <ul class="sidebar-menu" data-widget="tree">
        <center><li class="header" style="color: #fff;">Menú Principal</li></center>
        <!-- Optionally, you can add icons to the links -->
        <li class="active"><a href="/admin"><i class="glyphicon glyphicon-home"></i> <span>Inicio</span></a></li>
        <li><a href="/modulo"><i class="glyphicon glyphicon-folder-open"></i> <span>Módulos</span></a></li>
        <li><a href="/empresa"><i class="glyphicon glyphicon-folder-open"></i> <span>Empresas</span></a></li>
        <li><a href="/subscripcion"><i class="glyphicon glyphicon-folder-open"></i> <span>Subscripciones</span></a></li>
        <li><a href="/user"><i class="glyphicon glyphicon-folder-open"></i> <span>Usuarios</span></a></li>
        <!--<li class="treeview">
          <a href="#"><i class="glyphicon glyphicon-lock"></i> <span>Empresas</span>
            <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
              </span>
          </a>
          <ul class="treeview-menu">
            <li><a href="#">Elepco</a></li>
            <li><a href="#">Baños</a></li>
          </ul>
        </li>-->
      </ul>
      <!-- /.sidebar-menu -->
    </section>
    <!-- /.sidebar -->
  </aside>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">


    <!-- Main content -->
    <main class="content container-fluid">

        @yield('content')

    </main>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

  <!-- Main Footer -->
  <footer class="main-footer">
    <!-- To the right -->
    <div class="pull-right hidden-xs">
       <a type="button" class="btn-floating btn-lg btn-fb" target="_blank"  href="https://www.facebook.com/TecnoSolutionsCorp/"><i class="fa fa-facebook" style="color: #365899;"></i></a>
      <!--Twitter-->
      <a type="button" class="btn-floating btn-lg btn-tw" href=""><i class="fa fa-twitter" style="color: #1c94e0;"></i></a>
      <!--Instagram-->
      <a type="button" class="btn-floating btn-lg btn-ins" href=""><i class="fa fa-instagram" style="color: #000;"></i></a>
      <!--Youtube-->
      <a type="button" class="btn-floating btn-lg btn-yt" href=""><i class="fa fa-youtube" style="color: #FF0000;"></i></a>
      <!--Github-->
      <a type="button" class="btn-floating btn-lg btn-git" href=""><i class="fa fa-github" style="color: black;"></i></a>


    </div>
    <!-- Default to the left -->
    <strong>Copyright &copy; 2018 <a target="_blank" href="#">JMC Developers</a>.</strong> Todos los derechos reservados.
  </footer>

  <!-- Control Sidebar -->
  <aside class="control-sidebar control-sidebar-dark">
    <!-- Create the tabs -->
    <ul class="nav nav-tabs nav-justified control-sidebar-tabs">
      <li class="active"><a href="#control-sidebar-home-tab" data-toggle="tab"><i class="fa fa-home"></i></a></li>
      <li><a href="#control-sidebar-settings-tab" data-toggle="tab"><i class="fa fa-gears"></i></a></li>
    </ul>
    <!-- Tab panes -->
    <div class="tab-content">
      <!-- Home tab content -->
      <div class="tab-pane active" id="control-sidebar-home-tab">
        <h3 class="control-sidebar-heading">Actividades recientes</h3>
        <ul class="control-sidebar-menu">
          <li>
            <a href="javascript:;">
              <i class="menu-icon fa fa-birthday-cake bg-red"></i>

              <div class="menu-info">
                <h4 class="control-sidebar-subheading">Cumpleaños</h4>

                <p>Ver los que se aproximan</p>
              </div>
            </a>
          </li>
        </ul>
        <!-- /.control-sidebar-menu

        <h3 class="control-sidebar-heading">Tasks Progress</h3>
        <ul class="control-sidebar-menu">
          <li>
            <a href="javascript:;">
              <h4 class="control-sidebar-subheading">
                Custom Template Design
                <span class="pull-right-container">
                    <span class="label label-danger pull-right">70%</span>
                  </span>
              </h4>

              <div class="progress progress-xxs">
                <div class="progress-bar progress-bar-danger" style="width: 70%"></div>
              </div>
            </a>
          </li>
        </ul>
     -->

      </div>
      <!-- /.tab-pane -->
      <!-- Stats tab content -->
      <div class="tab-pane" id="control-sidebar-stats-tab">Stats Tab Content</div>
      <!-- /.tab-pane -->
      <!-- Settings tab content -->
      <div class="tab-pane" id="control-sidebar-settings-tab">
        <form method="post">
          <h3 class="control-sidebar-heading">Configuraciones Generales</h3>

          <div class="form-group">
            <label class="control-sidebar-subheading">
             Reportar el uso del panel
              <input type="checkbox" class="pull-right" checked>
            </label>

            <p>
             Más información acerca de las configuraciones.
            </p>
          </div>
          <!-- /.form-group -->
        </form>
      </div>
      <!-- /.tab-pane -->
    </div>
  </aside>
  <!-- /.control-sidebar -->
  <!-- Add the sidebar's background. This div must be placed
  immediately after the control sidebar -->
  <div class="control-sidebar-bg"></div>
</div>
<!-- ./wrapper -->

<!-- REQUIRED JS SCRIPTS -->

<!-- jQuery 3 -->
<script src=" {{ asset('libreria_dash/jquery/jquery.min.js') }} " ></script>
<!-- Bootstrap 3.3.7 -->
<script src=" {{ asset('libreria_dash/bootstrap/js/bootstrap.min.js') }} "></script>
<!-- AdminLTE App -->
<script src=" {{ asset('libreria_dash/js/adminlte.min.js') }} " ></script>
<!-- DataTables -->
<script src="{{ asset('libreria_dash/datatables.net/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('libreria_dash/datatables.net-bs/js/dataTables.bootstrap.min.js') }}"></script>
<!-- page script -->
<script>
  $(function () {
    $('#tablaDinamica').DataTable()
  })
</script>
</body>
</html>
