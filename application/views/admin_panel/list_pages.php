<aside class="right-side">
    <section class="content-header"><h1>Контент</h1></section>
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box">
                    <div class="box-header">
                        <h3 class="box-title">Статические страницы</h3>
                    </div><!-- /.box-header -->
                    <div class="box-body table-responsive">
                        <?php if (empty($lists) || !isset($lists) || $lists == FALSE):?>
                            <h4>Нет существующих страниц.</h4>
                            <a class="btn btn-success" href="<?php echo site_url('administrator/create_pages');?>"><span class="glyphicon glyphicon-plus"></span> Создать новую страницу</a>
                        <?php else:?>
                            <form method="post" action="<?php echo base_url("administrator/delete/page_content/id");?>">
                                <table class="table table-bordered table-striped dataTable">
                                    <thead>
                                    <tr>
                                        <th></th>
                                        <th><center>Заголовок</center></th>
                                        <th><center>Ссылка</center></th>
                                        <th><center>Доступ</center></th>
                                        <th><center>Редактировать</center></th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php foreach ($lists as $list):?>
                                        <tr>
                                            <td><center><input name="id[]" value="<?php echo $list['id'];?>" type="checkbox"></center></td>
                                            <td><center><?php echo $list['title'];?></center></td>
                                            <td><center>page/<?php echo $list['alias'];?></center></td>
                                            <td><center><?php if ($list['access'] == '0'){
                                                        echo '<a class="btn btn-xs btn-success" href="'.site_url('administrator/publish/page_content/1/'.$list['id']).'">Опубликовать</a>';
                                                    } elseif ($list['access'] == '1') {
                                                        echo '<a class="btn btn-xs btn-warning" href="'.site_url('administrator/publish/page_content/0/'.$list['id']).'">Снять с публикации</a>';
                                                    }?></center></td>
                                            <td><center><a href="<?php echo site_url('administrator/edit_pages/'.$list['id']);?>"><span class="glyphicon glyphicon-pencil"></span></a></td>
                                        </tr>
                                    <?php endforeach;?>
                                    </tbody>
                                </table>
                                <div class="dt-footer">
                                    <input class="btn btn-danger" name="delete" type="submit" value="Удалить"> <a class="btn btn-success" href="<?php echo site_url('administrator/create_pages');?>"><span class="glyphicon glyphicon-plus"></span> Создать новую страницу</a>
                                </div>
                            </form>
                        <?php endif;?>
                    </div><!-- /.box-body -->
                </div><!-- /.box -->
            </div>
        </div>
    </section><!-- /.content -->
</aside><!-- /.right-side -->