<?php if(isset($message)):?>
<?php echo alert_message($message, 'info');?>
<?php endif;?>

<aside class="right-side">
    <section class="content-header"><h1>Пользователи системы</h1></section>
    <!-- Main content -->
    <section class="content">
        <div class="row">
		<div class="col-md-12">
			<div class="box-body table-responsive">
			<form method="post" action="<?php echo base_url("autorization/user_delete");?>">
			<table class="table table-bordered table-striped dataTable">
				<thead>
					<tr>
		                <th ></th>
		                <th class="text-center">Логин</th>
                        
		                <th class="text-center">Дополнительно</th>
		                <th class="text-center">Редактирование</th>
		             </tr>
				</thead>
				<tbody>
					<?php foreach ($users as $user):?>
					<tr>
						<td class="text-center">
							<?php //if ($user->groups[0]->name != $ion_auth_config['admin_group']):?>
								<input name="id[]" value="<?php echo $user['id'];?>" type="checkbox">
							<?php //endif;?>
						</td>
						<td><center><a href="<?php echo base_url() . 'crm/measure_work/' . $user['id']; ?>"><?php echo $user['username'];?></a></center></td>
                        <?php if($group['name'] == 'Монтажник'): ?>
                        <td class="text-center"><a class="label label-warning" href='<?php echo base_url("crm/calendar_work/".$user['id']); ?>'>Календарь</a></td>
                        <?php else: ?>
                        <td><center></center></td>
                        <?php endif; ?>
						<td><center><?php echo anchor("autorization/edit_user/".$user['id'], 'Редактировать') ;?></center></td>
					</tr>
					<?php endforeach;?>
				</tbody>
			</table>
			<p>
				<input type="hidden" name="table_name" value="users"/>
				<input class="btn btn-danger btn-small" name="delete" type="submit" value="Удалить">
				<a class="btn btn-success" href="<?php echo site_url('autorization/create_user');?>">Создать нового пользователя</a>
			</p>
			</form>
			</div>
		</div><!--/span-->

	</div><!--/row-->
</div>

    </section><!-- /.content -->
</aside><!-- /.right-side -->