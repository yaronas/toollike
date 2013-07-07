var _users = {
 _login: function() {
  var login = $('#login').val();
  var password = $('#password').val();
  $('#login_button .box_button_first').html('<div class="upload_inv"></div>');
  if (login != '' && password != '') {
      $.post('/auth', {
   method: 'login',
   login: login,
   password: password
  }, function(data) {
  var response = data.match(/\d+/);
   if(response == 1) {
    var error = '\
    <b>Не удается войти.</b>\
    <br />\
    <span style="padding-left: 1px;">Пожалуйста, проверьте правильность написания <b>логина</b> и <b>пароля</b>.</span>\
    ';
    _box._show({width: 500, title: 'Ошибка', textMessage: 0, message: error, second_button: 'Отмена'});
   } else if(response == 2) {
    $('#login_button .box_button_first').html('<div class="upload_inv"></div>');
    location.href = '/home';
   }
   $('#login_button .box_button_first').html('Войти');
  });
  } else {
      userErrorMessage ('Пожалуйста, заполните все необходимые поля');
      $('#login_button .box_button_first').html('Войти');
  }
 },

  _box_restore: function() {
    var messages = '\
    <div id ="task_info_table">\
    <br />\
     <div class="text">Установите в статусе данный текст «<b>Я люблю тебя</b>»\
   </div>\
    ';
    var error = '\
    <center><input type="text" placeholder = "Введите ваш логин" id="restore_login"><center><input type="password" placeholder = "Введите новый пароль" id="new_password"><div onclick="_users._restore();" id="reg_button" class="box_button_first_wrap"><div class="box_buttons box_button_first">Восстановить доступ <b>»</b></div></div></center>\
    ';
    _box._show({width: 330, title: 'Восстановление доступа к странице',textMessage: 0, message: error, second_button: 'Отмена'});
 },

  _restore: function() {
  var login = $('#restore_login').val();
  var password = $('#new_password').val();
  $('#reg_button .box_button_first').html('<div class="upload_inv"></div>');
  if (login != '' && password != '') {
    $.post('/auth', {
   method: 'restore',
   login: login,
   new_password: password
  }, function(data) {
    var response = data.match(/\d+/);
    if (response == 1) {
      $('#reg_button .box_button_first').html('<div class="upload_inv"></div>');
      location.href = '/home';
    } else if (response == 2) {
      userErrorMessage ('Пожалуйста, установите в статусе содержимое текста «<b>Я люблю тебя</b>»');
      $('#reg_button .box_button_first').html('Восстановить доступ <b>»</b>');
    } else if (response == 3) {
      userErrorMessage ('Данный пользователь не зарегистрирован в нашей системе ');
      $('#reg_button .box_button_first').html('Восстановить доступ <b>»</b>');
    }
    $('#reg_button .box_button_first').html('Восстановить доступ <b>»</b>');
  });
  } else {
      userErrorMessage ('Пожалуйста, заполните все необходимые поля');
      $('#reg_button .box_button_first').html('Восстановить доступ <b>»</b>');
  }
 },

 _reg: function() {
  var url = $('#reg_vk').val();
  var login = $('#reg_login').val();
  var password = $('#reg_password').val();
  $('#reg_error').hide().html('');
  $('#reg_button .box_button_first').html('<div class="upload_inv"></div>');
  if (url != '' && login != '' && password != '') {
   $.post('/auth', {
   method: 'reg',
   uid: url,
   login: login,
   password: password
  }, function(data) {
  var response = data.match(/\d+/);
  if(response == 2) {
      $('#reg_button .box_button_first').html('<div class="upload_inv"></div>');
      location.href = '/home';
   } else if(response == 5) {
    userErrorMessage ('Такой логин уже занят.');
    $('#reg_button .box_button_first').html('Зарегистрироваться <b>»</b>');
   } else if(response == 4) {
    userErrorMessage ('Пользователь с такой ссылкой уже зарегистрирован на нашем сайте.');
    $('#reg_button .box_button_first').html('Зарегистрироваться <b>»</b>');
   } else if(response == 3) {
    var error = '\
    <div id="reg_error_table">\
     <b>Подтвердите, что страница принадлежит Вам.</b>\
     <div class="text">Установите в статусе данный текст «<b>Я люблю тебя</b>». После завершения регистрации, можете убрать.</div>\
    </div>';
    $('#reg_error').show().html(error);
    $('#reg_button .box_button_first').html('Завершить регистрацию <b>»</b>');
   } else if(response == 6) {
    userErrorMessage ('Ошибка соединения с сервером.');
    $('#reg_button .box_button_first').html('Зарегистрироваться <b>»</b>');
   }
  });
  }  else {
      userErrorMessage ('Пожалуйста, заполните все необходимые поля');
      $('#reg_button .box_button_first').html('Зарегистрироваться <b>»</b>');
  }
 }
}

function userErrorMessage (userMessage) {
    document.getElementById('error_head').style.display = 'block';
    $('#error_head').html(userMessage);
    setTimeout(function() {
        document.getElementById('error_head').style.display = 'none';
   }, 5000);
}

function checkCurr(d) {
    if(window.event) {
      if(event.keyCode == 37 || event.keyCode == 39) return;
    }
    d.value = d.value.replace(/\D/g,'');
}

var _black_bg = {
 _show: function() {
  $('body').append($('#black_bg').text() ? '' : '<div id="black_bg"> </div>');
  $('#black_bg').show();
 },
 _hide: function() {
  $('#black_bg').hide();
 }
}

var _box = {
 _show: function(obj) {
if (obj.textMessage != 0) { 
    var usertext ='\
     <div id="message">\
      '+obj.textMessage+'\
     </div>\
    ';
} else {
  var usertext = '';
}

  var template = '\
   <div id="box">\
    <div id="box_content">\
     <div id="title">\
      <div id="left">\
       '+obj.title+'\
      </div>\
      <div id="right">\
       <a href="javascript://" onclick="_box._close()">Закрыть</a>\
      </div>\
     </div>\
     '+ usertext +'\
     <div id="message">\
      '+obj.message+'\
     </div>\
     <div id="footer">\
      <span id="button_first"></span>\
      <span id="button_two"></span>\
     </div>\
    </div>\
   </div>\
  ';
  _black_bg._show();
  $('body').append($('#box').text() ? '' : '<div id="box"> </div>');
  $('#box').show().html(template);
  $('#box').css('width', obj.width);
  if(obj.footer == 2) $('#box #footer').hide();
  else $('#box #footer').show();
  $('#box #button_first').html(obj.first_button ? '<div onclick="'+obj.first_button_click+'" class="box_button_first_wrap"><div class="box_buttons box_button_first">'+obj.first_button+'</div></div>' : '');
  $('#box #button_two').html(obj.second_button ? '<div onclick="_box._close()" class="box_button_two_wrap"><div class="box_buttons box_button_two">'+obj.second_button+'</div></div>' : '');
  $('#box').css({position: 'fixed', top: ($(window).height() - $('#box_content').height())/2, left:($(window).width() - obj.width)/2});
 },
 _close: function() {
  _black_bg._hide();
  $('#box').hide().html(' ');
  $('#box #button_first').html(' ');
  $('#box #button_two').html(' ');
  $('#box #footer').show();
 }
}

setTimeout(function() {
 // добавление элементов
 $('body').append('\
  <div id="loading">\
   <div id="load"></div>\
  </div>\
  <div id="push"></div>\
  <div id="push_url"></div>\
 ');

 // авторизация по нажатию на Enter
 $('#login, #password').keydown(function(event) {
  var keyCode = event.which;
  if(keyCode == 13) _users._login();
 });
 // регистрация по нажатию на Enter
 $('#reg_vk, #reg_login, #reg_password').keydown(function(event) {
  var keyCode = event.which;
  if(keyCode == 13) _users._reg();
 });}, 100);

