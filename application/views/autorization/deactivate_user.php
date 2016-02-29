<div class="container-fluid"><!--closing tag in the footer.php-->
  <div class="row-fluid">
   
    <div class="span9">
      <h2>Отключить пользователя</h2>
      <p>Вы уверены, что хотите отключить пользователя '<?php echo $user->username; ?>'</p>

      <?php echo form_open("autorization/deactivate/".$user->id);?>

      <p>
       <label for="confirm">Да:  
       <input type="radio" name="confirm" value="yes" checked="checked" /></label>
       <label for="confirm">Нет:
       <input type="radio" name="confirm" value="no" /></label>
     </p>

     <?php echo form_hidden($csrf); ?>
     <?php echo form_hidden(array('id'=>$user->id)); ?>

     <p><input class="btn btn-primary btn-large" type="submit" value="Подтвердить"></p>

     <?php echo form_close();?>

   </div><!--/span-->
      </div><!--/row-->