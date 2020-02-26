<?php 

/*
    Plugin Name: Модуль записи на пробное занятие
    Plugin URI: http://rgpksmnv-m4.wsr.ru/
    Description: Параметры формы: ФИО, Возраст, e-mail, Телефон, Категория.
    Author: Павел Кузяев
    Version: 1.7.2
    Author URI: http://rgpksmnv-m4.wsr.ru/
*/

// Генерация формы
function probteach_form_generator() {
    global $wpdb; // Подключение к базе данных.
    if (isset($_POST['action']) && $_POST['action'] == 'add-review') {
        // Отправка данных через метод пост у шорткода
        $fio = $_POST['fio'];
        $yo = $_POST['yo'];
        $email = $_POST['email'];
        $tel = $_POST['tel'];
        $course = $_POST['course'];
        // Отправляем запрос в базу данных
        $t = $wpdb->get_results("INSERT INTO {$wpdb->prefix}probteach (fio, yo, email, tel, course) VALUES ('$fio', '$yo', '$email', '$tel', '$course');");
        // Генерируем успешное сообщение
        $html = "<label style='color: green;text-align: center;'>Вы успешно оставили заявку.</label>";
        return $html; // Вывод успешной заявки
	} else {
        ob_start(); // Включаем буферинг
        include("form.html"); // Грузим модуль
        $html = ob_get_contents(); // Получаем данные из модуля
        ob_end_clean(); // Закрываем буферинг
        return $html; // Вывод формы пользователю.
    }
}

// Создания шорт-кода для генерации меню
add_shortcode("probteach", "probteach_form_generator");

// Админ-панель
add_action('admin_menu', 'admin_generate_menu'); 
// Вызываем генерацию меню
function admin_generate_menu() {
    // Добавляем в меню оставление заявок
	add_menu_page('Добро пожаловать', 'Управление заявками', 'manage_options', 'show-reviews', 'admin_show_reviews');
}

// Показать последние заявки.
function admin_show_reviews() {
    // Подключаемся к базе данных
    global $wpdb;
    // Удаление заявки.
    if (isset($_POST['action']) && $_POST['action'] == 'delete') $wpdb->get_results("DELETE FROM {$wpdb->prefix}probteach WHERE id = ".$_POST['id']);
    // Меню показа заявок
    include "probteach_admin.php"; 
}
