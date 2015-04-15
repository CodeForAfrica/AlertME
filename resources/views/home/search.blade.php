@extends('layouts.frontend')

@section('title') Search @stop

@section('content')

  <div class="about">

    <div class="container">
      <div class="page-header">
        <h3>
          Search Results
          <small>{{ $projects_count }} results for "{{ \Input::get('q') }}"</small>
        </h3>
      </div>

      <div class="row">
        <div class="col-md-5">
          <form action="/search" role="search">
            <div class="input-group">
              <input class="form-control" id="navbarInput-01" type="search" placeholder="Search"
                     name="q" value="{{ \Input::get('q') }}">
              <span class="input-group-btn">
                <button type="submit" class="btn"><span class="fui-search"></span></button>
              </span>
            </div>
          </form>
        </div>
      </div>
      <!-- /.row -->

      <hr style="border-top:2px solid #e7e9ec;"/>

      <div class="row">
        <div class="col-md-8">

          @foreach ($projects as $project)
            <p>
              <a href="{{ secure_asset('project/'.$project->id) }}" target="_blank">
                {{ $project->title }}
              </a><br/>
              <small>
                {{ $project->description }} <br/>
                @foreach ($project->categories as $category)
                  <span class="label label-primary">{{ $category->title }}</span>
                @endforeach
              </small>
            </p>
          @endforeach

          <br/>

          <div class="text-center">
            {!! with(new \Greenalert\Http\Controllers\FlatUIPresenter($projects->appends(['q' =>
            \Input::get('q')])))->render() !!}
          </div>

        </div>
        <!-- /.col-md-8 -->

      </div>
      <!-- /.row -->


    </div>
    <!-- /.container -->

  </div> <!-- /.data-sources-list -->

@stop

@section('scripts')
@stop
