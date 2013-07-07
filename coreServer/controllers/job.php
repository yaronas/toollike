<?php

if ($userNumberLeftGobs = ADD::userCheckJob($this->userBaseInfo)) {
    echo '<div style="color: gray; font-size: 12px; padding: 20px; text-align: center;">Для работы с сервисом, вам потребуется выполнить <b>'.$userNumberLeftGobs.'</b> заданий</div>';
} else {

?>

<div class="task_add" id="tab_content">
    <a id="tab_add1"  class="active" href="javascript://">Мне нравится</a>
    <a id="tab_add2" href="javascript://">Рассказать друзьям</a>
    <a id="tab_add3" href="javascript://">Комментарии</a>
</div>
<div id="tab_content_hr"> </div>
<div id="task_add_error"> </div>
<div id="task_add">
    <div class="tab_add1">
        <div class="legend">
            <div class="label">Ссылка:</div>
            <div class="field"><input placeholder="Запись, фото или видео.." type="text" class="url url_likes"></div>
        </div>
        <div class="legend">
            <div class="label">Кол-во лайков:</div>
            <div class="field"><input type="text" class="count count_likes"></div>
        </div>
        <div class="legend">
            <div class="label">Цена за выполнение:</div>
            <div class="field"><input type="text" style="background: #f8f8f8" value="1" class="price price_likes"><div class="check_top1"></div></div>
        </div>
        <div onclick="tasks._add('like', $('.url_likes').val(), $('.count_likes').val(), $('.price_likes').val(), 1, 0)" class="box_button_first_wrap"><div class="box_buttons box_button_first"><span id="button_type1">Создать задание — <b id="auto_coins_likes">0 ♥</b></span><span id="button_type_load1"></span></div></div>
    </div>
 <div class="tab_add2" style="display: none;">
         <div class="legend">
          <div class="label">Ссылка:</div>
          <div class="field"><input placeholder="Запись, фото или видео.." type="text" class="url url_reposts"></div>
         </div>
         <div class="legend">          
          <div class="label">Кол-во репостов:</div>
          <div class="field"><input type="text" class="count count_reposts"></div>
         </div>
         <div class="legend">
          <div class="label">Цена за выполнение:</div>
                    <div class="field"><input type="text" style="background: #f8f8f8" value="1" class="price price_reposts"><div class="check_top2"></div></div>
                   </div>
         <div onclick="tasks._add('repost', $('.url_reposts').val(), $('.count_reposts').val(), $('.price_reposts').val(), 2, 0)" class="box_button_first_wrap"><div class="box_buttons box_button_first"><span id="button_type2">Создать задание — <b id="auto_coins_reposts">0 ♥</b></span><span id="button_type_load2"></span></div></div>
        </div>
         <div class="tab_add3" style="display: none;">
         <div class="legend">
          <div class="label">Ссылка:</div>
          <div class="field"><input placeholder="Например: vk.com/durov?w=wall1_1" type="text" class="url url_comments"></div>
         </div>
         <div class="legend">
          <div class="label">Комментарий:</div>
          <div class="field">
           <input type="text" class="comment">
          </div>
         </div>
         <div class="legend">
          <div class="label">Кол-во комментариев:</div>
          <div class="field"><input type="text" class="count count_comments"></div>
         </div>
         <div class="legend">
          <div class="label">Цена за выполнение:</div>
                    <div class="field"><input type="text" style="background: #f8f8f8" value="1" class="price price_comments"><div class="check_top3"></div></div>
                   </div>
         <div onclick="tasks._add('comment', $('.url_comments').val(), $('.count_comments').val(), $('.price_comments').val(), 3, $('.comment').val())" class="box_button_first_wrap"><div class="box_buttons box_button_first"><span id="button_type3">Создать задание — <b id="auto_coins_comments">0 ♥</b></span><span id="button_type_load3"></span></div></div>
        </div>
        </div>
        <center><font color="red"><b>Внимание!</b></font> Будьте бдительны при добавление задания проверяйте, что в пункте приватности у вас <b>открыта стена или группа</b>..</center>
        
<?php

}