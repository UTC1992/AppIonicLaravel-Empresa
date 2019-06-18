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
                            <input type="hidden" name="id_mod_emp" value="{{ $item->id_mod_emp }}">
                          @endforeach
                          <div class="form-group">
                              <label class="control-label col-md-2">Empresa:</label>
                              <div class="col-md-8">
                                  @foreach($subs as $item)
                                    <input value="{{ $item->idEmp }}" type="hidden" name="id_emp" />
                                    <input class="form-control" value=" {{ $item->nombreEmp }}" required readonly>
                                  @endforeach
                              </div>
                          </div>
                          <div class="form-group">
                              <label class="control-label col-md-2">Módulo:</label>
                              <div class="col-md-8">
                                  <select class="form-control" name="id_mod" required>
                                    @foreach($subs as $itemSub)
                                      <option value="{{ $itemSub->idMod }}">{{ $itemSub->nombreMod }}</option>
                                      @foreach($modulos as $item)
                                        @if ($item->id_mod != $itemSub->idMod)
                                        <option value="{{$item->id_mod}}">{{$item->nombre}}</option>
                                        @endif
                                      @endforeach
                                    @endforeach
                                    
                                  </select>
                              </div>
                          </div>
                          <div class="form-group">
                              <label class="control-label col-md-2">Planes:</label>
                              <div class="col-md-8">
                                  <select class="form-control" name="id_plan" required>
                                    @foreach($subs as $itemSubs)
                                      <option value="{{ $itemSubs->id_plan }}">{{ $itemSubs->nombre }}</option>
                                      @foreach($planes as $item)
                                        @if ($item->id_plan != $itemSubs->id_plan)
                                          <option value="{{$item->id_plan}}">{{$item->nombre}}</option>
                                        @endif
                                      @endforeach
                                    @endforeach
                                  </select>
                              </div>
                          </div>
                          <div class="form-group">
                            <label class="control-label col-md-2">Fecha de Inicio:</label>
                            <div class="col-md-8">
                              @foreach($subs as $item)
                                <input type="date" class="form-control" name="fecha_inicio" value="{{ $item->fecha_inicio }}" required>
                              @endforeach
                            </div>
                          </div>
                          <div class="form-group">
                            <label class="control-label col-md-2">Fecha de Fin:</label>
                            <div class="col-md-8">
                              @foreach($subs as $item)
                                <input type="date" class="form-control" name="fecha_fin" value="{{ $item->fecha_fin }}" required>
                                @endforeach
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
