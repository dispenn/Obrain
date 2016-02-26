<aside class="right-side">
  <section class="content-header"><h1><?php if(isset($form) && isset($id)) {echo 'Редактирование галереи';} else {echo 'Создание галереи';}?></h1></section>
  <section class="content">
    <div class="row">
        <div class="col-md-12" style="width:65%;">

            <form class="form-horizontal" role="form" method="post" enctype="multipart/form-data" action="<?php if(isset($form) && isset($id)) {echo base_url("administrator/edit_gallery/".$id);} else {echo base_url("administrator/create_gallery");}?>">
              <div class="form-group">
                <label class="col-sm-3 control-label">Название</label>
                <div class="col-sm-8">
                  <input class="form-control" type="text" name="form[title]" value="<?php if(isset($form['title'])) {echo $form['title'];} else {echo set_value('form[title]');}?>"/>
				</div>
				
              </div>
              <div class="form-group">
                <label class="col-sm-3 control-label">Alias</label>
                <div class="col-sm-8">
                  <input class="form-control" type="text" name="form[alias]" value="<?php if(isset($form['alias'])) {echo $form['alias'];} else {echo set_value('form[alias]');}?>"/>
                </div>
              </div>

              <div class="form-group">
                <label class="col-sm-3 control-label">Дата</label>
                <div class="col-sm-8">
                    <input id="datepicker" class="form-control" type="text" name="form[date]" value="<?php if(isset($form['date'])) {echo date("d.m.Y", $form['date']);} else {echo date('d.m.Y', time());}?>"/>
                </div>
              </div>  

              <div class="form-group">
                <label class="col-sm-3 control-label">Изображения</label>
                <div class="col-sm-3">
                    <input type="hidden" name="MAX_FILE_SIZE" value="3200000">
                    <input type="file" min="1" max="9999" name="file[]" multiple/>
                </div>
              </div>
              
              <div class="form-group">
                <label class="col-sm-3 control-label">Описание</label>
                <div class="col-md-6">
                    <textarea name="form[fulltext]"  id="fulltext" rows="10"><?php if(isset($form['fulltext'])) {echo $form['fulltext'];} else {echo $form['fulltext'];}?></textarea>
                </div>
            </div>
            <hr />
              <div class="form-group">
                <div class="col-sm-offset-2 col-sm-3">
                  <input class="btn btn-success" name="create" type="submit" value="Сохранить">
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
                            <div class="col-sm-4" style="float:left; margin-left: 0%;">
                                <?php if ($image['main_photo'] == TRUE): ?>
                                <a class="btn btn-success btn-xs disabled" href="#">Главное фото</a>
                                <?php else: ?>
                                
                                <!--
                                <form action="" id="myform">
                                <input type="hidden" name="mydata" id="id_gallery" value="<?=$id;?>" />
                                <input type="hidden" name="mydata" id="id_image" value="<?=$image['id'];?>" />
                                <input type="button" class="btn btn-success btn-xs" onclick="send()" value="Сделать главным" />
                                </form>
                                --> 
                                
                                <a class="btn btn-success btn-xs" href="<?php echo base_url('administrator/main_photo') . '/gallery_images/id_gallery/' . $id . '/' . $image['id']; ?>">Сделать главным</a>
                                     
                                <?php endif; ?>
                            </div>
                            <div class="col-sm-2" style="float:left; margin-left: 43%;">
                                <a class="btn btn-danger btn-xs" href="<?php echo base_url('administrator/delete_image') . '/gallery_images/' . $image['id']; ?>">Удалить</a>
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
    </section><!-- /.content -->
</aside><!-- /.right-side -->

<?php echo form_ckeditor(array(
    'id'              => 'fulltext',
    'width'           => '600',
    'height'          => '200',
    'value'         => htmlentities(set_value('fulltext'))
)); ?>

<script>
    $(function() {
        $( "#datepicker" ).datepicker({
            changeMonth: true,
            changeYear: true
        });
    });
</script>