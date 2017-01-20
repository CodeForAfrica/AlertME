@extends('layouts.backend')

@section('content')

  <div class="pages">

    <form action="{{ url('dashboard/pages') }}" method="POST">

      <input type="hidden" name="_token" value="{{ csrf_token() }}">

      <h5>Pages
        <button type="submit" class="btn btn-primary btn-embossed btn-wide"
                style="margin-left: 30px;">Save Changes
        </button>
      </h5>
      <hr/>

      <!-- Nav tabs -->
      <ul class="nav nav-tabs" role="tablist">
        <li role="presentation" class="active"><a href="#home" role="tab" data-toggle="tab">Home</a></li>
        <li role="presentation"><a href="#about" role="tab" data-toggle="tab">About</a></li>
      </ul>
      <br/>

      <!-- Tab panes -->
      <div class="tab-content">
        <!-- Home -->
        <div role="tabpanel" class="tab-pane active" id="home">
          <div class="row">
            <div class="col-md-3">
              <p class="text-muted"><em>This is the page that welcomes your users to the platform.</em></p>

              <p class="text-muted"><a href="/" target="_blank"><span class="fui-link"></span> Link</a></p>
            </div>
            <div class="col-md-8 col-md-offset-1">
              <input id="home[banner][title]" name="home[banner][title]" type="text" placeholder="Title"
                     class="form-control input-hg flat text-center"
                     value="{{ $home->data->banner->title }}"/>
              <input id="home[banner][description]" name="home[banner][description]" type="text" placeholder="Title"
                     class="form-control flat text-center"
                     value="{{ $home->data->banner->description }}"/>

              <hr/>

              <input id="home[how][title]" name="home[how][title]" type="text" placeholder="Title"
                     class="form-control input-hg flat text-center"
                     value="{{ $home->data->how->title }}"/>

              <div class="row">
                @foreach($home->data->how->blurbs as $key => $blurb)
                  <div class="col-sm-4">
                <textarea id="home[how][blurbs][{{$key}}][description]"
                          name="home[how][blurbs][{{$key}}][description]" placeholder="Description"
                          class="form-control flat pages-desc text-center">{{ $blurb->description }}</textarea>
                  </div>
                @endforeach
              </div>

            </div>
          </div>
        </div>

        <!-- About -->
        <div role="tabpanel" class="tab-pane" id="about">
          <div class="row">
            <div class="col-md-3">
              <p class="text-muted"><em>This is the page that speaks to what the platform is about.</em></p>

              <p class="text-muted"><a href="/about" target="_blank"><span class="fui-link"></span> Link</a></p>
            </div>
            <div class="col-md-8 col-md-offset-1">
              <input id="about[title]" name="about[title]" type="text" placeholder="Title"
                     class="form-control input-hg flat" value="{{ $about->data->title }}"/>
          <textarea id="about[description]" name="about[description]" class="form-control flat pages-desc"
                    rows="4" placeholder="Description">{{ $about->data->description }}</textarea>
            </div>
          </div>
        </div>
      </div>

    </form>

  </div> <!-- /.pages -->

@stop

@section('scripts-data')

@stop

@section('scripts')
  <script src="/assets/js/vendor/jquery.elastic.source.js"></script>
  <script src="/assets/js/backend/pages.js"></script>
@stop
