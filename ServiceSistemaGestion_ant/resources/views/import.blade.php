<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title></title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
  </head>
  <body>
    <div class="col-md-12">


        <div class="box box-primary">
                        <div class="box-header">
                          <h3 class="box-title">Cargar Datos de Actividades</h3>
                        </div><!-- /.box-header -->

        <div id="notificacion_resul_fcdu"></div>

        <form  id="f_cargar_datos_usuarios" name="f_cargar_datos_usuarios" method="post"  action="cargar2" class="formarchivo" enctype="multipart/form-data" >
            {{ csrf_field() }}

        <div class="box-body">

        <div class="form-group col-xs-12"  >
               <label>Agregar Archivo de Excel </label>
                <input name="archivo" id="archivo" type="file"   class="archivo form-control"  required/><br /><br />
        </div>


        <div class="box-footer">
                            <button type="submit" class="btn btn-primary">Cargar Datos</button>
        </div>




        </div>

        </form>

        </div>

    </div>
    <hr>
    <form class="" action="{{route('cargar3')}}" method="get" enctype="multipart/form-data">
      <input type="file" name="archivo" value="">
      <input type="submit" name="" value="Enviar">
    </form>
  </body>
  <script src="{{ asset('js/script.js') }}" defer></script>
  <script src="{{asset('js/jquery-3.3.1.min.js')}}"></script>
</html>
