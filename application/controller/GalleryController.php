<?php

class GalleryController extends Controller
{
    public function __construct(){
        parent::__construct();
    }

    public function index(){
        $this->View->render('gallery/index');
    }

    public function image_upload(){
        $user_id = Session::get("user_id");
        $filename = basename($_FILES["fileToUpload"]["name"]);
        $target_dir = "..\\gallery\\". $user_id;
        if(GalleryModel::doesImageExist($user_id, $target_dir . "\\". $filename)){
            echo("<h1>Image already exists</h1>");
            return;
        }
        
        if(!file_exists($target_dir)){
            mkdir($target_dir);
        }
        move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_dir . "\\". $filename);
        GalleryModel::addImage($user_id, $filename);
        Redirect::to("gallery/index");
    }

    public function delete(){
        $user_id = Session::get("user_id");
        $image = Request::post("image");
        
        GalleryModel::deleteImage($user_id, $image);

        Redirect::to("gallery/index");
    }

    public function share(){
        $user_id = Session::get("user_id");
        $image = Request::post("image");
        $path = "..\\gallery\\". $user_id . "\\" . $image;
        $public_path = "shared_images/" . sha1_file($path) . ".jpeg";
        copy($path, $public_path);
        
        GalleryModel::addSharedLink($user_id, $image, $public_path);

        Redirect::to("gallery/index");
    }
}
