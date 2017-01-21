@extends('layouts.backend')

@section('content')

  <div class="data-sources">

    <h5>
      Data Sources
      <button type="button" class="btn btn-info btn-embossed btn-wide btn-sm"
              id="btn-sync-modal" data-toggle="modal" data-target="#syncModal">
        <span class="fui-radio-unchecked"></span> Sync
      </button>
      <button type="button" class="btn btn-primary btn-embossed btn-wide btn-sm data-source-add"
              id="data-source-add" data-toggle="modal" data-target="#editModal">
        <span class="fui-plus"></span> Add
      </button>
    </h5>

    <hr/>

    <div class="data-sources-list">

      @if (count($datasources) === 0)
        <p class="lead" id="no-data-sources">It seems you don't have any data sources yet.
          <button type="button" class="btn btn-primary btn-sm data-source-add"
                  id="data-source-add-first" data-toggle="modal" data-target="#editModal">
            <span class="fui-plus"></span> Add
          </button>
          some now to get started.
        </p>
      @else

        @foreach ( $datasources as $datasource )
          <div class="row data-source" id="data-source-{{ $datasource->id }}">

            <div class="col-md-10">
              <p class="lead" id="title">{{ $datasource->title }}</p>

              <p class="text-muted"
                 id="desc">{{ $datasource->description != '' ? $datasource->description : '[No Description]' }}</p>

              <p>
                <small>Url:
                  <a href="{{ $datasource->url }}" target="_blank" id="url">{{ $datasource->url }}</a>
                </small>
              </p>
            </div>
            <!-- /.col-md-10 -->

            <div class="col-md-2 text-left">
              <p>
                <button type="button" class="btn btn-link btn-sm" alt="{{ $datasource->id }}"
                        id="btn-configure-{{ $datasource->id }}" data-toggle="modal" data-target="#configModal">
                  <span class="fui-cmd"></span> Configure
                </button>
              </p>
              <p>
                <button type="button" class="btn btn-link btn-sm" alt="{{ $datasource->id }}"
                        id="edit-data-source-{{ $datasource->id }}" data-toggle="modal" data-target="#editModal">
                  <span class="fui-new"></span> Edit
                </button>
              </p>
              <p>
                <button type="button" class="btn btn-link btn-sm" alt="{{ $datasource->id }}"
                        id="del-data-source-{{ $datasource->id }}" data-toggle="modal" data-target="#deleteModal">
                  <span class="text-danger"><span class="fui-trash"></span> Delete</span>
                </button>
              </p>
            </div>
            <!-- /.col-md-2 -->

          </div> <!-- /.row -->
          <hr/>
        @endforeach

      @endif

    </div>
    <!-- /.data-sources-list -->


    <!-- Modals -->

    <!-- Add + edit modal -->
    <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel"
         aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">

          <div class="modal-header">
            <button type="button" class="close close-modal" data-dismiss="modal" id="close_x-editModal">
              <span aria-hidden="true">&times;</span><span class="sr-only">Close</span>
            </button>
            <h4 class="modal-title" id="editModalLabel">Edit Data Source</h4>
          </div>

          <div class="modal-body">
            <div class="form-group">
              <label for="title">Title</label>
              <input type="text" class="form-control input-hg" id="title" placeholder="Title">
            </div>
            <div class="form-group">
              <label for="desc">Description</label>
              <input type="text" class="form-control input-lg" id="desc" placeholder="Description">
            </div>
            <div class="clearfix"></div>
            <div class="form-group">
              <label for="url">Url</label>
              <input type="text" class="form-control" id="url" placeholder="http://">
            </div>
            <small class="text-danger" id="error" style="display:none;">Error:</small>
            <div class="clearfix"></div>
          </div>

          <div class="modal-footer">
            <button type="button" class="btn btn-default btn-embossed btn-wide close-modal" data-dismiss="modal"
                    id="close-editModal">Close
            </button>
            <button type="button" class="btn btn-primary btn-embossed btn-wide" id="save-data-source">Save changes
            </button>
          </div>

        </div>
      </div>
    </div>


    <!-- Delete Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel"
         aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">

          <div class="modal-header">
            <button type="button" class="close close-modal" data-dismiss="modal">
              <span aria-hidden="true">&times;</span><span class="sr-only">Close</span>
            </button>
            <h4 class="modal-title" id="deleteModalLabel">Delete Data Source</h4>
          </div>

          <div class="modal-body">
            <p>Are you sure you would like to delete the following data source?</p>

            <div class="well">
              <p><b><span id="del_title"></span></b></p>

              <p><span id="del_desc"></span></p>

              <p>
                <small><span id="del_url"></span></small>
              </p>
            </div>

            <p class="text-center" id="loading-delete" style="display:none;">
              <i class="fa fa-circle-o-notch fa-spin"></i> Deleting this Data Source and all its contents...<br/>
              <small>This might take a few minutes</small>
            </p>
          </div>
          <!-- /.modal-body -->

          <div class="modal-footer">
            <button type="button" class="btn btn-default btn-embossed btn-wide close-modal" data-dismiss="modal">
              Cancel
            </button>
            <button type="button" class="btn btn-danger btn-embossed btn-wide" id="btn-delete">Delete Data Source
            </button>
          </div>

        </div>
        <!-- /.modal-content -->
      </div>
    </div>
    <!-- /.modal -->


    <!-- Configure Modal -->
    <div class="modal fade" id="configModal" tabindex="-1" role="dialog" aria-labelledby="configModalLabel"
         aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">

          <div class="modal-header">
            <button type="button" class="close close-modal" data-dismiss="modal">
              <span aria-hidden="true">&times;</span><span class="sr-only">Close</span>
            </button>
            <h4 class="modal-title" id="configModalLabel">Configure Data Source</h4>
          </div>

          <div class="modal-body">
            <p><em>This is where you tell the system how your data source is structured.</em></p>

            <div class="well">


              <!-- Title -->
              <div class="row config-title">
                <div class="col-sm-3">
                  <p><b>Data Source</b></p>
                </div>
                <div class="col-sm-9">
                  <p id="config-title"></p>
                </div>
                <!-- /.col-sm-9 -->
              </div>
              <!-- /.row .config-title -->


              <!-- Columns -->
              <div class="row config-columns">
                <div class="col-sm-3">
                  <p><b>Columns</b></p>
                </div>
                <div class="col-sm-9">
                  <p class="config-columns-list" style="display:none;"></p>

                  <!-- Alerts -->
                  <div class="alert alert-info" role="alert" style="display:none;">
                    <span class="fui-alert-circle"></span> We are still fetching this data source's details...<br/>
                    <button class="btn btn-sm btn-link btn-config-refresh">
                      <i class="fa fa-refresh"></i> Refresh to check for update
                    </button>
                  </div>
                  <div class="alert alert-danger" role="alert" style="display:none;">
                    <p>
                      <span class="fui-alert-circle"></span> This data source has an error.
                      Please check that the data is well structured, delete it and add it again.<br/>
                    </p>
                  </div>

                </div>
                <!-- /.col-sm-9 -->
              </div>
              <!-- /.row .config-columns -->


              <!-- Config Screen -->
              <div class="row config-screen" style="display:none;">
                <div class="col-sm-3">
                  <p><b>Configuration</b></p>
                </div>
                <div class="col-sm-9">

                  <!-- Alerts -->
                  <div class="alert alert-success alert-ready" role="alert" style="display:none;">
                    <span class="fui-alert-circle"></span> This data source is ready for configuriation.<br/>
                    <button class="btn btn-sm btn-link" id="btn-config-new">
                      <span class="fui-cmd"></span> Configure now
                    </button>
                  </div>

                  <!-- List configuration -->
                  <div class="config-list" style="display:none;">
                    <p>Platform required columns and related data source columns:</p>
                    <table class="table">
                      <thead>
                      <tr>
                        <th>Platform</th>
                        <th>Data Source</th>
                      </tr>
                      </thead>
                      <tbody>
                      <tr>
                        <td>ID</td>
                        <td>'+ds_cols[config_id]+'</td>
                      </tr>
                      <tr>
                        <td>Title</td>
                        <td>'+ds_cols[config_title]+'</td>
                      </tr>
                      <tr>
                        <td>Description</td>
                        <td>'+ds_cols[config_desc]+'</td>
                      </tr>
                      <tr>
                        <td>Geo Type</td>
                        <td></td>
                      </tr>
                      <tr>
                        <td>Geo Lat</td>
                        <td></td>
                      </tr>
                      <tr>
                        <td>Geo Lng</td>
                        <td></td>
                      </tr>
                      <tr>
                        <td>Geo Address</td>
                        <td></td>
                      </tr>
                      <tr>
                        <td>Status</td>
                        <td>'+ds_cols[config_status]+'</td>
                      </tr>
                      </tbody>
                    </table>
                  </div>
                  <!-- /.config-list -->

                  <!-- Edit configuration -->
                  <div class="config-edit" style="display:none;">
                    <div class="alert alert-success">
                      <p>Columns configuration for each project\row of data.</p>

                      <p>
                        <b>ID</b>
                        <small><em>(Important! Unique identifier)</em></small>
                        <br/>
                        <select id="config-sel-id"
                                class="form-control select select-primary select-block mbl">
                        </select>
                      </p>

                      <p>
                        <b>Title</b><br/>
                        <select id="config-sel-title"
                                class="form-control select select-primary select-block mbl">
                        </select>
                      </p>

                      <p>
                        <b>Description</b><br/>
                        <select id="config-sel-desc"
                                class="form-control select select-primary select-block mbl">
                        </select>
                      </p>

                      <p>
                        <b>Geolocation</b><br/>

                      <div class="row">
                        <div class="col-xs-3">Type:</div>
                        <div class="col-xs-9">
                          <label class="radio config-radio-geo-type">
                            <input type="radio" name="config-sel-geo-type" id="config-sel-geo-type-lat-lng"
                                   value="lat_lng" data-toggle="radio" checked="">
                            Lat + Lng
                          </label>
                          <label class="radio config-radio-geo-type">
                            <input type="radio" name="config-sel-geo-type" id="config-sel-geo-type-add"
                                   value="address" data-toggle="radio">
                            Address
                          </label>
                        </div>
                      </div>
                      <div id="config-edit-geo-type">
                        <div class="row config-edit-geo-type-lat-lng">
                          <div class="col-xs-3">Lat:</div>
                          <div class="col-xs-9">
                            <select id="config-sel-geo-lat"
                                    class="form-control select select-primary mbl">
                            </select>
                          </div>
                        </div>
                        <div class="row config-edit-geo-type-lat-lng">
                          <div class="col-xs-3">Long:</div>
                          <div class="col-xs-9">
                            <select id="config-sel-geo-lng"
                                    class="form-control select select-primary mbl">
                            </select>
                          </div>
                        </div>
                        <div class="row config-edit-geo-type-add" style="display:none;">
                          <div class="col-xs-3">Address:</div>
                          <div class="col-xs-9">
                            <select id="config-sel-geo-add"
                                    class="form-control select select-primary mbl">
                            </select>
                          </div>
                        </div>
                      </div>
                      <!-- /#geo_type -->
                      </p>
                      <p>
                        <b>Status</b><br/>
                        <select id="config-sel-status"
                                class="form-control select select-primary select-block mbl">
                        </select>
                      </p>

                      <p id="config-edit-error" class="text-danger alert alert-danger" style="display:none;">
                        <small><b>Error:</b> Please define all columns.</small>
                      </p>
                    </div>
                  </div>
                  <!-- /.config-edit -->

                </div>
                <!-- /.col-sm-9 -->
              </div>
              <!-- /.row .config-screen -->

              <p class="text-center" id="config-loading">
                <i class="fa fa-circle-o-notch fa-spin"></i><br/>Loading configuration...
              </p>

            </div>
          </div>

          <div class="modal-footer">
            <button type="button" class="btn btn-default btn-embossed btn-wide close-modal"
                    data-dismiss="modal">Close
            </button>

            <button type="button" class="btn btn-info btn-embossed btn-wide"
                    id="btn-config-edit" style="display:none;">Edit
            </button>
            <button type="button" class="btn btn-primary btn-embossed btn-wide"
                    id="btn-config-save" style="display:none;">Save
            </button>
          </div>

        </div>
      </div>
    </div>


    <!-- Sync Modal -->
    <div class="modal fade" id="syncModal" tabindex="-1" role="dialog"
         aria-labelledby="syncModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">

          <div class="modal-header">
            <button type="button" class="close close-modal" data-dismiss="modal">
              <span aria-hidden="true">&times;</span><span class="sr-only">Close</span>
            </button>
            <h4 class="modal-title" id="syncModalLabel">Sync Data Sources</h4>
          </div>

          <div class="modal-body">
            <p>We will sync the following data sources:</p>

            <div class="well sync-screen">

              <ol class="sync-list" style="display:none;"></ol>

              <!-- Alerts -->
              <div class="alert alert-danger" style="display:none;">
                <p><span class="fui-alert-circle"></span>
                  It seems you don't have any data sources yet.
                  <button type="button" class="btn btn-primary btn-sm">
                    <span class="fui-plus"></span> Add
                  </button>
                  some now to get started.
                </p>
              </div>
              <div class="alert alert-warning" style="display:none;">
                <p>
                  <span class="fui-alert-circle"></span>
                  <b>Oops:</b> There doesn't seem to be any data sources to sync at this moment. Please
                  <span class="text-primary"><span class="fui-cmd"></span>Configure</span>
                  some to be able to sync data.
                </p>
              </div>

            </div>
            <!-- /. -->
            <p class="text-muted"><em>
                If you don't see the data source you want to sync in the list above,
                make sure it is configured. To do so, click the <span class="text-primary">
            <span class="fui-cmd"></span>Configure</span> link next to it.
              </em></p>
          </div>

          <div class="modal-footer">
            <button type="button" class="btn btn-default btn-embossed btn-wide close-modal"
                    data-dismiss="modal">Cancel
            </button>
            <button type="button" class="btn btn-info btn-embossed btn-wide"
                    id="btn-sync">Sync Data Sources
            </button>
          </div>

        </div>
      </div>
    </div>

  </div> <!-- /.data-sources -->

@stop

@section('scripts-data')

  var data_sources = {!! $datasources !!};
  $.extend( pahali.datasources, {!! $datasources !!} );

  var edit_id = 0;
  var config_data = new Object();
  var data_source_columns = [];
  var ds_config = new Object();

@stop

@section('scripts')
  <script src="{{ asset('assets/js/backend/datasources.js') }}"></script>
  <script src="{{ asset('assets/js/backend/datasources-configure.js') }}"></script>
  <script src="{{ asset('assets/js/backend/datasources-sync.js') }}"></script>
@stop
