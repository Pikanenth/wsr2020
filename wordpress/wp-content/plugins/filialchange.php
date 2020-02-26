<?php 

/*
    Plugin Name: Смена филиала
    Plugin URI: http://rgpksmnv-m4.wsr.ru/
    Description: Модуль смены филиала в шапке
    Author: Павел Кузяев
    Version: 1.7.2
    Author URI: http://rgpksmnv-m4.wsr.ru/
*/


// Вызываем функцию
function filial_change() {
    // Информация филиалов
    return "
        <label style='font-weight: 200;'>Выбор филиала</label>
        <select id='validate_filial' style='display: block;width: 100%;padding: 10px;'>
            <option value='Ленина'>На Ленина</option>
            <option value='Советская'>На Советской</option>
            <option value='Гоголя'>На Гоголя</option>
            <option value='Куйбышева'>На Куйбышева</option>
        </select>
        <script>
            var filials = {
                'Ленина': `Филиал находится по адресу <b>ул.  Ленина, д. 1</b>, номер телефона <b>+7 (912) 812-00-01</b>`,
                'Советская': `Филиал находится по адресу <b>ул.  Советская, д. 2</b>, номер телефона <b>+7 (912) 812-00-02</b>`,
                'Гоголя': `Филиал находится по адресу <b>ул.  Гоголя, д. 3</b>, номер телефона <b>+7 (912) 812-00-03</b>`,
                'Куйбышева': `Филиал находится по адресу <b>ул.  Куйбышева, д. 4</b>, номер телефона <b>+7 (912) 812-00-04</b>`,
            };
            jQuery('#validate_filial').change(() => jQuery('.site-description').html(filials[jQuery('#validate_filial').val()]));
            jQuery('.site-description').html(filials[jQuery('#validate_filial').val()]);
        </script>
    ";
};

// Добавляем шорт код
add_shortcode("filial", "filial_change");