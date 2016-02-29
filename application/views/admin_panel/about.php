<aside class="right-side">
  <section class="content-header"><h1>Страница "О компании"</h1></section>
  <section class="content">
    <div class="row">
        <div class="col-md-12">

            <form class="form-horizontal" role="form" method="post" action="<?php echo base_url("administrator/about"); ?>">

              <div class="row">
                <label class="col-sm-2 control-label">О компании</label>
                <div class="col-md-8">
                    <textarea name="form[company]"  id="company" rows="10"><?php if(isset($form['company'])) {echo $form['company'];} else {echo set_value('form[company]');}?></textarea>
                </div>
              </div>
              <hr />
            
              <div class="row">
                <label class="col-sm-2 control-label">Как стать нашим клиентом</label>
                <div class="col-md-8">
                    <textarea name="form[client]"  id="client" rows="10"><?php if(isset($form['client'])) {echo $form['client'];} else {echo set_value('form[client]');}?></textarea>
                </div>
              </div>
              <hr />
              
              <div class="row">
                <label class="col-sm-2 control-label">Наши награды и достижения</label>
                <div class="col-md-8">
                    <textarea name="form[awards]"  id="awards" rows="10"><?php if(isset($form['awards'])) {echo $form['awards'];} else {echo set_value('form[awards]');}?></textarea>
                </div>
              </div>
              <hr />
            
              <div class="form-group">
                <div class="col-sm-offset-2 col-sm-10">
                  <input class="btn btn-success" name="create" type="submit" value="Сохранить">
                </div>
              </div>
            </form>   
            
            
            
            
        </div><!--/span-->
    </div><!--/row-->

    </section><!-- /.content -->
    
    <section class="content-header"><h2>Регионы присутствия</h2></section>
    <section class="content">
        <div class="row">
        <div class="col-md-12">
          <?php if (empty($list) || !isset($list) || $list == FALSE):?>
              <h4>Нет регионов.</h4>
              <a class="btn btn-success" href="<?php echo site_url('administrator/create_region');?>"><span class="glyphicon glyphicon-plus"></span> Создать</a>
          <?php else:?>
            <form method="post" action="<?php echo base_url("administrator/delete/regions/id");?>">
              <table class="table table-bordered table-striped dataTable">
                <thead>
                    <tr>
                        <th></th>
                        <th><center>Название</center></th>
                        <th><center>Alias</center></th>
                        <th><center>Размер</center></th>
                        <th><center>Редактировать</center></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($list as $gallery):?>
                      <tr>
                          <td><center><input name="id[]" value="<?php echo $gallery['id'];?>" type="checkbox"></center></td>
                          <td><center><?php echo $gallery['title'];?></center></td>
                          <td><center><?php echo $gallery['alias'];?></center></td>
                          <td><center><?php echo $gallery['size'];?></center></td>
                          <td><center><a href="<?php echo site_url('administrator/edit_region/'.$gallery['id']);?>"><span class="glyphicon glyphicon-pencil"></span></a></td>
                      </tr>
                    <?php endforeach;?>
                </tbody>
              </table>
              <div class="dt-footer">
                  <input class="btn btn-danger" name="delete" type="submit" value="Удалить">
                  
                  <a class="btn btn-success" href="<?php echo site_url('administrator/create_region');?>"><span class="glyphicon glyphicon-plus"></span> Создать</a>
              </div>
            </form>
          <?php endif;?>   
        </div><!--/span-->
      </div><!--/row-->
    </section><!-- /.content -->
    
</aside><!-- /.right-side -->

<?php echo form_ckeditor(array(
    'id'              => 'company',
    'width'           => '600',
    'height'          => '200',
    'value'         => htmlentities(set_value('company'))
)); ?>


<?php echo form_ckeditor_text(array(
    'id'              => 'client',
    'width'           => '600',
    'height'          => '200',
    'value'         => htmlentities(set_value('client'))
)); ?>


<?php echo form_ckeditor_text(array(
    'id'              => 'awards',
    'width'           => '600',
    'height'          => '200',
    'value'         => htmlentities(set_value('awards'))
)); ?>
