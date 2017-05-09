@extends('layouts.base')

@section('title')
    Reset Password
@endsection

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

                    <form role="form" method="POST" action="{{ url('/password/reset') }}">

                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <input type="hidden" name="token" value="{{ $token }}">

                        <div class="form-group {{{ $errors->has('email') ? 'has-error' : '' }}}">
                            <input type="email" name="email" class="form-control login-field"
                                   value="{{ Input::old('email') }}" placeholder="Email Address" id="login-email">
                            <label class="login-field-icon fui-mail" for="login-email"></label>
                        </div>
                        <div class="form-group {{{ $errors->has('password') ? 'has-error' : '' }}}">
                            <input type="password" name="password" class="form-control login-field"
                                   value="" placeholder="Password" id="login-pass">
                            <label class="login-field-icon fui-lock" for="login-pass"></label>
                        </div>
                        <div class="form-group {{{ $errors->has('password_confirmation') ? 'has-error' : '' }}}">
                            <input type="password" name="password_confirmation" class="form-control login-field"
                                   value="" placeholder="Confirm Password" id="login-pass-2">
                            <label class="login-field-icon fui-lock" for="login-pass-2"></label>
                        </div>
                        <button class="btn btn-primary btn-embossed btn-lg btn-block" type="submit">Reset Password</button>

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
