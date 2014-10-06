@extends('layouts.frontend')

@section('content')

  <div class="projects-list">

    @if (count($projects) === 0)
      <p class="lead" id="no-data-sources">It seems you don't have any data sources yet.
        <button type="button" class="btn btn-primary btn-sm data-source-add"
          id="data-source-add-first" data-toggle="modal" data-target="#editModal">
          <span class="fui-plus"></span> Add</button>
        some now to get started.
      </p>
    @else

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
