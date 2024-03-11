<div class="container">
    <link rel="stylesheet" href="<?php echo Config::get('URL'); ?>css/chat.css" />
   <h1>Chat</h1>
   <div class="box">
      <h2>You are chatting with <?php echo($this->receiver->user_name)?></h2>
      <?php foreach ($this->messages as $message) { ?>
      <div class="container">
         <?php if($message->sender->user_id == Session::get("user_id")){ ?>
         <div class="chat">
            <img src="<?= $message->sender->user_avatar_link; ?>" />
            <h3><?php echo($message->sender->user_name)?></h3>
            <p><?php echo($message->content)?></p>
            <span class="time-right">11:00</span>
         </div>
         <?php } ?>
         <?php if($message->receiver->user_id == Session::get("user_id")){ ?>
         <div class="chat darker">
            <img src="<?= $message->sender->user_avatar_link; ?>" class="right"/>
            <h3><?php echo($message->sender->user_name)?></h3>
            <p><?php echo($message->content)?></p>
            <span class="time-left">11:00</span>
         </div>
         <?php } ?>
      </div>
      <?php } ?>
   </div>
</div>
