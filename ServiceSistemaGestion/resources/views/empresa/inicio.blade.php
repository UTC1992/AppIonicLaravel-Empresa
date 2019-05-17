@extends('layouts.admin-dashboard')

@section('content')

  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>
      Gestión de empresas
      <small>Inicio</small>
    </h1>
    <ol class="breadcrumb">
      <li><a href="{{ route('admin.dashboard') }}"><i class="fa fa-home"></i>Inicio</a></li>
      <li class="active">Aquí</li>
    </ol>
  </section>
  <br>
  <div class="">
    <div class="col-md-12">
      <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modal-default">
        Nueva empresa
      </button>
      <br />
      <br />
      <div class="box">
          <div class="box-header with-border">
              <h3 class="box-title"><b>Lista de empresas</b></h3>
          </div>
          <!-- /.box-header -->
          <div class="box-body">
              <div class="table-responsive">
                  <table id="tablaDinamica" class="table table-bordered table-striped">
                    <thead>
                      <tr>
                          <th>
                              Nombre
                          </th>
                          <th>
                              Estado
                          </th>
                          <th>
                              IdAdmin
                          </th>
                          <th>
                              Acciones
                          </th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach($empresas as $item)
                      <tr>
                          <td>
                              {{ $item->nombre }}
                          </td>
                          <td>
                              {{ $item->estado }}
                          </td>
                          <td>
                              {{ $item->id_admin }}
                          </td>
                          <td>
                              <a class="btn btn-info btn-xs" href="/empresa/edit/{{$item->id_emp}}">Editar</a>
                              <a class="btn btn-danger btn-xs" href="/empresa/delete/{{$item->id_emp}}">Eliminar</a>
                              <a class="btn btn-warning btn-xs" href="/empresa/gestion/{{$item->id_emp}}">Configuración</a>
                          </td>
                      </tr>
                      @endforeach
                    </tbody>
                  </table>
              </div>
          </div>
      </div>
    </div>
  </div>

  <!--MODAL CREAR EMPRESA -->
  <div class="modal fade" id="modal-default" style="display: none; padding-right: 17px;">
    <div class="modal-dialog">
      <div class="modal-content" >
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title">Crear una nueva empresa</h4>
        </div>
        <form method="post" action="/empresa/save">
          @csrf
            <div class="modal-body">
              <div class="box box-primary">
                  <div class="box-body">
                      <div class="form-horizontal">
                          <div class="form-group">
                              <label class="control-label col-md-2">Nombre:</label>
                              <div class="col-md-8">
                                  <input type="text" class="form-control" name="nombre" value="" required>
                                  <input type="hidden" name="id_admin" value="{{ Auth::user()->id_admin }}">
                              </div>
                          </div>
                          <div class="form-group">
                              <label class="control-label col-md-2">Estado:</label>
                              <div class="col-md-8 ">
                                <div class="form-check form-check-inline">
                                  <input class="form-check-input" checked="true" type="radio" name="estado" id="inlineRadio1" value="1">
                                  <label class="form-check-label" for="inlineRadio1">Activo</label>
                                </div>
                                <div class="form-check form-check-inline">
                                  <input class="form-check-input" type="radio" name="estado" id="inlineRadio2" value="0">
                                  <label class="form-check-label" for="inlineRadio2">Inactivo</label>
                                </div>
                              </div>
                          </div>
                      </div>
                  </div>
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-danger pull-left" data-dismiss="modal">Cerrar</button><button type="submit" class="btn btn-primary">Guardar</button>
            </div>
        </form>
      </div>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
  </div>
  <!-- /.modal -->
  <!--MODAL CREAR EMPRESA-->
@endsection
