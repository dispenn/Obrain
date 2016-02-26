<aside class="right-side">
    <section class="content-header"><h1>Изображения для сайта</h1></section>
        <section class="content">
            <div class="row">
                <div class="col-md-12">
                    <form method="post" enctype="multipart/form-data" class="form-horizontal" role="form" action="<?php echo base_url("administrator/main_images"); ?>">               
                        <div class="box">
                
                        <table class="table table-bordered table-striped ">
                            <tbody>
                            <tr>
                                <td>Логотип <b>(*.png)</b></td>
                                <td><input type="file" min="1" max="9999" name="logo_top[]" /></td>
                                <td><img width="250px" src="<?php if(isset($form) && $form['id_logo_top'] != 0) { echo base_url() . substr($form['logo_top'], 2); } ?>" /></td>
                            </tr>
                            <tr>
                                <td>Баннер <b>(1269x464, *.png)</b></td>
                                <td><input type="file" min="1" max="9999" name="top[]" /></td>
                                <td><img width="250px" src="<?php if(isset($form) && $form['id_top'] != 0) { echo base_url() . substr($form['top'], 2); } ?>" /></td>
                            </tr>
                            <tr>
                                <td>Фон баннера <b>(ширина 1920, *.png)</b></td>
                                <td><input type="file" min="1" max="9999" name="top_bg[]" /></td>
                                <td><img width="250px" src="<?php if(isset($form) && $form['id_top_bg'] != 0) { echo base_url() . substr($form['top_bg'], 2); } ?>" /></td>
                            </tr>
                            <tr>
                                <td>Фон сайта <b>(*.jpg)</b></td>
                                <td><input type="file" min="1" max="9999" name="bg[]" /></td>
                                <td><img width="250px" src="<?php if(isset($form) && $form['id_bg'] != 0) { echo base_url() . substr($form['bg'], 2); } ?>" /></td>
                            </tr>
                            <tr>
                                <td>Логотип для мобильной версии <b>(*.png)</b></td>
                                <td><input type="file" min="1" max="9999" name="logo_topm[]" /></td>
                                <td><img width="250px" src="<?php if(isset($form) && $form['id_logo_topm'] != 0) { echo base_url() . substr($form['logo_topm'], 2); } ?>" /></td>
                            </tr>
                            </tbody>
                        </table>
                        
                
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