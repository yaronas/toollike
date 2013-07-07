function declOfNum(number, titles) {
 cases = [2, 0, 1, 1, 1, 2];
 return titles[ (number%100>4 && number%100<20)? 2 : cases[(number%10<5)?number%10:5] ];
}

var thisPageNum = 30;
var thisWork = false;
function getNextTask() {
  if (thisWork) {
    thisWork = false;
    $('#tasks_all_next').show().html('<div class="upload_inv"></div>');
    $.get("/task?method=jobs&userNumberFor="+thisPageNum, function (data) {
      if (data.length > 120) {
        $('#tasks_all_next').show().html('Показать ещё задания');
        $("#body_next_tasks").html($("#body_next_tasks").html()+" "+data);
        thisPageNum += 30;
        thisWork = true;
      } else {
        document.getElementById('tasks_all_next_wrap').style.display = 'none';
        $("#body_next_tasks").html($("#body_next_tasks").html()+"<div style='color: gray; font-size: 12px; padding: 20px; text-align: center;'>Не найдено заданий для выполнения..</div>");
      }
    });
  }
}

$(document).ready(function() {
  var scrH = $(window).height();
  var scrHP = $("#body_next_tasks").height();

$(window).scroll(function() {
    var scro = $(this).scrollTop();
    var scrHP = $("#body_next_tasks").height();
    var scrH2 = 0;
    scrH2 = scrH + scro;
    var leftH = scrHP - scrH2;
    if (leftH < -120) {
      leftTask = leftH;
      getNextTask();
    }
  });
});

var thisPageNumB = 10;
var thisHistori = false;
function getNextP() {
  if (thisHistori) {
    thisHistori = false;
    $('#tasks_all_next').show().html('<div class="upload_inv"></div>');
    $.get("/balanc?method=history&userNumberFor="+thisPageNumB, function (data) {
          $('#tasks_all_next').show().html('Показать ещё');
          $("#body_next_tasks").html($("#body_next_tasks").html()+" "+data);
      thisPageNumB += 10;
      thisHistori = true;
    });
  }
}

$(document).ready(function() {
  var scrH = $(window).height();
  var scrHP = $("#tasks_all_list").height();

$(window).scroll(function() {
    var scro = $(this).scrollTop();
    var scrHP = $("#tasks_all_list").height();
    var scrH2 = 0;
    scrH2 = scrH + scro;
    var leftH = scrHP - scrH2;

    if (leftH < 1) {
      getNextP();
    }
  });
});



function userErrorMessage (userMessage) {
    document.getElementById('error_head').style.display = 'block';
    $('#error_head').html(userMessage);
    setTimeout(function() {
        document.getElementById('error_head').style.display = 'none';
   }, 5000);
}

var tasks = {
  _add: function(type, url, count, price, num, comment) {
  if ((type) && (url) && (count) && (price)) {
    $('#task_add').find('.tab_add'+num).find('#button_type'+num).hide();
    $('#task_add').find('.tab_add'+num).find('#button_type_load'+num).show().html('<div class="upload_inv"></div>');
    $.get('/add?url='+url+'&type='+type+'&count='+count+'&price='+price+'&comment='+comment, function(data) {
    var response = data.match(/\d+/);
    $('#task_add').find('.tab_add'+num).find('#button_type'+num).show();
    $('#task_add').find('.tab_add'+num).find('#button_type_load'+num).hide();
   if(response == 4) {
    location.href = '/';
   } else if(response == 3) {
    userErrorMessage ('Вы уже выполняли это задание.');
   } else if(response == 7) {
    userErrorMessage ('Не удалось соединиться с сервером ВК. Попробуйте позже.');
   } else if(response == 8) {
    userErrorMessage ('Проверьте правильность введенной Вами ссылки.');
   } else if(response == 6) {
    userErrorMessage ('Не удалось соединиться с сервером. Попробуйте позже.');
   } else if(response == 9) {
    mini_wnd._show({title: 'Ошибка', text: 'Данное задание уже имеется в базе сервиса.', style: 'black'});
   } else if(response == 2) {
    mini_wnd._show({title: 'Ошибка', text: 'На Вашем счету недостаточно Like для размещения задания.', style: 'black'});
   } else if(response == 1) {
    mini_wnd._show({title: 'Задание добавлено', text: 'С Вашего счёта списано '+ price * count+' ♥ за добавление задания.', style: 'black'});
    $('#task'+id).fadeOut(300);
   } else {
    userErrorMessage ('Неизвестная ошибка. Попробуйте позже.');
   }
  });
  } else {
    userErrorMessage ('Пожалуйста, заполните все необходимые поля');
  }

},


  _check: function(id, sum) {
  $('#error_task'+id).html('');
  $('#task'+id).addClass('loading');
  $('#check'+id).html('<div style="margin-left: -2px;"><div class="upload_inv"></div></div>');
  $.get('/task?method=check&id='+id, function(data) {
     var response = data.match(/\d+/);
   $('#task'+id).removeClass('loading');
   $('#check'+id).html('<b>Выполнить задание</b>');
   if(response == 4) {
    location.href = '/';
   } else if(response == 3) {
    userErrorMessage ('Вы уже выполняли это задание.');
   } else if(response == 7) {
    userErrorMessage ('Не удалось соединиться с сервером ВК. Попробуйте позже.');
   } else if(response == 6) {
    userErrorMessage ('Не удалось соединиться с сервером. Попробуйте позже.');
   } else if(response == 9) {
    mini_wnd._show({title: 'Ошибка', text: 'Это задание достигло определенного количества участников.', style: 'black'});
    $('#task'+id).fadeOut(300);
   } else if(response == 2) {
    mini_wnd._show({title: 'Ошибка', text: 'Вы не выполнили задание.', style: 'black'});
   } else if(response == 1) {
    mini_wnd._show({title: 'Задание выполнено', text: 'На Ваш счёт зачислено '+sum+' ♥ за выполнное задание.', style: 'black'});
    $('#task'+id).fadeOut(300);
   } else {
    userErrorMessage ('Неизвестная ошибка. Попробуйте позже.');
   }
  });
 },

 _ignored: function(id) {
  $('#task'+id).addClass('loading');
  $('#ignored'+id).html('<div style="margin-left: -2px;"><div class="upload_inv"></div></div>');
  $.get('/task?method=ignored&id=' + id, function(data) {
   var response = data.match(/\d+/);
   $('#task'+id).removeClass('loading');
   $('#ignored'+id).html('<b>Не показывать</b>');
   if(response == '3') {
    userErrorMessage ('Это задание уже проигнорировано.');
   } else if(response == '6') {
    userErrorMessage ('Не удалось соединиться с сервером. Попробуйте позже.');
   } else if(response == '1') {
    mini_wnd._show({title: 'Задание скрыто', text: 'Это задание больше не будет показываться Вам.', style: 'black'});
    $('#task'+id).hide();
   } else {
    userErrorMessage ('Неизвестная ошибка. Попробуйте позже.');
   }
  });
 },

  _spam: function(id) {
  $('#task'+id).addClass('loading');
  $('#spam'+id).html('<div class="upload_inv"></div>');
  $.get('/task?method=spam&id=' + id, function(data) {
   var response = data.match(/\d+/);
   $('#task'+id).removeClass('loading');
   $('#spam'+id).html('<b>сюда</b>');
   if(response == '3') {
    userErrorMessage ('Вы уже жаловались на это задание.');
   } else if(response == '6') {
    userErrorMessage ('Не удалось соединиться с сервером. Попробуйте позже.');
   } else if(response == '1') {
    mini_wnd._show({title: 'Задание скрыто', text: 'Задание отправлено на проверку администрации.', style: 'black'});
    $('#task'+id).hide();
   } else {
    userErrorMessage ('Неизвестная ошибка. Попробуйте позже.');
   }
  });
 },

  _del: function(id) {
  _box._show({width: 500, title: 'Удаление задания', message: 'Вы действительно хотите удалить задание? Это действие нельзя будет отменить. <br /> <br /> Оставшиеся монеты будут <b>возвращены</b> Вам на счёт.', first_button: 'Удалить', first_button_click: '_box._close(); tasks._del_post('+id+')', second_button: 'Отмена'});
 },
 _del_post: function(id) {
  $('#task'+id).addClass('loading');
  $('#checkDel'+id).html('<div style="margin-left: -2px;"><div class="upload_inv"></div></div>');
  $.get('/task?method=del&id=' + id, function(data) {
   $('#task'+id).removeClass('loading');
   $('#checkDel'+id).html('<b>Удалить задание</b>');
   var response = data.match(/\d+/);
   if(response == '3') {
    userErrorMessage ('Неизвестная ошибка. Попробуйте позже.');
   } else if(response == '6') {
    mini_wnd._show({title: 'Ошибка', text: 'Не удалось соединиться с сервером. Попробуйте позже.', style: 'black'});
   } else if(response == '1') {
    $('#task'+id).hide();
    mini_wnd._show({title: 'Задание удалено', text: 'Задание было успешно удалено', style: 'black'});
   } else {
    userErrorMessage ('Неизвестная ошибка. Попробуйте позже.');
   }
  });
 }

}


var task_check = {
 _like: function(id, sum) {
  run_task = window.open('http://toollike.ru/task?method=go&id='+id, 'run_task', 'width=860, height=500, top='+((screen.height-500)/2)+',left='+((screen.width-860)/2)+', resizable=yes, scrollbars=no, status=yes');
  var run_task_int = setInterval(function() {
   if(run_task.closed) {
    clearInterval(run_task_int);
    tasks._check(id, sum);
   }
  }, 50);
},

 _comment: function(id) {
  run_task = window.open('http://toollike.ru/task?method=go&id='+id, 'run_task', 'width=860, height=500, top='+((screen.height-500)/2)+',left='+((screen.width-860)/2)+', resizable=yes, scrollbars=no, status=yes');
 },

 _new_comment: function(id, text) {
  _box._show({width: 700, title: 'Оставить комментарий', message: '<div id="box_comment">Перейдите по ссылке <a href="javascript://" onclick="task_check._comment('+id+')"><b>сюда</b></a> и оставьте следующий комментарий: <div id="comment">'+text+'</div></div>', first_button: 'Готово', first_button_click: '_box._close();tasks._check('+id+',$(\'#comment_hide'+id+'\').text())', second_button: 'Отмена'});
 }
 }

 jQuery(function () {
    jQuery(window).scroll(function () {
        if (jQuery(this).scrollTop() > 400)
            jQuery('a#move_up').fadeIn(600);
        else
            jQuery('a#move_up').fadeOut(600);
    });
    jQuery('a#move_up').click(function () {
        jQuery('body,html').animate({
            scrollTop: 0
        }, 0);
        return false;
    });
});


 var mini_num = 0;
var mini_wnd = {
 _show: function(obj) {
  var mini_wnd_id = mini_num++;
  $('body').append($('.mini_wnd').html() ? '' : '<div class="mini_wnd"></div>');
  var template = '<div style="display: none;" id="mini_wnd_id'+mini_wnd_id+'" class="main '+obj.style+'">\
    <div class="title">'+obj.title+'</div>\
    <div class="text">\
     '+obj.text+'</b>\
    </div>\
   </div>';
   $('.mini_wnd').prepend(template);
   $('#mini_wnd_id'+mini_wnd_id).fadeIn(500);
   $('#mini_wnd_id'+mini_wnd_id).click(function() {
    mini_wnd._close(mini_wnd_id);
   });
   setTimeout(function() {
    $('#mini_wnd_id'+mini_wnd_id).fadeOut(300);
   }, 3500);
 },
 _close: function(id) {
  $('#mini_wnd_id'+id).fadeOut(300);
 }
};


setTimeout(function() {
 // добавление элементов
 $('body').append('\
  <div id="loading">\
   <div id="load"></div>\
  </div>\
  <div id="push"></div>\
  <div id="push_url"></div>\
 ');
 // вкладки при добавлении нового задания
 $('.task_add a').click(function() {
  var id = $(this).attr('id');
  $('#task_error_table').hide();
  $('.task_add a').removeClass('active');
  $(this).addClass('active');
  $('div[class^="tab_add"]').hide();
  $('.'+id).show();
 });
 // считаем цену при добавлении задания
 $('#task_add .count_likes, #task_add .price_likes').keyup(function() {
  var count_page = $('.count_likes').val() * 1;
  var price_page = $('.price_likes').val() * 1;
  var result_points = count_page * price_page;
  $('#auto_coins_likes').text(result_points+' ♥');
 });
 $('#task_add .count_reposts, #task_add .price_reposts').keyup(function() {
  var count_page = $('.count_reposts').val() * 1;
  var price_page = $('.price_reposts').val() * 1;
  var result_points = count_page * price_page;
  $('#auto_coins_reposts').text(result_points+' ♥');
 });
  $('#task_add .count_comments, #task_add .price_comments').keyup(function() {
  var count_page = $('.count_comments').val() * 1;
  var price_page = $('.price_comments').val() * 1;
  var result_points = count_page * price_page;
  $('#auto_coins_comments').text(result_points+' ♥');
 });
}, 1000);

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