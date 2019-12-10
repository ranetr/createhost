<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="description" content="<?php echo $description ?>">
	<meta name="keywords" content="<?php echo $keywords ?>">
	
	<title><?php echo $title ?>. <?php echo $description ?></title>
    
	<!-- FONT -->
	<link href="//fonts.googleapis.com/css?family=Open+Sans:400,300,600" rel="stylesheet" type="text/css">
	
	<!-- CSS -->
	<link rel="stylesheet" href="/public/css/normalize.css">
	<link rel="stylesheet" href="/public/css/skeleton.css">

<style>
.header {
  margin-top: 6rem;
  text-align: center; }
.value-prop {
  margin-top: 1rem; }
.value-prop .t1 {
	font-size: 18px;
	line-height: 24px;
	margin-top: 18px;
	letter-spacing: -.3px;
	color: #26bcf1;
	white-space: pre-line; }
.value-props {
  margin-top: 4rem;
  margin-bottom: 4rem; }
.docs-header {
  text-transform: uppercase;
  font-size: 1.4rem;
  letter-spacing: .2rem;
  font-weight: 600; }
.docs-section {
  border-top: 1px solid #eee;
  padding: 4rem 0;
  margin-bottom: 0;}
.value-img {
  display: block;
  text-align: center;
  margin: 2.5rem auto 0; }
.heading-font-size {
  font-size: 1.2rem;
  color: #999;
  letter-spacing: normal; }



  /* Navbar */
  .navbar + .docs-section {
    border-top-width: 0; }
  .navbar,
  .navbar-spacer {
  	/* Fixed Navbar */
  	position: fixed;
    top: 0;
    left: 0;
    /**/
    display: block;
    width: 100%;
    height: 6.5rem;
    background: #fff;
    z-index: 99;
    border-top: 1px solid #eee;
    border-bottom: 1px solid #eee; }
  /*.navbar-spacer {
    display: none; }*/
  .navbar > .container {
    width: 90%; }
  .navbar-list {
    list-style: none;
    margin-bottom: 0; }
  .navbar-item {
    position: relative;
    float: left;
    margin-bottom: 0; }
  .navbar-link {
    text-transform: uppercase;
    font-size: 11px;
    font-weight: 600;
    letter-spacing: .2rem;
    margin-right: 35px;
    text-decoration: none;
    line-height: 6.5rem;
    color: #222; }
  .navbar-link.active {
    color: #33C3F0; }
  .has-docked-nav .navbar {
    position: fixed;
    top: 0;
    left: 0; }
  .has-docked-nav .navbar-spacer {
    display: block; }
  .navbar-right {
  	float: right;
  	display: none;
  }
  @media (min-width: 800px) {
  	.navbar-right {
  		display: block; }
  }

</style>

    <!-- JAVASCRIPTS -->
    <script src="/public/js/jquery.min.js"></script>
    <script src="/public/js/jquery.form.min.js"></script>
    <script src="/public/js/main.js"></script>
    <?php foreach($scripts as $item): ?><script src="<?php echo $item ?>"></script>
    <?php endforeach; ?><script>
      $(function() { <?php if(isset($error)): ?>showError('<?php echo $error ?>');<?php endif; ?> 
        <?php if(isset($warning)): ?>showWarning('<?php echo $warning ?>');<?php endif; ?> 
        <?php if(isset($success)): ?>showSuccess('<?php echo $success ?>');<?php endif; ?> });
    </script>
	
	<!-- From: https://ionicons.com/ -->
	<link href="https://unpkg.com/ionicons@4.5.10-1/dist/css/ionicons.min.css" rel="stylesheet">
</head>
<body>
	<nav class="navbar">
      <div class="container">
        <ul class="navbar-list">
          <li class="navbar-item"><a class="navbar-link" href="/"><img src="/public/img/vscale-logo-black.svg" width="70" style="margin: 23px 0"></a></li>
          <?php if($logged == true): ?>
          <li class="navbar-item"><a class="navbar-link" href="/servers/index">Серверы</a></li>
          <li class="navbar-item"><a class="navbar-link" href="/account/invoices">Платежи</a></li>
          <li class="navbar-item"><a class="navbar-link" href="/account/edit">Настройки</a></li>
          <?php else: ?>
          <li class="navbar-item"><a class="navbar-link" href="/#pricing">Цены</a></li>
          <li class="navbar-item"><a class="navbar-link" href="/#features">Возможности</a></li>
          <li class="navbar-item"><a class="navbar-link" href="/#help">Помощь</a></li>
          <?php endif; ?>
        </ul>
        <ul class="navbar-list navbar-right">
			<?php if($logged == true): ?>
			<li class="navbar-item"><a class="navbar-link" href="/account/logout">Выйти</a></li>
			<?php else: ?>
        	<li class="navbar-item"><a class="navbar-link" href="/account/login">Войти</a></li>
        	<li class="navbar-item"><a class="navbar-link" href="/account/register">Зарегистрироваться</a></li>
			<?php endif; ?>
        </ul>
      </div>
    </nav>
    <div class="container">
    	<div class="row">
            <div id="content" class="twelve columns" style="margin-top: 100px">
