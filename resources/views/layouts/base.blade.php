<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7" lang="en"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8" lang="en"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9" lang="en"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" lang="en"> <!--<![endif]-->
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>#GreenAlert | @yield('title', 'Keeping an eye out on the environment.')</title>
    <meta name="description" content="@yield('meta_description', 'Keeping an eye out on the environment.')">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <meta name="google-site-verification" content="Pcw6RyloANHvjuK_Y9by9fpUw1H7U14PKKtPlPsgHP4" />

    <!-- Place favicon.ico and apple-touch-icon.png in the root directory -->
    <link rel="shortcut icon" href="{{ asset('assets/img/favicon.ico') }}">

    <link rel="stylesheet" href="{{ asset('assets/css/_bower.css') }}">

    @yield('stylesheets')

    @yield('styles')

    <script src="{{ asset('assets/js/vendor/modernizr-2.6.2.min.js') }}"></script>

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


    <script src="{{ asset('assets/js/_bower.js') }}"></script>

    <script src="{{ asset('assets/js/plugins.js') }}"></script>
    <script src="{{ asset('assets/js/application.js') }}"></script>

    <!-- <script src="{{ asset('assets/js/flat-ui.min.js') }}"></script> -->

    <script src="{{ asset('assets/js/pahali/pahali.js') }}"></script>

    <script>
      pahali.base_url = "{{ url('/') }}";
      pahali.csrf_token = "{{ csrf_token() }}";
      pahali.country.code = '{{ env('COUNTRY_CODE', 'za') }}';
      @section('scripts-data')
        
      @show
    </script>

    <script src="{{ asset('assets/js/main.js') }}"></script>

    @yield('scripts')


    <!-- Google Analytics: change UA-XXXXX-X to be your site's ID. -->
    <script>
      (function(b,o,i,l,e,r){b.GoogleAnalyticsObject=l;b[l]||(b[l]=
      function(){(b[l].q=b[l].q||[]).push(arguments)});b[l].l=+new Date;
      e=o.createElement(i);r=o.getElementsByTagName(i)[0];
      e.src='//www.google-analytics.com/analytics.js';
      r.parentNode.insertBefore(e,r)}(window,document,'script','ga'));
      ga('create','{{ env('GOOGLE_ANALYTICS', 'UA-44795600-13') }}');
      ga('require', 'linkid', 'linkid.js');
      ga('send','pageview');
    </script>
  </body>
</html>
