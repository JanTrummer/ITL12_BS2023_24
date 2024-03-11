<?php

class ChatModel{
    public static function getMessages($sender_id, $receiver_id){
        $database = DatabaseFactory::getFactory()->getConnection();

        $sql = "SELECT * FROM messages WHERE (sender = :sender_id AND receiver = :receiver_id) OR (sender = :receiver_id AND receiver = :sender_id)";
        $query = $database->prepare($sql);
        $query->execute(array(':sender_id' => $sender_id, ':receiver_id' => $receiver_id));

        $all_messages = array();

        foreach ($query->fetchAll() as $message){
            $all_messages[$message->id] = new stdClass();
            $all_messages[$message->id]->content = $message->content;
            $all_messages[$message->id]->sender = UserModel::getPublicProfileOfUser($message->sender);
            $all_messages[$message->id]->receiver = UserModel::getPublicProfileOfUser($message->receiver);
            $all_messages[$message->id]->read = $message->read;
            $all_messages[$message->id]->time = $message->time;
        }

        return $all_messages;
    }
}