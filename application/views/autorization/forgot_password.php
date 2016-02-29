<div class="container-fluid"><!--closing tag in the footer.php-->
  <div class="row-fluid">
       <div class="span9">
<h1>Забыл пароль</h1>
<p>Введите электронную почту указанную при регистрации, на него прийдёт письмо с новым паролем.</p>

<div id="infoMessage"><?php echo $message;?></div>

<?php echo form_open("autorization/forgot_password");?>

      <p>
      	Электронная почта: <br />
      	<?php echo form_input($email);?>
      </p>
      
      <p><input class="btn btn-primary btn-large" type="submit" value="Отправить"></p>
      
<?php echo form_close();?>
</div><!--/span-->
  </div><!--/row-->
