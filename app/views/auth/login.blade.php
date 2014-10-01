@extends('layouts.base')

@section('title')
Login
@stop

{{-- Content --}}
@section('content')

<div class="container">

  <div class="row">
    <div class="col-md-4 col-md-offset-4">
      <h4>Login</h4>
    </div>
  </div>

  <div class="row">
    <div class="col-md-4 col-md-offset-4">
      <div class="login-form">

        {{ Form::open(array('url' => 'login')) }}

          <div class="form-group {{{ $errors->has('username') ? 'has-error' : '' }}}">
            <input type="text" name="username" class="form-control login-field"
              value="{{ Input::old('username') }}" placeholder="Username" id="login-name">
            <label class="login-field-icon fui-user" for="login-name"></label>

            <p class="small text-danger text-right">{{ $errors->first('username') }}</small>
          </div>

          <div class="form-group {{{ $errors->has('password') ? 'has-error' : '' }}}">
            <input type="password" name="password" class="form-control login-field"
              value="" placeholder="Password" id="login-pass">
            <label class="login-field-icon fui-lock" for="login-pass"></label>
            
            <p class="small text-danger text-right">{{ $errors->first('password') }}</small>
          </div>

          <button class="btn btn-primary btn-lg btn-block" type="submit">Log in</button>

        {{ Form::close() }}

        <a class="login-link" href="#">Lost your password?</a>
      </div>
    </div>
  </div>
</div>

@stop

@section('stylesheets')
<style>
  body {
    padding-top: 100px;
    padding-bottom: 40px;
    background-color: #1abc9c;
  }

  .has-error .form-control, .has-error .select2-search input[type=text] {
    color: #e74c3c;
    border-color: #e74c3c !important;
    box-shadow: none;
  }
</style>
@stop
