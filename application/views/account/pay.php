<?php echo $header ?>
				<div class="pb-2 mt-4 mb-2">
					<h2>Пополнение баланса</h2>
				</div>
				<form class="pb-4" action="#" id="payForm" method="POST">
					<fieldset class="form-group" disabled>
						<div class="row">
							<label class="col-sm-3 col-form-label text-sm-right">Выберите способ:</label>
							<div class="col-sm-5">
								<div class="custom-control custom-radio pt-1">
								  <input type="radio" id="card" name="method" value="card" class="custom-control-input">
								  <label class="custom-control-label" for="card"><img class="ml-1 mr-1" src="https://money.yandex.ru/b/_/znDCcGN9U__lRVsmiQ6akvmMXuE.svg" width="16" height="16"> Банковская карта</label>
								</div>
								<div class="custom-control custom-radio pt-1">
								  <input type="radio" id="ym" name="method" value="ym" class="custom-control-input">
								  <label class="custom-control-label" for="ym"><img class="ml-1 mr-1" src="https://money.yandex.ru/b/_/sqJ2MGna3IZGNFXC9k4QOrzUG-c.svg" width="16" height="16"> Яндекс.Деньги</label>
								</div>
								<div class="custom-control custom-radio pt-1">
								  <input type="radio" id="qiwi" name="method" value="qiwi" class="custom-control-input">
								  <label class="custom-control-label" for="qiwi"><img class="ml-1 mr-1" src="https://corp.qiwi.com/dam/jcr:48e11032-1fc9-4c4d-83dd-446bafefde56/qiwi_mini.png" width="16" height="16"> QIWI Кошелек</label>
								</div>
							</div>
						</div>
					</fieldset>
					<div class="form-group row">
						<label for="ammount" class="col-sm-3 col-form-label text-sm-right">Сумма:</label>
						<div class="col-sm-5">
							<div class="input-group">
							  <input type="text" class="form-control" id="ammount" name="ammount" placeholder="Сумма" value="100" disabled>
							  <div class="input-group-append">
								<span class="input-group-text">руб.</span>
							  </div>
							</div>
						</div>
					</div>
					<div class="form-group row">
						<div class="offset-sm-3 col-sm-9">
							<button type="submit" class="btn btn-primary" disabled>Продолжить</button>
							&nbsp; <small class="text-muted">Скоро...</small>
						</div>
					</div>
				</form>
				<script>
					$('#payForm').ajaxForm({ 
						url: '/account/pay/ajax',
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
									redirect(data.url);
									break;
							}
						},
						beforeSubmit: function(arr, $form, options) {
							$('button[type=submit]').prop('disabled', true);
						}
					});
				</script>
<?php echo $footer ?>
