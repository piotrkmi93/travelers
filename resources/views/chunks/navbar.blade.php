<div @if(Auth::user()) ng-controller="UserNavbarController" ng-init="navbarInit({{Auth::user()->id}})" @endif>
    <nav class="navbar navbar-default navbar-static-top">
        <div class="container">
            <div class="navbar-header">

                <!-- Collapsed Hamburger -->
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#app-navbar-collapse">
                    <span class="sr-only">Toggle Navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>

                <!-- Branding Image -->
                <a class="navbar-brand" href="{{ url('/') }}">
                    <img src="{{asset('images/logo-mini.png')}}" id="logo-mini"> Travelers
                </a>
            </div>

            <div class="collapse navbar-collapse" id="app-navbar-collapse">
                <!-- Left Side Of Navbar -->
                <ul class="nav navbar-nav">
                    @if (Auth::user())
                        <li><a href="{{ url('/') }}"><small><i class="fa fa-newspaper-o" aria-hidden="true"></i></small> Tablica</a></li>
                        <li><a href="{{ url('places/add') }}"><small><i class="fa fa-map-marker" aria-hidden="true"></i></small> Dodaj miejsce</a></li>
                        <li><a><small><i class="fa fa-map" aria-hidden="true"></i></small> Stwórz wycieczkę</a></li>
                        <li><a><small><i class="fa fa-search" aria-hidden="true"></i></small> Wyszukaj</a></li>
                    @endif
                </ul>

                <!-- Right Side Of Navbar -->
                <ul class="nav navbar-nav navbar-right">
                    <!-- Authentication Links -->
                    @if (Auth::guest())
                        <li><a href="{{ url('/login') }}">Logowanie</a></li>
                        <li><a href="{{ url('/register') }}">Rejestracja</a></li>
                    @else
                        <li><a style="cursor:pointer" class="animated" ng-class="{'swing': newNotifications}" ng-click="openNotificationsModal()"><i class="fa fa-bell-o notification-bell" aria-hidden="true"></i><small ng-show="notificationsCount" class="notification-bell-count"><% notificationsCount %></small></a></li>
                        <li><a href="{{ url('/messanger') }}"><i class="fa fa-comments messages-icon" aria-hidden="true"></i><small ng-show="newMessagesCount" class="messages-icon-count"><% newMessagesCount %></small></a></li>
                        <li class="dropdown">
                            <a class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                                <img class="min-avatar" src="{{ Auth::user()->avatar_photo_id != 0 ? asset(getThumb(Auth::user()->avatar_photo_id)) : asset('images/avatar_min_' . Auth::user()->sex . '.png')  }}"> {{ Auth::user()->first_name }} {{ Auth::user()->last_name }} <span class="caret"></span>
                            </a>

                            <ul class="dropdown-menu" role="menu">
                                <li><a href="{{ url('/user/' . Auth::user()->username ) . '#/board' }}"><i class="fa fa-btn fa-user"></i>Twój profil</a></li>
                                <li><a><i class="fa fa-btn fa-cog"></i>Ustawienia konta</a></li>
                                <li><a href="{{ url('/logout') }}"><i class="fa fa-btn fa-sign-out"></i>Wyloguj</a></li>
                            </ul>
                        </li>
                    @endif
                </ul>
            </div>
        </div>
    </nav>

    @if(Auth::user())
        @include('chunks.notificationsModal')
    @endif
</div>
