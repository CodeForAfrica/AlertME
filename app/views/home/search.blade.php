@extends('layouts.frontend')

@section('title') Search @stop

@section('content')

  <div class="about">

    <div class="container">
      <div class="page-header">
        <h3>Search Results
          <small>{{ $projects_count }} results for "{{ Input::get('q') }}"</small>
        </h3>
      </div>

      @foreach ($projects as $project)
        <p>{{$project->title}}</p>
      @endforeach

      <br/>
      <div class="text-center"><div class="pagination">
        <ul>

          {{ $pagination_html }}

        </ul>
      </div></div>

    </div> <!-- /.container -->

  </div> <!-- /.data-sources-list -->

@stop

@section('scripts')
@stop
