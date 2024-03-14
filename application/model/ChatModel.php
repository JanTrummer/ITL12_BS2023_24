<?php

class ChatModel{

    public static function setMessageRead($message){
        $database = DatabaseFactory::getFactory()->getConnection();

        $sql = "UPDATE messages SET `read` = 1 WHERE id = :id";
        $query = $database->prepare($sql);
        $query->execute(array(':id' => $message));
    }

    public static function getMessages($sender_id, $receiver_id){
        $database = DatabaseFactory::getFactory()->getConnection();

        $sql = "SELECT * FROM messages WHERE (sender = :sender_id AND receiver = :receiver_id) OR (sender = :receiver_id AND receiver = :sender_id)";
        $query = $database->prepare($sql);
        $query->execute(array(':sender_id' => $sender_id, ':receiver_id' => $receiver_id));

        $all_messages = array();

        foreach ($query->fetchAll() as $message){
            if($message->sender == $receiver_id){
                ChatModel::setMessageRead($message->id);
            }

            $all_messages[$message->id] = new stdClass();
            $all_messages[$message->id]->content = $message->content;
            $all_messages[$message->id]->sender = UserModel::getPublicProfileOfUser($message->sender);
            $all_messages[$message->id]->receiver = UserModel::getPublicProfileOfUser($message->receiver);
            $all_messages[$message->id]->read = $message->read;
            $all_messages[$message->id]->time = $message->time;
        }

        return $all_messages;
    }

    public static function addMessage($receiver_id, $content){
        $database = DatabaseFactory::getFactory()->getConnection();
        $sql = "INSERT INTO messages (sender, receiver, content) VALUES (:sender, :receiver, :content)";
        $query = $database->prepare($sql);
        $query->execute(array(':sender' => Session::get("user_id"), ':receiver' => $receiver_id, ':content' => $content));
    }

    public static function hasNewMessage($receiver_id, $sender_id){
        $database = DatabaseFactory::getFactory()->getConnection();

        $sql = "SELECT id FROM messages WHERE receiver = :receiver AND sender = :sender AND `read`=0";
        $query = $database->prepare($sql);
        $query->execute(array(':receiver' => $receiver_id, ':sender' => $sender_id));

        if($query->rowCount() == 0){
            return false;
        }
        else{
            return true;
        }
    }
}