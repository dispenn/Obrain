<aside class="right-side">
    <section class="content-header"><h1><?php if(isset($form) && isset($id)) {echo 'Редактирование продукции';} else {echo 'Новая продукция';}?></h1></section>
        <section class="content">
            <div class="row">
                <div class="col-md-12" style="width:65%;">
                    <form method="post" enctype="multipart/form-data" class="form-horizontal" role="form" action="<?php if(isset($form) && isset($id)) {echo base_url("administrator/edit_catalog/".$id);} else {echo base_url("administrator/create_catalog");}?>">               
                        <div class="row">
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
                                <label class="col-sm-2 control-label">Позиция</label>
                                <div class="col-sm-6">
                                    <input class="form-control" type="text" name="form[position]" value="<?php if(isset($form['position'])) {echo $form['position'];} else {echo set_value('form[position]');}?>"/>
                                </div>
                            </div>      
                                                
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Главное изображение</label>
                                <div class="col-sm-3">
                                    <input type="file" min="1" max="9999" name="file[]" />
                                </div>
                                <div class="col-sm-3">
                                </div>
                            </div>
                                  
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Фотогалерея</label>
                                <div class="col-sm-3">
                                    <input type="hidden" name="MAX_FILE_SIZE" value="3200000">
                                    <input type="file" min="1" max="9999" name="image[]" multiple/>
                                </div>
                            </div>      
                                                
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Категория</label>
                                <div class="col-sm-4">
                                    <select class="form-control" name="form[id_category]" value="<?php if(isset($form['id_category'])) {echo $form['id_category'];}?>">
                                        <?php if (empty($select_list) || !isset($select_list)):?>
                                            <option value="" disabled >Нет ни одной категории</option>
                                        <?php else:?>
                                            <?php foreach ($select_list as $select): ?>
                                                <option value="<?php echo $select['id'];?>" <?php if (isset($form['id_category']) && $form['id_category'] == $select['id']) {echo 'selected';} elseif (set_value('form[id_category]')==$select['id']){echo'selected';}?>><?php echo $select['title'];?></option>
                                            <?php endforeach;?>
                                        <?php endif;?>
                                    </select>
                                </div>
                                <div class="col-sm-2">
                                    <a href="<?=base_url('administrator/create_category_catalog')?>" class="btn btn-info btn-flat">Создать категорию</a>
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Описание</label>
                                <div class="col-sm-6">
                                    <textarea name="form[description]" id="description" cols="90" rows="10"><?php if(isset($form['description'])) {echo $form['description'];} else {echo set_value('form[description]');}?></textarea>
                                </div>
                            </div>
                        </div>
                        
                        <hr />
                        <div class="row">
                            <div class="col-md-2">
                                <div class="col-md-10">
                                  <input class="btn btn-success" name="save" type="submit" value="Сохранить">
                                </div>
                            </div>
                        </div>
                    </form> 
                </div><!--/span-->
                
                <?php if(isset($form) && isset($id)): ?>
                <div class="col-md-5" style="float:right; width:35%;">
                <form class="form-horizontal">
                    
                    <?php foreach($images as $image): ?>
                          <div class="form-group">
                            <label class="col-sm-4 control-label"> </label>
                          <div class="col-sm-5">
                                  <label>
                                      <img src="<?php echo base_url() . $image['path']; ?>" width="200" />
                                  </label>
                                <div class="row">
                                    <div class="col-sm-2" style="float:left; margin-left: 43%;">
                                        <a class="btn btn-danger btn-xs" href="<?php echo base_url('administrator/delete_image') . '/catalog_images/' . $image['id']; ?>">Удалить</a>
                                    </div>
                                </div>
                            </div>
                          </div>
                    <?php endforeach; ?>
                </form>
                </div>
                <?php endif; ?>
                
                
            </div><!--/row-->
            </div>
    </section>
</aside>

<?php echo form_ckeditor(array(
    'id'              => 'form[description]',
    'width'           => '700',
    'height'          => '200',
    'value'         => htmlentities(set_value('form[description]'))
)); ?>