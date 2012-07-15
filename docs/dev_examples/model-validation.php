<?php

/**
 * Пример валидации моделей
 */
class modelPost extends joosModel
{
    public $id;
    public $title;
    public $state;
    public $created_at;

    public function __construct()
    {
    }

    protected function get_validate_rules()
    {
        return array(
            array('title', 'required', 'message' => 'Заголовок надо!'),
            array('title', 'string:5..15', 'message' => 'Длина должна быть от :min до :max символов'),
            array('created_at', 'null', 'on' => 'update', 'message' => 'При измении записи оригинальную дату создания нельзя изменять!'), /* при измении записи created_at уже есть в базе и в моделе оно должно быть NULL */
        );
    }

}

$post = new modelPost;
$post->title = 'человеков!';
if ($post->validate()) {
    echo 'Всё круто!';
} else {
    echo 'Введённые данные формы невалидны';
    print_r($post->get_validation_error_messages());
}
