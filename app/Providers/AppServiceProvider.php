<?php

namespace App\Providers;

use App\Repositories\BugReportRepositoryInterface;
use App\Repositories\CityRepositoryInterface;
use App\Repositories\CommentLikeNotificationRepositoryInterface;
use App\Repositories\CommentLikeRepositoryInterface;
use App\Repositories\CommentNotificationRepositoryInterface;
use App\Repositories\CommentRepositoryInterface;
use App\Repositories\Eloquent\BugReportRepository;
use App\Repositories\Eloquent\CityRepository;
use App\Repositories\Eloquent\CommentLikeNotificationRepository;
use App\Repositories\Eloquent\CommentLikeRepository;
use App\Repositories\Eloquent\CommentNotificationRepository;
use App\Repositories\Eloquent\CommentRepository;
use App\Repositories\Eloquent\FriendsPairRepository;
use App\Repositories\Eloquent\GalleryRepository;
use App\Repositories\Eloquent\InvitationNotificationRepository;
use App\Repositories\Eloquent\LikeNotificationRepository;
use App\Repositories\Eloquent\LikeRepository;
use App\Repositories\Eloquent\MessageRepository;
use App\Repositories\Eloquent\NotificationRepository;
use App\Repositories\Eloquent\PhotoRepository;
use App\Repositories\Eloquent\PlaceCommentRepository;
use App\Repositories\Eloquent\PlaceLikeNotificationRepository;
use App\Repositories\Eloquent\PlaceLikeRepository;
use App\Repositories\Eloquent\PlaceRepository;
use App\Repositories\Eloquent\PostCommentRepository;
use App\Repositories\Eloquent\PostLikeNotificationRepository;
use App\Repositories\Eloquent\PostLikeRepository;
use App\Repositories\Eloquent\PostRepository;
use App\Repositories\Eloquent\TripCommentRepository;
use App\Repositories\Eloquent\TripInvitationNotificationRepository;
use App\Repositories\Eloquent\TripPlaceRepository;
use App\Repositories\Eloquent\TripRepository;
use App\Repositories\Eloquent\TripUserRepository;
use App\Repositories\Eloquent\UserRepository;
use App\Repositories\FriendsPairRepositoryInterface;
use App\Repositories\GalleryRepositoryInterface;
use App\Repositories\InvitationNotificationRepositoryInterface;
use App\Repositories\LikeNotificationRepositoryInterface;
use App\Repositories\LikeRepositoryInterface;
use App\Repositories\MessageRepositoryInterface;
use App\Repositories\NotificationRepositoryInterface;
use App\Repositories\PhotoRepositoryInterface;
use App\Repositories\PlaceCommentRepositoryInterface;
use App\Repositories\PlaceLikeNotificationRepositoryInterface;
use App\Repositories\PlaceLikeRepositoryInterface;
use App\Repositories\PlaceRepositoryInterface;
use App\Repositories\PostCommentRepositoryInterface;
use App\Repositories\PostLikeNotificationRepositoryInterface;
use App\Repositories\PostLikeRepositoryInterface;
use App\Repositories\PostRepositoryInterface;
use App\Repositories\TripCommentRepositoryInterface;
use App\Repositories\TripInvitationNotificationRepositoryInterface;
use App\Repositories\TripPlaceRepositoryInterface;
use App\Repositories\TripRepositoryInterface;
use App\Repositories\TripUserRepositoryInterface;
use App\Repositories\UserRepositoryInterface;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        // single models repositories
        $this -> app -> singleton(CityRepositoryInterface::class, CityRepository::class);
        $this -> app -> singleton(UserRepositoryInterface::class, UserRepository::class);
        $this -> app -> singleton(GalleryRepositoryInterface::class, GalleryRepository::class);
        $this -> app -> singleton(PhotoRepositoryInterface::class, PhotoRepository::class);
        $this -> app -> singleton(FriendsPairRepositoryInterface::class, FriendsPairRepository::class);
        $this -> app -> singleton(PostRepositoryInterface::class, PostRepository::class);

        // comments repositories
        $this -> app -> singleton(CommentRepositoryInterface::class, CommentRepository::class);
        $this -> app -> singleton(PostCommentRepositoryInterface::class, PostCommentRepository::class);

        // notifications repositories
        $this -> app -> singleton(NotificationRepositoryInterface::class, NotificationRepository::class);
        $this -> app -> singleton(InvitationNotificationRepositoryInterface::class, InvitationNotificationRepository::class);
        $this -> app -> singleton(CommentNotificationRepositoryInterface::class, CommentNotificationRepository::class);

        // likes notifications repositories
        $this -> app -> singleton(LikeNotificationRepositoryInterface::class, LikeNotificationRepository::class);
        $this -> app -> singleton(PostLikeNotificationRepositoryInterface::class, PostLikeNotificationRepository::class);
        $this -> app -> singleton(CommentLikeNotificationRepositoryInterface::class, CommentLikeNotificationRepository::class);

        // likes repositories
        $this -> app -> singleton(LikeRepositoryInterface::class, LikeRepository::class);
        $this -> app -> singleton(PostLikeRepositoryInterface::class, PostLikeRepository::class);
        $this -> app -> singleton(CommentLikeRepositoryInterface::class, CommentLikeRepository::class);

        // messages repository
        $this -> app -> singleton(MessageRepositoryInterface::class, MessageRepository::class);

        // places repositories
        $this -> app -> singleton(PlaceRepositoryInterface::class, PlaceRepository::class);
        $this -> app -> singleton(PlaceCommentRepositoryInterface::class, PlaceCommentRepository::class);
        $this -> app -> singleton(PlaceLikeRepositoryInterface::class, PlaceLikeRepository::class);
        $this -> app -> singleton(PlaceLikeNotificationRepositoryInterface::class, PlaceLikeNotificationRepository::class);

        // trips repositories
        $this -> app -> singleton(TripRepositoryInterface::class, TripRepository::class);
        $this -> app -> singleton(TripPlaceRepositoryInterface::class, TripPlaceRepository::class);
        $this -> app -> singleton(TripUserRepositoryInterface::class, TripUserRepository::class);
        $this -> app -> singleton(TripCommentRepositoryInterface::class, TripCommentRepository::class);
        $this -> app -> singleton(TripInvitationNotificationRepositoryInterface::class, TripInvitationNotificationRepository::class);

        // bug reports
        $this -> app -> singleton(BugReportRepositoryInterface::class, BugReportRepository::class);
    }
}
