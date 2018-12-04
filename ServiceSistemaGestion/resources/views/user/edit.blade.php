@extends('layouts.admin-dashboard')

@section('content')
  
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>
      Gestión de usuarios
      <small>Inicio</small>
    </h1>
    <ol class="breadcrumb">
      <li><a href="/admin"><i class="fa fa-home"></i>Inicio</a></li>
      <li class="active">Aquí</li>
    </ol>
  </section>
  <br>
  <div class="">
      <div class="col-md-6">
          <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">Editar un usuario</h3>
            </div>
            <form action="/user/update" method="post">
              @csrf
              <div class="box-body">
                  <div class="form-horizontal">
                      <input type="hidden" name="id" value="{{ $userEdit->id }}">
                      <div class="form-group">
                          <label class="control-label col-md-4">Empresa:</label>
                          <div class="col-md-6">
                              <select class="form-control" name="id_emp" required>
                                  <option value="{{ $userEdit->idEmp }}">{{ $userEdit->nombreEmp }}</option>
                                @foreach($empresas as $item)
                                  <option value="{{$item->id_emp}}">{{$item->nombre}}</option>
                                @endforeach
                              </select>
                          </div>
                      </div>
                      <div class="form-group">
                          <label class="control-label col-md-4">Nombre:</label>
                          <div class="col-md-6">
                              <input type="text" class="form-control" name="name" value="{{ $userEdit->name }}" required>
                          </div>
                      </div>
                      <div class="form-group">
                          <label class="control-label col-md-4">Email:</label>
                          <div class="col-md-6">
                              <input type="text" class="form-control" name="email" value="{{ $userEdit->email }}" required>
                          </div>
                      </div>
                  </div>
              </div>
              <div class="box-footer">
                  <div class="">
                      <input type="submit" value="Actualizar" class="btn btn-success" />
                  </div>
              </div>
            </form>
          </div>
      </div>

      <div class="col-md-6">
          <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">Resetear password</h3>
            </div>
            <form action="/user/updatepass" method="post">
              @csrf
              <div class="box-body">
                  <div class="form-horizontal">
                      <input type="hidden" name="id" value="{{ $userEdit->id }}">
                      
                      <div class="form-group">
                          <label class="control-label col-md-4">Password:</label>
                          <div class="col-md-6">
                              <input type="password" class="form-control" name="password" value="" required>
                          </div>
                      </div>
                  </div>
              </div>
              <div class="box-footer">
                  <div class="">
                      <input type="submit" value="Actualizar" class="btn btn-success" />
                  </div>
              </div>
            </form>
          </div>
      </div>
  </div>
@endsection
