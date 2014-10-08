@extends('layouts.base')

@section('body-class') frontend @stop

@section('stylesheets')
  <link rel="stylesheet" href="/assets/css/frontend.css">
@stop

@section('navigation')
  <nav class="navbar navbar-inverse navbar-embossed navbar-fixed-top navbar-lg" role="navigation">
    <!-- Navbar content -->
    <div class="container">

      <!-- Brand and toggle get grouped for better mobile display -->
      <div class="navbar-header">
        <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#ga-navbar-collapse-1">
          <span class="sr-only">Toggle navigation</span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
        </button>
        <a class="navbar-brand" href="/">#GreenAlert</a>
      </div>

      <!-- Collect the nav links, forms, and other content for toggling -->
      <div class="collapse navbar-collapse" id="ga-navbar-collapse-1">

        <ul class="nav navbar-nav">
          <li class="{{Request::path() == '/' ? 'active' : '';}}">
            <a href="/">Home</a></li>
          <li class="{{Request::path() == 'about' ? 'active' : '';}}">
            <a href="/about">About</a></li>
          @if ( Auth::guest() )
            <li class="{{Request::path() == 'login' ? 'active' : '';}}">
              <a href="/login">Login</a></li>
          @else
            <li class="{{Request::path() == 'dashboard' ? 'active' : '';}}">
              <a href="/dashboard">Dashboard</a></li>
          @endif
        </ul>



        <form class="navbar-form navbar-right" action="#" role="search">
          <div class="form-group">
            <div class="input-group">
              <input class="form-control" id="navbarInput-01" type="search" placeholder="Search">
              <span class="input-group-btn">
                <button type="submit" class="btn"><span class="fui-search"></span></button>
              </span>
            </div>
          </div>
        </form>

      </div><!-- /.navbar-collapse -->

    </div><!-- /.container -->
  </nav>
@stop

@section('scripts')
  <script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?libraries=places"></script>

  <script src='https://api.tiles.mapbox.com/mapbox.js/v2.1.2/mapbox.js'></script>
  <link href='https://api.tiles.mapbox.com/mapbox.js/v2.1.2/mapbox.css' rel='stylesheet' />

  <script src='http://jawj.github.io/OverlappingMarkerSpiderfier-Leaflet/bin/oms.min.js'></script>

  <script src="/assets/js/frontend.js"></script>
@stop
