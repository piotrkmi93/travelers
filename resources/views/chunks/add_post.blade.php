<div class="panel panel-default">
    <div class="panel-body post">

        <div class="row">
            <div class="col-md-12">
                <div class="post-header">
                    <img class="min-avatar" src="{{ Auth::user()->avatar_photo_id != 0 ? asset(getThumb(Auth::user()->avatar_photo_id)) : asset('images/avatar_min_' . Auth::user()->sex . '.png')  }}">
                    <h4>{{ Auth::user() -> first_name }} {{ Auth::user() -> last_name }}</h4>
                </div>
                <hr>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="post-content">

                    <textarea class="comment-new form-control" placeholder="Napisz coś..."></textarea>
                    <hr>
                    <div class="btn-group" role="group">
                        <button type="button" class="btn btn-primary">Dodaj zdjęcie</button>
                        <button type="button" class="btn btn-primary">Dodaj post</button>
                    </div>
                </div>
            </div>
        </div>



    </div>
</div>