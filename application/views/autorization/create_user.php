<script>
    $(document).ready(function()
    {
        var select = $('#people_gr').prop('value');
        if (select == 6) {
            $('#district').animate({height: 'show'}, 500);
            $('#district_manufacture').animate({height: 'hide'}, 500);
        }
        else if (select == 8 || select == 9) {
            $('#district_manufacture').animate({height: 'show'}, 500);
            $('#district').animate({height: 'hide'}, 500);
        }
        else {
            $('#district').animate({height: 'hide'}, 500);
            $('#district_manufacture').animate({height: 'hide'}, 500);
        }
    });
</script>

<aside class="right-side">
    <section class="content-header"><h1>Создать нового пользователя</h1></section>
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-md-12">
</div>
            <div class="col-md-3">
<script type="text/javascript">
            jQuery(function(){
                jQuery("#first_name").validate({
                    expression: "if (VAL) return true; else return false;",
                    message: "Обязательное поле"
                });
                 jQuery("#password").validate({
                    expression: "if (VAL) return true; else return false;",
                    message: "Обязательное поле"
                });
                
                jQuery("#password_confirm").validate({
                    expression: "if ((VAL == jQuery('#password').val()) && VAL) return true; else return false;",
                    message: "Пароли не совпадают"
                });
                
            });
</script>
                  
                  <form class="form-horizontal" role="form" method="post"action="<?php echo base_url("autorization/create_user");?>" id="create_user_form">
                        <p>
                              Логин: <br />
                              <?php echo form_input($first_name);?>
                        </p>

                        <p>
                              Группа доступа: <br />
                              
                              <select id="people_gr" onchange="loadBrigadies()" class="form-control" name="people_group" value="<?php if(isset($form['id_city'])) {echo $form['id_city'];}?>">
                  <?php if (empty($gpoups) || !isset($gpoups)):?>
                    <option value="" disabled >Нет групп</option>
                    <?php else:?>
                      <?php foreach ($gpoups as $key => $gpoup): ?>
                        <option value="<?php echo $key;?>"><?php echo $gpoup;?></option>
                      <?php endforeach;?>
                    <?php endif;?>
                    </select>
                    
                        </p>
                        
                        <div id="district" style="display:block;">
              <div class="form-group">
                <div class="col-sm-3">Бригада:</div>
                <div class="col-sm-12">
                  <select class="form-control" name="brigadies" value="<?php if(isset($form['id_district'])) {echo $form['id_district'];}?>">
                  <?php if (empty($select_brigadies) || !isset($select_brigadies)):?>
                    <option value="" disabled >Нет бригад</option>
                    <?php else:?>
                      <?php foreach ($select_brigadies as $select): ?>
                        <option value="<?php echo $select['id'];?>" <?php if (isset($form['id_district']) && $form['id_district'] == $select['id']) {echo 'selected';} elseif (set_value('form[id_district]')==$select['id']){echo'selected';}?>><?php echo $select['title'];?></option>
                      <?php endforeach;?>
                    <?php endif;?>
                    </select>
                </div>
              </div>
              </div>
              
                <div id="district_manufacture" style="display:block;">
              <div class="form-group">
                <div class="col-sm-3">Бригада:</div>
                <div class="col-sm-12">
                  <select class="form-control" name="brigadies_manufacture" value="<?php if(isset($form['id_district2'])) {echo $form['id_district2'];}?>">
                  <?php if (empty($select_brigadies_manufacture) || !isset($select_brigadies_manufacture)):?>
                    <option value="" disabled >Нет бригад</option>
                    <?php else:?>
                      <?php foreach ($select_brigadies_manufacture as $select): ?>
                        <option value="<?php echo $select['id'];?>" <?php if (isset($form['id_district']) && $form['id_district'] == $select['id']) {echo 'selected';} elseif (set_value('form[id_district2]')==$select['id']){echo'selected';}?>><?php echo $select['title'];?></option>
                      <?php endforeach;?>
                    <?php endif;?>
                    </select>
                </div>
              </div>
              </div>

                        <p>
                              Пароль: <br />
                              <?php echo form_input($password);?>
                        </p>

                        <p>
                              Повторите пароль: <br />
                              <?php echo form_input($password_confirm);?>
                        </p>


                        <p><input class="btn btn-primary btn-large" type="submit" value="Создать пользователя"></p>
                  </form>
                  
<script type="text/javascript">
jQuery(function($){
   $("#phone1").mask("8 (999) 999-9999");
});
$('#create_user_form').ketchup({}, {
  '.required'    : 'required',              //all fields in the form with the class 'required'
  '#email1': 'email' //one field in the form with the id 'fic-username'
  
});
</script>
            </div><!--/span-->
  </div><!--/row-->
  </section><!-- /.content -->
</aside><!-- /.right-side -->



<script>
    function loadBrigadies()
    {
        var select = $('#people_gr').prop('value');
        if (select == 6) {
            $('#district').animate({height: 'show'}, 500);
            $('#district_manufacture').animate({height: 'hide'}, 500);
        }
        else if (select == 8 || select == 9) {
            $('#district_manufacture').animate({height: 'show'}, 500);
            $('#district').animate({height: 'hide'}, 500);
        }
        else {
            $('#district').animate({height: 'hide'}, 500);
            $('#district_manufacture').animate({height: 'hide'}, 500);
        }
    }
</script>