@extends('layouts.backend')

@section('content')
<div class="settings">
  <h5>Settings</h5>

  <hr/>

  {{ Form::open(array('url' => 'dashboard/settings')) }}

    <h6>Geocode</h6>

    <p>Google Geocode API</p>
    <div class="form-group">
      {{ Form::label('key', 'API Key') }}
      <input type="text" class="form-control" id="key" placeholder="Enter API Key"
        value="{{ $geoapi->key }}" name="key">
      <p class="help-block">Example block-level help text here.</p>
    </div>

    <button type="submit" class="btn btn-primary btn-lg btn-embossed btn-wide">Save Changes</button>

  {{ Form::close() }}

</div>

</div> <!-- /.col-md-10 -->
</div> <!-- /.row -->
@stop
