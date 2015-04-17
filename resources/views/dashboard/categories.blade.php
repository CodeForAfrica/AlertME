@extends('layouts.backend')

@section('content')
  <div class="categories">
    <h5>
      Categories
      <button type="button" class="btn btn-primary btn-embossed btn-wide btn-sm category-add"
              id="category-add" data-toggle="modal" data-target="#editModal" style="margin-left: 30px;">
        <span class="fui-plus"></span> Add
      </button>
    </h5>
    <hr/>

    <div class="categories-list">

      @if (count($categories) === 0)
        <p class="lead" id="no-categories">It seems you don't have any categories yet.
          <button type="button" class="btn btn-primary btn-sm category-add"
                  id="category-add-first" data-toggle="modal" data-target="#editModal">
            <span class="fui-plus"></span> Add
          </button>
          some now to get started.
        </p>
      @else

        @foreach ( $categories as $category )
          <div class="media">
            <a class="pull-left" href="#">
              <img class="media-object"
                   src="{{ $category->icon_url != '' ? $category->icon_url : '/assets/img/icons/svg/retina.svg' }}"
                   alt="icon_url">
            </a>

            <div class="media-body">

              <div class="row" id="category-{{ $category->id }}">

                <div class="col-md-10">
                  <h4 class="media-heading" id="title">{{ $category->title }}</h4>
                  <!-- <p class="lead" id="title">{{ $category->title }}</p> -->
                  <p class="text-muted" id="desc">
                    {{ $category->description != '' ? $category->description : '[No Description]' }}
                  </p>

                  <p>Keywords: <span id="keywords" class="text-muted">
                  {{ $category->keywords != '' ? $category->keywords : '[No Keywords]' }}
                </span></p>

                  <p>
                    <small>Icon Url:
                      <a href="{{ $category->icon_url }}" target="_blank" id="icon_url">{{ $category->icon_url }}</a>
                    </small>
                  </p>
                </div>

                <div class="col-md-2 text-left">
                  <p><a href="#" class="btn btn-link btn-sm"
                        id="category-view-{{ $category->id }}" target="_blank">
                      <span class="fui-export"></span> View</a></p>

                  <p>
                    <button type="button" class="btn btn-link btn-sm" alt="{{ $category->id }}"
                            id="edit-category-{{ $category->id }}" data-toggle="modal" data-target="#editModal">
                      <span class="fui-new"></span> Edit
                    </button>
                  </p>
                  <p>
                    <button type="button" class="btn btn-link btn-sm" alt="{{ $category->id }}"
                            id="del-category-{{ $category->id }}" data-toggle="modal" data-target="#deleteModal">
                      <span class="text-danger"><span class="fui-trash"></span> Delete</span>
                    </button>
                  </p>
                </div>

              </div>
              <!-- /.row -->
            </div>
            <!-- /.media-body -->
          </div> <!-- /.media -->

          <hr/>
        @endforeach

      @endif

    </div>
    <!-- /.categories-list -->

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
            <h4 class="modal-title" id="editModalLabel">Add Category</h4>
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
            <div class="form-group tagsinput-primary">
              <label for="keywords">Keywords</label>
              <span id="keywords-span"><input id="keywords" class="tagsinput" data-role="tagsinput"/></span>
            </div>
            <div class="form-group">
              <label for="icon_url">Icon URL</label>
              <input type="text" class="form-control" id="icon_url" placeholder="http://">
            </div>
            <div class="alert alert-danger" style="display:none;">
              <small id="error">Error:</small>
            </div>
            <div class="clearfix"></div>
          </div>

          <div class="modal-footer">
            <button type="button" class="btn btn-default btn-embossed btn-wide close-modal" data-dismiss="modal"
                    id="close-editModal">Close
            </button>
            <button type="button" class="btn btn-primary btn-embossed btn-wide" id="category-save">Save changes</button>
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
            <h4 class="modal-title" id="deleteModalLabel">Delete Category</h4>
          </div>

          <div class="modal-body">
            <p>Are you sure you would like to delete the following category?</p>

            <div class="well">
              <div class="media">
                <a class="pull-left" href="#">
                  <img class="media-object" src="" id="img_icon_url" alt="icon_url">
                </a>

                <div class="media-body">
                  <h4 class="media-heading" id="title"></h4>

                  <p class="text-muted" id="desc"></p>

                  <p>Keywords: <span id="keywords" class="text-muted"></span></p>

                  <p>
                    <small>Icon Url:
                      <a href="#" target="_blank" id="icon_url"></a>
                    </small>
                  </p>
                </div>
              </div>
            </div>
          </div>

          <div class="modal-footer">
            <button type="button" class="btn btn-default btn-embossed btn-wide close-modal" data-dismiss="modal">
              Cancel
            </button>
            <button type="button" class="btn btn-danger btn-embossed btn-wide" id="category-delete">Delete Category
            </button>
          </div>

        </div>
      </div>
    </div>

  </div> <!-- /.categories -->

@stop

@section('scripts-data')

  var categories = {!! $categories !!};

  var edit_id = 0;

@stop

@section('scripts')
  <script src="/assets/js/backend/categories.js"></script>
@stop
