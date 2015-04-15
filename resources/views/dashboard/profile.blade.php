@extends('layouts.backend')

@section('content')
  <div class="profile">

    <form method="POST" action="{{ secure_url('dashboard/profile') }}">
      <input type="hidden" name="_token" value="{{ csrf_token() }}">

      <h5>
        Profile
        <button type="submit" class="btn btn-primary btn-embossed btn-wide"
                style="margin-left: 30px;">Save Changes
        </button>
      </h5>

      <hr/>

      <div class="row">
        <div class="col-md-8">
          <h6>Personal Details</h6>

          <div class="row">
            <div class="col-md-3">
              <p class="text-muted"><em>These are details to help us get in touch with you better and will allow us to
                  send updates.</em></p>
            </div>
            <div class="col-md-7">
              <div class="form-group">
                <label for="fullname">Full Name:</label>
                <input name="fullname" id="fullname" class="form-control" placeholder="Green Alerter"
                       value="{{ $user->fullname }}"/>
              </div>
              <div class="form-group">
                <label for="email">Email-address:</label>
                <input name="email" id="email" class="form-control" placeholder="green@alert.org"
                       value="{{ $user->email }}"/>
              </div>
            </div>
          </div>

          <hr/>

          <h6>Change Password</h6>

          <div class="row">
            <div class="col-md-3">
              <p class="text-muted"><em>To change your password, simply input the password here and hit save.</em></p>
            </div>
            <div class="col-md-7">
              <div class="form-group has-feedback {{{ $errors->has('password') ? 'has-error' : '' }}}">
                <input type="password" name="password" class="form-control login-field"
                       value="" placeholder="Password" id="login-pass">
                <span class="form-control-feedback fui-lock"></span>
              </div>
              <div class="form-group has-feedback {{{ $errors->has('password_confirmation') ? 'has-error' : '' }}}">
                <input type="password" name="password_confirmation" class="form-control login-field"
                       value="" placeholder="Confirm Password" id="login-pass-2">
                <span class="form-control-feedback fui-lock" for="login-pass-2"></span>
              </div>
            </div>
          </div>


        </div>
        <div class="col-md-4">
          <div class="well">
          </div>
        </div>
      </div>
      <!-- /.row -->

    </form>

  </div> <!-- /.profile -->
@stop
