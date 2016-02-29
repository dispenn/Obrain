<aside class="right-side">
    <section class="content-header"><h1><?php if(isset($form) && isset($id)) {echo 'Редактирование новости';} else {echo 'Новая новость';}?></h1></section>
        <section class="content">
            <div class="row">
                <div class="col-md-12">
                    <form method="post" enctype="multipart/form-data" class="form-horizontal" role="form" action="<?php if(isset($form) && isset($id)) {echo base_url("administrator/edit_news/".$id);} else {echo base_url("administrator/create_news");}?>">               
                        <div class="row">
                            <div class="col-md-7">
                                <div class="tab-content tab-page">
                                    <div class="tab-pane active" id="page">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label class="col-sm-4 control-label">Название</label>
                                                    <div class="col-sm-8">
                                                        <input class="form-control" type="text" name="form[title]" value="<?php if(isset($form['title'])) {echo $form['title'];} else {echo set_value('form[title]');}?>"/>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="col-sm-4 control-label">Alias</label>
                                                    <div class="col-sm-8">
                                                        <input class="form-control" type="text" name="form[alias]" value="<?php if(isset($form['alias'])) {echo $form['alias'];} else {echo set_value('form[alias]');}?>"/>
                                                    </div>
                                                </div>
                                                
                                                <div class="form-group">
                                                    <label class="col-sm-4 control-label">Изображение</label>
                                                    <div class="col-sm-3">
                                                        <input type="file" min="1" max="9999" name="file[]" />
                                                    </div>
                                                    <div class="col-sm-3">
                                                    </div>
                                                </div>
                                                
                                                <div class="form-group">
                                                    <label class="col-sm-4 control-label">Дата</label>
                                                    <div class="col-sm-8">
                                                        <input id="datepicker" class="form-control" type="text" name="form[date]" value="<?php if(isset($form['date'])) {echo $form['date'];} else {echo date('d.m.Y', time());}?>"/>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="col-sm-4 control-label">Краткое описание</label>
                                                    <div class="col-sm-8">
                                                        <input class="form-control" type="text" name="form[short_text]" value="<?php if(isset($form['short_text'])) {echo $form['short_text'];} else {echo set_value('form[short_text]');}?>"/>
                                                    </div>
                                                </div>
                                                
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <hr />
                        <div class="row">
                            <label class="col-sm-2 control-label">Подробное описание</label>
                            <div class="col-sm-8">
                                <textarea name="form[full_text]" id="full_text" cols="90" rows="10"><?php if(isset($form['full_text'])) {echo $form['full_text'];} else {echo set_value('form[full_text]');}?></textarea>
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
            </div><!--/row-->
            </div>
    </section>
</aside>

<?php echo form_ckeditor(array(
    'id'              => 'form[full_text]',
    'width'           => '800',
    'height'          => '200',
    'value'         => htmlentities(set_value('form[full_text]'))
)); ?>

<script>
    $(function() {
        $( "#datepicker" ).datepicker({
            changeMonth: true,
            changeYear: true
        });
    });
</script>