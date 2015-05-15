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

        <button class="btn btn-primary pull-right" type="button" data-toggle="collapse" data-target="#collapse-map-list" aria-expanded="false" aria-controls="collapse-map-list" id="collapse-map-list-btn-open"
            style="position:absolute; top:92px; left: -10px; z-index:999999; display:none;">
          <span class="fui-arrow-right"></span>
        </button>

        <div class="map-list bg-primary text-center collapse width container-fluid" id="collapse-map-list"
            style="overflow-y:scroll; overflow-x:hidden; max-width:100%;">
          <div class="row">
            <div class="col-xs-11">
              <div class="search-geo">
                <div class="input-group">
                  <span class="input-group-btn">
                    <button class="btn" data-toggle="tooltip" data-placement="bottom" title="Auto-complete powered">
                      <span class="fa fa-globe fa-lg"></span>
                    </button>
                  </span>
                  <input class="form-control" placeholder="Province, town, city or region..."
                    name="search-geo" id="search-geo">
                </div>

                <p>
                  <button class="btn btn-link" style="color:#fff;" autocomplete="off"
                    id="search-my-geo" data-loading-text='<i class="fa fa-crosshairs fa-spin"></i> Locating you...'>
                    <span class="fa fa-crosshairs"></span> Use my location
                  </button>
                </p>
              </div> <!-- /.search-geo -->

              <p id="loading-geo" style="display:none;" >
                <i class="fa fa-crosshairs fa-spin"></i>
                Finding projects in this area...
              </p>

              <div class="alerts text-left">
                <div class="alert alert-warning alert-dismissible" role="alert"
                    style="padding: 10px 35px 10px 15px; display:none;" id="search-my-geo-alert">
                  <button type="button" class="close" data-dismiss="alert">
                    <span aria-hidden="true">&times;</span><span class="sr-only">Close</span>
                  </button>
                  <strong>Geolocation Failed</strong><br/>
                  <small>Oops! It seems your are not in <em>South Africa</em>. Try searching for a location instead.</small>
                </div>

                <div class="alert alert-danger alert-dismissible" role="alert"
                    style="padding: 10px 35px 10px 15px; display:none;" id="search-my-geo-alert-denied">
                  <button type="button" class="close" data-dismiss="alert">
                    <span aria-hidden="true">&times;</span><span class="sr-only">Close</span>
                  </button>
                  <strong>Geolocation Failed</strong><br/>
                  <small>
                    It seems you haven't enabled <em>geolocation</em> in your browser. Fortunately you can fix this.<br/>
                    <strong>Learn more:</strong> 
                      <a href="https://support.google.com/chrome/answer/142065?hl=en" target="_blank">Chrome</a> |
                      <a href="https://www.mozilla.org/en-US/firefox/geolocation/" target="_blank">Firefox</a>
                  </small>
                </div>
              </div> <!-- /.alerts -->

            </div> <!-- /.col-xs-11 -->

            <div class="col-xs-1">
              <button class="btn btn-primary pull-right" type="button" data-toggle="collapse" data-target="#collapse-map-list" aria-expanded="false" aria-controls="collapse-map-list" id="collapse-map-list-btn-close"
                  style="margin-right:-25px;">
                <span class="fui-arrow-left"></span>
              </button>
            </div>
          </div> <!-- /.row -->

          <hr/>

          <button class="map-ctrl-alert btn-block btn btn-lg btn-embossed btn-primary"
            data-toggle="modal" data-target="#subscriptionModal">
            # Subscribe for alerts in this area
          </button>

          <hr/>

          <div class="map-filter text-left">
            @if (count($categories) != 0)
              <p><b>Category filter:</b></p>

              <div class="filter-cat" style="overflow-x:scroll;">
                <div class="btn-group btn-group-lg" data-toggle="buttons"
                    style="white-space: nowrap; border-radius: 6px; border: 2px solid #fff;">
                  
                  <label class="btn btn-inverse cat-sel cat-all" data-cat-id="all"
                      style="float: none; display: inline-block;">
                    <input type="radio" name="options" id="cat-all"> <i class="fa fa-globe fa-1x"></i> All
                  </label>@for ($i = 0; $i < count($categories); $i++)<label
                    class="btn btn-inverse cat-sel" data-cat-id="{{ $categories[$i]->id }}"
                    style="float:none; display:inline-block;">
                      <input type="radio" name="options" id="option2"> {{ $categories[$i]->title }}
                  </label>@endfor

                </div>
              </div> <!-- /.filter-cat -->
            @endif
          </div> <!-- /.map-filter.text-left -->

          <hr/>

          <div class="text-left">
            <p><b>Share this map:</b></p>
            <!-- Go to www.addthis.com/dashboard to customize your tools -->
            <div class="addthis_sharing_toolbox"></div>
            <!-- Go to www.addthis.com/dashboard to customize your tools -->
            <script type="text/javascript" src="https://s7.addthis.com/js/300/addthis_widget.js#pubid=ra-5481e9a015b90c80" async="async"></script>
          </div> <!-- /.text-left -->

        </div> <!-- /.map-list -->

      </div> <!-- /.map-wrapper -->

      <div class="map-controls pull-right">
        <div class="btn-group-vertical">
          <button id="map-ctrl-zoom-in" class="btn btn-sm btn-embossed btn-primary">+</button>
          <button id="map-ctrl-zoom-out" class="btn btn-sm btn-embossed btn-primary">-</button>
        </div>
      </div> <!-- /.map-controls -->

      <!-- <div class="map-loading text-center">
        <div id="info">
          <p class="lead"><i class="fa fa-globe fa-spin"></i> Loading map...</p>
        </div>
      </div> --> <!-- /.map-loading -->



      <!-- MODALS -->

      <!-- Subscribe Modal -->
      <div class="modal fade" id="subscriptionModal" tabindex="-1" role="dialog"
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

              <img id="map-alert" data-src="holder.js/600x200/#1ABC9C:#fff/text:Loading map..." class="img-rounded img-responsive"
                style="width:100%; height:200px;"/>
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
  var categories = {!! $categories != NULL ? $categories : 'false' !!};
  pahali.categories.set( {!! $categories->toJSON() !!} );
  pahali.projects.set( {!! $projects_all->toJSON() !!} );
@stop

@section('scripts')
  
  <script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?libraries=places"></script>

  <script src='https://api.tiles.mapbox.com/mapbox.js/v2.1.4/mapbox.js'></script>
  <link href='https://api.tiles.mapbox.com/mapbox.js/v2.1.4/mapbox.css' rel='stylesheet' />

  <link href="{{ secure_asset('assets/css/_bower.leaflet.css') }}" rel="stylesheet" />
  <script src="{{ secure_asset('assets/js/_bower.leaflet.js') }}"></script>
  
  <script src="{{ secure_asset('assets/js/frontend/routes.js') }}"></script>
  <script src="{{ secure_asset('assets/js/frontend/map.js') }}"></script>
  <script src="{{ secure_asset('assets/js/frontend/map-categories.js') }}"></script>
  <script src="{{ secure_asset('assets/js/frontend/map-subscribe.js') }}"></script>

  <script src="{{ secure_asset('assets/js/frontend/map-search.js') }}"></script>

  <script type="text/javascript">
    window.onload = function () {
      if($('body').width() > ($('#collapse-map-list').width() * 2)) {
        $('#collapse-map-list').collapse('show');
      } else {
        $('#collapse-map-list-btn-open').show();
      }
      
      $('#collapse-map-list').on('hidden.bs.collapse', function () {
        $('#collapse-map-list-btn-open').fadeIn();
      });
      $('#collapse-map-list').on('show.bs.collapse', function () {
        $('#collapse-map-list-btn-open').hide();
      });
    }
  </script>

@stop
