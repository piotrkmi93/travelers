<div class="modal fade" id="notifications-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Powiadomienia</h4>
            </div>
            <div class="modal-body notification-modal-body">
                <div class="panel panel-default" ng-repeat="notification in notifications">
                    <div class="panel-body">

                        <div class="row">
                            <div class="col-md-1">
                                <img class="min-avatar" ng-src="<% notification.senderAvatar %>">
                            </div>
                            <div class="col-md-11 notification-body">

                                <div ng-if="notification.type == 'invitation'">
                                    <a href="<% notification.senderUrl %>"><strong><% notification.senderName %></strong></a> zaprosił(a) Cię do znajomych!<small class="pull-right"><% notification.date | date:'fullDate' %> o <% notification.time %></small><br>
                                    <a style="color:green;cursor:pointer;" ng-click="acceptInvitation({{Auth::user()->id}}, notification.senderId)">Przyjmij zaproszenie</a> |
                                    <a style="color:red;cursor:pointer;" ng-click="deleteFromFriends({{Auth::user()->id}}, notification.senderId)">Odrzuć zaproszenie</a>
                                </div>

                                <div ng-if="notification.type == 'trip-invitation'">
                                    <a href="<% notification.senderUrl %>"><strong><% notification.senderName %></strong></a> zaprosił(a) Cię na wycieczkę <a href="#"><strong><% notification.name %></strong></a>!<small class="pull-right"><% notification.date | date:'fullDate' %> o <% notification.time %></small><br>
                                    <a style="color:green;cursor:pointer;">Przyjmij zaproszenie</a> |
                                    <a style="color:red;cursor:pointer;">Odrzuć zaproszenie</a>
                                </div>

                                <div ng-if="notification.type == 'post-like'">
                                    <a href="<% notification.senderUrl %>"><strong><% notification.senderName %></strong></a> polubił(a) Twój post!<small class="pull-right"><% notification.date | date:'fullDate' %> o <% notification.time %></small><br>
                                    <span ng-bind-html="trustAsHtml(notification.post_text)"></span> | <a style="color:green;cursor:pointer;" ng-click="deleteNotification(notification.id, notification.type)">Usuń powiadomienie</a>
                                </div>

                                <div ng-if="notification.type == 'comment-like'">
                                    <a href="<% notification.senderUrl %>"><strong><% notification.senderName %></strong></a> polubił(a) Twój komentarz!<small class="pull-right"><% notification.date | date:'fullDate' %> o <% notification.time %></small><br>
                                    <span ng-bind-html="trustAsHtml(notification.comment_text)"></span> | <a style="color:green;cursor:pointer;" ng-click="deleteNotification(notification.id, notification.type)">Usuń powiadomienie</a>
                                </div>

                                <div ng-if="notification.type == 'place-like'">
                                    <a href="<% notification.senderUrl %>"><strong><% notification.senderName %></strong></a> polubił(a) miejsce <a href="<% notification.url %>"><strong><% notification.name %></strong></a>!<small class="pull-right"><% notification.date | date:'fullDate' %> o <% notification.time %></small><br>
                                    <a style="color:green;cursor:pointer;" ng-click="deleteNotification(notification.id, notification.type)">Usuń powiadomienie</a>
                                </div>

                                <div ng-if="notification.type == 'post-comment'">
                                    <a href="<% notification.senderUrl %>"><strong><% notification.senderName %></strong></a> skomentował(a) Twój post!<small class="pull-right"><% notification.date | date:'fullDate' %> o <% notification.time %></small><br>
                                    <span ng-bind-html="trustAsHtml(notification.comment_text)"></span> | <a style="color:green;cursor:pointer;" ng-click="deleteNotification(notification.id, notification.type)">Usuń powiadomienie</a>
                                </div>

                                <div ng-if="notification.type == 'place-comment'">
                                    <a href="<% notification.senderUrl %>"><strong><% notification.senderName %></strong></a> skomentował(a) miejsce <a href="<% notification.url %>"><strong><% notification.name %></strong></a>!<small class="pull-right"><% notification.date | date:'fullDate' %> o <% notification.time %></small><br>
                                    <span ng-bind-html="trustAsHtml(notification.comment_text)"></span> | <a style="color:green;cursor:pointer;" ng-click="deleteNotification(notification.id, notification.type)">Usuń powiadomienie</a>
                                </div>

                            </div>
                        </div>

                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Zamknij</button>
            </div>
        </div>
    </div>
</div>