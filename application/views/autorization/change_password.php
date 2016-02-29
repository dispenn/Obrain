
<div class="container-fluid"><!--closing tag in the footer.php-->
      <div class="row-fluid">
         
            <div class="span9">
                  <h2>Изменить пароль</h2>

                  <div id="infoMessage"><?php echo $message;?></div>

                  <form method="post" action="<?php echo base_url("autorization/change_password");?>">
                        <p>Действующий пароль:<br />
                              <?php echo form_input($old_password);?>
                        </p>
                        
                        <p>Новый пароль (не менее <?php echo $min_password_length;?> символов):<br />
                              <?php echo form_input($new_password);?>
                        </p>
                        
                        <p>Повторите новый пароль:<br />
                              <?php echo form_input($new_password_confirm);?>
                        </p>
                        
                        <?php echo form_input($user_id);?>
                        <p><input class="btn btn-primary btn-large" type="submit" value="Изменить"></p>
                  </form>
            </div><!--/span-->
      </div><!--/row-->
