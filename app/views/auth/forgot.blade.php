@extends('layouts.base')

@section('title')
Forgot Password
@stop

{{-- Content --}}
@section('content')

<div class="container">

  <div class="row">
    <div class="col-md-4 col-md-offset-4">
      <h4>Forgot Password</h4>
    </div>
  </div>

  <div class="row">
    <div class="col-md-4 col-md-offset-4">
      <div class="login-form">

        <!-- Success Messages -->
        @if ($message = Session::get('status'))
          <div class="alert alert-success alert-block">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            <p><small>
              <b>Success:</b>
              {{{ $message }}}
            </small></p>
          </div>
        @endif
        <!-- Not So Success Messages -->
        @if ($message = Session::get('error'))
          <div class="alert alert-danger alert-block">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            <p><small>
              <b>Error:</b>
              {{{ $message }}}
            </small></p>
          </div>
        @endif

        <form action="{{ action('RemindersController@postRemind') }}" method="POST">
          <div class="form-group {{{ $errors->has('email') ? 'has-error' : '' }}}">
            <input type="email" name="email" class="form-control login-field"
              value="{{ Input::old('email') }}" placeholder="Email Address" id="login-email">
            <label class="login-field-icon fui-mail" for="login-email"></label>

            <p class="small text-danger text-right">{{ $errors->first('email') }}</small>
          </div>
          <button class="btn btn-primary btn-embossed btn-lg btn-block" type="submit">Send Reminder</button>
        </form>

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

@section('scripts')
<script>
  $(':checkbox').radiocheck();
</script>
@stop
