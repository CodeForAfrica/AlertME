@extends('layouts.frontend')

@section('content')

  <div class="home">

    @if (count($projects) === 0)
      <div class="container text-center">
        <br/>
        <p class="lead" id="no-projects">It seems there are no projects here yet.
          If you are the admin, please visit the <a href="dashboard">Dashboard</a>
          and a Data Source to get started.
        </p>
      </div>
    @else

      <div class="home-map">
        <div id="map" style="height:500px;">
        </div>
      </div>

      @foreach ( $projects as $project )
        <div class="row" id="project-{{ $project->id }}">

          <div class="col-md-10">
            <p class="lead" id="title">{{ $project->title }}</p>
            <p class="text-muted" id="desc">{{ $project->description != '' ? $project->description : '[No Description]'; }}</p>
            <p><small>Url:
              <a href="{{ $project->status }}" target="_blank" id="url">{{ $project->status }}</a>
            </small></p>
          </div>

        </div> <!-- /.row -->
        <hr/>
      @endforeach

    @endif

  </div> <!-- /.data-sources-list -->

@stop
