<div class="panel panel-default">
    <div class="panel-body comment">
        <div class="row">
            <div class="col-md-1">
                <img class="min-avatar" src="{{ Auth::user()->avatar_photo_id != 0 ? asset(getThumb(Auth::user()->avatar_photo_id)) : asset('images/avatar_min_' . Auth::user()->sex . '.png')  }}">
            </div>
            <div class="col-md-11">
                <textarea class="comment-new form-control" placeholder="Napisz coś..."></textarea>
                <a href="#" class="btn btn-sm btn-primary">Dodaj komentarz</a>
            </div>
        </div>
    </div>
</div>