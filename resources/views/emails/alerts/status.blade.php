@extends('layouts.email')

@section('body-class') email-alert bg-primary @stop

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
      <p class="text-muted">PROJECT STATUS UPDATED</p>
    </td></tr>
  </thead>

  <tbody>
    <tr><td>
      <p>
        <img src="{{ $map_image_link }}" class="img-rounded img-responsive"
          style="width:602px; height:202px; border:1px solid #ddd;"/>
      </p>
      <div style="padding-left: 30px">
        <p>
          <b>{{ $project->title }}</b><br/>
          <small>{{ $project->description }}</small><br/>
          <a href="{{ secure_asset('/project/'.$project->id)}}" target="_blank">
            <span class="fui-link"></span>
            <u>Full Details</u>
          </a>
        </p>
      </div>

      <small>
        This alert is coming to you courtesy of
        <a href="{{ $confirm_url }}" target="_blank"><u>your subscription</u></a>
        on #GreenAlert.
      </small>

      <br/><hr/>

      <small><unsubscribe>To update your subscription details, visit this <u>{{ $confirm_link }}</u>.</unsubscribe></small>
    </td></tr>
  </tbody>

</table>

@stop
