<?php echo $header ?>
				<div class="pb-2 mt-4 mb-2">
					<h2>Все лицензии</h2>
				</div>
				<table class="table">
					<thead>
						<tr>
							<th>ID</th>
							<th>Статус</th>
							<th>Сервер</th>
							<th>Адрес</th>
							<th>Владелец</th>
							<th>Срок лицензии</th>
							<th></th>
						</tr>
					</thead>
					<tbody>
						<?php foreach($licenses as $item): ?> 
						<tr class="table-<?php if($item['license_status'] == 0){echo 'danger';}elseif($item['license_status'] == 1){echo 'success';}?>" onClick="redirect('/admin/licenses/control/index/<?php echo $item['license_id'] ?>')">
							<td>#<?php echo $item['license_id'] ?></td>
							<td>
							<?php if($item['license_status'] == 0): ?> 
								<span class="badge badge-danger">Неактивна</span>
							<?php elseif($item['license_status'] == 1): ?> 
								<span class="badge badge-success">Активна</span>
							<?php endif; ?> 
							</td>
							<td>
							<?php if($item['license_plugin_status'] == 0): ?> 
								<span class="badge badge-danger">Offline</span>
							<?php elseif($item['license_status'] == 1): ?> 
								<span class="badge badge-success">Online</span>
							<?php endif; ?> 
							</td>
							<td><?php echo $item['license_ip'] ?>:<?php echo $item['license_port'] ?></td>
							<td><?php if($item['user_id'] != 0): ?><a class="text-dark" href="/admin/users/edit/index/<?php echo $item['user_id'] ?>"><?php echo $item['user_firstname'] ?> <?php echo $item['user_lastname'] ?></a><?php else: ?>&ndash;<?php endif; ?></td>
							<td><?php if(date("Y", strtotime($item['license_date_end'])) > 2100) echo 'навсегда'; else echo 'до '. date("d.m.Y", strtotime($item['license_date_end'])); ?></td>
							<td><?php if($item['license_mark'] != NULL): ?><span class="badge badge-sm badge-secondary" title="Пометка: <?php echo $item['license_mark'] ?>"><?php echo $item['license_mark'] ?></span><?php endif; ?></td>
						</tr>
						<?php endforeach; ?> 
						<?php if(empty($licenses)): ?> 
						<tr style="background-color: rgba(0,0,0,.05)">
							<td colspan="6" class="text-center">На данный момент нет лицензий.</td>
						<tr>
						<?php endif; ?> 
						<?php if($user_access_level >= 3): ?>
						<tr>
							<td colspan="6" class="text-center"><a href="/admin/licenses/create" class="btn btn-light"><i class="icon ion-md-add"></i> Создать лицензию</a></td>
						</tr>
						<?php endif; ?> 
					</tbody>
				</table>
				<?php echo $pagination ?> 
<?php echo $footer ?>
