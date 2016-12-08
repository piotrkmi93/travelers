@extends('layouts.app')

@section('content')
    <div class="container" >
        <div class="row" @if ( isCurrentUser($user->id) ) ng-controller="CurrentUserController" @else ng-controller="UserController" ng-init="otherUserInit({{Auth::user()->id}}, {{$user->id}})" @endif>
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-body text-center panel-content panel-user">
                        <div class="row" @if($user->background_photo_id != 0) style="background-image: url( {{asset(getImage($user->background_photo_id))}}) !important" @endif>
                            @if ( isCurrentUser($user->id) )
                                <form action="{{ url('user/change_background') }}" method="post" enctype="multipart/form-data">
                                    <input id="background-input" type="file" style="display: none;" accept="image/*" name="background">
                                    <a type="button" ng-click="chooseBackgroundClick()" class="btn btn-sm btn-primary" style="margin: 10px 10px 0 0; float: right;" ><i class="fa fa-camera" aria-hidden="true"></i> Zmień zdjęcie w tle</a>
                                    <button id="background-submit" type="submit" style="display: none;"></button>
                                </form>
                            @endif
                            <div class="col-md-4 col-md-offset-4">
                                <img src="{{ $user->avatar_photo_id != 0 ? asset(getImage($user->avatar_photo_id)) : asset('images/avatar_' . $user->sex . '.png')  }}">
                                @if ( isCurrentUser($user->id) )
                                    <form action="{{ url('user/change_avatar') }}" method="post" enctype="multipart/form-data">
                                        <input id="avatar-input" type="file" style="display: none;" accept="image/*" name="avatar">
                                        <a type="button" ng-click="chooseAvatarClick()" class="btn btn-sm btn-primary" style="position: absolute; bottom: 10%; right: 15%;" ><i class="fa fa-camera" aria-hidden="true"></i> Zmień avatar</a>
                                        <button id="avatar-submit" type="submit" style="display: none;"></button>
                                    </form>

                                @endif
                            </div>
                        </div>
                        <div class="row">
                            <h1><small><i class="fa fa-circle @if(isActive($user)) user-active @endif" aria-hidden="true"></i></small> {{ $user -> first_name }} {{ $user -> last_name }} <small>{{ '@' . $user -> username }}</small></h1>
                            @if ( !isCurrentUser($user->id) )
                                <hr>
                                <button ng-if="isUserYourFriend == false && isInvitationAccepted == false" ng-click="sendInvitation(yourId, userId)" type="button" class="btn btn-primary"><i class="fa fa-user-plus" aria-hidden="true"></i> Dodaj znajomego</button>
                                <button ng-if="isUserYourFriend == true && isInvitationAccepted == false && {{Auth::user()->id}} == invitationFrom" ng-click="deleteFromFriends(yourId, userId)" type="button" class="btn btn-danger"><i class="fa fa-user-times" aria-hidden="true"></i> Anuluj zaproszenie</button>
                                <button ng-if="isUserYourFriend == true && isInvitationAccepted == false && {{Auth::user()->id}} != invitationFrom" ng-click="acceptInvitation(yourId, userId)" type="button" class="btn btn-success"><i class="fa fa-user-plus" aria-hidden="true"></i> Przyjmij zaproszenie</button>
                                <button ng-if="isUserYourFriend == true && isInvitationAccepted == false && {{Auth::user()->id}} != invitationFrom" ng-click="deleteFromFriends(yourId, userId)" type="button" class="btn btn-danger"><i class="fa fa-user-times" aria-hidden="true"></i> Odrzuć zaproszenie</button>
                                <button ng-if="isUserYourFriend == true && isInvitationAccepted == true" ng-click="deleteFromFriends(yourId, userId)" type="button" class="btn btn-danger"><i class="fa fa-user-times" aria-hidden="true"></i> Usuń ze znajomych</button>
                                <button ng-if="isUserYourFriend == undefined && isInvitationAccepted == undefined" type="button" class="btn btn-default" disabled><i class="fa fa-cog spinning" aria-hidden="true"></i> Przetwarzam...</button>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-2 col-md-offset-2 user-sidebar">

                <div class="panel panel-default">
                    <div class="panel-body panel-content">

                        <div class="user-sidebar-item">
                            <a href="#/board">
                                <h1><i class="fa fa-newspaper-o" aria-hidden="true"></i></h1>
                                <h3>Tablica</h3>
                            </a>
                        </div>

                        <div class="user-sidebar-item">
                            <a href="#/information">
                                <h1><i class="fa fa-info-circle" aria-hidden="true"></i></h1>
                                <h3>Informacje</h3>
                            </a>
                        </div>

                        <div class="user-sidebar-item">
                            <a href="#/gallery">
                                <h1><i class="fa fa-picture-o" aria-hidden="true"></i></h1>
                                <h3>Galeria</h3>
                            </a>
                        </div>

                        <div class="user-sidebar-item">
                            <a href="#/friends">
                                <h1><i class="fa fa-users" aria-hidden="true"></i></h1>
                                <h3>Znajomi</h3>
                            </a>
                        </div>

                        <div class="user-sidebar-item">
                            <a href="#/places">
                                <h1><i class="fa fa-map-marker" aria-hidden="true"></i></h1>
                                <h3>Miejsca</h3>
                            </a>
                        </div>

                        <div class="user-sidebar-item">
                            {{--<a href="#/board">--}}
                                <h1><i class="fa fa-map" aria-hidden="true"></i></h1>
                                <h3>Wycieczki</h3>
                            {{--</a>--}}
                        </div>


                        {{--<a href="#/information"><h5><i class="fa fa-info-circle" aria-hidden="true"></i> Informacje</h5></a>--}}
                        {{--<a href="#/gallery"><h5><i class="fa fa-picture-o" aria-hidden="true"></i> Galeria</h5></a>--}}
                        {{--<a href="#/friends"><h5><i class="fa fa-users" aria-hidden="true"></i> Znajomi</h5></a>--}}
                        {{--<h5><i class="fa fa-map" aria-hidden="true"></i> Wycieczki</h5>--}}
                        {{--<h5><i class="fa fa-map-marker" aria-hidden="true"></i> Miejsca</h5>--}}
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-md-offset-4">
                <div ng-controller="UserViewController" ng-init="viewInit({{$user->id}}, {{Auth::user()->id}})" ng-view></div>
            </div>
        </div>
    </div>


@endsection
