@extends('layouts.frontend')

@section('styles')
<style>
  html, body {
    margin: 0;
    height: 100%;
  }
</style>
@stop

@section('content')

  <div class="home-map">

    @if (count($projects) === 0)
      <div class="container text-center">
        <br/>
        <p class="lead" id="no-projects">It seems there are no projects here yet.
          If you are the admin, please visit the <a href="dashboard">Dashboard</a>
          and a Data Source to get started.
        </p>
      </div>
    @else

      <div class="map-wrapper">

        <div id="map"></div> <!-- /#map -->

        <div class="map-list bg-primary text-center">
          <button class="map-ctrl-alert btn btn-wide btn-embossed btn-primary"
            data-toggle="modal" data-target="#subscriptionModal">
            <span class="fa fa-globe"></span> Subscribe for alerts in this area
          </button>
          <hr/>
          <div class="map-filter container-fluid text-left">
            @if (count($categories) != 0)
              <p><b>Categories</b></p>

              <div class="btn-group btn-group-justified filter-cat" data-toggle="buttons">
                
                <label class="btn btn-inverse cat-sel cat-all" data-cat-id="all">
                  <input type="radio" name="options" id="cat-all"> <i class="fa fa-globe fa-2x"></i><br/> All
                </label>

                @if (count($categories) > 3 )
                  @for ($i = 0; $i < 2; $i++)
                    <label class="btn btn-inverse cat-sel" data-cat-id="{{ $categories[$i]->id }}">
                      <input type="radio" name="options" id="cat-{{ $i }}">
                        <i class="fa fa-dot-circle-o fa-2x"></i><br/>
                        {{ $categories[$i]->title }}
                    </label>
                  @endfor
                  <div class="btn-group" data-toggle="buttons">
                    <label class="btn btn-inverse dropdown-toggle cat-other" data-toggle="dropdown">
                      <input type="radio" name="options" id="cat-other"> <i class="fa fa-ellipsis-h fa-2x"></i><br/>Other
                    </label>
                    <ul class="dropdown-menu" role="menu">
                      @for ($i = 2; $i < count($categories); $i++)
                        <li>
                          <a href="#" class="cat-sel" data-cat-id="{{ $categories[$i]->id }}">
                            {{ $categories[$i]->title }}
                          </a>
                        </li>
                      @endfor
                    </ul>
                  </div>
                @else
                  @for ($i = 0; $i < count($categories); $i++)
                    <label class="btn btn-inverse cat-sel" data-cat-id="{{ $categories[$i]->id }}">
                      <input type="radio" name="options" id="option2">
                        <i class="fa fa-dot-circle-o fa-2x"></i><br/>
                        {{ $categories[$i]->title }}
                    </label>
                  @endfor
                @endif
              </div>
            @endif
          </div> <!-- /.map-filter.container-fluid.text-left -->
        </div> <!-- /.map-list -->

      </div> <!-- /.map-wrapper -->

      <div class="map-controls pull-right">
        <div class="btn-group-vertical">
          <button id="map-ctrl-zoom-in" class="btn btn-sm btn-embossed btn-primary">+</button>
          <button id="map-ctrl-zoom-out" class="btn btn-sm btn-embossed btn-primary">-</button>
        </div>
        <!-- <br/><br/>
        <button class="map-ctrl-search btn btn-sm btn-embossed btn-primary">
          <small><span class="fa fa-search"></span></small></button>
        <br/><br/>
        <button class="map-ctrl-alert btn btn-sm btn-embossed btn-primary"
          data-toggle="modal" data-target="#alertModal">
          #</button> -->
      </div> <!-- /.map-controls -->

      <!-- <div class="map-loading text-center">
        <div id="info">
          <p class="lead"><i class="fa fa-globe fa-spin"></i> Loading map...</p>
        </div>
      </div> --> <!-- /.map-loading -->



      <!-- MODALS -->

      <!-- Subscribe Modal -->
      <div class="modal" id="subscriptionModal" tabindex="-1" role="dialog"
        aria-labelledby="subscriptionModalLabel" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">

            <div class="modal-header">
              <button type="button" class="close close-modal" data-dismiss="modal">
                <span aria-hidden="true">&times;</span><span class="sr-only">Close</span>
              </button>
              <h4 class="modal-title" id="subscriptionModalLabel">Subscribe for Alerts</h4>
            </div><!-- /.modal-header -->

            <div class="modal-body">

              <img id="map-alert" src="#" class="img-rounded img-responsive" />
              <hr/>

              <p>Enter your e-mail address below to receive alerts in this area.</p>
              <div class="form-horizontal" role="form">
                <div class="form-group map-alert-email">
                  <label for="map-alert-email" class="col-sm-2 control-label">Email</label>
                  <div class="col-sm-8">
                    <input type="email" class="form-control" id="map-alert-email" placeholder="Email">
                  </div>
                </div>
                <div class="form-group hidden">
                  <label for="map-alert-bounds" class="col-sm-2 control-label">Area</label>
                  <div class="col-sm-8">
                    <input type="email" class="form-control" id="map-alert-bounds" placeholder="Email">
                  </div>
                </div>
              </div>

              <!-- ALERTS -->
              <!-- Loading -->
              <div class="alert alert-info text-center" role="alert" style="display:none;">
                <small>
                  <i class="fa fa-circle-o-notch fa-spin"></i>
                  Subscribing... You'll soon be receiving updates from this area.
                </small>
              </div>
              <!-- Success -->
              <div class="alert alert-success alert-dismissible" role="alert" style="display:none;">
                <button type="button" class="close" data-dismiss="alert">
                  <span aria-hidden="true">&times;</span><span class="sr-only">Close</span>
                </button>
                <small><span class="fui-check-circle"></span>
                  Awesome! Check your e-mail to confirm subscription.
                </small>
              </div>
              <!-- Warning -->
              <div class="alert alert-warning alert-dismissible" role="alert" style="display:none;">
                <button type="button" class="close" data-dismiss="alert">
                  <span aria-hidden="true">&times;</span><span class="sr-only">Close</span>
                </button>
                <small>
                  <span class="fui-alert-circle"></span>
                  <b>Hmm...</b>
                  <span class="msg-error duplicate" style="display:none;"><br/>
                    Seems like you are already subscribed to this area.
                  </span>
                </small>
              </div>
              <!-- Error -->
              <div class="alert alert-danger alert-dismissible" role="alert" style="display:none;">
                <button type="button" class="close" data-dismiss="alert">
                  <span aria-hidden="true">&times;</span><span class="sr-only">Close</span>
                </button>
                <small>
                  <span class="fui-alert-circle"></span>
                  <b>Oops!</b> Looks like something went wrong.
                  <span class="msg-error email" style="display:none;"><br/>
                    Please check the e-mail address entered.
                  </span>
                  <span class="msg-error limit" style="display:none;"><br/>
                    You've reached the max number of alerts registration.
                  </span>
                  <span class="msg-error reload" style="display:none;"><br/>
                    Please <a href="javascript:location.reload();">reload</a> the page and try again.
                  </span>
                </small>
              </div>

            </div><!-- /.modal-body -->

            <div class="modal-footer">
              <button type="button"
                class="close-modal btn btn-embossed btn-default" data-dismiss="modal">Close</button>
              <button type="button"
                class="create-alert-btn btn btn-embossed btn-primary btn-wide"># Subscribe</button>
            </div><!-- /.modal-footer -->

          </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
      </div><!-- /.modal -->

      <!-- Subscribe zoom error modal -->
      <div class="modal fade" id="modal-subscribe-error" tabindex="-1" role="dialog"
        aria-labelledby="modal-subscribe-error-label" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-body text-center">
              <p>
                <span class="fa fa-rocket fa-4x"></span><br/>
                Unfortunately we cannot create subscriptions this far out.<br/>
                Zoom in closer to get this to work.
              </p>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-default btn-embossed" data-dismiss="modal">Close</button>
              <button type="button" class="btn btn-primary btn-embossed btn-wide"
                onclick="javascript:map.setZoom(11);$('#modal-subscribe-error').modal('hide');">Zoom In</button>
            </div>
          </div>
        </div>
      </div> <!-- /.modal -->


    @endif

  </div> <!-- /.home-map -->

@stop

@section('scripts-data')
  var categories = {{ $categories != NULL ? $categories : 'false' }};
  pahali.projects.set( {{ $projects_all->toJSON() }} );
@stop

@section('scripts')
  <script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?libraries=places"></script>

  <script src='https://api.tiles.mapbox.com/mapbox.js/v2.1.2/mapbox.js'></script>
  <link href='https://api.tiles.mapbox.com/mapbox.js/v2.1.2/mapbox.css' rel='stylesheet' />

  <link href="{{ secure_asset('assets/css/MarkerCluster.css') }}" rel="stylesheet" />
  <link href="{{ secure_asset('assets/css/MarkerCluster.Default.css') }}" rel="stylesheet" />
  <script src="{{ secure_asset('assets/js/vendor/leaflet.markercluster.js') }}"></script>
  
  <script src="{{ secure_asset('assets/js/frontend/routes.js') }}"></script>
  <script src="{{ secure_asset('assets/js/frontend/map.js') }}"></script>
  <script src="{{ secure_asset('assets/js/frontend/map-categories.js') }}"></script>
  <script src="{{ secure_asset('assets/js/frontend/map-subscribe.js') }}"></script>

@stop
