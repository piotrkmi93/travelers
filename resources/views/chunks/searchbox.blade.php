<form class="navbar-form navbar-left" ng-controller="SearchEngineController" ng-init="init({{ Auth::user()->id }})">
    <div class="form-group">

        <input id="search-input" type="text" class="form-control" placeholder="Wyszukaj ludzi oraz miejsca..." ng-model="phrase" ng-focus="focus()" ng-blur="focus()">

        <div id="search-result" ng-show="show && phrase.length">

            <h4 ng-if="users.length > 0">Użytkownicy <small>(<% users.length %>)</small>:</h4>
            <div class="search-result-user" ng-repeat="user in users">
                <img src="<% user.avatar %>" class="min-avatar">
                <a href="<% user.link %>"><strong><% user.name %></strong></a>
                <br>
                <small>
                    <span><i class="fa fa-circle" ng-class="{'user-active': user.is_active}"></i></span>
                    <span style="color: grey;"><i class="fa fa-map-marker"></i> <% user.city %></span>
                    <span style="color: blue;" ng-if="user.is_you"> <i class="fa fa-user"></i> Ty</span>
                    <span style="color: green;" ng-if="user.is_friend.isUserYourFriend && user.is_friend.isInvitationAccepted"> <i class="fa fa-users"></i> Znajomy</span>
                    <span style="color: goldenrod;" ng-if="user.is_friend.isUserYourFriend && !user.is_friend.isInvitationAccepted"> <i class="fa fa-user-plus"></i> Wysłano zaproszenie</span>
                    <span style="color: crimson;" ng-if="user.sex == 'm'"><i class="fa fa-mars"></i> Mężczyzna</span>
                    <span style="color: darkorchid;" ng-if="user.sex == 'f'"><i class="fa fa-venus"></i> Kobieta</span>
                </small>
            </div>

            <h4 ng-if="places.length > 0"><hr>Miejsca <small>(<% places.length %>)</small>:</h4>
            <div class="search-result-place" ng-repeat="place in places">
                <div class="search-result-place-symbol">
                    <i ng-if="place.place_type == 'attraction'" class="fa fa-university"></i>
                    <i ng-if="place.place_type == 'accommodation'" class="fa fa-bed"></i>
                </div>
                <a href="<% place.link %>"><strong><% place.name %></strong></a>
                <br>
                <small>
                    <span><i class="fa fa-heart like" ng-class="{'like-liked': place.is_liked}"></i> </span>
                    <span style="color: grey;"><i class="fa fa-map-marker"></i> <% place.city %></span>
                </small>
            </div>

        </div>
    </div>
</form>