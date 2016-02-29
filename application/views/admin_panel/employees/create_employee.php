<aside class="right-side">
    <section class="content-header"><h1><?php if(isset($form) && isset($id)) {echo 'Редактирование сотрудника';} else {echo 'Новый сотрудник';}?></h1></section>
        <section class="content">
            <div class="row">
                <div class="col-md-12">
                    <form method="post" enctype="multipart/form-data" class="form-horizontal" role="form" action="<?php if(isset($form) && isset($id)) {echo base_url("administrator/edit_employee/".$id);} else {echo base_url("administrator/create_employee");}?>">               
                        <div class="row">
                            <div class="col-md-7">
                                <div class="tab-content tab-page">
                                    <div class="tab-pane active" id="page">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label class="col-sm-4 control-label">ФИО</label>
                                                    <div class="col-sm-8">
                                                        <input class="form-control" type="text" name="form[name]" value="<?php if(isset($form['name'])) {echo $form['name'];} else {echo set_value('form[name]');}?>"/>
                                                    </div>
                                                </div>
                                                
                                                <div class="form-group">
                                                    <label class="col-sm-4 control-label">Должность</label>
                                                    <div class="col-sm-8">
                                                        <input class="form-control" type="text" name="form[post]" value="<?php if(isset($form['post'])) {echo $form['post'];} else {echo set_value('form[post]');}?>"/>
                                                    </div>
                                                </div>
                                                
                                                <div class="form-group">
                                                    <label class="col-sm-4 control-label">Телефон</label>
                                                    <div class="col-sm-8">
                                                        <input class="form-control" type="text" name="form[phone]" value="<?php if(isset($form['phone'])) {echo $form['phone'];} else {echo set_value('form[phone]');}?>"/>
                                                    </div>
                                                </div>
                                                
                                                <div class="form-group">
                                                    <label class="col-sm-4 control-label">Email</label>
                                                    <div class="col-sm-8">
                                                        <input class="form-control" type="text" name="form[email]" value="<?php if(isset($form['email'])) {echo $form['email'];} else {echo set_value('form[email]');}?>"/>
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
                                                <label class="col-sm-4 control-label">Отдел</label>
                                                <div class="col-sm-5">
                                                  <select class="form-control" name="form[id_department]" value="<?php if(isset($form['id_department'])) {echo $form['id_department'];}?>">
                                                  <?php if (empty($select_list) || !isset($select_list)):?>
                                                    <option value="" disabled >Нет ни одного отдела</option>
                                                    <?php else:?>
                                                      <?php foreach ($select_list as $select): ?>
                                                        <option value="<?php echo $select['id'];?>" <?php if (isset($form['id_department']) && $form['id_department'] == $select['id']) {echo 'selected';} elseif (set_value('form[id_department]')==$select['id']){echo'selected';}?>><?php echo $select['title'];?></option>
                                                      <?php endforeach;?>
                                                    <?php endif;?>
                                                    </select>
                                                </div>
                                                <div class="col-sm-2">
                                                    <a href="<?=base_url('administrator/create_department')?>" class="btn btn-info btn-flat">Создать отдел</a>
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