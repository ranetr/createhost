<?php echo $header ?>
				<div class="pb-2 mt-4 mb-2">
					<h2>Купить лицензию</h2>
				</div>
				<form action="#" id="orderForm" method="POST">
					<div class="pb-2 mt-4 mb-2">
						<h4>Основная информация</h4>
					</div>
					<div class="form-group row">
						<label for="ip" class="col-sm-3 col-form-label text-sm-right">Адрес сервера:</label>
						<div class="col-sm-5">
							<input type="text" class="form-control" id="ip" name="ip" placeholder="127.0.0.1:7777">
							<small class="text-muted">Вы всегда сможете изменить эти параметры</small>
						</div>
					</div>
					<div class="form-group row">
						<label for="months" class="col-sm-3 col-form-label text-sm-right">Период оплаты:</label>
						<div class="col-sm-4">
							<select class="form-control" id="months" name="months" onChange="updateForm()">
								<option value="1">1 месяц</option>
								<option value="3">3 месяца (-5%)</option>
								<option value="6">6 месяцев (-10%)</option>
								<option value="12">12 месяцев (-15%)</option>
								<option value="0">Навсегда (-30%)</option>
							</select>
						</div>
					</div>
					<div class="pb-2 mt-4 mb-2">
						<h4>Стоимость</h4>
					</div>
					<div class="form-group row">
						<label for="price" class="col-sm-3 text-sm-right">Итого:</label>
						<div class="col-sm-5">
							<p class="lead" id="price">0.00 руб.</p>
						</div>
					</div>
					<div class="form-group row">
						<div class="offset-sm-3 col-sm-9">
							<button type="submit" class="btn btn-primary" disabled>Заказать</button>
							&nbsp; <small class="text-muted">Скоро...</small>
						</div>
					</div>
				</form>
				<script>
					$('#orderForm').ajaxForm({ 
						url: '/licenses/order/ajax',
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
									setTimeout("redirect('/licenses/control/index/" + data.id + "')", 1500);
									break;
							}
						},
						beforeSubmit: function(arr, $form, options) {
							$('button[type=submit]').prop('disabled', true);
							showWarning("Подождите пожалуйста");
						}
					});
					
					$(document).ready(function() {
						updateForm();
					});
					
					function updateForm() {
						var price = 0; // 490
						var months = $("#months option:selected").val();
						switch(months) {
							case "3":
								price = 3 * price * 0.95;
								break;
							case "6":
								price = 6 * price * 0.90;
								break;
							case "12":
								price = 12 * price * 0.85;
								break;
							case "0":
								price = 0; // 8490
								break;
						}
						$('#price').text(price.toFixed(2) + ' руб.');
					}
				</script>
<?php echo $footer ?>
