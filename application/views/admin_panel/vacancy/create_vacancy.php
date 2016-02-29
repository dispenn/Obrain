<?php
$form['preview'] = '<p></p>

                            <h3>Обязанности:</h3>
                            
                            <ul>
                            	<li></li>
                            </ul>
                            
                            <h3>Требования:</h3>
                            
                            <ul>
                            	<li></li>
                            </ul>
                            
                            <h3>Условия:</h3>
                            
                            <ul>
                            	<li></li>
                            </ul>
                            
                            <p></p>';
?>
<aside class="right-side">
    <section class="content-header"><h1><?php if(isset($form) && isset($id)) {echo 'Редактирование вакансии';} else {echo 'Новая вакансия';}?></h1></section>
        <section class="content">
            <div class="row">
                <div class="col-md-12">
                    <form method="post" enctype="multipart/form-data" class="form-horizontal" role="form" action="<?php if(isset($form) && isset($id)) {echo base_url("administrator/edit_vacancy/".$id);} else {echo base_url("administrator/create_vacancy");}?>">               
                        <div class="row">
                            <div class="col-md-7">
                                <div class="tab-content tab-page">
                                    <div class="tab-pane active" id="page">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label class="col-sm-4 control-label">Название</label>
                                                    <div class="col-sm-8">
                                                        <input class="form-control" required type="text" name="form[name]" value="<?php if(isset($form['name'])) {echo $form['name'];} else {echo set_value('form[name]');}?>"/>
                                                    </div>
                                                </div>
                                                
                                                <div class="form-group">
                                                    <label class="col-sm-4 control-label">Зарплата</label>
                                                    <div class="col-sm-8">
                                                        <input class="form-control" required type="text" name="form[salary]" value="<?php if(isset($form['salary'])) {echo $form['salary'];} else {echo set_value('form[salary]');}?>"/>
                                                    </div>
                                                </div>
                                                
                                                <div class="row">
                                                    <label class="col-sm-4 control-label">Подробное описание</label>
                                                    <div class="col-sm-8">
                                                        <textarea name="form[desc]" required id="desc" cols="90" rows="10"><?php if(isset($form['desc'])) {echo $form['desc'];} else {echo $form['preview'];}?></textarea>
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


<?php echo form_ckeditor(array(
    'id'              => 'form[desc]',
    'width'           => '800',
    'height'          => '200',
    'value'         => htmlentities(set_value('form[desc]'))
)); ?>
