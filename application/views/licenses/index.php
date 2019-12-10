<?php echo $header ?>
				<div class="pb-2 mt-4 mb-2">
					<h2>Мои лицензии</h2>
				</div>
				<table class="table">
					<thead>
						<tr>
							<th>ID</th>
							<th>Статус</th>
							<th>Сервер</th>
							<th>IP</th>
							<th><i class="icon ion-md-people"></i> Игроков</th>
							<th>Срок лицензии</th>
						</tr>
					</thead>
					<tbody>
						<?php foreach($licenses as $item): ?> 
						<tr class="table-<?php if($item['license_status'] == 0){echo 'danger';}elseif($item['license_status'] == 1){echo 'success';}?>" onClick="redirect('/licenses/control/index/<?php echo $item['license_id'] ?>')">
							<td>№<?php echo $item['license_id'] ?></td>
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
							<td><?php if(!is_null($item['license_plugin_version'])): ?><?php echo $item['license_stats_svplayers'] ?> / <?php echo $item['license_stats_players'] ?> / <?php echo $item['license_stats_maxplayers'] ?><?php else: ?>&ndash;<?php endif; ?></td>
							<td><?php if(date("Y", strtotime($item['license_date_end'])) > 2100) echo 'навсегда'; else echo 'до '. date("d.m.Y", strtotime($item['license_date_end'])); ?></td>
						</tr>
						<?php endforeach; ?> 
						<?php if(empty($licenses)): ?> 
						<tr style="background-color: rgba(0,0,0,.05)">
							<td colspan="6" class="text-center">На данный момент у вас нет лицензий.</td>
						<tr>
						<?php endif; ?> 
					</tbody>
				</table>
				<center><a href="/licenses/order" class="btn btn-light"><i class="icon ion-md-add"></i> Купить лицензию</a></center>
				<?php echo $pagination ?> 
<?php echo $footer ?>
