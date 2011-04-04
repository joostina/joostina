<?php

/**
 * Склонение русских имён и фамилий
 *
 * var rn = new RussianName('Паниковский Михаил Самуэльевич');
 * rn.fullName(rn.gcaseRod); // Паниковского Михаила Самуэльевича
 *
 * Список констант по падежам см. ниже в коде.
 *
 * Пожалуйста, присылайте свои уточнения мне на почту. Спасибо.
 *
 * @version  0.1.3
 * @author   Johnny Woo <agalkin@agalkin.ru>
 */
class lastName {

	var $exceptions = array(
		"	дюма,тома,дега,люка,ферма,гамарра,петипа . . . . .",
		'	гусь,ремень,камень,онук,богода,нечипас,долгопалец,маненок,рева,кива . . . . .',
		'	вий,сой,цой,хой -я -ю -я -ем -е'
	);
	var $suffixes = array(
		'f	б,в,г,д,ж,з,й,к,л,м,н,п,р,с,т,ф,х,ц,ч,ш,щ,ъ,ь . . . . .',
		'f	ска,цка  -ой -ой -ую -ой -ой',
		'f	ая       --ой --ой --ую --ой --ой',
		'	ская     --ой --ой --ую --ой --ой',
		'f	на       -ой -ой -у -ой -ой',
		'	иной -я -ю -я -ем -е',
		'	уй   -я -ю -я -ем -е',
		'	ца   -ы -е -у -ей -е',
		'	рих  а у а ом е',
		'	ия                      . . . . .',
		'	иа,аа,оа,уа,ыа,еа,юа,эа . . . . .',
		'	их,ых                   . . . . .',
		'	о,е,э,и,ы,у,ю           . . . . .',
		'	ова,ева            -ой -ой -у -ой -ой',
		'	га,ка,ха,ча,ща,жа  -и -е -у -ой -е',
		'	ца  -и -е -у -ей -е',
		'	а   -ы -е -у -ой -е',
		'	ь   -я -ю -я -ем -е',
		'	ия  -и -и -ю -ей -и',
		'	я   -и -е -ю -ей -е',
		'	ей  -я -ю -я -ем -е',
		'	ян,ан,йн   а у а ом е',
		'	ынец,обец  --ца --цу --ца --цем --це',
		'	онец,овец  --ца --цу --ца --цом --це',
		'	ц,ч,ш,щ   а у а ем е',
		'	ай  -я -ю -я -ем -е',
		'	ой  -го -му -го --им -м',
		'	ах,ив   а у а ом е',
		'	ший,щий,жий,ний  --его --ему --его -м --ем',
		'	кий,ый   --ого --ому --ого -м --ом',
		'	ий       -я -ю -я -ем -и',
		'	ок  --ка --ку --ка --ком --ке',
		'	ец  --ца --цу --ца --цом --це',
		'	в,н   а у а ым е',
		'	б,г,д,ж,з,к,л,м,п,р,с,т,ф,х   а у а ом е'
	);

}

class firstName {

	var $exceptions = array(
		'	лев    --ьва --ьву --ьва --ьвом --ьве',
		'	павел  --ла  --лу  --ла  --лом  --ле',
		'm	шота   . . . . .',
		'f	рашель,нинель,николь,габриэль,даниэль   . . . . .'
	);
	var $suffixes = array(
		'	е,ё,и,о,у,ы,э,ю   . . . . .',
		'f	б,в,г,д,ж,з,й,к,л,м,н,п,р,с,т,ф,х,ц,ч,ш,щ,ъ   . . . . .',
		'f	ь   -и -и . ю -и',
		'm	ь   -я -ю -я -ем -е',
		'	га,ка,ха,ча,ща,жа  -и -е -у -ой -е',
		'	а   -ы -е -у -ой -е',
		'	ия  -и -и -ю -ей -и',
		'	я   -и -е -ю -ей -е',
		'	ей  -я -ю -я -ем -е',
		'	ий  -я -ю -я -ем -и',
		'	й   -я -ю -я -ем -е',
		'	б,в,г,д,ж,з,к,л,м,н,п,р,с,т,ф,х,ц,ч	 а у а ом е'
	);

}

class middleName {

	var $exceptions = array();
	var $suffixes = array(
		'	ич   а  у  а  ем  е',
		'	на  -ы -е -у -ой -е'
	);

}

class Rules {

	var $lastName, $firstName, $middleName;

	function Rules() {
		$this->lastName = new lastName();
		$this->firstName = new firstName();
		$this->middleName = new middleName();
	}

}

class RussianNameProcessor {

	var $sexM = 'm';
	var $sexF = 'f';
	var $gcaseIm = 'nominative';
	var $gcaseNom = 'nominative';	  // именительный
	var $gcaseRod = 'genitive';
	var $gcaseGen = 'genitive';		// родительный
	var $gcaseDat = 'dative';									   // дательный
	var $gcaseVin = 'accusative';
	var $gcaseAcc = 'accusative';	  // винительный
	var $gcaseTvor = 'instrumentative';
	var $gcaseIns = 'instrumentative'; // творительный
	var $gcasePred = 'prepositional';
	var $gcasePos = 'prepositional';   // предложный
	var $fullNameSurnameLast = false;
	var $ln = '', $fn = '', $mn = '', $sex = '';
	var $rules;
	var $initialized = false;

	function init() {
		if ($this->initialized) {
			return;
		}
		$this->rules = new rules();
		$this->prepareRules();
		$this->initialized = true;
	}

	function RussianNameProcessor($lastName, $firstName = NULL, $middleName = NULL, $sex = NULL) {
		$this->init();
		if (!isset($firstName)) {
			preg_match("/^\s*(\S+)(\s+(\S+)(\s+(\S+))?)?\s*$/u", $lastName, $m);
			if (!$m)
				exit("Cannot parse supplied name");
			if ($m[5] && preg_match("/(ич|на)$/u", $m[3]) && !preg_match("/(ич|на)$/u", $m[5])) {
				// Иван Петрович Сидоров
				$lastName = $m[5];
				$firstName = $m[1];
				$middleName = $m[3];
				$this->fullNameSurnameLast = true;
			} else {
				// Сидоров Иван Петрович
				$lastName = $m[1];
				$firstName = $m[3];
				$middleName = $m[5];
			}
		}
		$this->ln = $lastName;
		if (isset($firstName))
			$this->fn = $firstName;
		else
			$this->fn = '';
		if (isset($middleName))
			$this->mn = $middleName;
		else
			$this->mn = '';
		if (isset($sex))
			$this->sex = $sex;
		else
			$this->sex = $this->getSex();

		return;
	}

	function prepareRules() {
		foreach (array("lastName", "firstName", "middleName") as $type) {
			foreach (array("suffixes", "exceptions") as $key) {
				$n = count($this->rules->$type->$key);
				for ($i = 0; $i < $n; $i++) {
					$this->rules->$type->{$key}[$i] = $this->rule($this->rules->$type->{$key}[$i]);
				}
			}
		}
	}

	function rule($rule) {
		preg_match("/^\s*([fm]?)\s*(\S+)\s+(\S+)\s+(\S+)\s+(\S+)\s+(\S+)\s+(\S+)\s*$/u", $rule, $m);
		if ($m)
			return array(
				"sex" => $m[1],
				"test" => explode(',', $m[2]),
				"mods" => array($m[3], $m[4], $m[5], $m[6], $m[7])
			);
		return false;
	}

	// склоняем слово по указанному набору правил и исключений
	function word($word, $sex, $wordType, $gcase) {
		// исходное слово находится в именительном падеже
		if ($gcase == $this->gcaseNom)
			return $word;

		// составные слова
		if (preg_match("/[-]/u", $word)) {
			$list = $word->split('-');
			$n = count($list);
			for ($i = 0; $i < $n; $i++) {
				$list[$i] = $this->word($list[$i], $sex, $wordType, $gcase);
			}
			return join('-', $list);
		}

		// Иванов И. И.
		if (preg_match("/^[А-ЯЁ]\.?$/iu", $word))
			return $word;
		$this->init();
		$rules = $this->rules->$wordType;

		if ($rules->exceptions) {
			$pick = $this->pick($word, $sex, $gcase, $rules->exceptions, true);
			if ($pick)
				return $pick;
		}
		$pick = $this->pick($word, $sex, $gcase, $rules->suffixes, false);
		if ($pick)
			return $pick;
		else
			return $word;
	}

	// выбираем из списка правил первое подходящее и применяем
	function pick($word, $sex, $gcase, $rules, $matchWholeWord) {
		$wordLower = Jstring::strtolower($word);
		$n = count($rules);
		for ($i = 0; $i < $n; $i++) {
			if ($this->ruleMatch($wordLower, $sex, $rules[$i], $matchWholeWord)) {
				return $this->applyMod($word, $gcase, $rules[$i]);
			}
		}
		return false;
	}

	// проверяем, подходит ли правило к слову
	function ruleMatch($word, $sex, $rule, $matchWholeWord) {
		if ($rule["sex"] == $this->sexM && $sex == $this->sexF)
			return false; // male by default
 if ($rule["sex"] == $this->sexF && $sex != $this->sexF)
			return false;
		$n = count($rule["test"]);
		for ($i = 0; $i < $n; $i++) {
			$test = $matchWholeWord ? $word : Jstring::substr($word, max(Jstring::strlen($word) - Jstring::strlen($rule["test"][$i]), 0));
			if ($test == $rule["test"][$i])
				return true;
		}
		return false;
	}

	// склоняем слово (правим окончание)
	function applyMod($word, $gcase, $rule) {
		switch ($gcase) {
			case $this->gcaseNom: $mod = '.';
				break;
			case $this->gcaseGen: $mod = $rule["mods"][0];
				break;
			case $this->gcaseDat: $mod = $rule["mods"][1];
				break;
			case $this->gcaseAcc: $mod = $rule["mods"][2];
				break;
			case $this->gcaseIns: $mod = $rule["mods"][3];
				break;
			case $this->gcasePos: $mod = $rule["mods"][4];
				break;
			default: exit("Unknown grammatic case: " + gcase);
		}
		$n = Jstring::strlen($mod);
		for ($i = 0; $i < $n; $i++) {
			$c = Jstring::substr($mod, $i, 1);
			switch ($c) {
				case '.': break;
				case '-': $word = Jstring::substr($word, 0, Jstring::strlen($word) - 1);
					break;
				default: $word .= $c;
			}
		}
		return $word;
	}

	function getSex() {
		if (Jstring::strlen($this->mn) > 2) {
			switch (Jstring::substr($this->mn, -2)) {
				case 'ич': return $this->sexM;
				case 'на': return $this->sexF;
			}
		}
		return '';
	}

	function fullName($gcase) {
		$tmpstr = ($this->fullNameSurnameLast ? '' : $this->lastName($gcase) . ' ')
				. $this->firstName($gcase) . ' ' . $this->middleName($gcase)
				. ($this->fullNameSurnameLast ? ' ' . $this->lastName($gcase) : '');
		return preg_replace("/^ +| +$/u", '', $tmpstr);
	}

	function lastName($gcase) {
		return $this->word($this->ln, $this->sex, 'lastName', $gcase);
	}

	function firstName($gcase) {
		return $this->word($this->fn, $this->sex, 'firstName', $gcase);
	}

	function middleName($gcase) {
		return $this->word($this->mn, $this->sex, 'middleName', $gcase);
	}

}