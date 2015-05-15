@extends('layouts.frontend')

@section('title') {{ strlen($project->title) > 80 ? substr($project->title, 0, 80).'...' : $project->title }} @stop

@section('content')

  <div class="project">

    <div class="container">
      <div class="page-header">
        <h4>{{ strlen($project->title) > 80 ? substr($project->title, 0, 80).'...' : $project->title }}</h4>
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

          <hr/>

          <div class="well" style="background: url('{{ secure_asset('assets/img/fist-icon.png') }}') no-repeat left;">
            <div class="row">
              <div class="col-sm-6">
                <button type="button" class="btn btn-default btn-block btn-embossed" data-toggle="modal"
                        data-target="#actnow-modal-petition" disabled="disabled">Petition
                </button>
                <button type="button" class="btn btn-default btn-block btn-embossed" data-toggle="modal"
                        data-target="#actnow-modal-connect" disabled="disabled">Connect
                </button>
              </div>
              <div class="col-sm-6">
                <button type="button" class="btn btn-primary btn-block btn-embossed" data-toggle="modal"
                        data-target="#subscriptionModal">Follow
                </button>
                <button type="button" class="btn btn-primary btn-block btn-embossed" data-toggle="modal"
                        data-target="#actnow-modal-share">Share / Embed
                </button>
              </div>
            </div>
            <!-- /.row -->
          </div>
          <!-- /.well -->

          <hr/>

        </div>
        <!-- /.col-md-5 -->

        <div class="col-md-7">
          <p>{{ $project->description }}</p>
          <hr/>
          <h6>Details</h6>

          <div class="project-details">
            <dl class="dl-horizontal">
              @foreach($project->data as $key => $value)
                <dt>
                  <small>{{ $key }}</small>
                </dt>
                <dd>
                  <small>{{ empty($value) ? '-' : $value }}</small>
                </dd>
              @endforeach
            </dl>
          </div>
        </div>
        <!-- /.col-md-7 -->

      </div>
      <!-- /.row -->

      <br/>

      <div id="disqus_thread"></div>
      <script type="text/javascript">
        /* * * CONFIGURATION VARIABLES: EDIT BEFORE PASTING INTO YOUR WEBPAGE * * */
        var disqus_shortname = 'greenalert'; // required: replace example with your forum shortname

        /* * * DON'T EDIT BELOW THIS LINE * * */
        (function () {
          var dsq = document.createElement('script');
          dsq.type = 'text/javascript';
          dsq.async = true;
          dsq.src = '//' + disqus_shortname + '.disqus.com/embed.js';
          (document.getElementsByTagName('head')[0] || document.getElementsByTagName('body')[0]).appendChild(dsq);
        })();
      </script>
      <noscript>Please enable JavaScript to view the <a href="http://disqus.com/?ref_noscript">comments powered by
          Disqus.</a></noscript>

    </div>
    <!-- /.container -->


    <!-- MODALS -->

    <!-- Subscribe Modal -->
    <div class="modal fade" id="subscriptionModal" tabindex="-1" role="dialog"
         aria-labelledby="subscriptionModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">

          <div class="modal-header">
            <button type="button" class="close close-modal" data-dismiss="modal">
              <span aria-hidden="true">&times;</span><span class="sr-only">Close</span>
            </button>
            <h4 class="modal-title" id="subscriptionModalLabel">Subscribe for Alerts</h4>
          </div>
          <!-- /.modal-header -->

          <div class="modal-body">

            <p><b>{{ strlen($project->title) > 80 ? substr($project->title, 0, 80).'...' : $project->title }}</b></p>
            <hr/>

            <img id="subscription-map" class="img-rounded img-responsive"
                 style="background: #eee url('{{ $map_image_link }}') no-repeat center; height:200px; width:100%; background-size:cover;"/>
            <hr/>

            <p>Enter your e-mail address below to receive alerts on this project.</p>

            <div class="form-horizontal" role="form">
              <div class="form-group subscription-email">
                <label for="subscription-email" class="col-sm-2 control-label">Email</label>

                <div class="col-sm-8">
                  <input type="email" class="form-control" id="subscription-email" placeholder="Email">
                </div>
              </div>
            </div>

            <!-- ALERTS -->
            <!-- Loading -->
            <div class="alert alert-info text-center" role="alert" style="display:none;">
              <small>
                <i class="fa fa-circle-o-notch fa-spin"></i>
                Subscribing... You'll soon be receiving updates on this project.
              </small>
            </div>
            <!-- Success -->
            <div class="alert alert-success alert-dismissible" role="alert" style="display:none;">
              <button type="button" class="close" data-dismiss="alert">
                <span aria-hidden="true">&times;</span><span class="sr-only">Close</span>
              </button>
              <small><span class="fui-check-circle"></span>
                Awesome! Check your e-mail to confirm subscription.
              </small>
            </div>
            <!-- Warning -->
            <div class="alert alert-warning alert-dismissible" role="alert" style="display:none;">
              <button type="button" class="close" data-dismiss="alert">
                <span aria-hidden="true">&times;</span><span class="sr-only">Close</span>
              </button>
              <small>
                <span class="fui-alert-circle"></span>
                <b>Hmm...</b>
                <span class="msg-error duplicate" style="display:none;"><br/>
                  Seems like you are already subscribed to this project.
                </span>
              </small>
            </div>
            <!-- Error -->
            <div class="alert alert-danger alert-dismissible" role="alert" style="display:none;">
              <button type="button" class="close" data-dismiss="alert">
                <span aria-hidden="true">&times;</span><span class="sr-only">Close</span>
              </button>
              <small>
                <span class="fui-alert-circle"></span>
                <b>Oops!</b> Looks like something went wrong.
                <span class="msg-error email" style="display:none;"><br/>
                  Please check the e-mail address entered.
                </span>
                <span class="msg-error limit" style="display:none;"><br/>
                  You've reached the max number of alerts registration.
                </span>
                <span class="msg-error reload" style="display:none;"><br/>
                  Please <a href="javascript:location.reload();">reload</a> the page and try again.
                </span>
              </small>
            </div>

          </div>
          <!-- /.modal-body -->

          <div class="modal-footer">
            <button type="button" class="btn btn-embossed btn-default btn-wide" data-dismiss="modal">Close</button>
            <button type="button" class="subscribe-btn btn btn-embossed btn-primary btn-wide"># Subscribe</button>
          </div>
          <!-- /.modal-footer -->

        </div>
        <!-- /.modal-content -->
      </div>
      <!-- /.modal-dialog -->
    </div>
    <!-- /.modal -->

    <!-- Embed Modal -->
    <div class="modal fade" id="embedModal" tabindex="-1" role="dialog"
         aria-labelledby="embedModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">
              <span aria-hidden="true">&times;</span><span class="sr-only">Close</span>
            </button>
            <h4 class="modal-title" id="embedModalLabel"><span class="fui-windows"></span> Embed Project</h4>
          </div>
          <div class="modal-body">
            <p>Embed this project into your website by using the following iframe:</p>

            <div class="form-group">
              <input onClick="this.setSelectionRange(0, this.value.length)" class="form-control"
                     value="<iframe src=&quot;{{ secure_asset('api/v1/projects/'.$project->id.'?embed=true') }}&quot; width=&quot;425&quot; height=&quot;355&quot; frameborder=&quot;0&quot; marginwidth=&quot;0&quot; marginheight=&quot;0&quot; style=&quot;border:1px solid #CCC; border-width:1px; margin-bottom:5px; max-width: 100%;&quot; allowfullscreen>Loading project...</iframe>"
                     style="color:#34495e;" readonly/>
            </div>
          </div>
          <!-- /,modal-body -->
          <div class="modal-footer">
            <button type="button" class="btn btn-embossed btn-default btn-wide" data-dismiss="modal">Close</button>
          </div>
        </div>
        <!-- /.modal-content -->
      </div>
      <!-- /.modal-dialog -->
    </div>
    <!-- /.modal -->

    <!-- ActNOW -->

    <!-- Petition -->
    <div class="modal fade" id="actnow-modal-petition" tabindex="-1" role="dialog" aria-labelledby="actNOW-petition"
         aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                  aria-hidden="true">&times;</span></button>
            <h4 class="modal-title">Petition</h4>
          </div>
          <div class="modal-body text-center">
            <p class="lead" style="margin-bottom: 0;"><em>Coming soon...</em></p>

            <p>Petitions are a great way to get affirmative action. Here you will be you to register as an Interested
              and Affected Party and petition the relevant authority(ies).</p>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-embossed btn-default btn-wide" data-dismiss="modal">Close</button>
          </div>
        </div>
        <!-- /.modal-content -->
      </div>
      <!-- /.modal-dialog -->
    </div>
    <!-- /.modal -->

    <!-- Connect -->
    <div class="modal fade" id="actnow-modal-connect" tabindex="-1" role="dialog" aria-labelledby="actNOW-connect"
         aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                  aria-hidden="true">&times;</span></button>
            <h4 class="modal-title">Connect</h4>
          </div>
          <div class="modal-body text-center">
            <p class="lead" style="margin-bottom: 0;"><em>Coming soon...</em></p>

            <p>Connect with individuals and organizations interested in this and similar projects.</p>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-embossed btn-default btn-wide" data-dismiss="modal">Close</button>
          </div>
        </div>
        <!-- /.modal-content -->
      </div>
      <!-- /.modal-dialog -->
    </div>
    <!-- /.modal -->

    <!-- Share -->
    <div class="modal fade" id="actnow-modal-share" tabindex="-1" role="dialog" aria-labelledby="actNOW-share"
         aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                  aria-hidden="true">&times;</span></button>
            <h4 class="modal-title">Share</h4>
          </div>
          <div class="modal-body">
            <div class="addthis_sharing_toolbox"></div>
            <hr/>
            <p>Embed this project into your website by using the following iframe:</p>

            <div class="form-group">
              <input onClick="this.setSelectionRange(0, this.value.length)" class="form-control"
                     value="<iframe src=&quot;{{ secure_asset('api/v1/projects/'.$project->id.'?embed=true') }}&quot; width=&quot;425&quot; height=&quot;355&quot; frameborder=&quot;0&quot; marginwidth=&quot;0&quot; marginheight=&quot;0&quot; style=&quot;border:1px solid #CCC; border-width:1px; margin-bottom:5px; max-width: 100%;&quot; allowfullscreen>Loading project...</iframe>"
                     style="color:#34495e;" readonly/>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-embossed btn-default btn-wide" data-dismiss="modal">Close</button>
          </div>
        </div>
        <!-- /.modal-content -->
      </div>
      <!-- /.modal-dialog -->
    </div>
    <!-- /.modal -->

  </div> <!-- /.project -->

@stop

@section('scripts')

  <script type="text/javascript">
    var map_image_link = '{!! $map_image_link !!}';
    var project_id = {!! $project->id !!};
    var geojson = '{!! $geojson !!}';
  </script>

  <script src="{{ secure_asset('assets/js/frontend/project.js') }}"></script>

  <!-- Go to www.addthis.com/dashboard to customize your tools -->
  <script type="text/javascript"
          src="https://s7.addthis.com/js/300/addthis_widget.js#pubid=ra-5481e9a015b90c80"></script>

@stop

