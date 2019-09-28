<?php

/**
 * @var $errors array
 */
?>

<form action="/site/csv-processor/" method="post" enctype="multipart/form-data">
    Select CSV to upload:
    <p>Файл Групп: <input type="file" name="groups"></p>
    <p>Файл Продуктов: <input type="file" name="products"></p>
    <p><input type="submit" value="Upload CSV files" name="submit"></p>
</form>

