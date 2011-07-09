<?php

// http://rmcreative.ru/blog/post/import-bolshchikh-sql-dampov-cherez-php#c4753
error_reporting(E_ALL);
mysql_connect('localhost', 'root', 'root');
mysql_select_db('jgittest');
mysql_query('SET NAMES "utf8"');
$dump = file_get_contents('sql/core.sql');
$q = '';
$state = 0;
$len = strlen($dump);
for ($i = 0; $i < $len; $i++) {
	switch ($dump{$i}) {
		case '"':
			if ($state == 0)
				$state = 1;
			elseif ($state == 1)
				$state = 0;
			break;
		case "'":
			if ($state == 0)
				$state = 2;
			elseif ($state == 2)
				$state = 0;
			break;
		case "`":
			if ($state == 0)
				$state = 3;
			elseif ($state == 3)
				$state = 0;
			break;
		case ";":
			if ($state == 0) {
				//echo $q . "\n;\n";
				mysql_query($q);
				$q = '';
				$state = 4;
			}
			break;
		case "\\":
			if (in_array($state, array(1, 2, 3)))
				$q.=$dump[$i++];
			break;
	}
	if ($state == 4)
		$state = 0;else
		$q.=$dump{$i};
}

echo 'Всё Ок!';