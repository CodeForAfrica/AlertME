@extends('layouts.frontend')

@section('title') Project @stop

@section('content')

  <div class="about">

    <div class="container ">
      <div class="page-header">
        <h4>{{ $project->title }}</h4>
      </div>

      @if(isset($msg_confirm))
        <div class="alert alert-success" style="padding: 10px 15px;">
          <small><b>Awesome!</b>
            We have sent you an e-mail to confirm your subscription to this project.
            <span class="fui-check-circle pull-right"></span>
          </small>
        </div>
      @elseif(isset($msg_duplicate))
        <div class="alert alert-warning" style="padding: 10px 15px;">
          <small><b>Hmm...</b>
            Seems like you are already subscribed to this project.
            <span class="fui-alert-circle pull-right"></span>
          </small>
        </div>
      @endif

      <div class="row">

        <div class="col-md-5">
          <img src="{{ $map_image_link }}" style="width:100%;"
            class="img-responsive img-rounded"/>
          <br/>
          <a href="#" class="btn btn-block btn-embossed btn-primary">
            <span class="fa fa-globe"></span>
            Subscribe for Alerts
          </a>
        </div>

        <div class="col-md-7">
          <p>{{$project->description}}</p>
          <hr/>
          <h6>Details</h6>
          <div class="project-details">
            @foreach($project->data as $key => $value)
              <div class="row">
                <div class="col-md-4 text-right">
                  <small><b>{{ $key }}</b></small>
                </div>
                <div class="col-md-8">
                  <small>
                    {{ empty($value) ? '-' : $value }}
                  </small>
                </div>
              </div>
            @endforeach
          </div>
        </div>

      </div> <!-- /.row -->

      <br/>
      <div id="disqus_thread"></div>
      <script type="text/javascript">
        /* * * CONFIGURATION VARIABLES: EDIT BEFORE PASTING INTO YOUR WEBPAGE * * */
        var disqus_shortname = 'greenalert'; // required: replace example with your forum shortname

        /* * * DON'T EDIT BELOW THIS LINE * * */
        (function() {
          var dsq = document.createElement('script'); dsq.type = 'text/javascript'; dsq.async = true;
          dsq.src = '//' + disqus_shortname + '.disqus.com/embed.js';
          (document.getElementsByTagName('head')[0] || document.getElementsByTagName('body')[0]).appendChild(dsq);
        })();
      </script>
      <noscript>Please enable JavaScript to view the <a href="http://disqus.com/?ref_noscript">comments powered by Disqus.</a></noscript>
      

    </div> <!-- /.container -->

  </div> <!-- /.data-sources-list -->

@stop

@section('scripts')
  <script src="{{ secure_asset('assets/js/vendor/readmore.min.js') }}"></script>
  <script type="text/javascript">
    $('.project-details').readmore({
      moreLink: '<a href="#">More...</a>',
      lessLink: '<a href="#">Less...</a>'
    });
  </script>
@stop

