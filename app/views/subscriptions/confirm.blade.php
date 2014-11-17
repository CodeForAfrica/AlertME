@extends('layouts.frontend')

@section('title') Subscription @stop

@section('content')

  <div class="about subscription-confirm">

    <div class="container ">
      <div class="page-header">
        <h3>Subscription</h3>
      </div>

      @if(isset($msg_confirm))
        <div class="alert alert-success" style="padding: 10px 15px;">
          <small>Subscription confirmed! <span class="fui-check-circle pull-right"></span></small>
        </div>
      @elseif(isset($msg_details))
        <div class="alert alert-success" style="padding: 10px 15px;">
          <small>Details updated! <span class="fui-check-circle pull-right"></span></small>
        </div>
      @endif

      <div class="row">
        <div class="col-md-7">
          <p><a href="{{$map_link}}">Projects in this area <span class="fui-arrow-right"></span></a></p>
          <hr/>

          <h5>Updates In This Area</h5>
          <hr>
          <p>There are no updates yet. But you'll definitely be receiving them.</p>


        </div>
        <div class="col-md-5">
          <img src="{{ $map_image_link }}" class="img-rounded img-responsive"
            style="width:602px; height:202px; border:1px solid #ddd;"/>
          <br/><br/>
          <form class="form-horizontal" role="form" action="" method="post">
            <div class="form-group">
              <label for="subscriber-email" class="col-sm-2 control-label">Email</label>
              <div class="col-sm-10">
                <label id="subscriber-email" class="col-sm-2 control-label">{{ $user_email }}</label>
              </div>
            </div>
            <div class="form-group">
              <label for="subscriber-name-" class="col-sm-2 control-label">Name</label>
              <div class="col-sm-10">
                <input type="text" class="form-control" id="subscriber-name"
                  name="fullname" placeholder="Full Name" value="{{ $user->fullname }}">
              </div>
            </div>
            <div class="form-group">
              <div class="col-sm-offset-2 col-sm-10">
                <button type="submit" class="btn btn-primary btn-embossed btn-wide">
                  Update Details
                </button>
              </div>
            </div>
          </form>
        </div>
      </div>

    </div> <!-- /.container -->

  </div> <!-- /.data-sources-list -->

@stop

@section('scripts')
@stop
