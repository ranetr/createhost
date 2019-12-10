<?php echo $header ?>
		<h2 style="padding: 0.5em 0;">Сброс пароля</h2>

		<form id="restoreForm" action="#" method="POST">
			<div class="row">
				<div class="six columns">
				  <label for="email">Электронная почта</label>
				  <input class="u-full-width" type="email" id="email" name="email" placeholder="test@mailbox.com">
				</div>
				<div class="six columns" style="color: #999;padding-left: 30px;">Ссылка для сброса пароля будет отправлена на <br> указанный адрес электронной почты</div>
			</div>
			<button class="button-primary" type="submit">Сбросить пароль</button>
			<div style="color: #888">Вспомнили? <a href="/account/login">Войти</a></div>
		</form>
		<script>
			$('#restoreForm').ajaxForm({ 
				url: '/account/restore/ajax',
				dataType: 'text',
				success: function(data) {
					console.log(data);
					data = $.parseJSON(data);
					switch(data.status) {
						case 'error':
							showError(data.error);
							reloadImage('.captcha img');
							$('button[type=submit]').prop('disabled', false);
							break;
						case 'success':
							showSuccess(data.success);
							break;
					}
				},
				beforeSubmit: function(arr, $form, options) {
					$('button[type=submit]').prop('disabled', true);
				}
			});
			$('.captcha img').click(function() {
				reloadImage(this);
			});
		</script>
<?php echo $footer ?>
