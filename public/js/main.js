/*
* @Developed by Skeleton
*/

/* Ошибки, предупреждения... */
function showError(text) {
	var element = $('<div class="alert alert-danger"><strong>Ошибка!</strong> ' + text + '</div>').prependTo('#content');
	setTimeout(function() {
		element.fadeOut(500, function() {
			$(this).remove();
		});
	}, 10000);
}
function showWarning(text) {
	var element = $('<div class="alert alert-warning"><strong>Проверка данных...</strong> ' + text + '</div>').prependTo('#content');
	setTimeout(function() {
		element.fadeOut(500, function() {
			$(this).remove();
		});
	}, 10000);
}
function showSuccess(text) {
	var element = $('<div class="alert alert-success"><strong>Выполнено!</strong> ' + text + '</div>').prependTo('#content');
	setTimeout(function() {
		element.fadeOut(500, function() {
			$(this).remove();
		});
	}, 10000);
}

function redirect(url) {
	document.location.href=url;
}

function reloadImage(img) {
	var src = $(img).attr('src');
	$(img).attr('src', src);
};

function reload() {
	window.location.reload();
}
