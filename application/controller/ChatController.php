<?php
class ChatController extends Controller{
    /**
     * Construct this object by extending the basic Controller class
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * This method controls what happens when you move to /chat/index in your app.
     */
    public function index()
    {
        if(!Session::userIsLoggedIn()){
            Redirect::home();
            return;
        }
        $this->View->render('chat/index', array(
            'users' => UserModel::getPublicProfilesOfAllUsers(),
            'groups' => ChatController::getUserGroups()
        ));
    }

    

    /**
     * Opens the chat window for the selected user
     */
    public function showChat($receiver_id){
        if (isset($receiver_id) && Session::userIsLoggedIn()) {
            $this->View->render('chat/showChat', array(
                'sender' => UserModel::getPublicProfileOfUser(Session::get('user_id')),
                'receiver' => UserModel::getPublicProfileOfUser($receiver_id),
                'messages' => ChatModel::getMessages(Session::get('user_id'), $receiver_id))
            );
        } else {
            Redirect::home();
        }
    }

    public function showGroupChat($groupID){
        if (isset($groupID) && Session::userIsLoggedIn()) {
            $this->View->render('chat/showGroupChat', array(
                "messages" => ChatModel::getGroupMessages($groupID),
                "groupID" => $groupID
            ));
        } else {
            Redirect::home();
        }
    }

    public function chat_action(){
        ChatModel::addMessage(Request::post("receiver_id"), Request::post("message"));
        Redirect::to("chat/showChat/". Request::post("receiver_id"));
    }

    public static function shouldDisplayNewMessageNotification($sender, $receiver){
        return ChatModel::hasNewMessage($receiver, $sender);
    }

    public static function getNewMessageCount($sender, $receiver){
        return ChatModel::getMessageCount($receiver, $sender);
    }

    public static function getGroupMemberNames($members){
        $names = array();

        for($i = 0; $i < count($members); $i++){
            $member = $members[$i];

            $names[$i] = UserModel::getUserNameByID($member);
        }

        return $names;
    }

    public static function getNameForUser($userID){
        return UserModel::getUserNameByID($userID);
    }

    public static function create_group(){
        $members = Request::post("groupMembers");
        $name = Request::post("groupName");

        ChatModel::createGroup($members, $name);
        Redirect::to("chat");
    }

    public static function getUserGroups(){
        $userGroups = array();
        $id = 0;

        foreach(ChatModel::getGroups() as $group){
            $members = $group->members;
            
            if(in_array(Session::get("user_id"), $members)){
                $userGroups[$id] = $group;
                $id = $id + 1;
            }
        }

        return $userGroups;
    }

    public function send_group_message(){
        $sender = Session::get("user_id");
        $message = Request::post("message");
        $group_id = Request::post("groupID");
        
        ChatModel::addGroupMessage($group_id, $sender, $message);
        Redirect::to("chat/showGroupChat/" . $group_id);
    }
}