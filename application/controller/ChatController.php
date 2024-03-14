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
            'users' => UserModel::getPublicProfilesOfAllUsers()
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

    public function chat_action(){
        ChatModel::addMessage(Request::post("receiver_id"), Request::post("message"));
        Redirect::to("chat/showChat/". Request::post("receiver_id"));
    }

    public static function shouldDisplayNewMessageNotification($sender, $receiver){
        return ChatModel::hasNewMessage($receiver, $sender);
    }
}