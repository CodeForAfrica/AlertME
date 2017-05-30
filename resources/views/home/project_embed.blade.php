@extends('layouts.base')

@section('title') Project | {{ strlen($project->title) > 80 ? substr($project->title, 0, 80).'...' : $project->title }} @stop

@section('content')

  <div class="project">

    <div class="container">
      <a href="{{ secure_asset('project/'.$project->id) }}" target="_blank">
        <h4>{{ strlen($project->title) > 80 ? substr($project->title, 0, 80).'...' : $project->title }}</h4>
      </a>
      <hr/>

      @if(isset($msg_confirm))
        <div class="alert alert-success" style="padding: 10px 15px;">
          <small><b>Awesome!</b>
            We have sent you an e-mail to confirm your subscription to this project.
            <span class="fui-check-circle pull-right"></span>
          </small>
        </div>
      @elseif(isset($msg_duplicate))
        <div class="alert alert-warning" style="padding: 10px 15px;">
          <small><b>Hmm...</b>
            Seems like you are already subscribed to this project.
            <span class="fui-alert-circle pull-right"></span>
          </small>
        </div>
      @endif

      <div class="row">

        <div class="col-md-5">
          <img src="{{ $map_image_link }}" style="width:100%;"
            class="img-responsive img-rounded"/>
          <br/>
          <!-- Go to www.addthis.com/dashboard to customize your tools -->
          <div class="addthis_sharing_toolbox"></div>
          <!-- Go to www.addthis.com/dashboard to customize your tools -->
          <script type="text/javascript" src="https://s7.addthis.com/js/300/addthis_widget.js#pubid=ra-5481e9a015b90c80" async="async"></script>
          <hr/>
          <a href="{{ secure_asset('project/'.$project->id) }}" target="_blank"
            class="btn btn-block btn-primary btn-embossed">
            <span class="fui-link"></span> View on {{ env('APP_NAME', '#GreenAlert') }}
          </a>
          <br/>
        </div>


        <div class="col-md-7">
          <p>{{$project->description}}</p>
          <hr/>
          <h6>Details</h6>
          <div class="project-details">
            <dl class="dl-horizontal">
              @foreach($project->data as $key => $value)
                <dt><small>{{ $key }}</small></dt>
                <dd><small>{{ empty($value) ? '-' : $value }}</small></dd>
              @endforeach
            </dl>
          </div>
          <br/>
        </div>

      </div> <!-- /.row -->

    </div> <!-- /.container -->

  </div> <!-- /.project -->

  <div class="bg-primary">
    <div class="container">
      <a href="{{ secure_asset('') }}" target="_blank">
        <h4 style="margin-top: 15px;">{{ env('APP_NAME', '#GreenAlert') }}</h4>
      </a>
    </div>
  </div>

@stop

