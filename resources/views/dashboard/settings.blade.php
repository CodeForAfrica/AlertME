@extends('layouts.backend')

@section('content')
  <div class="settings">

    <form method="POST" action="{{ secure_url('dashboard/settings') }}">
      <input type="hidden" name="_token" value="{{ csrf_token() }}">

      <h5>
        Settings
        <button type="submit" class="btn btn-primary btn-embossed btn-wide"
                style="margin-left: 30px;">Save Changes
        </button>
      </h5>

      <hr/>

      <div class="row">
        <div class="col-md-6">
          <h6>Geocode</h6>

          <p>Google Geocode API</p>

          <div class="form-group">
            <label for="key">API Key</label>
            <input type="text" class="form-control" id="key" placeholder="Enter API Key"
                   value="{{ $geoapi->key }}" name="key">

            <p class="help-block"><a
                  href="https://developers.google.com/maps/documentation/geocoding/#api_key" target="_blank"><span
                    class="fui-info-circle"></span> What is and how
                to get the Google Geocoding API.</a></p>
          </div>
        </div>
      </div>

    </form>

  </div>

@stop
