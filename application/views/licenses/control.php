<?php echo $header ?>
				<div class="pb-2 mt-4 mb-2">
					<h2>Управление лицензией #<?php echo $license['license_id'] ?></h2>
				</div>
				<div class="card mb-4">
					<div class="card-header">Основная информация</div>
					<div class="card-body">
						<table class="table mb-0">
							<tr>
								<th width="200px" rowspan="20" style="border-top: 0">
									<div align="center" class="pb-1">
										<img src="https://ionicons.com/ionicons/svg/md-cloud.svg" style="width:160px; margin-bottom:5px;">
									</div>
									<?php if($license['license_status'] == 1): ?> 
									<button style="width: 100%;margin-bottom: 5px;" type="button" class="btn btn-success" onClick="sendAction(<?php echo $server['server_id'] ?>,'start')"><span class="glyphicon glyphicon-off"></span> Скачать плагин</button>
									<?php elseif($license['license_status'] == 0): ?> 
									<button style="width: 100%;margin-bottom: 5px;" type="button" class="btn btn-info" onClick="sendAction(<?php echo $server['server_id'] ?>,'restart')"><span class="glyphicon glyphicon-refresh"></span> Продлить</button>
									<?php endif; ?>
								</th>
								<th style="border-top: 0">Адрес:</th>
								<td style="border-top: 0"><?php echo $license['license_ip'] ?>:<?php echo $license['license_port'] ?></td>
							</tr>
							<tr>
								<th>Статус сервера:</th>
								<td>
									<?php if($license['license_plugin_status'] == 0): ?> 
									<span class="badge badge-danger">Offline</span>
									<?php elseif($license['license_plugin_status'] == 1): ?> 
									<span class="badge badge-success">Online</span>
									<?php endif; ?> 
								</td>
							</tr>
							<?php if($license['license_plugin_version'] != null): ?> 
							<tr>
								<th>Запущен:</th>
								<td><small><?php echo date('d.m.Y в H:i', strtotime($license['license_stats_start'])) ?></small></td>
							</tr>
							<?php if($license['license_plugin_status'] == 0): ?>
							<tr>
								<th>Остановлен:</th>
								<td><small><?php echo date('d.m.Y в H:i', strtotime($license['license_stats_end'])) ?></small></td>
							</tr>
							<?php endif; ?>
							<tr>
								<th>Версия плагина:</th>
								<td><?php echo $license['license_plugin_version'] ?></td>
							</tr>
							<tr>
								<th>Игроки:</th>
								<td><abbr title="Игроков с плагином"><?php echo $license['license_stats_svplayers'] ?></abbr> / <abbr title="Игроков всего"><?php echo $license['license_stats_players'] ?></abbr></td>
							</tr>
							<tr>
								<th>Слоты:</th>
								<td><abbr title="Максимально допустимое количество игроков"><?php echo $license['license_stats_maxplayers'] ?></abbr></td>
							</tr>
							<?php else: ?>
							<tr>
								<th>Версия плагина:</th>
								<td><span class="badge badge-info">Нет данных</span></td>
							</tr>
							<tr>
								<th>Игроки:</th>
								<td><span class="badge badge-info">Нет данных</span></td>
							</tr>
							<tr>
								<th>Слоты:</th>
								<td><span class="badge badge-info">Нет данных</span></td>
							</tr>
							<?php endif; ?>
							<tr>
								<th>Дата окончания лицензии:</th>
								<td><?php echo date("d.m.Y", strtotime($license['license_date_end'])) ?> <a href="/licenses/pay/index/<?php echo $license['license_id'] ?>" class="badge badge-light">Продлить</a></td>
							</tr>
							<tr>
								<th>Статус лицензии:</th>
								<td>
									<?php if($license['license_status'] == 0): ?> 
									<span class="badge badge-danger">Заблокирована</span>
									<?php elseif($license['license_status'] == 1): ?> 
									<span class="badge badge-success">Активирована</span>
									<?php endif; ?> 
								</td>
							</tr>
						</table>
					</div>
				</div>
				<div class="pb-2 mt-4 mb-2">
					<h3>Редактирование</h3>
				</div>
				<form action="#" id="editForm" method="POST">
					<div class="form-group row">
						<label for="ip" class="col-sm-3 col-form-label text-sm-right">Адрес:</label>
						<div class="col-sm-5">
							<input type="text" class="form-control" id="ip" name="ip" placeholder="127.0.0.1:7777" value="<?php echo $license['license_ip'].':'.$license['license_port'] ?>">
						</div>
					</div>
					<div class="form-group row">
						<div class="offset-sm-3 col-sm-9">
							<button type="submit" class="btn btn-primary">Сохранить</button>
						</div>
					</div>
				</form>
				<script>
					$('#editForm').ajaxForm({ 
						url: '/licenses/control/ajax/<?php echo $license['license_id'] ?>',
						dataType: 'text',
						success: function(data) {
							console.log(data);
							data = $.parseJSON(data);
							switch(data.status) {
								case 'error':
									showError(data.error);
									$('button[type=submit]').prop('disabled', false);
									break;
								case 'success':
									showSuccess(data.success);
									setTimeout("reload()", 1500);
									break;
							}
						},
						beforeSubmit: function(arr, $form, options) {
							$('button[type=submit]').prop('disabled', true);
						}
					});
				</script>
<?php echo $footer ?>
