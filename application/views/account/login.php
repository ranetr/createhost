<?php echo $header ?> 
		<h2 style="padding: 0.5em 0;">Вход в систему</h2>

		<!-- The above form looks like this -->
		<form id="loginForm" action="#" method="POST">
			<label for="email">Электронная почта</label>
			<input style="width: 460px" type="email" id="email" name="email" placeholder="test@mailbox.com">
			<label for="password">Пароль</label>
			<input style="width: 460px" type="password" id="password" name="password" placeholder="********">
			<!--<label class="example-send-yourself-copy">
			<input type="checkbox">
			<span class="label-body">Запомнить меня</span>
			</label>-->
			<div><button class="button-primary" type="submit">Войти</button></div>
			<div><a href="/account/register">Еще не зарегистрированы?</a></div>
			<div><a href="/account/restore">Забыли пароль?</a></div>
		</form>
		<script>
			$('#loginForm').ajaxForm({ 
				url: '/account/login/ajax',
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
							setTimeout("redirect('/')", 1500);
							break;
					}
				},
				beforeSubmit: function(arr, $form, options) {
					$('button[type=submit]').prop('disabled', true);
				}
			});
		</script>
<?php echo $footer ?>
