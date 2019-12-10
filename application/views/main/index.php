<?php echo $header ?>
	<section class="header">
      <h2 class="title">Виртуальные серверы для разработчиков</h2>
      <p class="heading-font-size" style="font-size: 1.6rem">Быстрые серверы с легкой настройкой и удобным управлением</p>
		<!-- Form Email Create Server -->
		<form action="/account/register/ajax" method="POST">
        <input type="hidden" name="noajax" value="true">
		    <input style="width: 400px" type="email" name="email" placeholder="Укажите адрес электронной почты">
		    <input class="button-primary" type="submit" value="Создать сервер">
		</form>

      <!--<div class="value-props row">
        <div class="four columns value-prop">
          <img class="value-img" src="http://getskeleton.com/images/feather.svg">
          Light as a feather at ~400 lines &amp; built with mobile in mind.
        </div>
        <div class="four columns value-prop">
          <img class="value-img" src="http://getskeleton.com/images/pens.svg">
          Styles designed to be a starting point, not a UI framework.
        </div>
        <div class="four columns value-prop">
          <img class="value-img" src="http://getskeleton.com/images/watch.svg">
          Quick to start with zero compiling or installing necessary.
        </div>
      </div>-->
    </section>

	<div class="value-props row" style="padding-top: 7rem">
        <div class="four columns value-prop">
          <img class="value-img" src="/public/img/sys.png" width="200">
        </div>
        <div class="eight columns value-prop">
          <h4 class="title">Высокая производительность</h4>
          <p class="t1">Вся инфраструктура построена<br> на современном оборудовании</p>
          <ul>
            <li>SSD-диски серверного класса</li>
            <li>Быстрая память</li>
            <li>Многоядерные процессоры нового поколения Intel Xeon E5</li>
          </ul>
        </div>
    </div>

    <hr>

	<div class="value-props row">
        <div class="seven columns value-prop">
          <h4 class="title">Удобный интерфейс панели</h4>
          <p class="t1">Интерфейс для людей, а не для гиков</p>
          <ul>
            <li>Создание и управление серверами всего в пару кликов</li>
            <li>Вся необходимая информация всегда под рукой</li>
            <li>Подробная статистика о платежах и списаниях</li>
          </ul>
        </div>
        <div class="four columns value-prop">
          <img class="value-img" src="/public/img/panel.png" width="200">
        </div>
    </div>

    <hr>

	<div class="value-props row">
        <div class="four columns value-prop">
          <img class="value-img" src="/public/img/os.png" width="200">
        </div>
        <div class="eight columns value-prop">
          <h4 class="title">Операционные системы</h4>
          <p class="t1">Установка в 1 клик</p>
          <ul>
            <li>Для установки доступны всегда свежие дистрибутивы Linux на любой вкус: Ubuntu, Debian, CentOS, OpenSUSE, Fedora.</li>
            <li>Установка готового игрового хостинга LitePanel (подробнее)</li>
            <li>Масштабирование проектов на лету!</li>
            <li>И многое другое...</li>
          </ul>
        </div>
    </div>

    <hr>

	<section class="header" style="margin: 9rem auto 9rem auto">
		<form>
		    <input style="width: 400px" type="email" placeholder="Укажите адрес электронной почты">
		    <input class="button-primary" type="submit" value="Создать сервер">
		    <p class="heading-font-size">Нажимая кнопку "Создать сервер", вы соглашаетесь с условиями политики в отношении обработки и защиты персональных данных.</p>
		</form>
    </section>

<?php echo $footer ?>
