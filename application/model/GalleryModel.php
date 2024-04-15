<?php

class GalleryModel{
 
    public static function addImage($user, $path){
        $database = DatabaseFactory::getFactory()->getConnection();
        $sql = "INSERT INTO gallery_images (user, path) VALUES (:user, :path);";
        $query = $database->prepare($sql);
        $query->execute(array(":user" => $user, ":path" => $path));
    }

    public static function getImages($user){
        $database = DatabaseFactory::getFactory()->getConnection();
        $sql = "SELECT id, path, shared_link FROM gallery_images WHERE user = :user";
        $query = $database->prepare($sql);
        $query->execute(array(":user" => $user));

        $data = $query->fetchAll();
        $paths = array();

        foreach ($data as $path){
            $paths[$path->id] = new stdClass();
            $paths[$path->id]->path = $path->path;
            $paths[$path->id]->public_link = $path->shared_link;
        }

        return $paths;
    }

    public static function deleteImage($user, $path){
        $database = DatabaseFactory::getFactory()->getConnection();
        $sql = "DELETE FROM gallery_images WHERE user = :user AND path = :path";
        $query = $database->prepare($sql);
        $query->execute(array(":user" => $user, ":path" => $path));
    }

    public static function addSharedLink($user, $path, $link){
        $database = DatabaseFactory::getFactory()->getConnection();
        $sql = "UPDATE gallery_images SET shared_link = :link WHERE user = :user AND path = :path";
        $query = $database->prepare($sql);
        $query->execute(array(":user" => $user, ":path" => $path, ":link" => $link));
    }

    public static function doesImageExist($user, $path){
        $database = DatabaseFactory::getFactory()->getConnection();
        $sql = "SELECT id FROM gallery_images WHERE user = :user AND path = :path";
        $query = $database->prepare($sql);
        $query->execute(array(":user" => $user, ":path" => $path));
        $query->fetchAll();

        if($query->rowCount() > 0){
            return true;
        }

        return false;
    }
}