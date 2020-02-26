<label>Заявки</label>
<?php 
    // Получаем заявки из базы данных
    $data = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}probteach ORDER BY id DESC");
    foreach($data as $item): // Перебираем информацию
?>
<!-- Показываем блок с заявкой -->
<div class="block" style="background: white;box-shadow: 2px 2px 4px black;margin: 10px;padding: 15px;border-radius: 4px;">
    <!-- Информация о заявке -->
    <label>К вам записался <b><?=$item->fio ?></b></label>
    <p>Возраст: <b><?=$item->yo ?></b></p>
    <p>Email: <b><?=$item->email ?></b></p>
    <p>Телефон: <b><?=$item->tel ?></b></p>
    <p>Категория: <b><?=$item->course ?></b></p>
    <!-- Удаление заявки -->
    <form method="post">
        <input type="hidden" name="action" value="delete" />
        <input type="hidden" name="id" value="<?=$item->id ?>" />
        <button type="submit">Удалить заявку</button>
    </form>
</div>
<?php endforeach ?>