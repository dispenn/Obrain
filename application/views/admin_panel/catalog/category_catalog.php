<aside class="right-side">
  <section class="content-header"><h1>Категории продукции</h1></section>
    <section class="content">
      <div class="row">
        <div class="col-md-11">
          <?php if (empty($list) || !isset($list) || $list == FALSE):?>
              <h4>Нет существующих категорий.</h4>
          <?php else:?>
            <form method="post" action="<?php echo base_url("administrator/delete/category_catalog/id");?>">
              <table class="table table-bordered table-striped dataTable">
                <thead>
                    <tr>
                        <th></th>
                        <th><center>Название</center></th>
                        <th><center>Главная категория</center></th>
                        <th><center>Позиция</center></th>
                        <th><center>Редактировать</center></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($list as $gallery):?>
                      <tr>
                          <td><center><input name="id[]" value="<?php echo $gallery['id'];?>" type="checkbox"></center></td>
                          <td><center><?php echo $gallery['title'];?></center></td>
                          <td><center><?php if(isset($gallery['main_category_title']) && $gallery['main_category_title'] != "") { echo $gallery['main_category_title']; } else { echo ""; } ?></center></td>
                          <td><center><?php echo $gallery['position'];?></center></td>
                          <td><center><a href="<?php echo site_url('administrator/edit_category_catalog/'.$gallery['id']);?>"><span class="glyphicon glyphicon-pencil"></span></a></td>
                      </tr>
                    <?php endforeach;?>
                </tbody>
              </table>
              <div class="dt-footer">
                  <input class="btn btn-danger" name="delete" type="submit" value="Удалить">
              </div>
            </form>
          <?php endif;?>   
        </div><!--/span-->
      </div><!--/row-->
    </section><!-- /.content -->
  <section class="content-header"><h1><?php if(isset($form) && isset($id)) {echo 'Редактирование категории';} else {echo 'Создание категории';}?></h1></section>
  <section class="content">
    <div class="row">
        <div class="col-md-12">
          <form class="form-horizontal" role="form" method="post" action="<?php if(isset($form) && isset($id)) {echo base_url("administrator/edit_category_catalog/".$id);} else {echo base_url("administrator/create_category_catalog");}?>">
            <div class="form-group">
              <label class="col-sm-2 control-label">Название</label>
              <div class="col-sm-6">
                <input class="form-control" type="text" name="form[title]" value="<?php if(isset($form['title'])) {echo $form['title'];} else {echo set_value('form[title]');}?>"/>
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-2 control-label">Alias</label>
              <div class="col-sm-6">
                <input class="form-control" type="text" name="form[alias]" value="<?php if(isset($form['alias'])) {echo $form['alias'];} else {echo set_value('form[alias]');}?>"/>
              </div>
            </div>
            
            <div class="form-group">
                <label class="col-sm-2 control-label">Главная категория</label>
                <div class="col-sm-6">
                    <select class="form-control" name="form[id_main_category]" value="<?php if(isset($form['id_main_category'])) {echo $form['id_main_category'];}?>">
                        <option value="">Нет</option>
                        <?php if (isset($main_category_list) && !empty($main_category_list)):?>
                            <?php foreach ($main_category_list as $select): ?>
                                <option value="<?php echo $select['id'];?>" <?php if (isset($form['id_main_category']) && $form['id_main_category'] == $select['id']) {echo 'selected';} elseif (set_value('form[id_main_category]')==$select['id']){echo'selected';}?>><?php echo $select['title'];?></option>
                            <?php endforeach;?>
                        <?php endif;?>
                    </select>
                </div>
            </div>
            
            <div class="form-group">
              <label class="col-sm-2 control-label">Позиция</label>
              <div class="col-sm-6">
                <input class="form-control" type="text" name="form[position]" value="<?php if(isset($form['position'])) {echo $form['position'];} else {echo set_value('form[position]');}?>"/>
              </div>
            </div>
            
            <div class="form-group">
                <label class="col-sm-2 control-label">Иконка</label>
                <div class="col-sm-6">
                    <select class="form-control" name="form[id_icon]" value="<?php if(isset($form['id_icon'])) {echo $form['id_icon'];}?>">
                        <?php if (empty($select_list) || !isset($select_list)):?>
                            <option value="" disabled >Нет иконок</option>
                        <?php else:?>
                            <?php foreach ($select_list as $select): ?>
                                <option value="<?php echo $select['id'];?>" <?php if (isset($form['id_icon']) && $form['id_icon'] == $select['id']) {echo 'selected';} elseif (set_value('form[id_icon]')==$select['id']){echo'selected';}?>><?php echo $select['title'];?></option>
                            <?php endforeach;?>
                        <?php endif;?>
                    </select>
                </div>
            </div>
            
            <hr />
            <div class="row">
                <label class="col-sm-2 control-label">Подробное описание</label>
                <div class="col-sm-6">
                    <textarea name="form[text]" id="text" cols="90" rows="10"><?php if(isset($form['text'])) {echo $form['text'];} else {echo set_value('form[text]');}?></textarea>
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
    </div>
  </section><!-- /.content -->
</aside><!-- /.right-side -->

<?php echo form_ckeditor(array(
    'id'              => 'form[text]',
    'width'           => '800',
    'height'          => '200',
    'value'         => htmlentities(set_value('form[text]'))
)); ?>