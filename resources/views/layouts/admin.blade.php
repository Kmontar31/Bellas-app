<!DOCTYPE html>
<!--
This is a starter template page. Use this page to start your new project from
scratch. This page gets rid of all links and provides the needed markup only.
-->
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Bellisimas</title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome Icons -->
  <link rel="stylesheet" href="{{url('plugins/fontawesome-free/css/all.min.css')}}">
  <!-- Theme style -->
  <link rel="stylesheet" href="{{url('dist/css/adminlte.min.css')}}">
 
  <!--iconos de boortsap-->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
  
  @stack('styles')
</head>
<body class="hold-transition sidebar-mini">
<div class="wrapper">

  <!-- Navbar -->
  <nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
      </li>
      <li class="nav-item d-none d-sm-inline-block">
        <a href="{{url('/admin')}}" class="nav-link">Inicio</a>
      </li>
      <li class="nav-item d-none d-sm-inline-block">
        <a href="{{url('/admin/servicios')}}" class="nav-link">Servicios</a>
      </li>
      <li class="nav-item d-none d-sm-inline-block">
        <a href="{{url('/admin/profesionales')}}" class="nav-link">Profesionales</a>
      </li>
      <li class="nav-item d-none d-sm-inline-block">
        <a href="{{url('/admin/clientes')}}" class="nav-link">Clientes</a>
      </li>
      <li class="nav-item d-none d-sm-inline-block">
        <a href="{{url('/admin/horarios')}}" class="nav-link">Disponibilidad</a>
      </li>
      <li class="nav-item d-none d-sm-inline-block">
        <a href="{{url('/admin/agenda/calendar')}}" class="nav-link">Agenda</a>
      </li>
    </ul>
  </nav>
  <!-- /.navbar -->

  <!-- Main Sidebar Container -->
  <aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="index3.html" class="brand-link">
      <img src="{{url('dist/img/AdminLTELogo.png')}}" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
      <span class="brand-text font-weight-light">Bellas</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar user panel (optional) -->
      <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="info">
          <a href="#" class="d-block">{{ Auth::user()->name }}</a>
        </div>
      </div>

      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <!-- Add icons to the links using the .nav-icon class
              with font-awesome or any other icon font library -->
          <li class="nav-item">
            <a href="#" class="nav-link active">
              <i class="nev-icon fas bi bi-person-fill"></i>
              <p>
                Inicio
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
             <a href="http://127.0.0.1:8000/admin/servicios" class="nav-link active">
              <i class="nev-icon fas bi bi-person-fill"></i>
              <p>
                Servicio 
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
             <a href="http://127.0.0.1:8000/admin/profesionales" class="nav-link active">
              <i class="nev-icon fas bi bi-person-fill"></i>
              <p>
                Profesioanles
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
             <a href="http://127.0.0.1:8000/admin/clientes" class="nav-link active">
              <i class="nev-icon fas bi bi-person-fill"></i>
              <p>
                Clientes 
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
             <a href="http://127.0.0.1:8000/admin/horarios" class="nav-link active">
              <i class="nev-icon fas bi bi-person-fill"></i>
              <p>
                Disponibilidad
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
               <a href="http://127.0.0.1:8000/admin/agenda/calendar" class="nav-link active">
              <i class="nev-icon fas bi bi-person-fill"></i>
              <p>
                Agenda
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
          </li>
          <li class="nav-item">
            <a href="{{ route('logout') }}" class="nav-link" style="background-color: #a9200e" id="" 
              onclick="event.preventDefault();
                        document.getElementById('logout-form').submit();">
              <i class="nav-icon fas bi bi-door-closed"></i>
              <p>
                  Cerrar la sesión
              </p>
            </a>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                </div>
          </li>
        </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>

    <div class="content-wrapper">
        <br>
        <div class="container">
            @yield('content')
        </div>
    </div>

  <!-- Control Sidebar -->
  <aside class="control-sidebar control-sidebar-dark">
    <!-- Control sidebar content goes here -->
    <div class="p-3">
      <h5>Title</h5>
      <p>Sidebar content</p>
    </div>
  </aside>
  <!-- /.control-sidebar -->

  <!-- Main Footer -->
  <footer class="main-footer">
    <!-- To the right -->
    <div class="float-right d-none d-sm-inline">
      Anything you want
    </div>
    <!-- Default to the left -->
    <strong>Copyright &copy; 2014-2021 <a href="https://adminlte.io">Bellas Spa</strong> All rights reserved.
  </footer>
</div>
<!-- ./wrapper -->

<!-- REQUIRED SCRIPTS -->

<!-- jQuery -->
<script src="{{url('plugins/jquery/jquery.min.js')}}"></script>
<!-- Bootstrap 4 -->
<script src="{{url('plugins/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
<!-- AdminLTE App -->
<script src="{{url('dist/js/adminlte.min.js')}}"></script>
@stack('scripts')
</body>
</html>