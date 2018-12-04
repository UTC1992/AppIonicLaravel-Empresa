@extends('layouts.admin-dashboard')

@section('content')
  
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>
      Gestión de módulos
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
        Nuevo usuario
      </button>
      <br />
      <br />
      <div class="box">
          <div class="box-header with-border">
              <h3 class="box-title"><b>Lista de usuarios </b></h3>
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
                              Email
                          </th>
                          <th>
                              Empresa
                          </th>
                          <th>
                              Acciones
                          </th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach($users as $item)
                      <tr>
                          <td>
                              {{ $item->name }}
                          </td>
                          <td>
                              {{ $item->email }}
                          </td>
                          <td>
                            {{ $item->nombreEmp }}
                          </td>
                          <td>
                              <a class="btn btn-info" href="/user/edit/{{$item->id}}">Editar</a>
                              <a class="btn btn-danger" href="/user/delete/{{$item->id}}">Eliminar</a>
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
          <h4 class="modal-title">Crear un nuevo usuario</h4>
        </div>
        <form method="POST" action="{{ route('user.registerUser') }}">
          @csrf
            <div class="modal-body">
              <div class="box box-primary">
                  <div class="box-body">
                      <div class="form-horizontal">
                        <div class="form-group row">
                              <label class="col-md-4 col-form-label text-md-right">Empresa:</label>
                              <div class="col-md-6">
                                  <select class="form-control" name="id_emp" required>
                                      <option value="">--Seleccionar--</option>
                                    @foreach($empresas as $item)
                                      <option value="{{$item->id_emp}}">{{$item->nombre}}</option>
                                    @endforeach
                                  </select>
                              </div>
                        </div>
                        
                        <div class="form-group row">
                          <label for="name" class="col-md-4 col-form-label text-md-right">{{ __('Nombre:') }}</label>

                          <div class="col-md-6">
                              <input id="name" type="text" class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" name="name" value="{{ old('name') }}" required autofocus>

                              @if ($errors->has('name'))
                                  <span class="invalid-feedback" role="alert">
                                      <strong>{{ $errors->first('name') }}</strong>
                                  </span>
                              @endif
                          </div>
                        </div>

                        <div class="form-group row">
                            <label for="email" class="col-md-4 col-form-label text-md-right">{{ __('E-Mail:') }}</label>

                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ old('email') }}" required>

                                @if ($errors->has('email'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="password" class="col-md-4 col-form-label text-md-right">{{ __('Password:') }}</label>

                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" required>

                                @if ($errors->has('password'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="password-confirm" class="col-md-4 col-form-label text-md-right">{{ __('Confirmar Password:') }}</label>

                            <div class="col-md-6">
                                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required>
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
