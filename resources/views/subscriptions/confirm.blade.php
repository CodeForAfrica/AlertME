@extends('layouts.frontend')

@section('title') Subscription @stop

@section('content')

  <div class="about subscription-confirm">

    <div class="container ">
      <div class="page-header">
        <h3>
          Subscription
          @if ($subscription->trashed())
            <small class="text-danger">[Unsubscribed]</small>
            <button type="button" class="btn btn-wide btn-embossed btn-primary pull-right"
              data-toggle="modal" data-target="#unsubscribeModal">
              Restore Subscription
            </button>
          @else
            <button type="button" class="btn btn-wide btn-embossed btn-default pull-right"
              data-toggle="modal" data-target="#unsubscribeModal">
              Unsubscribe
            </button>
          @endif
        </h3>
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
          <p>
            <a href="{{ $map_link }}" target="_blank">
              Projects in this area <span class="fui-arrow-right"></span>
            </a>
          </p>
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
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
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

      <br/>

    </div> <!-- /.container -->

    <!-- MODALS -->
    <!-- Unsubscribe -->
    <div class="modal fade" id="unsubscribeModal" tabindex="-1" role="dialog"
      aria-labelledby="unsubscribeModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">
              <span aria-hidden="true">&times;</span><span class="sr-only">Close</span>
            </button>
            <h4 class="modal-title" id="unsubscribeModalLabel">
              {{ $subscription->trashed() ? 'Restore Subscription' : 'Unsubscribe' }}
            </h4>
          </div>
          <div class="modal-body">
              Are you sure you want to {{ $subscription->trashed() ? 'restore your subscription' : 'unsubscribe' }}?
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default btn-embossed btn-wide" data-dismiss="modal">
              Close
            </button>
            <button type="button"
              class="btn btn-{{ $subscription->trashed() ? 'primary' : 'danger' }} btn-embossed btn-wide unsubscribe-btn"
              data-loading-text="{{ $subscription->trashed() ? 'Restoring' : 'Unsubscribing' }}...">
              {{ $subscription->trashed() ? 'Restore Subscription' : 'Unsubscribe' }}
            </button>            
          </div>
        </div>
      </div>
    </div>

  </div> <!-- /.subscription-confirm -->

@stop

@section('scripts')
  <script type="text/javascript">
    $('.unsubscribe-btn').on('click', function () {
      var $btn = $(this).button('loading')
      $.ajax({
        url: "{{ secure_asset('api/v1/subscriptions/'.$subscription->id) }}",
        type: 'DELETE',
        data: {
          '_token': '{{ csrf_token() }}',
          {{ $subscription->trashed() ? '"restore": 1,' : '' }}
          'confirm_token': '{{ $subscription->confirm_token }}'
        }
      }).done(function(response) {
        location.reload();
      });
    })
  </script>
@stop
