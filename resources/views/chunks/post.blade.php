<div class="panel panel-default">
    <div class="panel-body post">

        <div class="row">
            <div class="col-md-12">
                <div class="post-header">
                    <img class="min-avatar" src="{{ $user->avatar ? $user->avatar : asset('images/avatar_min_' . $user->sex . '.png')  }}">
                    <h4>{{ $user -> first_name }} {{ $user -> last_name }}</h4>
                    <h6>2016-08-12 16:28</h6>
                </div>
                <hr>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="post-content">
                    <img src="{{asset('images/login_background.jpg')}}">
                    <p>Lorem ipsum dolor sit amet enim. Etiam ullamcorper. Suspendisse a pellentesque dui, non felis. Maecenas malesuada elit lectus felis, malesuada ultricies. Curabitur et ligula. Ut molestie a, ultricies porta urna. Vestibulum commodo volutpat a, convallis ac, laoreet enim. Phasellus fermentum in, dolor. Pellentesque facilisis. Nulla imperdiet sit amet magna. Vestibulum dapibus, mauris nec malesuada fames ac turpis velit, rhoncus eu, luctus et interdum adipiscing wisi. Aliquam erat ac ipsum. Integer aliquam purus. Quisque lorem tortor fringilla sed, vestibulum id, eleifend justo vel bibendum sapien massa ac turpis faucibus orci luctus non, consectetuer lobortis quis, varius in, purus. Integer ultrices posuere cubilia Curae, Nulla ipsum dolor lacus, suscipit adipiscing. Cum sociis natoque penatibus et ultrices volutpat. Nullam wisi ultricies a, gravida vitae, dapibus risus ante sodales lectus blandit eu, tempor diam pede cursus vitae, ultricies eu, faucibus quis, porttitor eros cursus lectus, pellentesque eget, bibendum a, gravida ullamcorper quam. Nullam viverra consectetuer. Quisque cursus et, porttitor risus. Aliquam sem. In hendrerit nulla quam nunc, accumsan congue. Lorem ipsum primis in nibh vel risus. Sed vel lectus. Ut sagittis, ipsum dolor quam.</p>
                    <hr>
                </div>
            </div>

            <div class="col-md-12">

                @include('chunks.comment')

                @include('chunks.comment')

                @include('chunks.add_comment')

            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <hr>
                <a href="#" class="like like-liked"><i class="fa fa-heart" aria-hidden="true"></i> Fajne!</a>
                {{--<a href="#" class="comments"><i class="fa fa-comments" aria-hidden="true"></i> Komentarze <small>(12)</small></a>--}}
            </div>
        </div>

    </div>
</div>