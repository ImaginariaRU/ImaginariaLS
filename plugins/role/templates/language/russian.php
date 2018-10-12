<?php

/* -------------------------------------------------------
 *
 *   LiveStreet (v1.0)
 *   Plugin Role (v.0.6)
 *   Copyright © 2011 Bishovec Nikolay
 *
 * --------------------------------------------------------
 *
 *   Plugin Page: http://netlanc.net
 *   Contact e-mail: netlanc@yandex.ru
 *
  ---------------------------------------------------------
 */

return array(
    'title' => 'Роли',
    'create_title' => 'Добавить роль',
    'user_role_title' => 'Роли пользователей',
    'avatar_title' => 'Выбор аватара',
    'title_people' => 'Редактирование пользователей',
    'title_admins' => 'Назначение администраторов',
    'create_name' => 'Название',
    'create_text' => 'Описание роли',
    'create_rate' => 'Рейтинг роли',
    'create_rate_note' => 'Рейтинг при изменении порога которого пользователь будет автоматичести прикреплен/откреплен у роли',
    'create_reg' => ' - возможность выбора при регистрации',
    'create_reg_note' => 'если отметить эту галку то роль можно будет выбрать при регистрации',

    'create_acl' => 'Права доступа',
    'create_user' => 'управление пользвателями',
    'create_user_banned' => 'управление баном <span style="color: red;">(блок бана в разработке)</span>',
    'create_user_add' => 'добавлять профили',
    'create_user_edit' => 'редактировать профили',
    'create_user_delete' => 'удалять профили',
    'create_blog' => 'управление блогами',
    'create_blog_add' => 'добавлять',
    'create_blog_edit' => 'редактировать',
    'create_blog_delete' => 'удалять',
    'create_blog_topic' => 'управление топиками в блогах',
    'create_blog_topic_add' => 'добавлять',
    'create_blog_topic_edit' => 'редактировать',
    'create_blog_topic_delete' => 'удалять',
    'create_blog_topic_comment' => 'управление коментариями в топиках',
    'create_blog_topic_comment_add' => 'добавлять',
    'create_blog_topic_comment_edit' => 'редактировать',
    'create_blog_topic_comment_delete' => 'удалять',
    'create_submit' => 'добавить',
    'create_submit_save' => 'сохранить',
    'list_title' => 'Список ролей',
    'add_user' => 'Добавить',
    'user_login' => 'Login пользователя',
    'edit_role' => 'изменить роль',

    'select_registration_role' => 'Выберите роль',
    'registration_empty' => 'Не выбрана роль',
    'registration_error' => 'Не найдена роль',
    'create_name_error' => 'Название роли должно быть от 2 до 200 символов',
    'create_acl_error' => 'Назначьте права для роли',
    'create_text_error' => 'Текст роли должен быть от 2 до 500 символов',

    'not_found' => 'Такой роли не существует',
    'user_role_exist' => 'Пользователю уже назначена роль',
    'user_role_add_ok' => 'Роль назначена',
    'list_empty' => 'Список ролей пока еще пуст',
    'delete_ok' => 'Роль удалена',
    'user_role_not_exist' => 'Такой связи не существует или она уже удалена',
    'user_role_del_ok' => 'Связь удалена',
    'delete' => '&#215;',

    'menu_users_title' => 'Права пользователей',
    'menu_roles_title' => 'Роли',
    'add_user_acl' => 'Добавить пользователя',

    'create_login' => 'Логин пользователя',
    'users_list_title' => 'Список пользователей',

    'users_title' => 'Люди',
    'user_add' => 'Добавить пользователя',
    'user_act' => ' активировать',
    'user_login' => 'Login',
    'user_mail' => 'Мыло',
    'user_pass' => 'Пароль',
    'user_submit_add' => 'Добавить',
    'user_form_hide' => 'Скрыть форму',
    'user_search_form_title' => 'Искать пользователя по логину',
    'user_view' => 'Найти',
    'user_list' => 'Список пользователей',
    'user_edit_title' => 'Редактировать',
    'user_act_title' => 'Активировать',
    'user_dellete_title' => 'Удалить',

    'users_not_id' => 'Записи с таким пользователем не существует либо она уже удалена',
    'user_delete_ok' => 'Права пользователя удалены',
    'save_ok' => 'Сохранено',
    'delete_admin_not_acceses' => 'Админов удалять нельзя, будет атата',
    'edit_admin_not_acceses' => 'Админов редактировать нельзя, будет атата',
    'users_list_role' => 'пользователи',
    'acl_list_role' => 'права роли',
    'edit_role_ok' => 'Настрйки сохранены',
    'acl_no_edit' => 'Прежде чем сохранть измените хотябы один параметр',
    'people_delete_ok' => 'Пользователь удален',

    'comment_edit' => 'Редактировать',
    'comment_save' => 'Сохранить',
    'comment_cancel' => 'Отменить',
    'comment_error_not_found' => 'Комент не найден или уже удален',
    'comment_error_can_edit' => 'Не хватает прав для редактирования коментариев',
    'comment_edit_ok' => 'Коментарий сохранен',
    'comment_info_edit_user' => 'Комментарий отредактирован <b>%%date%%</b> пользователем <b>%%login%%</b>',

    'menu_people_title' => 'Люди',
    'users_title' => 'Люди',
    'menu_admins_title' => 'Администраторы',
    'create_admin' => 'Добавить администратора',
    'create_login' => 'Login',
    'admin_list_title' => 'Список админов',
    'admin_list_empty' => 'Список админов eще пуст',
    'admin_create_error' => 'Этот пользователь уже является админом',
    'admin_not_accesses' => 'Этого админа запрещено лишать прав',
    'admin_users_not_admin' => 'Пользователь и так не является админом',

    'menu_avatar_title' => 'Аватар',
    'select_avatar' => 'Выбор аватара',
    'create_login' => 'Введите логин пользователя',
    'create_avatar_submit' => 'выбрать',
    'no_sense_to_choose' => 'Нету смысла выбирать себя',
    'avatar_info' => 'Вы залогинены под аватаром, Ваш настоящий профиль - ',
    'avatar_exit' => 'Покинуть аватар',

    'not_access_time_limit' => 'Истекло время для редактирование комментариев',
    'not_access_children_isset' => 'Запрещено редактировать, коментарии с ответами',

    'avatar_delete' => ' - удалить аватар',
    'create_avatar' => 'Аватар',

    'not_user' => 'Владельцы этой роли еще не назначены.',
    'creat_rating_use' => ' - использовать роль для автоматической смены пользователей',
    'create_block' => ' - выводить блок с пользователями в сайдбаре',
    'create_block_note' => 'Заполните URL страниц и позицию в блоке (через точку с запятой) на которых выводить блок, кажбый URL с новой строки. Например:<br/>
			    /blogs/%;100<br/>
			    /profile/%;150',


    /**
     * Block
     */
    'block_menu_title' => 'Меню ролей',

);
?>
