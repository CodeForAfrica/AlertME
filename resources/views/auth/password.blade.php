@extends('layouts.base')

@section('title')
  Reset Password
@stop

{{-- Content --}}
@section('content')

  <div class="container">

    <div class="row">
      <div class="col-md-4 col-md-offset-4">
        <h4>Reset Password</h4>
      </div>
    </div>

    <div class="row">
      <div class="col-md-4 col-md-offset-4">
        <div class="login-form">

          @if (session('status'))
            <div class="alert alert-success">
              {{ session('status') }}
            </div>
          @endif

          @if (count($errors) > 0)
            <div class="alert alert-danger">
              <strong>Whoops!</strong> There were some problems with your input.<br><br>
              <ul>
                @foreach ($errors->all() as $error)
                  <li>{{ $error }}</li>
                @endforeach
              </ul>
            </div>
          @endif

          <form role="form" method="POST" action="{{ secure_url('/password/email') }}">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">

            <div class="form-group {{{ $errors->has('email') ? 'has-error' : '' }}}">
              <input type="email" name="email" class="form-control login-field"
                     value="{{ Input::old('email') }}" placeholder="Email Address" id="login-email">
              <label class="login-field-icon fui-mail" for="login-email"></label>
            </div>
            <button class="btn btn-primary btn-embossed btn-lg btn-block" type="submit">Send Password Reset Link
            </button>
          </form>

        </div>
      </div>
    </div>
  </div>

@endsection

@section('stylesheets')
  <style>
    body {
      padding-top: 100px;
      padding-bottom: 40px;
      background-color: #1abc9c;
    }

    .has-error .form-control, .has-error .select2-search input[type=text] {
      color: #e74c3c;
      border-color: #e74c3c !important;
      box-shadow: none;
    }
  </style>
@endsection

@section('scripts')
  <script>
    $(':checkbox').radiocheck();
  </script>
@endsection
