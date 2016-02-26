
<div class="container-fluid"><!--closing tag in the footer.php-->
	<div class="row-fluid">
		
		<div class="span9">
			<h2>Редактировать группу</h2>
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
			<?php echo form_open(current_url());?>
				<p>
					Имя группы: <br />
					<?php echo form_input($group_name);?>
				</p>

				<p>
					Описание: <br />
					<?php echo form_input($group_description);?>
				</p>

				<p><input class="btn btn-primary btn-large" type="submit" value="Сохранить изменения"></p>
			<?php echo form_close();?>
		</div><!--/span-->
  </div><!--/row-->