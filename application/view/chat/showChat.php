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
            <span class="time-right"><?php echo($message->time)?></span>
         </div>
         <?php } ?>
         <?php if($message->receiver->user_id == Session::get("user_id")){ ?>
         <div class="chat darker" >
            <img style="float: right" src="<?= $message->sender->user_avatar_link; ?>"/>
            <h3 style="float: right"><?php echo($message->sender->user_name)?></h3>
            <p><?php echo($message->content)?></p>
            <span class="time-left"><?php echo($message->time)?></span>
         </div>
         <?php } ?>
      </div>
      <?php } ?>

      <form method="post" action="<?php echo Config::get('URL'); ?>chat/chat_action" method="post">
            <input type="text" name="message" placeholder="Your message" required />
            <input type="hidden" name="receiver_id" value=<?php echo($this->receiver->user_id)?>/>
            
            <input type="submit" value="Send" />
      </form>
   </div>
</div>
