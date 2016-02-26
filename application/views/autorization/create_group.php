<?php if(isset($message)):?>
<?php echo alert_message($message, 'info');?>
<?php endif;?>

<aside class="right-side">
<section class="content-header"><h1>Группы пользователей</h1></section>
<section class="content">

<div class="container-fluid"><!--closing tag in the footer.php-->
	<div class="row-fluid">
		
		<div class="span9">
			<h2>Создать группу</h2>
			<p>Заполните все поля формы.</p>
<script type="text/javascript">
            jQuery(function(){
                jQuery("#group_name").validate({
                    expression: "if (VAL) return true; else return false;",
                    message: "Обязательное поле"
                });
                jQuery("#description").validate({
                    expression: "if (VAL) return true; else return false;",
                    message: "Обязательное поле"
                });
            });
</script>   
			<div id="infoMessage"><?php echo $message;?></div>
			<form method="post" action="<?php echo base_url("autorization/create_group");?>">
				<p>
					Имя группы: <br />
					<?php echo form_input($group_name);?>
				</p>

				<p>
					Описание: <br />
					<?php echo form_input($description);?>
				</p>

				<p><input class="btn btn-primary btn-large" type="submit" value="Создать группу"></p>
			</form>
		</div><!--/span-->
  </div><!--/row-->
  
    </section><!-- /.content -->
  </aside><!-- /.right-side -->