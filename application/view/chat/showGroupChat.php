<div class="container">
    <link rel="stylesheet" href="<?php echo Config::get('URL'); ?>css/chat.css" />
   <h1>Chat</h1>
   <div class="box">
   <table id="user_table" class="overview-table">
        <thead>
            <tr>
                <td>Benutzer</td>
                <td>Nachricht</td>
            </tr>
        </thead>
        <?php foreach ($this->messages as $message) { ?>
        <tr>
            <td><?php echo(ChatController::getNameForUser($message->sender)); ?></td>
            <td><?php echo($message->message)?></td>
        </tr>
        <?php }?>
    </table>
    <br>
    <form method="post" action="<?php echo Config::get('URL'); ?>chat/send_group_message" method="post">
        <input type="text" name="message" placeholder="message" required/>
        <input type="hidden" name="groupID" value=<?php echo($this->groupID)?>/>
        <input type="submit" value="Senden"/>
    </form>
   </div>
</div>
