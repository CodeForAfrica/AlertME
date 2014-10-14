@extends('layouts.backend')

@section('content')

<div class="pages">
  {{ Form::open(array('url' => 'dashboard/pages')) }}

  <h5>Pages
    <button type="submit" class="btn btn-primary btn-embossed btn-wide"
      style="margin-left: 30px;">Save Changes</button>
  </h5>
  <hr/>

  <div class="row">
    <div class="col-md-3">
      <h6>About</h6>
      <p class="text-muted"><em>This is the page that speaks to what the platform is about.</em></p>
      <p class="text-muted"><a href="/about" target="_blank"><span class="fui-link"></span> Link</a></p>
    </div>
    <div class="col-md-8 col-md-offset-1">
      <input id="about_title" name="about_title" type="text" placeholder="Title"
        class="form-control input-hg flat" value="{{ $about->title }}" />
      <textarea id="about_desc" name="about_desc" class="form-control flat"
        rows="4" placeholder="Description">{{ $about->description }}</textarea>
    </div>
  </div>

  {{ Form::close() }}

</div> <!-- /.about -->

</div> <!-- /.col-md-10 -->
</div> <!-- /.row -->
@stop

@section('scripts-data')

@stop

@section('scripts')
  <script src="/assets/js/vendor/jquery.elastic.source.js"></script>
  <script src="/assets/js/backend/pages.js"></script>
@stop
