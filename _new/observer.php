<?php

// http://www.phpfreaks.com/forums/index.php?topic=298597.0
class Login {
	const STATE_BEFORE_LOGIN = 'beforeLogin';
	const STATE_AFTER_LOGIN = 'afterLogin';
	const STATE_FAILED_AUTH = 'failedAuth';

	private $_storage;

	public function __construct() {
		$this->_storage = new SplObjectStorage();
	}

	public function attach(LoginHook $observer) {
		$this->_storage->attach($observer);
	}

	public function detach(LoginHook $observer) {
		$this->_storage->detach($observer);
	}

	public function notify($state) {
		foreach ($this->_storage as $observer) {
			$observer->update($this, $state);
		}
	}

	public function authenticate($username, $password) {
		$this->notify(self::STATE_BEFORE_LOGIN);
		if ($username == 'test' && $password == 'hello') {
			$this->notify(self::STATE_AFTER_LOGIN);
		} else {
			$this->notify(self::STATE_FAILED_AUTH);
		}

		// possibly do other stuff here as well
	}

}

interface LoginHook  {

	public function update(Login $login, $state);
}

class PostLoginHook implements LoginHook {

	public function update(Login $login, $state) {
		if ($state !== Login::STATE_AFTER_LOGIN)
			return;

		echo 'Doing some magical stuff after login.' . PHP_EOL;
	}

}

class PreLoginHook implements LoginHook {

	public function update(Login $login, $state) {
		if ($state !== Login::STATE_BEFORE_LOGIN)
			return;

		echo 'Doing some magical stuff before login.' . PHP_EOL;
	}

}

class FailedLoginHook implements LoginHook {

	public function update(Login $login, $state) {
		if ($state !== Login::STATE_FAILED_AUTH)
			return;

		echo 'Doing some magical stuff when someone provides invalid credentials.' . PHP_EOL;
	}

}

class LoginErorrrrer implements LoginHook {

	public function update(Login $login, $state) {
		if ($state !== Login::STATE_FAILED_AUTH) {
			return;
		}
		echo 'ты чо бля, не та квсё!';
	}

}

$login = new Login();
echo '<pre>';
print_r($login);

// attach observers
$login->attach(new PreLoginHook());
$login->attach(new PostLoginHook());
$login->attach(new FailedLoginHook());
$login->attach( new LoginErorrrrer );

print_r($login);

// do stuff
$login->authenticate('hello', 'world');
//$login->authenticate('test', 'hello');