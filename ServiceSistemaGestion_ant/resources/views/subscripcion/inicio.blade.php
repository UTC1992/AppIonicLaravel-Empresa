@extends('layouts.admin-dashboard')

@section('content')
  
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>
      Gestión de subscripciones
      <small>Inicio</small>
    </h1>
    <ol class="breadcrumb">
      <li><a href="/admin"><i class="fa fa-home"></i>Inicio</a></li>
      <li class="active">Aquí</li>
    </ol>
  </section>
  <br>
  <div class="">
    <div class="col-md-12">
      <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modal-default">
        Nueva Subscripción
      </button>
      <br />
      <br />
      <div class="box">
          <div class="box-header with-border">
              <h3 class="box-title"><b>Lista de subscripciones</b></h3>
          </div>
          <!-- /.box-header -->
          <div class="box-body">
              <div class="table-responsive">
                  <table id="tablaDinamica" class="table table-bordered table-striped">
                    <thead>
                      <tr>
                          <th>
                              Empresa
                          </th>
                          <th>
                              Módulo
                          </th>
                          <th>
                              Acciones
                          </th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach($subs as $item)
                      <tr>
                          <td>
                              {{ $item->nombreEmp }}
                          </td>
                          <td>
                              {{ $item->nombreMod }}
                          </td>
                          <td>
                              <a class="btn btn-info" href="/subscripcion/edit/{{$item->idSubs}}">Editar</a>
                              <a class="btn btn-danger" href="/subscripcion/delete/{{$item->idSubs}}">Eliminar</a>
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
          <h4 class="modal-title">Crear una nueva subscripción</h4>
        </div>
        <form method="post" action="/subscripcion/save">
          @csrf
            <div class="modal-body">
              <div class="box box-primary">
                  <div class="box-body">
                      <div class="form-horizontal">
                          <div class="form-group">
                              <label class="control-label col-md-2">Empresa:</label>
                              <div class="col-md-8">
                                  <select class="form-control" name="id_emp" required>
                                      <option value="">--Seleccionar--</option>
                                    @foreach($empresas as $item)
                                      <option value="{{$item->id_emp}}">{{$item->nombre}}</option>
                                    @endforeach
                                  </select>
                              </div>
                          </div>
                          <div class="form-group">
                              <label class="control-label col-md-2">Módulo:</label>
                              <div class="col-md-8">
                                  <select class="form-control" name="id_mod" required>
                                      <option value="">--Seleccionar--</option>
                                    @foreach($modulos as $item)
                                      <option value="{{$item->id_mod}}">{{$item->nombre}}</option>
                                    @endforeach
                                  </select>
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
