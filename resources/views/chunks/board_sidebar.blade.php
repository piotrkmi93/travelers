<div ng-controller="UserFriendsController" ng-init="userFriendsControllerInit({{Auth::user()->id}})">
    <div class="row">
        <div class="col-md-12">
            <input class="form-control" type="search" placeholder="Filtruj..." ng-model="search.phrase">
        </div>
    </div>

    <hr>

    <div class="row" ng-repeat="friend in friends">
        <div class="col-md-12">
            <a href="{{asset('user')}}<% '/' + friend.username %>#/board">
                <img src="<% friend.avatar_thumb %>" class="min-avatar pull-left" style="margin-right: 10px;">
                <h4 class="pull-left"><% friend.name %></h4>
                <small class="pull-right"><i class="fa fa-circle" ng-class="{'user-active': friend.is_active}" aria-hidden="true"></i></small>
            </a>
        </div>
    </div>
</div>