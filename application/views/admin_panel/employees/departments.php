<aside class="right-side">
  <section class="content-header"><h1>Отделы</h1></section>
    <section class="content">
      <div class="row">
        <div class="col-md-11">
          <?php if (empty($list) || !isset($list) || $list == FALSE):?>
              <h4>Нет существующих категорий.</h4>
          <?php else:?>
            <form method="post" action="<?php echo base_url("administrator/delete/departments/id");?>">
              <table class="table table-bordered table-striped dataTable">
                <thead>
                    <tr>
                        <th></th>
                        <th><center>Название</center></th>
                        <th><center>Редактировать</center></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($list as $gallery):?>
                      <tr>
                          <td><center><input name="id[]" value="<?php echo $gallery['id'];?>" type="checkbox"></center></td>
                          <td><center><?php echo $gallery['title'];?></center></td>
                          <td><center><a href="<?php echo site_url('administrator/edit_department/'.$gallery['id']);?>"><span class="glyphicon glyphicon-pencil"></span></a></td>
                      </tr>
                    <?php endforeach;?>
                </tbody>
              </table>
              <div class="dt-footer">
                  <input class="btn btn-danger" name="delete" type="submit" value="Удалить">
              </div>
            </form>
          <?php endif;?>   
        </div><!--/span-->
      </div><!--/row-->
    </section><!-- /.content -->
  <section class="content-header"><h1><?php if(isset($form) && isset($id)) {echo 'Редактирование отдела';} else {echo 'Создание отдела';}?></h1></section>
  <section class="content">
    <div class="row">
        <div class="col-md-12">
          <form class="form-horizontal" role="form" method="post" action="<?php if(isset($form) && isset($id)) {echo base_url("administrator/edit_department/".$id);} else {echo base_url("administrator/create_department");}?>">
            <div class="form-group">
              <label class="col-sm-2 control-label">Название</label>
              <div class="col-sm-6">
                <input class="form-control" type="text" name="form[title]" value="<?php if(isset($form['title'])) {echo $form['title'];} else {echo set_value('form[title]');}?>"/>
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-2 control-label">Alias</label>
              <div class="col-sm-6">
                <input class="form-control" type="text" name="form[alias]" value="<?php if(isset($form['alias'])) {echo $form['alias'];} else {echo set_value('form[alias]');}?>"/>
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