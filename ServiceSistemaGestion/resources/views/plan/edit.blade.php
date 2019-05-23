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
      <div class="col-md-6">
          <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">Editar un plan</h3>
            </div>
            <form action="/plan/update" method="post">
              @csrf
              <div class="box-body">
                  <div class="form-horizontal">
                      <input type="hidden" name="id_plan" value="{{ $res->id_plan }}">
                      <div class="form-group">
                          <label class="control-label col-md-3">Plan:</label>
                          <div class="col-md-8">
                              <input type="text" class="form-control" name="nombre" value="{{ $res->nombre }}" required>
                          </div>
                      </div>
                      <div class="form-group">
                            <label class="control-label col-md-3">Descripción:</label>
                            <div class="col-md-8">
                                <textarea class="form-control" name="descripcion" required>{{ $res->descripcion }}</textarea>
                            </div>
                          </div>
                          <div class="form-group">
                            <label class="control-label col-md-3">Número de usuarios:</label>
                            <div class="col-md-8">
                                <input type="text" class="form-control" name="num_tecnicos" value="{{ $res->num_tecnicos }}" required>
                            </div>
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
