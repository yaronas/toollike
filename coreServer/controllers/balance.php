<div id="content_text_balance">
	<br /> <br />
	<div class="setbal_section">
		<h2>Состояние личного счёта</h2>
		<div id="setbal_hr"></div>
		<br /> 
		<b>Like</b> – это универсальная валюта сервиса. Кроме этого, Like можно оплатить задании. Обратите внимание, что услуга считается оказанной в момент зачисления Like, возврат средств невозможен.
	</div>
	<div class="gray_title"> 
		<center> На Вашем счёте: <b><?= $this->userBaseInfo['userMoney']; ?> ♥</b> </center> 
	</div>
	<br />
	<div class="title_balance_hr"> </div>
</div>

<div id = "history_balance">
	<table id="body_next_tasks" class="main">
		<?= BALANC::userStart($this->userBaseInfo); ?>
	</table>
	<div id="tasks_all_next_wrap"> <div id="tasks_all_next">Показать ещё</div> </div>
</div>