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
    <section class="content-header"><h1>Редактировать пользователя</h1></section>
    <!-- Main content -->
    <section class="content">
        <div class="row">
<script type="text/javascript">
            jQuery(function(){
                jQuery("#first_name").validate({
                    expression: "if (VAL) return true; else return false;",
                    message: "Обязательное поле"
                });
                 jQuery("#password").validate({
                    expression: "if (VAL) return true; else return true;",
                    message: "Обязательное поле"
                });
                
                jQuery("#password_confirm").validate({
                    expression: "if (VAL == jQuery('#password').val()) return true; else return false;",
                    message: "Пароли не совпадают"
                });
                
            });
</script>  
<div class="col-md-12">      
</div>
            <div class="col-md-3">
                  

                  <?php echo form_open(current_url());?>
                  <p>
                        Логин: <br />
                        <?php echo form_input($first_name);?>
                  </p>

                  <p>
                        Группа доступа: <br />
                        
                        <select id="people_gr" onchange="loadBrigadies()" class="form-control" name="people_group" value="<?php if(isset($form['id_city'])) {echo $form['id_city'];}?>">
                  <?php if (empty($gpoups) || !isset($gpoups)):?>
                    <option value="" disabled >Нет городов</option>
                    <?php else:?>
                      <?php foreach ($gpoups as $key => $gpoup): ?>
                        <option value="<?php echo $key;?>" <?php if (isset($group_number) && $group_number == $key) {echo 'selected';}?>><?php echo $gpoup;?></option>
                      <?php endforeach;?>
                    <?php endif;?>
                    </select>
                        
                       <?//php echo form_dropdown('people_group', $gpoups, $group_number);?>
                  </p>
                  
                  <div id="district" style="display:block;">
              <div class="form-group">
                <div class="col-sm-3">Бригада:</div>
                <div class="col-sm-12">
                  <select class="form-control" name="brigadies" value="<?php if(isset($brigade_number)) {echo $brigade_number;}?>">
                  <?php if (empty($select_brigadies) || !isset($select_brigadies)):?>
                    <option value="" disabled >Нет бригад</option>
                    <?php else:?>
                      <?php foreach ($select_brigadies as $select): ?>
                        <option value="<?php echo $select['id'];?>" <?php if (isset($brigade_number) && $brigade_number == $select['id']) {echo 'selected';} ?>><?php echo $select['title'];?></option>
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
                  <select class="form-control" name="brigadies_manufacture" value="<?php if(isset($brigade_manufacture_number)) {echo $brigade_manufacture_number;}?>">
                  <?php if (empty($select_brigadies_manufacture) || !isset($select_brigadies_manufacture)):?>
                    <option value="" disabled >Нет бригад</option>
                    <?php else:?>
                      <?php foreach ($select_brigadies_manufacture as $select): ?>
                        <option value="<?php echo $select['id'];?>" <?php if (isset($brigade_manufacture_number) && $brigade_manufacture_number == $select['id']) {echo 'selected';} ?>><?php echo $select['title'];?></option>
                      <?php endforeach;?>
                    <?php endif;?>
                    </select>
                </div>
              </div>
              </div>
              
              
              <br /><br />

                  <p>
                        Пароль: <br />
                        <?php echo form_input($password);?>
                  </p>

                  <p>
                        Повторите пароль: <br />
                        <?php echo form_input($password_confirm);?>
                  </p>
                  <?php echo form_hidden('id', $user->id);?>
                  <?php echo form_hidden($csrf); ?>

                  <p><input class="btn btn-primary btn-large" type="submit" value="Сохранить изменения"></p>
                  <?php echo form_close();?>
            </div><!--/span-->
      </div><!--/row-->
</div>

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