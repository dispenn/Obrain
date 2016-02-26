<aside class="right-side">
    <section class="content-header"><h1>Галерея</h1></section>
    <!-- Main content -->
    <section class="content">
    <div class="row">
        <div class="col-md-11">
            <?php if (empty($list) || !isset($list) || $list == FALSE):?>
                <h4>Нет существующих наименований.</h4>
                <a class="btn btn-success" href="<?php echo site_url('administrator/create_gallery');?>">Создать</a>
            <?php else:?>
            <form method="post" action="<?php echo base_url("administrator/delete_gallery");?>">
            <table class="table table-bordered table-striped dataTable">
                <thead>
                    <tr>
                        <th></th>
                        <!-- <th><center>Картинка</center></th> -->
                        <th><center>Заголовок</center></th>
                        <th><center>Дата</center></th>
                        <th><center>Редактировать</center></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($list as $list_item):?>
                      <tr>
                          <td><center><input name="id[]" value="<?php echo $list_item['id'];?>" type="checkbox"></center></td>
                          <!-- <td><center><img height="30" weight="20" src="<?php echo base_url(substr($list_item['path'], 1));?>"/></center></td> -->
                          <td><center><?php echo $list_item['title'];?></center></td>
                          <td><center><?php echo date("d.m.Y", $list_item['date']);?></center></td>
                          <td><center><a href="<?php echo site_url('administrator/edit_gallery/'.$list_item['id']);?>"><span class="glyphicon glyphicon-pencil"></span></a></td>
                      </tr>
                    <?php endforeach;?>
                </tbody>
            </table>
            <div class="dt-footer">
                <input class="btn btn-danger" name="delete" type="submit" value="Удалить"> <a class="btn btn-success" href="<?php echo site_url('administrator/create_gallery');?>"><span class="glyphicon glyphicon-plus"></span> Создать</a>
            </div>
        </form>
        <?php endif;?>   
        </div><!--/span-->
    </div><!--/row-->
    </section><!-- /.content -->
</aside><!-- /.right-side -->