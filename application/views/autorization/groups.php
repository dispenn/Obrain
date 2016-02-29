<?php if(isset($message)):?>
<?php echo alert_message($message, 'info');?>
<?php endif;?>

<aside class="right-side">
<section class="content-header"><h1>Группы пользователей</h1></section>
<section class="content">

<div class="container-fluid"><!--closing tag in the footer.php-->
  <div class="row-fluid">
    
    <div class="span9">
      <h1>Группы пользователей</h1>
      <p>Все группы доступа в системе.</p>

      <div id="infoMessage"><?php echo $message;?></div>
  <p><a class="btn btn-primary" href="<?php echo site_url('autorization/create_group');?>">Создать новую группу</a></p>
<form method="post" action="<?php echo base_url("autorization/delete");?>">
  <?php if (empty($group) || !isset($group)):?>
      <h4>В списке нет групп.</h4>
    <?php else:?>
      <table class="table table-bordered table-striped">
        <thead>
          <tr>
            <th></th>
            <th>Название группы доступа</th>
            <th>Описание</th>
            <th>Редактирование</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($group as $groups):?>
              <tr>
                <td><center><input name="id[]" value="<?php echo $groups->id;?>" type="checkbox"></center></td>
                <td><?php echo $groups->name;?></td>
                <td><?php echo $groups->description;?></td>
                <td><center><a href="<?php echo base_url().'autorization/edit_group/'.$groups->id;?>"><i class="icon-pencil"></i></a></center></td>
            </tr>
          <?php endforeach;?>
        </tbody>
  </table>
  <input type="hidden" name="table_name" value="groups"/>
  <input type="hidden" name="field_name" value="id"/>
  <input class="btn btn-danger" name="delete" type="submit" value="Удалить">
<?php endif;?>
  </form>
</div><!--/span-->
  </div><!--/row-->
  
  </section><!-- /.content -->
  </aside><!-- /.right-side -->