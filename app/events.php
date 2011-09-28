<?php

// запрет прямого доступа
defined( '_JOOS_CORE' ) or die();


// событие, повешенное на событие load для модели Blog
joosEvents::add_events( 'model.on_load.Blog' , function( $evens , $results , Blog $obj ) {

		// если блогозапись успешно загрузилась - обработаем все внешние ссылки через специальную функцию
		if ( $results ) {
			// обрабатываем все внешние ссылки
			$obj->fulltext = joosText::outlink_parse( $obj->fulltext );
			$obj->fulltext = str_replace( 'куц' , ' [замена] ' , $obj->fulltext );
		}
	} );

// еще одна задача, повешенная на то же событие агрузки блогозаписи
joosEvents::add_events( 'model.on_load.Blog' , function( $evens , $results , Blog $obj ) {

		// если блогозапись успешно загрузилась - обработаем все внешние ссылки через специальную функцию
		if ( $results ) {
			$obj->fulltext = str_replace( '[замена]' , ' =-) ' , $obj->fulltext );
		}
	} );