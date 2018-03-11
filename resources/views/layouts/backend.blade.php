@extends('layouts.base')

@section('title')
  Dashboard
@stop

@section('body-class') dashboard @stop

@section('stylesheets')
  <link rel="stylesheet" href="/assets/css/backend.css">
@stop

@section('navigation')
  <nav class="navbar navbar-default navbar-embossed navbar-static-top" role="navigation">
    <!-- Navbar content -->
    <div class="container-fluid">

      <!-- Brand and toggle get grouped for better mobile display -->
      <div class="navbar-header">
        <button type="button" class="navbar-toggle collapsed" data-toggle="collapse"
                data-target="#ga-navbar-collapse-1">
          <span class="sr-only">Toggle navigation</span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
        </button>
        <a class="navbar-brand" href="/">#</a>
        <a class="navbar-brand" href="/dashboard">Dashboard</a>
      </div>

      <!-- Collect the nav links, forms, and other content for toggling -->
      <div class="collapse navbar-collapse" id="ga-navbar-collapse-1">

        <form class="form-inline navbar-right" method="post" action="/logout">
          <button class="btn btn-default btn-sm navbar-btn" type="submit">Logout</button>
          <input type="hidden" name="_token" value="{{ csrf_token() }}">
        </form>

      </div>
      <!-- /.navbar-collapse -->

    </div>
    <!-- /.container -->
  </nav>
@stop

@section('navigation-side')

  <div class="container-fluid dashboard">

    <div class="row">
      <div class="col-md-2">
        <div class="btn-group-vertical btn-block navigation-side">
          <a href="/dashboard" class="btn btn-embossed btn-primary
          {{ $request->path() == 'dashboard' ? 'active' : '' }}">
            <span class="fui-home"></span> Home</a>
        </div>
        <hr/>
        <div class="btn-group-vertical btn-block navigation-side">
          <a href="/dashboard/datasources" class="btn btn-embossed btn-primary
          {{ $request->path() == 'dashboard/datasources' ? 'active' : '' }}">
            <span class="fui-upload"></span> Data Sources</a>
          <a href="/dashboard/categories" class="btn btn-embossed btn-primary
          {{ $request->path() == 'dashboard/categories' ? 'active' : '' }}">
            <span class="fui-tag"></span> Categories</a>
          <a href="/dashboard/pages" class="btn btn-embossed btn-primary
          {{ $request->path() == 'dashboard/pages' ? 'active' : '' }}">
            <span class="fui-document"></span> Pages</a>
        </div>
        <hr/>
        <div class="btn-group-vertical btn-block navigation-side">
          <a href="/dashboard/subscriptions" class="btn btn-embossed btn-primary
          {{ $request->path() == 'dashboard/subscriptions' ? 'active' : '' }}">
            <b>#</b> Subscriptions</a>
        </div>
        <hr/>
        <div class="btn-group-vertical btn-block navigation-side">
          <a href="/dashboard/profile" class="btn btn-embossed btn-primary
          {{ $request->path() == 'dashboard/profile' ? 'active' : '' }}">
            <span class="fui-user"></span> Profile</a>
        </div>
        <div class="btn-group-vertical btn-block navigation-side">
          <a href="/dashboard/settings" class="btn btn-embossed btn-primary
          {{ $request->path() == 'dashboard/settings' ? 'active' : '' }}">
            <span class="fui-gear"></span> Settings</a>
        </div>
      </div>

      <div class="col-md-10">
        <!-- Success Messages -->
        @if ($message = \Illuminate\Support\Facades\Session::get('success'))
          <div class="alert alert-success alert-block">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            <p>
              <small>
                <b>Success:</b>
                {!! $message !!}
              </small>
            </p>
          </div>
          @endif
              <!-- Not So Success Messages -->
          @if ($message = \Illuminate\Support\Facades\Session::get('error'))
            <div class="alert alert-danger alert-block">
              <button type="button" class="close" data-dismiss="alert">&times;</button>
              <p>
                <small>
                  <b>Error:</b>
                  {!! $message !!}
                </small>
              </p>
            </div>
          @endif
          @stop

        @section('footer')
      </div>
      <!-- /.col-md-10 -->
    </div>
    <!-- /.row -->

    <br/>

    <hr/>
    <p class="text-muted text-right">
      <small>Built by Code for Africa</small>
    </p>

  </div> <!-- /.container-fluid -->
@stop
