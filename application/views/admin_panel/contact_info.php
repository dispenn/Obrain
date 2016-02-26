<aside class="right-side">
  <section class="content-header"><h1>Контактная информация</h1></section>
  <section class="content">
    <div class="row">
        <div class="col-md-12">

            <form class="form-horizontal" role="form" method="post" action="<?php if ($id == 1) echo base_url("administrator/contact_info"); else { echo base_url("administrator/contact_info2"); } ?>">
              <div class="form-group">
                <label class="col-sm-2 control-label">Адрес</label>
                <div class="col-sm-6">
                  <input class="form-control" type="text" name="form[address]" value="<?php if(isset($form['address'])) {echo $form['address'];} else {echo set_value('form[address]');}?>"/>
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label">Телефон</label>
                <div class="col-sm-6">
                  <input class="form-control" type="text" name="form[phone]" value="<?php if(isset($form['phone'])) {echo $form['phone'];} else {echo set_value('form[phone]');}?>"/>
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label">E-mail</label>
                <div class="col-sm-6">
                  <input class="form-control" type="text" name="form[email]" value="<?php if(isset($form['email'])) {echo $form['email'];} else {echo set_value('form[email]');}?>"/>
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label">2Gis скрипт</label>
                <div class="col-sm-8">
                  <textarea name="form[script_map]"  id="script_map" cols="60" rows="5"><?php if(isset($form['script_map'])) {echo $form['script_map'];} else {echo set_value('form[script_map]');}?></textarea>
                </div>
              </div>
              <hr />
              <div class="row">
                <label class="col-sm-2 control-label">Текст</label>
                <div class="col-md-8">
                    <textarea name="form[fulltext]"  id="fulltext" rows="10"><?php if(isset($form['fulltext'])) {echo $form['fulltext'];} else {echo set_value('form[fulltext]');}?></textarea>
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
    'id'              => 'fulltext',
    'width'           => '600',
    'height'          => '200',
    'value'         => htmlentities(set_value('fulltext'))
)); ?>
