<?php

class moduleActionsTest extends moduleActions {


	public static function default_action() {
		return array('output' => '111');
	}

    public static function test_action() {
   		return array('output' => 'Hellooo');
   	}


}
