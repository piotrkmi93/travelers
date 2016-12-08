<?php

namespace App\Repositories\Eloquent;

use App\Post;
use App\Repositories\PostRepositoryInterface;

class PostRepository implements PostRepositoryInterface {

    private $model;

    public function __construct(Post $model) {
        $this -> model = $model;
    }

    public function create($text, $photo_id, $author_user_id, $type) {
        $post = new Post();
        $post -> text = $text;
        if ($photo_id) $post -> photo_id = $photo_id;
        $post -> author_user_id = $author_user_id;
        $post -> type = $type;
        $post -> save();
        return $post -> id;
    }

    public function edit($id, $text) {
        $post = $this -> model -> find($id);
        $post -> text = $text;
        return $post -> save();
    }

    public function delete($id) {
        $post = $this -> model -> find($id);
        $post -> delete();
        return $post -> delete();
    }

    public function getById($id) {
        return $this -> model -> find($id);
    }

    /**
     * Zwraca posty jednego użytkownika
     * @param $author_user_id
     * @return mixed
     */
    public function getUserPosts($author_user_id, $offset){
        return $this -> model -> where('author_user_id', '=', $author_user_id) -> skip($offset) -> take(10) -> orderBy('created_at', 'desc') -> get();
    }

    /**
     * Zwraca posty użytkownika odpytującego oraz jego znajomych
     * @param $users
     * @return mixed
     */
    public function getUsersPosts($users, $offset){
        $users_ids = array();
        foreach ($users as $user) array_push($users_ids, $user->id);
        return $this -> model -> whereIn('author_user_id', $users_ids) -> skip($offset) -> take(10) -> orderBy('created_at', 'desc') -> get();
    }

    public function countUserPosts($user_id){
        return $this -> model -> where('author_user_id', '=', $user_id) -> count();
    }
}