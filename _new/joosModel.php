<?php

class Model extends joosModel {

	public
	$id, $title, $body, $user_id, $created_at;

	// пример указания правил валидации, предположительно
	private function get_validate_rules() {
		return array(
			'id' => array(
				'req' => false,
				'type' => 'int',
				'lengh' => '1:11'
			),
			'title' => array(
				'req' => true,
				'type' => 'string',
				'lengh' => array(
					'data' => '5:200', // длина строки от 5 до 200 символов
					'message' => 'Заголовок должен быть дилной от 5 до 200 символов',
					'message_min' => 'Заголовко слишком длинный', // или
					'message_max' => 'Заголовко слишком короткий' // или
				)
			)
		);
	}

}

/*
 Новые плагины joosAutoadmin:
 - dropdown => array(  
	 'option'=>array( 1=>'Перво',2=>'Вторе' )
  ) 
  
 */