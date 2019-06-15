@extends('layouts.admin-dashboard')

@section('content')

  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>
      Historial de acciones
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
        <div class="box box-primary box-body form-horizontal">
            <form method="post" action="/historial/inicio">
                @csrf
                <div class="form-group">
                    <label class="control-label col-lg-2">Empresa:</label>
                    <div class="col-md-3">
                        <select class="form-control" name="id_emp" required>
                            <option value="">--Seleccionar--</option>
                            @foreach($empresas as $item)
                            <option value="{{$item->id_emp}}">{{$item->nombre}}</option>
                            @endforeach
                        </select>
                    </div>
                    <label class="control-label col-lg-2">Fecha de Inicio:</label>
                    <div class="col-md-3">
                        <input type="date" class="form-control" name="fecha_actividad" value="" required>
                    </div>
                    <button type="submit" class="btn btn-primary" >
                        Buscar
                    </button>
                </div>

            </form>
        </div>

      <div class="box">
          <div class="box-header with-border">
              <h3 class="box-title"><b>Acciones realizadas</b></h3>
          </div>
          <!-- /.box-header -->
          <div class="box-body">
              <div class="table-responsive">
                  <table id="tablaDinamica" class="table table-bordered table-striped">
                    <thead>
                      <tr>
                          <th>
                              Acción
                          </th>
                          <th>
                              Observacióm
                          </th>
                          <th>
                              Usuario
                          </th>
                          <th>
                              Módulo
                          </th>
                          <th>
                              Fecha
                          </th>
                      </tr>
                    </thead>
                    <tbody>
                        @foreach($historiales as $item)
                        <tr>
                            <td>
                                {{ $item->accion }}
                            </td>
                            <td>
                                {{ $item->observacion }}
                            </td>
                            <td>
                                {{ $item->nombre }}
                            </td>
                            <td>
                                {{ $item->modulo }}
                            </td>
                            <td>
                                {{ $item->created_at }}
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

@endsection
