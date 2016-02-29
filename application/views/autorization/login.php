<style>
 .form-signin
 {
  max-width: 330px;
  padding: 15px;
  margin: 0 auto;
}
.form-signin .form-signin-heading, .form-signin .checkbox
{
  margin-bottom: 10px;
}
.form-signin .checkbox
{
  font-weight: normal;
}
.form-signin .form-control
{
  position: relative;
  font-size: 16px;
  height: auto;
  padding: 10px;
  -webkit-box-sizing: border-box;
  -moz-box-sizing: border-box;
  box-sizing: border-box;
}
.form-signin .form-control:focus
{
  z-index: 2;
}
.form-signin input[type="text"]
{
  margin-bottom: -1px;
  border-bottom-left-radius: 0;
  border-bottom-right-radius: 0;
}
.form-signin input[type="password"]
{
  margin-bottom: 10px;
  border-top-left-radius: 0;
  border-top-right-radius: 0;
}
.account-wall
{
  margin-top: 50px;
  padding: 10px 0px 10px 0px;
  background-color: #f7f7f7;
  -moz-box-shadow: 0px 2px 2px rgba(0, 0, 0, 0.3);
  -webkit-box-shadow: 0px 2px 2px rgba(0, 0, 0, 0.3);
  box-shadow: 0px 2px 2px rgba(0, 0, 0, 0.3);
}
.login-title
{
  color: #555;
  font-size: 18px;
  font-weight: 400;
  display: block;
}
.profile-img
{
  width: 96px;
  height: 96px;
  margin: 0 auto 10px;
  display: block;
  -moz-border-radius: 50%;
  -webkit-border-radius: 50%;
  border-radius: 50%;
}
.need-help
{
  margin-top: 10px;
}
.new-account
{
  display: block;
  margin-top: 10px;
}
</style>
<div class="container">
  <div class="row">
    <div class="col-sm-6 col-md-4 col-md-offset-4">
      <div class="account-wall" style="text-align:center; background-color: #fff;">
        <div ><a href="<?php echo base_url();?>"><img src="<?php echo base_url('themes/img/logo.png');?>" height="100"  alt="cms apptech" /></a></div>
        <hr />
        <h1 class="text-center login-title">Личный кабинет</h1>
        <form method="post" class="form-signin" action="<?php echo base_url("autorization/login");?>">
          <input type="text" name="identity" id="identity" class="form-control" placeholder="Имя пользователя" required autofocus>
          <input type="password" name="password" id="password" class="form-control" placeholder="Пароль" required>
          <!--<label class="checkbox pull-left">
            <?//php echo form_checkbox('remember', '1', FALSE, 'id="remember"');?> 
            Запомнить меня
          </label>-->
          <span class="clearfix"></span>
          <button class="btn btn-lg btn-primary btn-block" type="submit">Войти</button>
		  </br>
		  <p><a href="<?php echo base_url('forgot_password');?>">Восстановление пароля</a></p>
        </form>
      </div>
	  
      <?php if ( ! empty($message)):?>
      <div class="alert alert-warning">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
        <strong>Предупреждение!</strong> <div id="infoMessage"><?php echo $message;?></div>
      </div>
      <?php endif;?>
    </div>

  </div>
</div>