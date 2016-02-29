<aside class="right-side">
    <section class="content-header"><h1>Продукция</h1></section>
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box">
                    <div class="box-body table-responsive">
                        <?php if (empty($lists) || !isset($lists) || $lists == FALSE):?>
                            <h4>Список пуст.</h4>
                            <a class="btn btn-success" href="<?php echo site_url('administrator/create_catalog');?>"><span class="glyphicon glyphicon-plus"></span> Создать</a>
                        <?php else:?>
                        <form method="post" action="<?php echo base_url("administrator/delete_with_image/catalog");?>">
                        <table class="table table-bordered table-striped ">
                            <thead>
                                <tr>
                                    <th></th>
                                    <th><center>Изображение</center></th>
                                    <th><center>Название</center></th>
                                    <th><center>Категория</center></th>
                                    <th><center>Описание</center></th>
                                    <th><center>Редактировать</center></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($lists as $list):?>
                                <tr>
                                    <td><center><input name="id[]" value="<?php echo $list['id'];?>" type="checkbox"></center></td>
                                    <td><center><img width="200px" src="<?php echo base_url() . substr($list['path'], 2); ?>" /></center></td>
                                    <td><center><?php echo $list['title'];?></center></td>
                                    <td><center><?php echo $list['category'];?></center></td>
                                    <td><center><?php echo $list['description'];?></center></td>
                                    <td><center><a href="<?php echo site_url('administrator/edit_catalog/'.$list['id']);?>"><span class="glyphicon glyphicon-pencil"></span></a></td>
                                </tr>
                                <?php endforeach;?>
                            </tbody>
                        </table>
                        <div class="dt-footer">
                            <input class="btn btn-danger" name="delete" type="submit" value="Удалить"> <a class="btn btn-success" href="<?php echo site_url('administrator/create_catalog');?>"><span class="glyphicon glyphicon-plus"></span> Создать</a>
                        </div>
                    </form>
                    <?php endif;?> 
                    </div><!-- /.box-body -->
                </div><!-- /.box -->
            </div>
        </div>
    </section><!-- /.content -->
</aside><!-- /.right-side -->