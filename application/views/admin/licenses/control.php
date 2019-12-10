<?php echo $header ?>
				<div class="pb-2 mt-4 mb-2">
					<h2>Управление лицензией #<?php echo $license['license_id'] ?> <?php if($license['license_mark'] != null) echo "<span class='badge badge-secondary'>$license[license_mark]</span>"; ?></h2>
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
									<div id="controlBtns">
										<div class="dropdown">
										  <button class="btn btn-primary dropdown-toggle" style="width: 100%;margin-bottom: 5px;" type="button" id="dropdownDownload" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
										    Скачать плагин
										  </button>
										  <div class="dropdown-menu" aria-labelledby="dropdownDownload">
										    <a class="dropdown-item" href="/admin/licenses/control/download/<?php echo $license['license_id'] ?>/dll">sampvoice.dll <small class="text-muted">for Win</small></a>
										    <a class="dropdown-item" href="/admin/licenses/control/download/<?php echo $license['license_id'] ?>/so">sampvoice.so <small class="text-muted">for Linux</small></a>
										  </div>
										</div>
										<button style="width: 100%;margin-bottom: 5px;" type="button" class="btn btn-<?php echo (($license['license_status'] == 1) ? 'danger' : 'success')?>" onClick="sendAction(<?php echo $license['license_id'] ?>,'status')"><?php echo (($license['license_status'] == 1) ? 'Заблокировать' : 'Разблокировать')?></button>
									</div>
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
							<?php if($license['user_id'] != 0): ?>
							<tr>
								<th>Владелец:</th>
								<td><a href="/admin/users/edit/index/<?php echo $license['user_id'] ?>"><?php echo $license['user_firstname'] ?> <?php echo $license['user_lastname'] ?></a></td>
							</tr>
							<?php endif; ?>
							<tr>
								<th>Дата окончания лицензии:</th>
								<td><?php echo date("d.m.Y", strtotime($license['license_date_end'])) ?></td>
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
						<label for="userid" class="col-sm-3 col-form-label text-sm-right">Владелец:</label>
						<div class="col-sm-5">
							<input type="text" class="form-control" id="userid" name="userid" placeholder="ID пользователя" value="<?php echo $license['user_id'] ?>">
							<small class="text-muted">Оставьте поле пустым, если владельца нет</small>
						</div>
					</div>
					<div class="form-group row">
						<label for="token" class="col-sm-3 col-form-label text-sm-right">Токен: <a href="#" onclick="return false;" style="cursor: default;" data-toggle="tooltip" data-placement="top" title="Используется при формировании файла"><i class="icon ion-md-help-circle-outline"></i></a></label>
						<div class="col-sm-5">
							<input type="text" class="form-control" id="token" name="token" placeholder="Личный токен" value="<?php echo $license['license_token'] ?>">
						</div>
					</div>
					<div class="form-group row">
						<label for="mark" class="col-sm-3 col-form-label text-sm-right">Пометка:</label>
						<div class="col-sm-5">
							<input type="text" class="form-control" id="mark" name="mark" placeholder="Краткая пометка (необязательно)" value="<?php echo $license['license_mark'] ?>">
						</div>
					</div>
					<div class="form-group row">
						<label for="dateend" class="col-sm-3 col-form-label text-sm-right">Срок лицензии:</label>
						<div class="col-sm-5">
							<input type="date" class="form-control" id="dateend" name="dateend" placeholder="Дата оканчания лицензии" value="<?php echo date("Y-m-d", strtotime($license['license_date_end'])) ?>">
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
						url: '/admin/licenses/control/ajax/<?php echo $license['license_id'] ?>',
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
									setTimeout("reload()", 1000);
									break;
							}
						},
						beforeSubmit: function(arr, $form, options) {
							$('button[type=submit]').prop('disabled', true);
						}
					});
					function sendAction(licenseid, action) {
						switch(action) {
							case "status":
							{
								if(!confirm("Вы уверенны в том, что хотите изменить статус лицензии?")) return;
								break;
							}
						}
						$.ajax({ 
							url: '/admin/licenses/control/action/'+licenseid+'/'+action,
							dataType: 'text',
							success: function(data) {
								console.log(data);
								data = $.parseJSON(data);
								switch(data.status) {
									case 'error':
										showError(data.error);
										$('#controlBtns button').prop('disabled', false);
										break;
									case 'success':
										showSuccess(data.success);
										setTimeout("reload()", 1000);
										break;
								}
							},
							beforeSend: function(arr, options) {
								$('#controlBtns button').prop('disabled', true);
							}
						});
					}
					$(function () {
					  $('[data-toggle="tooltip"]').tooltip()
					})
				</script>
<?php echo $footer ?>
