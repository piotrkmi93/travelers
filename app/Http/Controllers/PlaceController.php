<?php

namespace App\Http\Controllers;

use App\Repositories\CityRepositoryInterface;
use App\Repositories\CommentLikeNotificationRepositoryInterface;
use App\Repositories\CommentLikeRepositoryInterface;
use App\Repositories\CommentNotificationRepositoryInterface;
use App\Repositories\CommentRepositoryInterface;
use App\Repositories\GalleryRepositoryInterface;
use App\Repositories\LikeNotificationRepositoryInterface;
use App\Repositories\LikeRepositoryInterface;
use App\Repositories\NotificationRepositoryInterface;
use App\Repositories\PhotoRepositoryInterface;
use App\Repositories\PlaceCommentRepositoryInterface;
use App\Repositories\PlaceLikeNotificationRepositoryInterface;
use App\Repositories\PlaceLikeRepositoryInterface;
use App\Repositories\PlaceRepositoryInterface;
use App\Repositories\PostRepositoryInterface;
use App\Repositories\UserRepositoryInterface;
use Faker\Provider\File;
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\Auth;

class PlaceController extends Controller
{
    private $placeRepository,
            $photoRepository,
            $userRepository,
            $likeRepository,
            $placeLikeRepository,
            $notificationRepository,
            $likeNotificationRepository,
            $placeLikeNotificationRepository,
            $placeCommentRepository,
            $postRepository,
            $galleryRepository,
            $cityRepository,
            $commentRepository,
            $commentLikeRepository,
            $commentLikeNotificationRepository,
            $commentNotificationRepository;

    /**
     * PlaceController constructor.
     * @param PlaceRepositoryInterface $placeRepository
     * @param PhotoRepositoryInterface $photoRepository
     * @param UserRepositoryInterface $userRepository
     * @param LikeRepositoryInterface $likeRepository
     * @param PlaceLikeRepositoryInterface $placeLikeRepository
     * @param NotificationRepositoryInterface $notificationRepository
     * @param LikeNotificationRepositoryInterface $likeNotificationRepository
     * @param PlaceLikeNotificationRepositoryInterface $placeLikeNotificationRepository
     * @param PlaceCommentRepositoryInterface $placeCommentRepository
     * @param PostRepositoryInterface $postRepository
     * @param GalleryRepositoryInterface $galleryRepository
     * @param CityRepositoryInterface $cityRepository
     * @param CommentRepositoryInterface $commentRepository
     * @param CommentLikeRepositoryInterface $commentLikeRepository
     * @param CommentLikeNotificationRepositoryInterface $commentLikeNotificationRepository
     * @param CommentNotificationRepositoryInterface $commentNotificationRepository
     */
    public function __construct(PlaceRepositoryInterface $placeRepository,
                                PhotoRepositoryInterface $photoRepository,
                                UserRepositoryInterface $userRepository,
                                LikeRepositoryInterface $likeRepository,
                                PlaceLikeRepositoryInterface $placeLikeRepository,
                                NotificationRepositoryInterface $notificationRepository,
                                LikeNotificationRepositoryInterface $likeNotificationRepository,
                                PlaceLikeNotificationRepositoryInterface $placeLikeNotificationRepository,
                                PlaceCommentRepositoryInterface $placeCommentRepository,
                                PostRepositoryInterface $postRepository,
                                GalleryRepositoryInterface $galleryRepository,
                                CityRepositoryInterface $cityRepository,
                                CommentRepositoryInterface $commentRepository,
                                CommentLikeRepositoryInterface $commentLikeRepository,
                                CommentLikeNotificationRepositoryInterface $commentLikeNotificationRepository,
                                CommentNotificationRepositoryInterface $commentNotificationRepository){

        $this -> placeRepository = $placeRepository;
        $this -> photoRepository = $photoRepository;
        $this -> userRepository = $userRepository;
        $this -> likeRepository = $likeRepository;
        $this -> placeLikeRepository = $placeLikeRepository;
        $this -> notificationRepository = $notificationRepository;
        $this -> likeNotificationRepository = $likeNotificationRepository;
        $this -> placeLikeNotificationRepository = $placeLikeNotificationRepository;
        $this -> placeCommentRepository = $placeCommentRepository;
        $this -> postRepository = $postRepository;
        $this -> galleryRepository = $galleryRepository;
        $this -> cityRepository = $cityRepository;
        $this -> commentRepository = $commentRepository;
        $this -> commentLikeRepository = $commentLikeRepository;
        $this -> commentLikeNotificationRepository = $commentLikeNotificationRepository;
        $this -> commentNotificationRepository = $commentNotificationRepository;
    }

    /**
     * Zwraca widok miejsca
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request){
        $slug = $request -> slug;
        $place = $this -> placeRepository -> getBySlug( $slug );
        $images = $this -> photoRepository -> getByGalleryId( $place -> gallery_id ) -> toArray();
        $city = $this -> cityRepository -> getById($place -> city_id);
        $user = $this -> userRepository -> getById($place -> author_user_id);

        foreach($images as &$image){
            $image['img'] = asset($image['image_url']);
            $image['thumb'] = asset($image['thumb_url']);
            unset($image['image_url']);
            unset($image['thumb_url']);
            unset($image['id']);
            unset($image['gallery_id']);
        }

        $liked = $this -> placeLikeRepository -> exists(Auth::user()->id, $place->id) ? 1 : 0;
        $likes = $this -> placeLikeRepository -> count($place->id);

        $images = json_encode( $images );

        return view( 'places.index', compact( 'place', 'images', 'liked', 'likes', 'city', 'user' ) );
    }

    /**
     * Zwraca formularz do stworzenia lub edycji miejsca
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function getPlaceForm(Request $request){
        $slug = $request -> slug;

        if ( !isset($slug) ) {
            return view( 'places.form' );
        }

        $place = $this -> placeRepository -> getBySlug( $slug );
        $city = $this -> cityRepository -> getById($place -> city_id);

        if ($place->author_user_id != Auth::user()->id){
            return back();
        }

        $images = $this -> photoRepository -> getByGalleryId( $place -> gallery_id )->toArray();
        foreach ($images as &$image){
            $image = asset($image['thumb_url']);
        }
        $images = json_encode($images);

        return view( 'places.form', compact( 'place', 'images', 'city' ) );
    }

    /**
     * Tworzy nowe miejsce na podstawie przesłanych danych
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function savePlace(Request $request){

        $user_id = Auth::user()->id;
        $name = $request -> name;
        $slug = $request -> slug;
        $place_type = $request -> place_type;
        $short_description = $request -> short_description;
        $long_description = $request -> long_description;
        $phone = $request -> phone;
        $address = $request -> address;
        $email = $request -> email;
        $latitude = $request -> latitude;
        $longitude = $request -> longitude;
        $city_id = $request -> city_id;

        $place = $this -> placeRepository -> getBySlug($slug);

        if(isset($place)){
            $place = $this -> placeRepository -> edit($place->id, $name, $slug, $short_description, $long_description, $phone, $address, $email, $latitude, $longitude, $city_id);
        } else {
            $gallery_id = $this -> galleryRepository -> create();
            $place = $this -> placeRepository -> create($name, $slug, $short_description, $long_description, $gallery_id, $phone, $address, $email, $latitude, $longitude, $place_type, $user_id, $city_id);
        }

        if (isset($request->images) && count($request->images) > 0) {
            foreach ($request->images as $image) {
                $this->photoRepository->add($image, $place->gallery_id, 'normal');
            }
        }

        return redirect('places/' . $place -> slug);
    }

    /**
     * Zwraca miejsca stworzone przez użytkownika
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getUserPlaces(Request $request){
        $user_id = $request -> user_id;
        $places = $this -> placeRepository -> getByAuthorId($user_id) -> toArray();

        foreach($places as &$place){
            $place['url'] = asset('places/' . $place['slug']);
            $place['is_owner'] = $user_id == Auth::user() -> id;
            if($place['is_owner']) $place['edit_url'] = asset('places/edit/' . $place['slug']);
            unset($place['slug']);
        }

        return response() -> json(array('places' => $places));
    }

    /**
     * Usuwa miejsce oraz wszystkie jego elementy
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function deletePlace(Request $request){
        $place = $this -> placeRepository -> getById($request -> place_id);

        $placeLikes = $this -> placeLikeRepository -> getAllByPlaceId($place->id); // wszystkie lajki miejsca
        foreach ($placeLikes as $placeLike){
            $this -> likeRepository -> delete($placeLike -> like_id); // usuń lajk miejsca
            $this -> placeLikeRepository -> delete($placeLike -> id); // usuń lajk
        }

        $placeLikeNotifications = $this -> placeLikeNotificationRepository -> getByPlaceId($place->id); // notyfikacje o polubieniach miejsca
        foreach ($placeLikeNotifications as $placeLikeNotification){
            $likeNotification = $this -> likeNotificationRepository -> get($placeLikeNotification -> like_notification_id);
            $this -> notificationRepository -> delete($likeNotification -> notification_id); // usuń notyfikację
            $this -> likeNotificationRepository -> delete($likeNotification -> id); // usuń notyfikację o lajku
            $this -> likeNotificationRepository -> delete($placeLikeNotification -> id);
        }

        $placeComments = $this -> placeCommentRepository -> getByPlaceId($place -> id); // wszystkie komentarze miejsca
        foreach($placeComments as $placeComment){
            $comment = $this -> commentRepository -> getById($placeComment -> comment_id);

            $commentLikes = $this -> commentLikeRepository -> getByCommentId($comment->id); // wszystkie lajki tego komentarza
            foreach ($commentLikes as $commentLike){
                $this -> likeRepository -> delete($commentLike -> like_id); // usuń lajka
                $this -> commentLikeRepository -> delete($commentLike->id); // usuń lajka komentarza
            }

            $commentLikeNotifications = $this -> commentLikeNotificationRepository -> getByCommentId($comment -> id); // notyfikacje odnośnie lajków tego komentarza
            foreach ($commentLikeNotifications as $commentLikeNotification){
                $likeNotification = $this -> likeNotificationRepository -> get($commentLikeNotification->like_notification_id); // notyfikacja polubienia
                $this -> notificationRepository -> delete($likeNotification -> notification_id); // usuń notyfikację
                $this -> likeNotificationRepository -> delete($likeNotification -> id); // usuń notyfikację polubienia
                $this -> commentLikeNotificationRepository -> delete($commentLikeNotification->id); // usuń notyfikację polubienia komentarza
            }

            $commentNotifications = $this -> commentNotificationRepository -> getByCommentId($comment -> id); // notyfikacje odnośnie komentowania
            foreach ($commentNotifications as $commentNotification){
                $this -> notificationRepository -> delete($commentNotification -> notification_id);
                $this -> commentNotificationRepository -> delete($commentNotification -> id);
            }

            $this -> commentRepository -> delete($comment -> id);
            $this -> placeCommentRepository -> delete($placeComment -> id);
        }

        $photos = $this -> photoRepository -> getByGalleryId($place -> gallery_id);
        foreach ($photos as $photo)
            $this -> photoRepository -> delete($photo->id);
        $this -> galleryRepository -> delete($place -> gallery_id);

        return response() -> json(['success' => $this -> placeRepository -> delete($place->id)]);
    }

    /**
     * Tworzy polubienie miejsca
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function like(Request $request){
        $place_id = $request -> place_id;
        $user_id = $request -> user_id;
        $like_id = $this -> likeRepository -> create($user_id, 'place');
        $place_like_id = $this -> placeLikeRepository -> create($place_id, $like_id);

        $place_author_user_id = $this -> placeRepository -> getById($place_id) -> author_user_id;
        if($user_id != $place_author_user_id){
            $notification_id = $this -> notificationRepository -> create($place_author_user_id, 'like');
            $like_notification_id = $this -> likeNotificationRepository -> create($like_id, $notification_id, 'place');
            $this -> placeLikeNotificationRepository -> create($like_notification_id, $place_id);
        }

        return response() -> json($place_like_id);
    }

    /**
     * Usuwa polubienie miejsca
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function unlike(Request $request){
        $place_id = $request -> place_id;
        $user_id = $request -> user_id;

        $find = $this -> placeLikeRepository -> find($user_id, $place_id)[0];
        $this -> placeLikeRepository -> delete($find->place_like_id);
        return response() -> json(
            $this -> likeRepository -> delete($find->like_id)
        );
    }

    /**
     * Sprawdza czy istnieje już miejsce o takim slugu
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function isSlugExists(Request $request){
        $place = $this -> placeRepository -> getBySlug($request -> slug);
        return response() -> json( isset( $place ) ? true : false );
    }

    /**
     * Przeszukuje miejsca na podstawie frazy oraz id miasta
     *
     * @param int $city_id
     * @param string $phrase
     * @return \Illuminate\Http\JsonResponse
     */
    public function getByPhraseAndCityId($city_id, $phrase)
    {
        return response() -> json(['places' => $this -> placeRepository -> getByPhraseAndCityId($phrase, $city_id)]);
    }
}
