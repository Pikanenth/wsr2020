<?php 

/*
    Plugin Name: Калькулятор услуг
    Plugin URI: http://rgpksmnv-m4.wsr.ru/
    Description: Цена формируется из следующих параметров в зависимости от выбора пользователя:
    Author: Павел Кузяев
    Version: 1.7.2
    Author URI: http://rgpksmnv-m4.wsr.ru/
*/

// Вызывание формы
function calculator_init() {
    ob_start(); // Включаем буферинг
    include("form.html"); // Грузим модуль
    $html = ob_get_contents(); // Получаем данные из модуля
    ob_end_clean(); // Закрываем буферинг
    return $html; // Вывод данных
};

// Создание шорт-кода
add_shortcode("calculator", "calculator_init");