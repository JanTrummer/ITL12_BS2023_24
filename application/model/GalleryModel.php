<?php

class GalleryModel{
 
    public static function addImage($user, $path){
        $database = DatabaseFactory::getFactory()->getConnection();
        $sql = "INSERT INTO gallery_images (user, path) VALUES (:user, :path);";
        $query = $database->prepare($sql);
        $query->execute(array(":user" => $user, ":path" => $path));
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