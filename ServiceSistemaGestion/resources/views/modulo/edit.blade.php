@extends('layouts.admin-dashboard')

@section('content')
  
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>
      Gestión de módulos
      <small>Inicio</small>
    </h1>
    <ol class="breadcrumb">
      <li><a href="{{ route('admin.dashboard') }}"><i class="fa fa-home"></i>Inicio</a></li>
      <li class="active">Aquí</li>
    </ol>
  </section>
  <br>
  <div class="">
      <div class="col-md-6">
          <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">Editar un módulo</h3>
            </div>
            <form action="/modulo/update" method="post">
              @csrf
              <div class="box-body">
                  <div class="form-horizontal">
                      <input type="hidden" name="id_mod" value="{{ $res->id_mod }}">
                      <div class="form-group">
                          <label class="control-label col-md-2">Nombre:</label>
                          <div class="col-md-8">
                              <input type="text" class="form-control" name="nombre" value="{{ $res->nombre }}" required>
                          </div>
                      </div>
                      <div class="form-group">
                        <label class="control-label col-md-2">Código:</label>
                        <div class="col-md-8">
                            <input type="text" class="form-control" name="codigo" value="{{ $res->codigo }}" required>
                        </div>
                      </div>
                      <div class="form-group">
                        <label class="control-label col-md-2">Ruta:</label>
                        <div class="col-md-8">
                            <input type="text" class="form-control" name="ruta" value="{{ $res->ruta }}" required>
                        </div>
                      </div>
                      <div class="form-group">
                        <label class="control-label col-md-2">Icono:</label>
                        <div class="col-md-8">
                            <input type="text" class="form-control" name="icono_menu" value="{{ $res->icono_menu }}" required>
                        </div>
                      </div>
                      <div class="form-group">
                          <label class="control-label col-md-2">Estado:</label>
                          @if($res->estado == 1)
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
                          @elseif($res->estado == 0)
                              <div class="col-md-8 ">
                                <div class="form-check form-check-inline">
                                  <input class="form-check-input"  type="radio" name="estado" id="inlineRadio1" value="1">
                                  <label class="form-check-label" for="inlineRadio1">Activo</label>
                                </div>
                                <div class="form-check form-check-inline">
                                  <input class="form-check-input" checked="true" type="radio" name="estado" id="inlineRadio2" value="0">
                                  <label class="form-check-label" for="inlineRadio2">Inactivo</label>
                                </div>
                              </div>
                          @endif
                          

                      </div>
                  </div>
              </div>
              <div class="box-footer">
                  <div class="">
                      <input type="submit" value="Guardar" class="btn btn-success" />
                  </div>
              </div>
            </form>
          </div>
      </div>
  </div>
@endsection
