<?php
/*
* @LitePanel
* @Version: 1.0.1
* @Date: 29.12.2012
* @Developed by QuickDevel
*/
?>
<?php echo $header ?>
				<div class="pb-2 mt-4 mb-2">
					<h2>Создание лицензии</h2>
				</div>
				<form action="#" id="createForm" method="POST">
					<div class="pb-2 mt-4 mb-2">
						<h4>Основная информация</h4>
					</div>
					<div class="form-group row">
						<label for="ip" class="col-sm-3 col-form-label text-sm-right">Адрес:</label>
						<div class="col-sm-5">
							<input type="text" class="form-control" id="ip" name="ip" placeholder="127.0.0.1:7777">
						</div>
					</div>
					<div class="form-group row">
						<label for="userid" class="col-sm-3 col-form-label text-sm-right">Владелец:</label>
						<div class="col-sm-5">
							<input type="text" class="form-control" id="userid" name="userid" placeholder="ID пользователя">
							<small class="text-muted">Оставьте поле пустым, если владельца нет</small>
						</div>
					</div>
					<div class="pb-2 mt-4 mb-2">
						<h4>Дополнительная информация</h4>
					</div>
					<div class="form-group row">
						<label for="mark" class="col-sm-3 col-form-label text-sm-right">Пометка: <a href="#" onclick="return false;" style="cursor: default;" data-toggle="tooltip" data-placement="top" title="Краткая пометка, которая видна только администрации"><i class="icon ion-md-help-circle-outline"></i></a></label>
						<div class="col-sm-5">
							<input type="text" class="form-control" id="mark" name="mark" placeholder="необязательно">
						</div>
					</div>
					<div class="form-group row">
						<label for="months" class="col-sm-3 col-form-label text-sm-right">Срок лицензии:</label>
						<div class="col-sm-4">
							<select class="form-control" id="months" name="months">
								<option value="1">1 месяц</option>
								<option value="3">3 месяца</option>
								<option value="6">6 месяцев</option>
								<option value="12">1 год</option>
								<option value="1200">Навсегда</option>
							</select>
						</div>
					</div>
					<div class="form-group row">
						<label for="status" class="col-sm-3 col-form-label text-sm-right">Статус:</label>
						<div class="col-sm-4">
							<select class="form-control" id="status" name="status">
								<option value="0">Заблокирована</option>
								<option value="1" selected>Активирована</option>
							</select>
						</div>
					</div>
					<div class="form-group row">
						<div class="offset-sm-3 col-sm-9">
							<button type="submit" class="btn btn-primary">Создать</button>
						</div>
					</div>
				</form>
				<script>
					$('#createForm').ajaxForm({ 
						url: '/admin/licenses/create/ajax',
						dataType: 'json',
						success: function(data) {
							switch(data.status) {
								case 'error':
									showError(data.error);
									$('button[type=submit]').prop('disabled', false);
									break;
								case 'success':
									showSuccess(data.success);
									setTimeout("redirect('admin/licenses/control/index/" + data.id + "')", 1500);
									break;
							}
						},
						beforeSubmit: function(arr, $form, options) {
							$('button[type=submit]').prop('disabled', true);
						}
					});
					$(function () {
					  $('[data-toggle="tooltip"]').tooltip()
					})
				</script>
<?php echo $footer ?>
