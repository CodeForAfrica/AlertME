<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7" lang="en"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8" lang="en"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9" lang="en"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" lang="en"> <!--<![endif]-->
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>{{ env('APP_NAME', '#GreenAlert') }} | @yield('title', 'Keeping an eye out on the environment.')</title>
    <meta name="description" content="@yield('meta_description', 'Keeping an eye out on the environment.')">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Place favicon.ico and apple-touch-icon.png in the root directory -->
    <link rel="shortcut icon" href="{{ secure_asset('assets/img/favicon.ico') }}">

    <link rel="stylesheet" href="{{ secure_asset('assets/css/_bower.css') }}">

    @yield('stylesheets')

    @yield('styles')

  </head>
  <body class="@yield('body-class')">
    <!--[if lt IE 7]>
    <p class="browsehappy">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
    <![endif]-->
    

    <!-- Add your site or application content here -->
    @yield('navigation')

    @yield('navigation-side')

    @yield('content', 'Sorry, no content.')

    @yield('sidebar')

    @yield('footer')

  </body>
</html>
