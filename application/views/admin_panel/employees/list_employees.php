<aside class="right-side">
    <section class="content-header"><h1>Сотрудники</h1></section>
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box">
                    <div class="box-body table-responsive">
                        <?php if (empty($lists) || !isset($lists) || $lists == FALSE):?>
                            <h4>Список пуст.</h4>
                            <a class="btn btn-success" href="<?php echo site_url('administrator/create_employee');?>"><span class="glyphicon glyphicon-plus"></span> Создать</a>
                        <?php else:?>
                        <form method="post" action="<?php echo base_url("administrator/delete_with_image/employees");?>">
                        <table class="table table-bordered table-striped ">
                            <thead>
                                <tr>
                                    <th></th>
                                    <th><center>Изображение</center></th>
                                    <th><center>ФИО</center></th>
                                    <th><center>Отдел</center></th>
                                    <th><center>Должность</center></th>
                                    <th><center>Редактировать</center></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($lists as $list):?>
                                <tr>
                                    <td><center><input name="id[]" value="<?php echo $list['id'];?>" type="checkbox"></center></td>
                                    <td><center><img width="200px" src="<?php echo base_url() . substr($list['path'], 2); ?>" /></center></td>
                                    <td><center><?php echo $list['name'];?></center></td>
                                    <td><center><?php echo $list['department'];?></center></td>
                                    <td><center><?php echo $list['post'];?></center></td>
                                    <td><center><a href="<?php echo site_url('administrator/edit_employee/'.$list['id']);?>"><span class="glyphicon glyphicon-pencil"></span></a></td>
                                </tr>
                                <?php endforeach;?>
                            </tbody>
                        </table>
                        <div class="dt-footer">
                            <input class="btn btn-danger" name="delete" type="submit" value="Удалить"> <a class="btn btn-success" href="<?php echo site_url('administrator/create_employee');?>"><span class="glyphicon glyphicon-plus"></span> Создать</a>
                        </div>
                    </form>
                    <?php endif;?> 
                    </div><!-- /.box-body -->
                </div><!-- /.box -->
            </div>
        </div>
    </section><!-- /.content -->
</aside><!-- /.right-side -->