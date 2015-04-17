@extends('layouts.backend')

@section('content')

<h5>Home</h5>
<hr/>

<div class="row">
  <div class="col-md-8">
    <p></p>
    <div class="row">
      <div class="col-md-6">
        <div class="panel panel-default">
          <div class="panel-heading">
            <h3 class="panel-title">Projects</h3>
          </div>
          <div class="panel-body">
            <p></p>

            <div role="tabpanel">

              <!-- Nav tabs -->
              <ul class="nav nav-tabs" role="tablist">
                <li role="presentation" class="active">
                  <a href="#projects-popular" aria-controls="home" role="tab" data-toggle="tab">Popular</a>
                </li>
                <li role="presentation">
                  <a href="#projects-latest" aria-controls="profile" role="tab" data-toggle="tab">Latest</a>
                </li>
                <li role="presentation">
                  <a href="#projects-subscribed" aria-controls="messages" role="tab" data-toggle="tab">Top Subscribed</a>
                </li>
              </ul>

              <!-- Tab panes -->
              <div class="tab-content">
                <div role="tabpanel" class="tab-pane active" id="projects-popular">
                  <p><small><em>Popular projects by views.</em></small></p>
                </div>
                <div role="tabpanel" class="tab-pane" id="projects-latest">
                  <p><small><em>Latest projects added.</em></small></p>
                </div>
                <div role="tabpanel" class="tab-pane" id="projects-subscribed">
                  <p><small><em>Most subscribed projects.</em></small></p>
                </div>
              </div>

            </div> <!-- tabpanel -->
          </div>
        </div>
        <div class="panel panel-default">
          <div class="panel-heading">
            <h3 class="panel-title">Comments</h3>
          </div>
          <div class="panel-body">
            Panel content
          </div>
        </div>
      </div> <!-- /.col-md-6 -->
      <div class="col-md-6">
        <div class="panel panel-default">
          <div class="panel-heading">
            <h3 class="panel-title">Subscriptions</h3>
          </div>
          <div class="panel-body">
            Panel content
          </div>
        </div>
        <div class="panel panel-default">
          <div class="panel-heading">
            <h3 class="panel-title">Users</h3>
          </div>
          <div class="panel-body">
            Panel content
          </div>
        </div>
      </div> <!-- /.col-md-6 -->
    </div>
  </div>
  <div class="col-md-4">
    <div class="well">
    </div>
  </div>
</div>

@stop
