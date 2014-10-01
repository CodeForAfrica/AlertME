@extends('layouts.backend')

@section('content')

<div class="data-sources">

  <h5>
    Data Sources
    <button type="button" class="btn btn-info btn-sm" >
      <span class="fui-radio-unchecked"></span> Sync</button>
    <button type="button" class="btn btn-primary btn-sm"
      id="add-data-source" data-toggle="modal" data-target="#editModal">
      <span class="fui-plus"></span> Add</button>
  </h5>

  <hr/>

  <div class="data-sources-list">

    @if (count($datasources) === 0)
      <p class="lead" id="no-data-sources">It seems you don't have any data sources yet.
        <button type="button" class="btn btn-primary btn-sm"
          id="add-data-source-first" data-toggle="modal" data-target="#editModal">
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
          <button type="button" class="close" data-dismiss="modal" id="close_x-editModal">
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
          <button type="button" class="btn btn-default" data-dismiss="modal" id="close-editModal">Close</button>
          <button type="button" class="btn btn-primary" id="save-data-source">Save changes</button>
        </div>

      </div>
    </div>
  </div>

  <!-- Delete Modal -->
  <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">

        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">
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
          <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
          <button type="button" class="btn btn-danger" id="delete-data-source">Delete Data Source</button>
        </div>

      </div>
    </div>
  </div>


</div> <!-- /.data-sources -->

</div> <!-- /.col-md-10 -->
</div> <!-- /.row -->
@stop

@section('scripts-data')
var edit_id = 0;
@stop
