<div class="panel panel-default">
    <div class="panel-body comment">
        <div class="row">
            <div class="col-md-1">
                <img class="min-avatar" src="{{ $user->avatar ? $user->avatar : asset('images/avatar_min_' . $user->sex . '.png')  }}">
            </div>
            <div class="col-md-11">
                <p><strong>{{ $user -> first_name }} {{ $user -> last_name }}</strong> Lorem ipsum dolor sit amet enim. Etiam ullamcorper. Suspendisse a pellentesque dui, non felis.</p>
                <hr>
                <a href="#" class="like like-liked"><i class="fa fa-heart" aria-hidden="true"></i> Fajne!</a><small class="pull-right">2016-08-12 16:28</small>
            </div>
        </div>
    </div>
</div>