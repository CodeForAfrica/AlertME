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
    <tr>
      <td>
        <h3>#GreenAlert</h3>

        <p class="text-muted">CONFIRM SUBSCRIPTION</p>
      </td>
    </tr>
    </thead>

    <tbody>
    <tr>
      <td>
        @if ( $project_id != 0 )
          <p><b>{{ strlen($project_title) > 80 ? substr($project_title, 0, 80).'...' : $project_title }}</b></p>
          <hr/>
        @endif
        <p>
          <img src="{{ $map_image_link }}" class="img-rounded img-responsive"
               style="width:602px; height:202px; border:1px solid #ddd;"/>
        </p>

        <p>Awesome! You are one step away from starting to receive alerts from
          this {{ $project_id == 0 ? 'area' : 'project' }}.</p>

        <p><b>Confirm subscription by visiting this <u><a href="{{ $confirm_url }}">link</a></u>.</b></p>
        <small>Link not working? Copy and paste this link into your browser:<br/><u>{{ $confirm_url }}</u></small>
        <br/><br/>

      </td>
    </tr>
    <tr>
      <td>
        <small>
          <unsubscribe>Didn't subscribe? You can ignore this e-mail.</unsubscribe>
        </small>
      </td>
    </tr>
    </tbody>

  </table>

@stop
