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
      <p class="text-muted">CONFIRM SUBSCRIPTION</p>
    </td></tr>
  </thead>

  <tbody>
    <tr><td>
      <p>
        <img src="{{ $map_image_link }}" class="img-rounded img-responsive"
          style="width:602px; height:202px; border:1px solid #ddd;"/>
      </p>
      <p>Awesome! You are one step away from starting to receive alerts from this area.</p>
      <p><b>Confirm subscription by visiting this <u>{{ $confirm_link }}</u>.</b></p>
      <small>Link not working? Copy and paste this link into your browser:<br/><u>{{ $confirm_url }}</u></small>
      <br/><br/>
      <small><unsubscribe>Didn't subscribe? You can ignore this e-mail.</unsubscribe></small>
    </td></tr>
  </tbody>

</table>



@stop