<!doctype html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Tecno Solutions Corp.</title>

        <!-- Scripts -->
        <script src="{{ asset('js/app.js') }}" defer></script>
        <script src="{{ asset('inicio-app/popper.min.js') }}" defer></script>

        <!-- Styles -->
        <link href="{{ asset('css/app.css') }}" rel="stylesheet">
        <link href="{{ asset('inicio-app/cover.css') }}" rel="stylesheet">

    </head>
    <body class="text-center bg-dark" >
      <div class="cover-container d-flex w-100 h-100 p-3 mx-auto flex-column">
        <header class="masthead mb-auto">
          <div class="inner">
            <h3 class="masthead-brand">
              <a href="{{ url('/') }}"  style="color: white;">
                  <strong>JMC Developers.</strong>
              </a>
            </h3>
            <nav class="nav nav-masthead justify-content-center">
              <a class="nav-link active" href="/">Inicio</a>
              <!--<a class="nav-link" href="#">Ingresar</a>-->
              <a class="nav-link" href="{{ route('admin.dashboard') }}">Admin</a>
            </nav>
          </div>
        </header>

        <main role="main" class="inner cover">
          <img class="img-fluid" src="{{ asset('img/logotsc.png') }}">
          <h2 class="cover-heading">Soluciones Tecnológicas para ti.</h2>
        </main>

        <footer class="mastfoot mt-auto">
          <div class="inner">
            <p>Copyright © 2018 <a target="_blank" href="https://www.tecnosolutionscorp.com/">JMC.</a> Todos los derechos reservados.</p>
          </div>
        </footer>
      </div>

  </body>
</html>
