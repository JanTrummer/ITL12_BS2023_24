<div class="container">
   <h1>Chat</h1>
   <div class="box">
      <!-- echo out the system feedback (error and success messages) -->
      <?php $this->renderFeedbackMessages(); ?>
      <div>
         <table id="user_table" class="overview-table">
            <thead>
               <tr>
                  <td>Avatar</td>
                  <td>Username</td>
                  <td>Chat</td>
               </tr>
            </thead>
            <?php foreach ($this->users as $user) { ?>
                <?php if($user->user_id != Session::get("user_id")): ?>
                <tr class="<?= ($user->user_active == 0 ? 'inactive' : 'active'); ?>">
                <td class="avatar">
                    <?php if (isset($user->user_avatar_link)) { ?>
                    <img src="<?= $user->user_avatar_link; ?>" />
                    <?php } ?>
                </td>
                <td <?php if(ChatController::shouldDisplayNewMessageNotification($user->user_id, Session::get("user_id"))){
                  echo("style='color: red'");
                  } ?>><?= $user->user_name; ?></td>
                <td><a href="<?= Config::get('URL') . 'chat/showChat/' . $user->user_id; ?>"><?php if(ChatController::shouldDisplayNewMessageNotification($user->user_id, Session::get("user_id"))){echo(" New messages: ". ChatController::getNewMessageCount($user->user_id, Session::get("user_id")));}else{echo("Chat");}?></a></td>
                <?php endif; ?>
            </tr>
            <?php } ?>
         </table>
         <h2>Groups</h2>
         <table id="group_name" class="overview-table">
            <thead>
               <tr>
                  <td>Name</td>
                  <td>Mitglieder</td>
                  <td>Chat</td>
               </tr>
            </thead>
            <?php foreach ($this->groups as $group) { ?>
               <td> <?php echo($group->name)?> </td>
               <td> <?php foreach(ChatController::getGroupMemberNames($group->members) as $name){
                  echo($name);
                  echo("<br>");
               } ?> </td>
               <td><a href="<?= Config::get('URL') . 'chat/showGroupChat/' . $group->id; ?>">Chat</a></td>
            </tr>
            <?php } ?>
         </table>
         <h2>Create Group</h2>
         <form method="post" action="<?php echo Config::get('URL'); ?>chat/create_group" method="post">
            <input type="text" name="groupName" placeholder="Gruppenname" required/>
            <input type="text" name="groupMembers" placeholder="1,2,3" required/>
            <input type="submit" value="submit"/>
         </form>
      </div>
   </div>
</div>
