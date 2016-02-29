<aside class="right-side">
    <section class="content-header"><h1><?php if(isset($form) && isset($id)) {echo 'Редактирование баннера';} else {echo 'Новый баннер';}?></h1></section>
        <section class="content">
            <div class="row">
                <div class="col-md-12">
                    <form method="post" enctype="multipart/form-data" class="form-horizontal" role="form" action="<?php if(isset($form) && isset($id)) {echo base_url("administrator/edit_banner/".$id);} else {echo base_url("administrator/create_banner");}?>">               
                        <div class="row">
                            <div class="col-md-7">
                                <div class="tab-content tab-page">
                                    <div class="tab-pane active" id="page">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label class="col-sm-4 control-label">Изображение</label>
                                                    <div class="col-sm-3">
                                                        <input type="file" min="1" max="9999" name="file[]" />
                                                    </div>
                                                    <div class="col-sm-3">
                                                    </div>
                                                  </div>
                                            
                                                <div class="form-group">
                                                    <label class="col-sm-4 control-label">Ссылка</label>
                                                    <div class="col-sm-8">
                                                        <input class="form-control" type="text" name="form[link]" value="<?php if(isset($form['link'])) {echo $form['link'];} else {echo set_value('form[link]');}?>"/>
                                                    </div>
                                                </div>
                                                
                                                <div class="form-group">
                                                    <label class="col-sm-4 control-label">Позиция</label>
                                                    <div class="col-sm-8">
                                                        <input class="form-control" type="text" name="form[position]" value="<?php if(isset($form['position'])) {echo $form['position'];} else {echo set_value('form[position]');}?>"/>
                                                    </div>
                                                </div>
                                                
                                                <div class="form-group">
                                                    <label class="col-sm-4 control-label">Строка</label>
                                                    <div class="col-sm-8">
                                                    
                                                        <select class="form-control" name="form[position_row]" value="<?php if(isset($form['position_row'])) {echo $form['position_row'];}?>">
                                                            <option value="1" <?php if (isset($form['position_row']) && $form['position_row'] == 1) {echo 'selected';} ?>>1</option>
                                                            <option value="2" <?php if (isset($form['position_row']) && $form['position_row'] == 2) {echo 'selected';} ?>>2</option>
                                                            <option value="3" <?php if (isset($form['position_row']) && $form['position_row'] == 3) {echo 'selected';} ?>>3</option>
                                                            <option value="4" <?php if (isset($form['position_row']) && $form['position_row'] == 4) {echo 'selected';} ?>>4</option>
                                                        </select>

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