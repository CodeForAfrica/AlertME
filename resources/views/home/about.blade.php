@extends('layouts.frontend')

@section('title') About @stop

@section('content')

  <div class="about" style="padding-bottom:60px;">

    <div class="container ">
      <div class="page-header">
        <h1>{{ $about->data->title == '' ? 'About' : $about->data->title }}</h1>
      </div>

      @if ( $about->data->description == '' )

        <p>South African law says planned development projects, including mines,
          dams, power stations, roads and landfill sites, need to have their
          environmental impacts assessed bofore they can go ahead.</p>
        <p>#GreenAlert helps you to find out what Environmental Impact Assessments
          (EIAs) are happening in your area.</p>
        <p>Find your location to see the details of an EIA: its official ID, the
          project description, status of the development, and the government body
          responsible for authorising and monitoring the development.</p>
        <p>You can keep up to date with the changing status of EIAs that interest
          you by registering for personalised alerts. We will send you real-time
          notifications by email or SMS.</p>
        <p>And you can help keep the developers accountable by joining and
          participating in our community network.</p>

      @else

        {!! Markdown::convertToHtml($about->data->description) !!}

      @endif

      <hr style="margin:50px 0;"/>

      <div class="row home-logos">
        <div class="col-md-3">
          <h4>Partners</h4>
          <p>#GreenAlert has been made possible through support from the following partners:</p>
        </div>
        <div class="col-md-9">
          <p>
            <a href="http://oxpeckers.org" target="_blank">
              <img src="{{ asset('assets/img/logos/oxpeckers-long.png') }}"/>
            </a>
          </p>
          <p>
            <a href="http://www.codeforafrica.org" target="_blank">
              <img src="{{ asset('assets/img/logos/cfafrica.png') }}"/>
            </a>
            <a href="http://africannewschallenge.org" target="_blank">
              <img src="{{ asset('assets/img/logos/anic.png') }}" style="height:65px;"/>
            </a>
            <a href="http://www.sej.org/" target="_blank">
              <img src="{{ asset('assets/img/logos/sej.png') }}"/>
            </a>
          </p>
        </div>
      </div> <!-- /.row -->

    </div> <!-- /.container -->

  </div> <!-- /.about -->

@stop

@section('scripts')
@stop
