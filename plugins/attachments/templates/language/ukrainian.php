<?php
//
//  Attachments plugin
//  (P) Rafrica.net Studio, 2010 - 2012
//  http://we.rafrica.net/
//

return array(
    // попап окна (алерты)
    'new_topic_error_not_allowed_file_types' => 'Формат файлу невірний. Спробуйте наступні формати: ',
    'do_you_really_want_to_delete_this_file' => 'Видалити файл?',

    // инфо-подсказки
    'new_topic_add_file_msg' => '<span>Долучити файл:</span>',
    'new_topic_first_message' => 'Оберіть файл',
    'new_topic_uploading_file_now' => 'Файл вантажиться...',
    'new_topic_file_upload_done' => 'Файл додано',
    'element_title_download_file' => 'Скачати файл',
    'element_title_delete_file' => 'Видалити файл',
    'file_was_deleted' => 'Файл видалено',
    'not_attached_files' => 'Неприєднані файлы:',
    'not_attached_files_title' => 'Файлы, які були завантажені, але топік не опубліковано і не збережено',
    'attach_file_to_new_topic' => 'Долучити файли до цього топіка',
    'cant_attach_file_to_saved_topic' => 'Долучити файли можна тільки до нового топіка',
    'there_is_no_unattached_files' => 'Немає долучених файлів.',

    // вывод списка топиков
    'topic_files_attached' => 'Долучено ',
    'topic_N_files' => ' файлів.',
    'files_in_topics' => 'Файли в топіку: ',

    // общие ошибки (клиентская часть)
    'sidebar_detection_general_error' => 'Ошибка в вёрстке шаблона для сайдбара плагина Attachments. Сохраняйте класы обьектов для корректной работы',
    'wrong_parameter_file_form_place' => 'Неверный параметр положения формы для загрузки файлов в конфиге для плагина Attachments',

    // ошибки обработки серверной части
    'upload_not_logged_in' => 'Увійдіть в систему',
    'upload_rating_to_low' => 'у вас рейтингу малувато для завантаження файлів',
    'upload_file_to_big' => 'Завеликий файл',
    'upload_universal_answer' => 'Go away!',
    'upload_error_creating_subfolder' => 'У нас проблеми зі створенням папки файлів користувача',
    'upload_error_moving_to_subfolder' => 'Помилка переміщення файла',
    'upload_error_topic_if_full' => 'Забагато файлів для одного топіку',
    'upload_unattached_limit' => 'У вас забагато недолучених вайлів. Видаліть файли зі списку справа, або долучить їх до цього топіку',

    'upload_err_ini_size' => 'The uploaded file exceeds the upload_max_filesize directive in php.ini',
    'upload_err_form_size' => 'The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form',
    'upload_err_partial' => 'The uploaded file was only partially uploaded',
    'upload_err_no_file' => 'No file was uploaded',
    'upload_err_no_tmp_dir' => 'Missing a temporary folder',
    'upload_err_cant_write' => 'Failed to write file to disk',
    'upload_err_extension' => 'File upload stopped by extension',
    'upload_err_unknown' => 'Unknown upload error',
);
