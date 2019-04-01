@extends('layouts.admin-dashboard')

@section('content')


<div class="box">
  <div class="box-header with-border">
    <h3 class="box-title">Configuración  de la empresa: {{$empresa->nombre}}</h3>
    <input id="_token" type="hidden" name="" value="{{ csrf_token() }}">
    <input type="hidden" id="empresa" value="{{$empresa->id_emp}}">
    <div class="box-tools pull-right">
      <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Mostrar Tabla">
        <i class="fa fa-minus"></i></button>
        <button type="button" class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Quitar Tabla">
          <i class="fa fa-times"></i></button>
        </div>
      </div>
      <div class="box-body">
        <form-component> </form-component>
        <table-component> </table-component>
        <generate-component></generate-component>
        </div>
      </div>
    </div>




@endsection
