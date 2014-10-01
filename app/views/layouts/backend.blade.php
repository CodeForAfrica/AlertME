@extends('layouts.base')

@section('title')
Dashboard
@stop

@section('stylesheets')
  <link rel="stylesheet" href="/assets/css/backend.css">
@stop

@section('navigation')
  <nav class="navbar navbar-default navbar-embossed navbar-static-top" role="navigation">
    <!-- Navbar content -->
    <div class="container-fluid">

      <!-- Brand and toggle get grouped for better mobile display -->
      <div class="navbar-header">
        <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#ga-navbar-collapse-1">
          <span class="sr-only">Toggle navigation</span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
        </button>
        <a class="navbar-brand" href="/dashboard">Dashboard</a>
      </div>

      <!-- Collect the nav links, forms, and other content for toggling -->
      <div class="collapse navbar-collapse" id="ga-navbar-collapse-1">

        <a class="btn btn-default btn-sm navbar-btn navbar-right" type="button"
          href="/logout">Logout</a>

      </div><!-- /.navbar-collapse -->

    </div><!-- /.container -->
  </nav>
@stop

@section('navigation-side')

@stop

@section('scripts')
  <script src="/assets/js/backend.js"></script>
@stop
