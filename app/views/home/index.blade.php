@extends('layouts.frontend')

@section('content')

  <div class="home-index">

    <div class="container-fluid bg-primary text-center" style="background:linear-gradient(rgba(52, 73, 94, 0.45), rgba(52, 73, 94, 0.45)), url('/assets/img/bg/rhino-lady.jpg') top; background-size: cover;">
      <br/><br/>
      <h3>Find Environmental Impact Assesments Near You</h3>
      <h5>And register for alerts in your area...</h5>
      <br/>

      <div class="row" style="margin-bottom:5px;">
        <div class="col-md-4 col-md-offset-4">
          <div class="input-group input-group-hg input-group-rounded">
            <span class="input-group-btn">
              <button type="submit" class="btn"><span class="fa fa-globe fa-lg"></span></button>
            </span>
            <input type="text" class="form-control" placeholder="Province, town, city or region" name="search-geo" id="search-geo" style="border-top-right-radius: 0; border-bottom-right-radius: 0;">
            <span class="input-group-btn">
              <button type="submit" class="btn"><span class="fui-arrow-right"></span></button>
            </span>
          </div>
        </div>
      </div>


      <p><button class="btn btn-link" style="color:#fff;" id="search-my-geo">
        <span class="fa fa-crosshairs"></span> Use my location
      </a></button></p>

      <p id="loading-geo" style="display:none;" >
        <i class="fa fa-crosshairs fa-spin"></i>
        Finding projects in this area...
      </p>

      <p id="loading-my-geo" style="display:none;" >
        <i class="fa fa-crosshairs fa-spin"></i>
        Locating you...
      </p>

      <div class="container text-left">
        <div class="row">
          <div class="col-md-6 col-md-offset-3">
            <div class="alert alert-warning alert-dismissible" role="alert"
              style="padding: 10px 35px 10px 15px; display:none;" id="search-my-geo-alert">
              <button type="button" class="close" data-dismiss="alert">
                <span aria-hidden="true">&times;</span><span class="sr-only">Close</span>
              </button>
              <strong>Oops!</strong> It seems your location didn't work. Try search instead.
            </div>
          </div>
        </div>
      </div>

      <br/><br/><br/><br/>
    </div>

    <div class="container text-center" style="padding: 50px 0;">
      <h3>How #GreenAlert Works</h3><br/>
      <div class="row">
        <div class="col-md-4">
          <span class="fa-stack fa-3x">
            <i class="fa fa-circle fa-stack-2x"></i>
            <i class="fa fa-map-marker fa-stack-1x fa-inverse"></i>
          </span>
          <p class="lead">Find a Environmental Impact Assesments happening near you.</p>
        </div>
        <div class="col-md-4">
          <span class="fa-stack fa-3x">
            <i class="fa fa-circle fa-stack-2x"></i>
            <i class="fa fa-bolt fa-stack-1x fa-inverse"></i>
          </span>
          <p class="lead">Register for alerts in your area to get updates of new or current EIAs.</p>
        </div>
        <div class="col-md-4">
          <span class="fa-stack fa-3x">
            <i class="fa fa-circle fa-stack-2x"></i>
            <i class="fa fa-pencil-square-o fa-stack-1x fa-inverse"></i>
          </span>
          <p class="lead">Take action by getting your friends involved in signing a petition.</p>
        </div>
      </div>
    </div>

    <div class="container-fluid bg-info text-right" style="padding: 50px 0;">
      <div class="container">
        <div class="row">
          <div class="col-md-6 text-center">
            <p class="lead">No. of EIAs tracked</p>
            <h1 style="font-size: 100px;">8,546</h1>
          </div>
          <div class="col-md-3">
            <p>Last Updated</p>
            <p><b>16th Oct, 2014</b></p>
            <p>Petitions</p>
            <p><b>200+</b></p>
          </div>
          <div class="col-md-3">
            <p>Subscribers</p>
            <p><b>1,000+</b></p>
            <p>Subscriptions</p>
            <p><b>3,000+</b></p>
          </div>
        </div>
      </div>
    </div>

    <div class="container text-center" style="padding: 50px 0;">
      <p>
        <a href="http://oxpeckers.org" target="_blank">
          <img src="/assets/img/logos/oxpeckers.png" style="height:110px;"/>
        </a>
        <span style="width:50px; display:inline-block;"></span>
        <a href="http://codeforafrica.org" target="_blank">
          <img src="/assets/img/logos/c4a.png" style="height:110px;"/>
        </a>
      </p>
    </div>

  </div> <!-- /.home.index -->

@stop

@section('scripts')
  <script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?libraries=places"></script>
  <script src="/assets/js/frontend/map-search.js"></script>
@stop
