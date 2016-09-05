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
</head>
<body id="app-layout" ng-app="app">
    @include('chunks.navbar')
    <div id="paginator"></div>
    @yield('content')

    <!-- JavaScripts -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.2.3/jquery.min.js" integrity="sha384-I6F5OKECLVtK/BL+8iSLDEHowSAfUo76ZL9+kGAgTRdiByINKJaqTPH/QVNS1VDb" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.6/js/bootstrap.min.js" integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS" crossorigin="anonymous"></script>
    {{--<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/froala-editor/2.3.4/js/froala_editor.min.js"></script>--}}
    <script src="{{asset('assets/js/scripts.js')}}"></script>
{{--    <script src="{{ asset('assets/vendor/jquery.js') }}"></script>--}}

    {{-- Vendor js --}}
    <script src="{{asset('assets/vendor/angular/angular.js')}}"></script>
    <script src="{{asset('assets/vendor/angular/angular-route.js')}}"></script>
    <script src="{{asset('assets/vendor/angular/angular-animate.js')}}"></script>
    <script src="{{asset('assets/vendor/angular/angular-sanitize.js')}}"></script>
    <script src="{{asset('assets/vendor/angular/ui-bootstrap-tpls-2.0.1.js')}}"></script>
    <script src="{{asset('assets/vendor/angular/angular-locale_pl-pl.js')}}"></script>
    <script src="{{asset('assets/vendor/angular/ng-infinite-scroll.js')}}"></script>
    {{--<script src="{{asset('assets/vendor/rangy/rangy-core.js')}}"></script>--}}
    {{--<script src="{{asset('assets/vendor/rangy/rangy-selectionsaverestore.js')}}"></script>--}}
    {{--<script src="{{asset('assets/vendor/angular/textAngularSetup.js')}}"></script>--}}
    {{--<script src="{{asset('assets/vendor/angular/textAngular.min.js')}}"></script>--}}
    <script src="{{asset('assets/vendor/color-picker/color-picker.js')}}"></script>
    <script src="{{asset('assets/vendor/angular/angular-wysiwyg.js')}}"></script>


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

</body>
</html>
