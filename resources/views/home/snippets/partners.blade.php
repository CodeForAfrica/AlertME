<div class="row home-logos">
    <div class="col-md-3">
        <h4>Partners</h4>
        <p>{{ env('APP_NAME', '#GreenAlert') }} has been made possible through support from the following partners:</p>
    </div>
    <div class="col-md-9">
        <p>
            <a href="http://oxpeckers.org" target="_blank">
                <img src="{{ asset('assets/img/logos/oxpeckers-long.png') }}"/>
            </a>
            @if(env('OWNER_NAME', 'Oxpeckers') != 'Oxpeckers')
                <a href="{{ env('OWNER_URL', 'https://codefornigeria.org') }}" target="_blank"  rel="noopener">
                    <img src="{{ env('OWNER_LOGO', '/assets/img/logos/cfnigeria.png') }}"/>
                </a>
            @endif
        </p>
        <p class="funders">
            <a href="http://www.codeforafrica.org" target="_blank">
                <img src="{{ asset('assets/img/logos/cfafrica.png') }}" style="height: 60px;"/>
            </a>
            <a href="http://africannewschallenge.org" target="_blank">
                <img src="{{ asset('assets/img/logos/bmgf.png') }}"/>
            </a>
            <a href="http://www.sej.org/" target="_blank">
                <img src="{{ asset('assets/img/logos/osiwa.jpg') }}"/>
            </a>
        </p>
    </div>
</div> <!-- /.row -->
