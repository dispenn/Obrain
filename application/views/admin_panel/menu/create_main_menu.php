<aside class="right-side">
    <section class="content-header"><h1><?php if(isset($form) && isset($id)) {echo 'Редактирование категории';} else {echo 'Новая категория';}?></h1></section>
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <form method="post" enctype="multipart/form-data" class="form-horizontal" role="form" action="<?php if(isset($form) && isset($id)) {echo base_url("administrator/edit_main_menu/".$id);} else {echo base_url("administrator/create_main_menu");}?>">
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Название</label>
                        <div class="col-sm-8">
                            <input class="form-control" type="text" name="form[title]" value="<?php if(isset($form['title'])) {echo $form['title'];} else {echo set_value('form[title]');}?>"/>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-2 control-label">Ссылка</label>
                        <div class="col-sm-8">
                            <input class="form-control" type="text" name="form[link]" value="<?php if(isset($form['link'])) {echo $form['link'];} else {echo set_value('form[link]');}?>"/>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-2 control-label">Класс</label>
                        <div class="col-sm-8">
                            <input class="form-control" type="text" name="form[class]" value="<?php if(isset($form['class'])) {echo $form['class'];} else {echo set_value('form[class]');}?>"/>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-2 control-label">Позиция</label>
                        <div class="col-sm-8">
                            <input class="form-control" type="text" name="form[position]" value="<?php if(isset($form['position'])) {echo $form['position'];} else {echo set_value('form[position]');}?>"/>
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