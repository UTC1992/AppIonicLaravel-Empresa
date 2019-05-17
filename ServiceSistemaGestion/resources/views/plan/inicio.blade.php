@extends('layouts.admin-dashboard')

@section('content')
  
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>
      Gestión de planes
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
        Nuevo plan
      </button>
      <br />
      <br />
      <div class="box">
          <div class="box-header with-border">
              <h3 class="box-title"><b>Lista de planes</b></h3>
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
                            Descripción
                          </th>
                          <th>
                              Número de usuarios
                          </th>
                          <th>
                              Acciones
                          </th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach($planes as $item)
                      <tr>
                          <td>
                              {{ $item->nombre }}
                          </td>
                          <td>
                            {{ $item->descripcion }}
                          </td>
                          <td>
                              {{ $item->num_tecnicos }}
                          </td>
                          <td>
                              <a class="btn btn-info btn-xs" href="/plan/edit/{{$item->id_plan}}">Editar</a>
                              <a class="btn btn-danger btn-xs" href="/plan/delete/{{$item->id_plan}}">Eliminar</a>
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
          <h4 class="modal-title">Crear un nuevo plan</h4>
        </div>
        <form method="post" action="/plan/save">
          @csrf
            <div class="modal-body">
              <div class="box box-primary">
                  <div class="box-body">
                      <div class="form-horizontal">
                          <div class="form-group">
                              <label class="control-label col-md-3">Plan:</label>
                              <div class="col-md-8">
                                  <input type="text" class="form-control" name="nombre" value="" required>
                              </div>
                          </div>
                          <div class="form-group">
                            <label class="control-label col-md-3">Descripción:</label>
                            <div class="col-md-8">
                                <textarea class="form-control" name="descripcion" value="" required></textarea>
                            </div>
                          </div>
                          <div class="form-group">
                            <label class="control-label col-md-3">Número de usuarios:</label>
                            <div class="col-md-8">
                                <input type="text" class="form-control" name="num_tecnicos" value="" required>
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

