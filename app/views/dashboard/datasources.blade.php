@extends('layouts.backend')

@section('content')

<div class="data-sources">

  <h5>
    Data Sources
    <button type="button" class="btn btn-info btn-embossed btn-wide btn-sm"
      id="data-source-sync" data-toggle="modal" data-target="#syncModal">
      <span class="fui-radio-unchecked"></span> Sync
    </button>
    <button type="button" class="btn btn-primary btn-embossed btn-wide btn-sm"
      id="data-source-add" data-toggle="modal" data-target="#editModal">
      <span class="fui-plus"></span> Add
    </button>
  </h5>

  <hr/>

  <div class="data-sources-list">

    @if (count($datasources) === 0)
      <p class="lead" id="no-data-sources">It seems you don't have any data sources yet.
        <button type="button" class="btn btn-primary btn-sm"
          id="data-source-add-first" data-toggle="modal" data-target="#editModal">
          <span class="fui-plus"></span> Add</button>
        some now to get started.
      </p>
    @else

      @foreach ( $datasources as $datasource )
        <div class="row" id="data-source-{{ $datasource->id }}">

          <div class="col-md-10">
            <p class="lead" id="title">{{ $datasource->title }}</p>
            <p class="text-muted" id="desc">{{ $datasource->description != '' ? $datasource->description : '[No Description]'; }}</p>
            <p><small>Url:
              <a href="{{ $datasource->url }}" target="_blank" id="url">{{ $datasource->url }}</a>
            </small></p>
          </div>

          <div class="col-md-2 text-left">
            <p><button type="button" class="btn btn-link btn-sm" alt="{{ $datasource->id }}"
              id="config-data-source-{{ $datasource->id }}" data-toggle="modal" data-target="#configModal">
              <span class="fui-cmd"></span> Configure</button></p>
            <p><button type="button" class="btn btn-link btn-sm" alt="{{ $datasource->id }}"
              id="edit-data-source-{{ $datasource->id }}" data-toggle="modal" data-target="#editModal">
              <span class="fui-new"></span> Edit</button></p>
            <p><button type="button" class="btn btn-link btn-sm" alt="{{ $datasource->id }}"
              id="del-data-source-{{ $datasource->id }}" data-toggle="modal" data-target="#deleteModal">
              <span class="text-danger"><span class="fui-trash"></span> Delete</button></span>
            </p>
          </div>

        </div> <!-- /.row -->
        <hr/>
      @endforeach

    @endif

  </div> <!-- /.data-sources-list -->


  <!-- Modals -->

  <!-- Add + edit modal -->
  <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
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
          <small class="text-danger" id="error" style="display:none;">Error: </small>
          <div class="clearfix"></div>
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-default btn-embossed btn-wide close-modal" data-dismiss="modal" id="close-editModal">Close</button>
          <button type="button" class="btn btn-primary btn-embossed btn-wide" id="save-data-source">Save changes</button>
        </div>

      </div>
    </div>
  </div>

  <!-- Delete Modal -->
  <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
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
            <p><small><span id="del_url"></span></small></p>
          </div>
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-default btn-embossed btn-wide close-modal" data-dismiss="modal">Cancel</button>
          <button type="button" class="btn btn-danger btn-embossed btn-wide" id="delete-data-source">Delete Data Source</button>
        </div>

      </div>
    </div>
  </div>

  <!-- Configure Modal -->
  <div class="modal fade" id="configModal" tabindex="-1" role="dialog" aria-labelledby="configModalLabel" aria-hidden="true">
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
            <div class="row">
              <div class="col-sm-3">
                <p><b>Data Source</b></p>
              </div>
              <div class="col-sm-9">
                <p id="title"></p>
              </div>
            </div>
          </div>
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-default btn-embossed btn-wide close-modal" data-dismiss="modal">Close</button>
          <span id="edit-config-btn">
            <button type="button" class="btn btn-info btn-embossed btn-wide" id="edit-config">Edit</button>
          </span>
        </div>

      </div>
    </div>
  </div>

  <!-- Sync Modal -->
  <div class="modal fade" id="syncModal" tabindex="-1" role="dialog" aria-labelledby="syncModalLabel" aria-hidden="true">
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
          <div class="well">
            <p><b><span id="del_title"></span></b></p>
            <p><span id="del_desc"></span></p>
            <p><small><span id="del_url"></span></small></p>
          </div>
          <p class="text-muted"><em>
            If you don't see the data source you want to sync above, make sure it is
            <span class="text-success"><span class="fui-cmd"></span> configured</span>.
          </em></p>
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-default btn-embossed btn-wide close-modal" data-dismiss="modal">Cancel</button>
          <button type="button" class="btn btn-info btn-embossed btn-wide" id="sync-data-sources">Sync Data Sources</button>
        </div>

      </div>
    </div>
  </div>


</div> <!-- /.data-sources -->

</div> <!-- /.col-md-10 -->
</div> <!-- /.row -->
@stop

@section('scripts-data')

var data_sources = {{ $datasources }};

var edit_id = 0;
var config_data = new Object();
var data_source_columns = [];
var ds_config = new Object();

var config_id = -1;
var config_title = -1;
var config_desc = -1;
var config_geo_type = 'lat_lng';
var config_geo_lat = -1;
var config_geo_lng = -1;
var config_geo_add = -1;
var config_status = -1;

var config_sel_cols_html = '';

@stop
