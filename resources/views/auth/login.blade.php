@extends('layouts.base')

@section('title')
  Login
@stop

{{-- Content --}}
@section('content')

  <div class="container">

    <div class="row">
      <div class="col-md-4 col-md-offset-4">
        <h4>Login</h4>
      </div>
    </div>

    <div class="row">
      <div class="col-md-4 col-md-offset-4">
        <div class="login-form">

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

          <form role="form" method="POST" action="{{ url('/login') }}">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">

            <div class="form-group {{ $errors->has('username') ? 'has-error' : '' }}">
              <input type="text" name="username" class="form-control login-field"
                     value="{{ $request->old('username') }}" placeholder="Username" id="login-name">
            </div>

            <div class="form-group {{ $errors->has('password') ? 'has-error' : '' }}">
              <input type="password" name="password" class="form-control login-field"
                     value="" placeholder="Password" id="password">
              <label class="login-field-icon fui-lock" for="password"></label>
            </div>

            <div class="form-group checkbox" style="height:20px;">
              <label for="remember" style="line-height: 100%; padding-left: 0;">
                <input type="checkbox" name="remember" id="remember" data-toggle="checkbox"> Remember me
              </label>
            </div>

            <button class="btn btn-primary btn-embossed btn-lg btn-block" type="submit">Log in</button>

          </form>

          <a class="login-link" href="{{ url('/password/email') }}">Forgot password?</a>

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
