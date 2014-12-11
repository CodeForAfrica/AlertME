@extends('layouts.base')

@section('body-class') frontend @stop

@section('stylesheets')
  <link rel="stylesheet" href="/assets/css/frontend.css">
@stop

@section('navigation')
  <nav class="navbar navbar-inverse navbar-embossed navbar-fixed-top navbar-lg" role="navigation">
    <!-- Navbar content -->
    <div class="container">

      <!-- Brand and toggle get grouped for better mobile display -->
      <div class="navbar-header">
        <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#ga-navbar-collapse-1">
          <span class="sr-only">Toggle navigation</span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
        </button>
        <a class="navbar-brand" href="/">#GreenAlert</a>
      </div>

      <!-- Collect the nav links, forms, and other content for toggling -->
      <div class="collapse navbar-collapse" id="ga-navbar-collapse-1">

        <ul class="nav navbar-nav">
          <li class="{{Request::path() == '/' ? 'active' : '';}}">
            <a href="/">Home</a></li>
          <li class="{{Request::path() == 'map' ? 'active' : '';}}">
            <a href="/map">Map</a></li>
          <li class="{{Request::path() == 'about' ? 'active' : '';}}">
            <a href="/about">About</a></li>
          @if ( Auth::guest() )
            <li class="{{Request::path() == 'login' ? 'active' : '';}}">
              <a href="/login">Login</a></li>
          @else
            <li class="{{Request::path() == 'dashboard' ? 'active' : '';}}">
              <a href="/dashboard">Dashboard</a></li>
          @endif
        </ul>


        <form class="navbar-form navbar-right" action="/search" role="search">
          <div class="form-group">
            <div class="input-group">
              <input class="form-control" id="navbarInput-01" type="search" placeholder="Search"
                name="q" value="{{Input::get('q')}}">
              <span class="input-group-btn">
                <button type="submit" class="btn"><span class="fui-search"></span></button>
              </span>
            </div>
          </div>
        </form>

      </div><!-- /.navbar-collapse -->

    </div><!-- /.container -->
  </nav>
@stop

@section('footer')
<footer class="bg-primary">
  <div class="container text-center">
    <div class="row">
      <div class="col-md-6 text-left">
        <p>
          <a href="/">Home</a> .
          <a href="/about">About</a> .
          <a href="/dashboard">Dashoard</a> .
          <a href="https://github.com/CodeForAfrica/GreenAlert" target="_blank">Github</a> .
          <a href="http://oxpeckers.org" target="_blank">Oxpeckers</a>
        </p>
        <hr/>
        <p class="text-muted"><em>
          All code on this website is <a href="https://github.com/CodeForAfrica/GreenAlert" target="_blank">Open Source</a>.</br>
          Content on this site, made by Oxpeckers, is licensed under a <a rel="license" href="http://creativecommons.org/licenses/by-sa/4.0/" target="_blank">Creative Commons Attribution-ShareAlike 4.0 International License</a>.</br>
          Refer to our <a href="{{ secure_asset('about') }}" target="_blank">attributions</a> page for attributions of other work on the site.
        </em></p>
      </div>
      <div class="col-md-6 text-right">
        <h3 class="text-muted">#GreenAlert</h3>
        <p>An <a href="http://oxpeckers.org" target="_blank">Oxpeckers'</a> Project</p>
        <p>Built by <a href="http://codeforafrica.org" target="_blank">Code for Africa</a></p>
      </div>
    </div>
  </div>
</footer>

<script>
// Include the UserVoice JavaScript SDK (only needed once on a page)
UserVoice=window.UserVoice||[];(function(){var uv=document.createElement('script');uv.type='text/javascript';uv.async=true;uv.src='//widget.uservoice.com/bFsioBFsYYe3fBl1hFBFOQ.js';var s=document.getElementsByTagName('script')[0];s.parentNode.insertBefore(uv,s)})();

//
// UserVoice Javascript SDK developer documentation:
// https://www.uservoice.com/o/javascript-sdk
//

// Set colors
UserVoice.push(['set', {
  accent_color: '#6aba2e',
  trigger_color: 'white',
  trigger_background_color: 'rgba(46, 49, 51, 0.6)'
}]);

// Identify the user and pass traits
// To enable, replace sample data with actual user traits and uncomment the line
UserVoice.push(['identify', {
  //email:      'john.doe@example.com', // User’s email address
  //name:       'John Doe', // User’s real name
  //created_at: 1364406966, // Unix timestamp for the date the user signed up
  //id:         123, // Optional: Unique id of the user (if set, this should not change)
  //type:       'Owner', // Optional: segment your users by type
  //account: {
  //  id:           123, // Optional: associate multiple users with a single account
  //  name:         'Acme, Co.', // Account name
  //  created_at:   1364406966, // Unix timestamp for the date the account was created
  //  monthly_rate: 9.99, // Decimal; monthly rate of the account
  //  ltv:          1495.00, // Decimal; lifetime value of the account
  //  plan:         'Enhanced' // Plan name for the account
  //}
}]);

// Add default trigger to the bottom-right corner of the window:
UserVoice.push(['addTrigger', { mode: 'contact', trigger_position: 'bottom-right' }]);

// Or, use your own custom trigger:
//UserVoice.push(['addTrigger', '#id', { mode: 'contact' }]);

// Autoprompt for Satisfaction and SmartVote (only displayed under certain conditions)
UserVoice.push(['autoprompt', {}]);
</script>
@stop
