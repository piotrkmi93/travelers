<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Travelers</title>

    <!-- WYSIWYG editor -->
    {{--<link href="https://cdnjs.cloudflare.com/ajax/libs/froala-editor/2.3.4/css/froala_editor.min.css" rel="stylesheet" type="text/css" />--}}
    {{--<link href="https://cdnjs.cloudflare.com/ajax/libs/froala-editor/2.3.4/css/froala_style.min.css" rel="stylesheet" type="text/css" />--}}
{{--    <link href="{{asset('assets/vendor/angular/textAngular.css')}}" rel="stylesheet" type="text/css" />--}}
    <link href="{{asset('assets/vendor/color-picker/color-picker.css')}}" rel="stylesheet" type="text/css">

    <!-- Fonts -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css" integrity="sha384-XdYbMnZ/QjLh6iI4ogqCTaIjrFk87ip+ekIjefZch0Y+PvJ8CDYtEs1ipDmPorQ+" crossorigin="anonymous">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Lato:100,300,400,700">
    {{--<link rel="stylesheet" href="{{ asset('assets/css/font-awesome.css') }}">--}}

    <!-- Styles -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">


    <link rel="stylesheet" href="{{asset('assets/css/animate.css')}}">

    <link rel="stylesheet" href="{{asset('assets/css/main.css')}}">

    <link rel="stylesheet" href="{{asset('assets/vendor/angular/ngGallery.css')}}">
</head>
<body id="app-layout" ng-app="app">
    @include('chunks.navbar')
    <div id="paginator"></div>
    @yield('content')

    @if(Auth::user())
    <div id="bug-report">
        <button class="btn btn-lg btn-warning" data-toggle="modal" data-target="#bug-report-modal"><i class="fa fa-bug" aria-hidden="true"></i> <span>Zgłoś błąd</span></button>
    </div>
        @include('chunks.bug_report')
    @endif

    <!-- JavaScripts -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.2.3/jquery.min.js" integrity="sha384-I6F5OKECLVtK/BL+8iSLDEHowSAfUo76ZL9+kGAgTRdiByINKJaqTPH/QVNS1VDb" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.6/js/bootstrap.min.js" integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS" crossorigin="anonymous"></script>
    {{--<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/froala-editor/2.3.4/js/froala_editor.min.js"></script>--}}
    <script src="{{asset('assets/js/scripts.js')}}"></script>
{{--    <script src="{{ asset('assets/vendor/jquery.js') }}"></script>--}}
    <script src='//maps.googleapis.com/maps/api/js?key=AIzaSyDITB_jJuL58WIVNvBN7LHS0XJbvEK6BwA&sensor=false'></script>
    {{-- AIzaSyDITB_jJuL58WIVNvBN7LHS0XJbvEK6BwA --}}

    {{-- Vendor js --}}
    <script src="{{asset('assets/vendor/angular/lodash.min.js')}}"></script>
    <script src="{{asset('assets/vendor/typeahead.js')}}"></script>
    <script src="{{asset('assets/vendor/angular/angular.js')}}"></script>
    <script src="{{asset('assets/vendor/angular/angular-simple-logger.js')}}"></script>
    <script src="{{asset('assets/vendor/angular/angular-google-maps.min.js')}}"></script>
    <script src="{{asset('assets/vendor/angular/angular-route.js')}}"></script>
    <script src="{{asset('assets/vendor/angular/angular-animate.js')}}"></script>
    <script src="{{asset('assets/vendor/angular/angular-sanitize.js')}}"></script>
    <script src="{{asset('assets/vendor/angular/ui-bootstrap-tpls-2.0.1.js')}}"></script>
    <script src="{{asset('assets/vendor/angular/angular-locale_pl-pl.js')}}"></script>
    <script src="{{asset('assets/vendor/angular/ng-infinite-scroll.js')}}"></script>
    <script src="{{asset('assets/vendor/color-picker/color-picker.js')}}"></script>
    <script src="{{asset('assets/vendor/angular/angular-wysiwyg.js')}}"></script>
    <script src="{{asset('assets/vendor/scrollglue.js')}}"></script>
    <script src="{{asset('assets/vendor/angular/ngGallery.js')}}"></script>


    {{-- Angular Application --}}
    <script src="{{asset('assets/js/app/app.js')}}"></script>
    <script src="{{asset('assets/js/app/config.js')}}"></script>

    <script src="{{asset('assets/js/app/modules/CityModule/CityModule.js')}}"></script>
    <script src="{{asset('assets/js/app/modules/CityModule/services/CitySearchService.js')}}"></script>

    <script src="{{asset('assets/js/app/modules/RegisterModule/RegisterModule.js')}}"></script>
    <script src="{{asset('assets/js/app/modules/RegisterModule/controllers/RegisterController.js')}}"></script>

    <script src="{{asset('assets/js/app/modules/UserModule/UserModule.js')}}"></script>
    <script src="{{asset('assets/js/app/modules/UserModule/controllers/UserViewController.js')}}"></script>
    <script src="{{asset('assets/js/app/modules/UserModule/controllers/CurrentUserController.js')}}"></script>
    <script src="{{asset('assets/js/app/modules/UserModule/controllers/UserController.js')}}"></script>
    <script src="{{asset('assets/js/app/modules/UserModule/controllers/UserNavbarController.js')}}"></script>
    <script src="{{asset('assets/js/app/modules/UserModule/controllers/UserInformationController.js')}}"></script>
    <script src="{{asset('assets/js/app/modules/UserModule/controllers/UserBoardController.js')}}"></script>
    <script src="{{asset('assets/js/app/modules/UserModule/controllers/UserGalleryController.js')}}"></script>
    <script src="{{asset('assets/js/app/modules/UserModule/controllers/UserPlaceController.js')}}"></script>
    <script src="{{asset('assets/js/app/modules/UserModule/services/UserService.js')}}"></script>

    <script src="{{asset('assets/js/app/modules/NotificationModule/NotificationModule.js')}}"></script>
    <script src="{{asset('assets/js/app/modules/NotificationModule/services/NotificationService.js')}}"></script>

    <script src="{{asset('assets/js/app/modules/UserModule/controllers/UserFriendsController.js')}}"></script>
    <script src="{{asset('assets/js/app/modules/UserModule/services/UserFriendsService.js')}}"></script>

    <script src="{{asset('assets/js/app/modules/PostModule/PostModule.js')}}"></script>
    <script src="{{asset('assets/js/app/modules/PostModule/controllers/PostController.js')}}"></script>
    <script src="{{asset('assets/js/app/modules/PostModule/services/PostService.js')}}"></script>
    <script src="{{asset('assets/js/app/modules/PostModule/directives/add-post/add-post.js')}}"></script>
    <script src="{{asset('assets/js/app/modules/PostModule/directives/post/post.js')}}"></script>
    <script src="{{asset('assets/js/app/modules/PostModule/directives/post-like/post-like.js')}}"></script>
    <script src="{{asset('assets/js/app/modules/PostModule/directives/post-delete/post-delete.js')}}"></script>

    <script src="{{asset('assets/js/app/modules/LikeModule/LikeModule.js')}}"></script>
    <script src="{{asset('assets/js/app/modules/LikeModule/services/LikeService.js')}}"></script>

    <script src="{{asset('assets/js/app/modules/CommentModule/CommentModule.js')}}"></script>
    <script src="{{asset('assets/js/app/modules/CommentModule/services/CommentService.js')}}"></script>
    <script src="{{asset('assets/js/app/modules/CommentModule/directives/add-comment/add-comment.js')}}"></script>
    <script src="{{asset('assets/js/app/modules/CommentModule/directives/comment/comment.js')}}"></script>
    <script src="{{asset('assets/js/app/modules/CommentModule/directives/comment-like/comment-like.js')}}"></script>
    <script src="{{asset('assets/js/app/modules/CommentModule/directives/comment-delete/comment-delete.js')}}"></script>

    <script src="{{asset('assets/js/app/modules/MessageModule/MessageModule.js')}}"></script>
    <script src="{{asset('assets/js/app/modules/MessageModule/controllers/MessageController.js')}}"></script>
    <script src="{{asset('assets/js/app/modules/MessageModule/controllers/ConversationController.js')}}"></script>
    <script src="{{asset('assets/js/app/modules/MessageModule/services/MessageService.js')}}"></script>

    <script src="{{asset('assets/js/app/modules/PlaceModule/PlaceModule.js')}}"></script>
    <script src="{{asset('assets/js/app/modules/PlaceModule/controllers/PlaceFormController.js')}}"></script>
    <script src="{{asset('assets/js/app/modules/PlaceModule/controllers/PlaceController.js')}}"></script>
    <script src="{{asset('assets/js/app/modules/PlaceModule/services/PlaceService.js')}}"></script>

    <script src="{{asset('assets/js/app/modules/BugReportModule/BugReportModule.js')}}"></script>
    <script src="{{asset('assets/js/app/modules/BugReportModule/controllers/BugReportController.js')}}"></script>
    <script src="{{asset('assets/js/app/modules/BugReportModule/services/BugReportService.js')}}"></script>

    <script src="{{asset('assets/js/app/modules/SearchEngineModule/SearchEngineModule.js')}}"></script>
    <script src="{{asset('assets/js/app/modules/SearchEngineModule/controllers/SearchEngineController.js')}}"></script>
    <script src="{{asset('assets/js/app/modules/SearchEngineModule/services/SearchEngineService.js')}}"></script>

    <script src="{{asset('assets/js/app/modules/TripModule/TripModule.js')}}"></script>
    <script src="{{asset('assets/js/app/modules/TripModule/controllers/TripFormController.js')}}"></script>
    <script src="{{asset('assets/js/app/modules/TripModule/controllers/TripController.js')}}"></script>
    <script src="{{asset('assets/js/app/modules/TripModule/services/TripService.js')}}"></script>
</body>
</html>
