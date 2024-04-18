<?php

class ChatModel{

    public static function setMessageRead($message){
        $database = DatabaseFactory::getFactory()->getConnection();

        $sql = "CALL setMessageToRead(?)";
        $query = $database->prepare($sql);
        $query->bindParam(1, $message);
        $query->execute();
        $query->closeCursor();
    }

    public static function getMessages($sender_id, $receiver_id){
        $database = DatabaseFactory::getFactory()->getConnection();

        $sql = "CALL getMessages(?, ?)";
        $query = $database->prepare($sql);
        $query->bindParam(1, $sender_id);
        $query->bindParam(2, $receiver_id);
        $query->execute();

        $all_messages = array();
        $data = $query->fetchAll();
        $query->closeCursor();

        foreach ($data as $message){
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
        $sql = "CALL addMessage(?, ?, ?)";
        $query = $database->prepare($sql);
        $sender_id = Session::get("user_id");
        $query->bindParam(1, $sender_id);
        $query->bindParam(2, $receiver_id);
        $query->bindParam(3, $content);
        $query->execute();
        $query->closeCursor();
    }

    public static function hasNewMessage($receiver_id, $sender_id){
        $database = DatabaseFactory::getFactory()->getConnection();

        $sql = "SELECT id FROM messages WHERE receiver = :receiver AND sender = :sender AND `read`=0";
        $query = $database->prepare($sql);
        $query->execute(array(':receiver' => $receiver_id, ':sender' => $sender_id));

        if($query->rowCount() == 0){
            $query->closeCursor();
            return false;
        }
        else{
            $query->closeCursor();
            return true;
        }
    }

    public static function getMessageCount($receiver_id, $sender_id){
        $database = DatabaseFactory::getFactory()->getConnection();

        $sql = "SELECT id FROM messages WHERE receiver = :receiver AND sender = :sender AND `read`=0";
        $query = $database->prepare($sql);
        $query->execute(array(':receiver' => $receiver_id, ':sender' => $sender_id));
        $count = $query->rowCount();
        
        $query->closeCursor();

        return $count;
    }

    public static function getMembers($groupID){
        $database = DatabaseFactory::getFactory()->getConnection();

        $sql = "SELECT members FROM groups WHERE id = :id";
        $query = $database->prepare($sql);
        $query->execute(array(':id' => $groupID));
        $data = $query->fetchAll();
        $members = array_map('intval', explode(",", $data[0]->members));

        return $members;
    }

    public static function getGroupMessages($groupID){
        $database = DatabaseFactory::getFactory()->getConnection();

        $sql = "SELECT sender, message FROM groupMessages WHERE groupID = :groupID";
        $query = $database->prepare($sql);
        $query->execute(array(":groupID" => $groupID));
        $data = $query->fetchAll();

        $messages = array();
        for($i = 0; $i < count($data); $i++){
            $messages[$i] = new stdClass;
            
            $messages[$i]->sender = $data[$i]->sender;
            $messages[$i]->message = $data[$i]->message;
        }

        return $messages;
    }

    public static function getGroups(){
        $database = DatabaseFactory::getFactory()->getConnection();

        $sql = "SELECT id, name FROM groups";
        $query = $database->prepare($sql);
        $query->execute();

        $data = $query->fetchAll();
        $groups = array();
        
        for($i = 0; $i < count($data); $i++){
            $entry = $data[$i];

            $groups[$i] = new stdClass();
            $groups[$i]->id = $entry->id;
            $groups[$i]->name = $entry->name;
            $groups[$i]->members = ChatModel::getMembers($entry->id);
        }

        return $groups;
    }

    public static function createGroup($name, $members){
        $database = DatabaseFactory::getFactory()->getConnection();

        $sql = "INSERT INTO groups (members, name) VALUES (:name, :members)";
        $query = $database->prepare($sql);
        $query->execute(array(':members' => $members, ':name' => $name));
    }

    public static function addGroupMessage($groupID, $sender, $message){
        $database = DatabaseFactory::getFactory()->getConnection();

        $sql = "INSERT INTO groupmessages (groupID, sender, message) VALUES (:groupID, :sender, :message)";
        $query = $database->prepare($sql);
        $query->execute(array(":groupID" => $groupID, ":sender" => $sender, ":message" => $message));   
    }
}