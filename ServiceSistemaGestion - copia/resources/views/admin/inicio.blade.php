@extends('layouts.admin-dashboard')

@section('content')
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>
      Inicio
      <small>Gestionar</small>
    </h1>
    <ol class="breadcrumb">
      <li><a href="{{ route('admin.dashboard') }}"><i class="fa fa-home"></i>Inicio</a></li>
      <li class="active">Aquí</li>
    </ol>
  </section>

@endsection
