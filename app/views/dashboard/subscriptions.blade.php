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
      <p class="lead">It seems there are no subscriptions yet.</p>
    @else

      @foreach ($subscriptions as $subscription)
        {{ $subscription->id }}
        <hr/>
      @endforeach

      <div class="text-center">
        {{ $subscriptions->links() }}
      </div>

    @endif

  </div> <!-- /.categories-list -->

  <!-- Modals -->


</div> <!-- /.dashboard-subscriptions -->

</div> <!-- /.col-md-10 -->
</div> <!-- /.row -->
@stop

@section('scripts-data')


@stop

@section('scripts')
  <script src="/assets/js/backend/subscriptions.js"></script>
@stop
