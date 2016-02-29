<aside class="right-side">
    <section class="content-header"><h1><?php if(isset($form) && isset($id)) {echo 'Редактирование страницы';} else {echo 'Новая страница';}?></h1></section>
    <section class="content">
        <div class="row">
            <div class="col-md-8">
                <form method="post" enctype="multipart/form-data" class="form-horizontal" action="<?php if(isset($form) && isset($id)) {echo base_url("administrator/edit_pages/".$id);} else {echo base_url("administrator/create_pages");}?>">
                    <div class="row">
                        <div class="col-md-7">
                            <ul class="nav nav-tabs" style="margin-bottom: 15px;">
                                <li class="active"><a href="#page" data-toggle="tab">Страница</a></li>
                                <li ><a href="#metadata" data-toggle="tab">SEO</a></li>
                            </ul>
                            <div class="tab-content tab-page">
                                <div class="tab-pane active" id="page">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label class="col-sm-4 control-label">Заголовок</label>
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
                                                <label class="col-sm-4 control-label">Статус</label>
                                                <div class="col-sm-8">
                                                    <select class="form-control" name="form[access]" value="<?php if(isset($form['access'])) {echo $form['access'];}?>">
                                                        <option value="" <?php if (isset($form['access']) && empty($form['access'])) {echo 'selected';} else {echo set_select('form[access]', '');}?> ></option>
                                                        <option value="1" <?php if (isset($form['access']) && $form['access'] == 1) {echo 'selected';} else {echo set_select('form[access]', '1');}?> >Опубликовать</option>
                                                        <option value="0" <?php if (isset($form['access']) && $form['access'] == 0) {echo 'selected';} else {echo set_select('form[access]', '0');}?> >Черновик</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane " id="metadata">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label class="col-sm-4 control-label">Ключевые слова (keywords)</label>
                                                <div class="col-sm-8">
                                                    <input class="form-control" type="text" name="form[metakey]" value="<?php if(isset($form['metakey'])) {echo $form['metakey'];} else {echo set_value('form[metakey]');}?>"/>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-sm-4 control-label">Описание (description)</label>
                                                <div class="col-sm-8">
                                                    <input class="form-control" type="text" name="form[metadesc]" value="<?php if(isset($form['metadesc'])) {echo $form['metadesc'];} else {echo set_value('form[metadesc]');}?>"/>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-8">
                            <textarea name="form[fulltext]"><?php if(isset($form['fulltext'])) {echo $form['fulltext'];} else {echo set_value('form[fulltext]');}?></textarea>
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
                <div class="col-md-4">

                    <form class="form-horizontal">

                        <?php foreach($images as $image): ?>
                            <div class="form-group">
                                <label class="col-sm-4 control-label"> </label>
                                <div class="col-sm-5">
                                    <label>
                                        <img src="<?php echo base_url() . $image['path']; ?>" width="200" />
                                    </label>
                                    <div class="row">
                                        <div class="col-sm-offset-4 col-sm-2" style="float:left; margin-left: 0%;">
                                        </div>
                                        <div class="col-sm-offset-4 col-sm-2" style="float:left; margin-left: 43%;">
                                            <a class="btn btn-danger btn-xs" href="<?php echo base_url('administrator/delete_image') . '/pages_images/' . $image['id']; ?>">Удалить</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </form>

                </div>
            <?php endif; ?>

        </div><!--/row-->
    </section>
</aside>
<?php echo form_ckeditor(array(
    'id'              => 'form[fulltext]',
    'width'           => '800',
    'height'          => '200',
    'value'         => htmlentities(set_value('form[fulltext]'))
)); ?>