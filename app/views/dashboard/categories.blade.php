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

</div> <!-- /.categories -->

</div> <!-- /.col-md-10 -->
</div> <!-- /.row -->
@stop
