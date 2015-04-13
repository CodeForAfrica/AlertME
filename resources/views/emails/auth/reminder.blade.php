
@extends('layouts.email')

@section('body-class') email-new bg-primary @stop

@section('stylesheets')
  <link rel="stylesheet" href="{{ secure_asset('/assets/css/frontend.css') }}">
@stop

@section('styles')
<style>
  .table {
    max-width: 500px;
  }
  body {
    padding-top: 20px;
  }
</style>
@stop

@section('content')

<table class="table" align="center">

  <thead>
    <tr><td>
      <h3>#GreenAlert</h3>
      <p class="text-muted">PASSWORD RESET</p>
    </td></tr>
  </thead>

  <tbody>
    <tr><td>
      <p>To reset your password, complete this form: <small><a href="{{ URL::to('password/reset', array($token)) }}"><u>{{ URL::to('password/reset', array($token)) }}</u></a></small>.</p>
			<p>This link will expire in {{ Config::get('auth.reminder.expire', 60) }} minutes.</p>
      <br/>
    </td></tr>
    <tr><td>
      <small><unsubscribe>Didn't send this? You can ignore this e-mail.</unsubscribe></small>
    </td></tr>
  </tbody>

</table>

@stop
