<?php echo $header ?>
				<!--<h2 style="padding: 0.2em 0;">Аккаунт</h2>

				<table class="u-full-width">
				  <tbody>
				    <tr>
				      <td width="40%">Логин</td>
				      <th>26</th>
				    </tr>
				    <tr>
				      <td>Электронная почта</td>
				      <td>zhidkoov@gmail.com</td>
				    </tr>
				    <tr>
				      <td>ФИО</td>
				      <td>Жидков Максим Николаевич</td>
				    </tr>
				    <tr>
				      <td>Телефон</td>
				      <td>79002778068</td>
				    </tr>
				    <tr>
				      <td>Лимит серверов</td>
				      <td>10</td>
				    </tr>  
				  </tbody>
				</table>
				<p>Для увеличения лимита и изменения ваших персональных данных необходимо написать в чат поддержки</p>

				<h3 style="padding: 0.2em 0;">Изменить пароль</h3>
				<form id="editForm" action="#" method="POST">
					<div class="row">
						<div class="six columns">
							<label for="password">Текущий пароль</label>
							<input class="u-full-width" type="password" id="password" name="password" placeholder="Введите текущий пароль">
						</div>
						<div class="six columns">
							<label for="newpassword">Новый пароль</label>
							<input class="u-full-width" type="password" id="newpassword" name="newpassword" placeholder="Введите новый пароль">
						</div>
					</div>
					<button class="button-primary" type="submit">Изменить</button>
				</form>-->

				<h2 style="padding: 0.2em 0;">Остался последний шаг</h2>
				
				<p>Для завершения регистрации необходимо указать свое ФИО и подтвердить номер телефона</p>

				<form id="editForm" action="#" method="POST">
					<div class="row">
						<div class="four columns">
							<label for="firstname">Имя</label>
							<input class="u-full-width" type="text" id="firstname" name="firstname" placeholder="Введите имя">
						</div>
						<div class="four columns">
							<label for="lastname">Фамилия</label>
							<input class="u-full-width" type="text" id="lastname" name="lastname" placeholder="Введите фамилию">
						</div>
						<div class="four columns">
							<label for="middlename">Отчество</label>
							<input class="u-full-width" type="text" id="middlename" name="middlename" placeholder="Введите отчество">
						</div>
					</div>
					<div class="row">
						<div class="six columns">
							<label for="phone">Телефон</label>
							<input class="u-full-width" type="text" id="phone" name="phone" placeholder="Введите номер телефона начиная с 7">
						</div>
						<div class="six columns" style="display: none">
							<label for="code">Код подтверждения</label>
							<input class="u-full-width" type="text" id="code" name="code" placeholder="Введите код из смс сообщения">
						</div>
						<div class="six columns" style="color: #999;padding-left: 30px;padding-top: 10px;">
							На указанный номер телефона будет отправлено смс с кодом. Зарегистрировать новый аккаунт с данным номером будет невозможно.
						</div>
					</div>
					<label style="padding: 15px 0">
						<input type="checkbox" name="offer">
						<span class="label-body">Я ознакомился(-ась) и согласен(-на) с условиями Договора оферты.</span>
					</label>
					<button class="button-primary" type="submit">Продолжить</button>
				</form>
				<script>
					$('#editForm').ajaxForm({ 
						url: '/account/edit/ajax',
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
									setTimeout("redirect('/servers/index')", 1500);
									break;
							}
						},
						beforeSubmit: function(arr, $form, options) {
							$('button[type=submit]').prop('disabled', true);
						}
					});
					$(function() {
						$("#phone").mask("79999999999");
					});
				</script>
<?php echo $footer ?>
