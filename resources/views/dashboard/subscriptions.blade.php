@extends('layouts.backend')

@section('content')
  <div class="dashboard-subscriptions">
    <h5>
      Subscriptions
      <!-- <button type="button" class="btn btn-primary btn-embossed btn-wide btn-sm category-add"
        id="category-add" data-toggle="modal" data-target="#editModal" style="margin-left: 30px;">
        <span class="fui-plus"></span> Add
      </button> -->
    </h5>
    <hr/>

    <div class="subscriptions-list">

      @if (count($subscriptions) === 0)
        <p class="lead">It seems you don't have any subscriptions yet.</p>
      @else

        <div class="row">
          <div class="col-md-8">

            @foreach ($subscriptions as $key => $subscription)
              <p>
                <a href="{{ secure_asset('subscriptions/'.$subscription->confirm_token) }}" target="_blank">
                  {{ $subscription->confirm_token }}
                </a>
                <span style="width:10px; display:inline-block;"></span>
                <small>
                  @if ( $subscription->trashed() )
                    <span class="label label-danger">Unsubscribed</span>
                  @elseif ( $subscription->status == 0 )
                    <span class="label label-info">Unconfirmed</span>
                  @elseif ( $subscription->status == 1 )
                    <span class="label label-success">Confirmed</span>
                  @endif
                </small>
                <br/>
                <small>Email: {{ $subscription->user->email }}</small>
              </p>

              <hr/>
            @endforeach

            <div class="text-center">
              {!! with(new \Greenalert\Http\Controllers\FlatUIPresenter($subscriptions))->render() !!}
            </div>
          </div>
          <div class="col-md-4">
            <div class="well">
              <p><b>Overview</b></p>

              <div class="row">
                <div class="col-sm-3">
                  <p><b>Total:</b></p>
                </div>
                <div class="col-sm-9">
                  <p>{{ $subscriptions->total() }}</p>
                </div>
              </div>

            </div>
          </div>
        </div>

      @endif

    </div>
    <!-- /.categories-list -->

    <!-- Modals -->


  </div> <!-- /.dashboard-subscriptions -->

@stop

@section('scripts-data')


@stop

@section('scripts')
  {{--<script src="/assets/js/backend/subscriptions.js"></script>--}}
@stop
