<?php echo $header ?>
				<h2 style="padding: 0.5em 0;">Регистрация</h2>

				<form id="registerForm" action="#" method="POST">
					<div class="row">
						<div class="six columns">
						  <label for="email">Электронная почта</label>
						  <input class="u-full-width" type="email" id="email" name="email" placeholder="test@mailbox.com" value="<?php echo $email ?>">
						</div>
						<div class="six columns" style="color: #999;padding-left: 30px;">На указанный адрес будет отправлено письмо со<br> ссылкой для активации аккаунта</div>
					</div>
					<button class="button-primary" type="submit">Зарегистрироваться</button>
					<div><a href="/account/login">Уже зарегистрированы?</a></div>
				</form>
				<script>
					$('#registerForm').ajaxForm({ 
						url: '/account/register/ajax',
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
									break;
							}
						},
						beforeSubmit: function(arr, $form, options) {
							$('button[type=submit]').prop('disabled', true);
						}
					});
				</script>
<?php echo $footer ?>
