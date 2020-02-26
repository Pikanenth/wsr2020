<?php 

/*
    Plugin Name: Обновление обьявлений без перезагрузки страницы
    Plugin URI: http://rgpksmnv-m4.wsr.ru/
    Description: Обновление обьявлений без перезагрузки страницы
    Author: Павел Кузяев
    Version: 1.7.2
    Author URI: http://rgpksmnv-m4.wsr.ru/
*/

// Вызываем функцию
function updatenewsnoupdate() {
    print("
        <script>
            const updatenewsnoupdate = () => {
                jQuery('.navigation.pagination a[href]').click(function(e) {
                    e.preventDefault();
                    jQuery.get(jQuery(this).attr('href'), (data) => {
                        jQuery('#content').html(jQuery(data).find('#content')[0].innerHTML);
                        updatenewsnoupdate();
                    });
                });
            }
            updatenewsnoupdate(); 
        </script>
    "); // Вызываем функцию, после загрузки страницы, получаем пагинацию, берем html контент внутри обьявлений и заменяем его.
};

// Подключаем в конце сайта.
add_action("wp_footer", "updatenewsnoupdate");