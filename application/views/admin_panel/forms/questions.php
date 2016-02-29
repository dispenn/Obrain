<aside class="right-side">
    <section class="content-header"><h1>Вопросы</h1></section>
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box">
                    <div class="box-body table-responsive">
                        <?php if (empty($lists) || !isset($lists) || $lists == FALSE):?>
                            <h4>Список пуст.</h4>
                        <?php else:?>
                        <form method="post" action="<?php echo base_url("administrator/delete/forms/id");?>">
                        <table class="table table-bordered table-striped ">
                            <thead>
                                <tr>
                                    <th></th>
                                    <th><center>Имя</center></th>
                                    <th><center>Компания</center></th>
                                    <th><center>Телефон</center></th>
                                    <th><center>Email</center></th>
                                    <th><center>Текст</center></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($lists as $list):?>
                                <tr>
                                    <td><center><input name="id[]" value="<?php echo $list['id'];?>" type="checkbox"></center></td>
                                    <td><center><?php echo $list['name'];?></center></td>
                                    <td><center><?php echo $list['company'];?></center></td>
                                    <td><center><?php echo $list['phone'];?></center></td>
                                    <td><center><?php echo $list['email'];?></center></td>
                                    <td><center><?php echo $list['text'];?></center></td>
                                </tr>
                                <?php endforeach;?>
                            </tbody>
                        </table>
                        <div class="dt-footer">
                            <input class="btn btn-danger" name="delete" type="submit" value="Удалить">
                        </div>
                    </form>
                    <?php endif;?> 
                    </div><!-- /.box-body -->
                </div><!-- /.box -->
            </div>
        </div>
    </section><!-- /.content -->
</aside><!-- /.right-side -->