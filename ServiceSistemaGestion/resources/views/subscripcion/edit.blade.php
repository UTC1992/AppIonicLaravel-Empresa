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
      <div class="col-md-6">
          <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">Editar una subscripción</h3>
            </div>
            <form action="/subscripcion/update" method="post">
              @csrf
              <div class="box-body">
                  <div class="form-horizontal">
                          @foreach($subs as $item)
                            <input type="hidden" name="id_mod_emp" value="{{ $item->idSubs }}">
                          @endforeach
                          <div class="form-group">
                              <label class="control-label col-md-2">Empresa:</label>
                              <div class="col-md-8">
                                  <select class="form-control" name="id_emp" required>
                                    @foreach($subs as $item)
                                      <option value="{{ $item->idEmp }}">{{ $item->nombreEmp }}</option>
                                    @endforeach
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
                                    @foreach($subs as $item)
                                      <option value="{{ $item->idMod }}">{{ $item->nombreMod }}</option>
                                    @endforeach
                                    @foreach($modulos as $item)
                                      <option value="{{$item->id_mod}}">{{$item->nombre}}</option>
                                    @endforeach
                                  </select>
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
