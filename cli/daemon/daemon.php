<?php

global $pids;
$pids = array();

print "Parent : " . getmypid() . "\n";

//Демонизируем процесс (форкаемся и убиваем родителя, запущенного из консоли)
$pid = pcntl_fork();
if ($pid) {
    //Только родитель знал pid ребенка - он знал слишком много...
    print "Parent : " . getmypid() . " exiting\n";
    exit();
}

$daemon_pid = getmypid();
print "Daemon : " . $daemon_pid . "\n";

//Задаем обработчик сигналов
declare(ticks = 1) ;

pcntl_signal(SIGTERM, "sig_handler");
pcntl_signal(SIGHUP, "sig_handler");
pcntl_signal(SIGINT, "sig_handler");
pcntl_signal(SIGUSR1, "sig_handler");

//Указываем воркер для запуска (нужен абсолютный путь, процессы выполняются мимо shell`а и не ориентируются в пространстве)
$program_end = array(">/dev/null", "2>&1");

//Определяем путь к интерпретатору PHP (по той же причине, что и для воркера)
$php_path = system('which php');
if (!$php_path) {
    die ("Ошибка определения местоположения интерпретатора PHP");
}

// список ссылок для парсера
$task_params_array = array(
    'http://www.joostina.local/layouts/blog_index',
    'http://www.joostina.local/layouts/blog_post',
    'http://www.joostina.local/layouts/users_index'
);

$running_previous_stage = false;

//Запускаем "бесконечный процесс" для демона - нужен для контроля дочерних процессов, как только все они отработают - выполняем финальные действия и удаляемся в закат
while (TRUE) {
    //Условие для форка
    if (count($task_params_array) && ($task_params = array_shift($task_params_array))) {
        echo $task_params."\n";
        $pid = pcntl_fork();
        if (!$pid) {
            //Дочерний процесс - запускаем специально обученного воркера
            $data_for_worker = array(__DIR__ . DIRECTORY_SEPARATOR .'tasks'.DIRECTORY_SEPARATOR. "example.php",$task_params);
            pcntl_exec($php_path, array_merge($data_for_worker, $program_end));
            exit();
        } else {
            //Родитель - добавляем дочерний процесс в общий список
            $pids[] = $pid;
        }
    }

    //Собираем все дочерние процессы, которые скончались сами - зомби должны быть упокоены
    $dead_and_gone = pcntl_waitpid(-1, $status, WNOHANG);
    while ($dead_and_gone > 0) {
        //Убираем pid зомби из массива
        unset($pids[array_search($dead_and_gone, $pids)]);
        //Ищем еще зомби
        $dead_and_gone = pcntl_waitpid(-1, $status, WNOHANG);
    }

    //Если отработали все извлекающие файлы процессы - значит, миссия выполнена
    if (!count($pids)) {

        print "Daemon : " . getmypid() . " работа выполнена\n";
        posix_kill($daemon_pid, SIGKILL);
    }

    //Пауза в цикле демона
    sleep(1);
}

//Обработчик для сигналов
function sig_handler($signo){
    global $pids;
    if ($signo == SIGTERM || $signo == SIGHUP || $signo == SIGINT) {
        
        //Если родитель перезапускаетсяили убит - посылаем им сигнал KILL
        foreach ($pids as $p) {
            posix_kill($p, $signo);
        }
        
        //Ждем, пока дочерние процессы передохнут
        foreach ($pids as $p) {
            pcntl_waitpid($p, $status);
        }
        
        //Надеваем свой плащ и волшебную шляпу...
        print "Daemon : " . getmypid() . " все потоки отработали, выключаюсь\n";
        exit();
    } else if ($signo == SIGUSR1) {
        print "У меня уже " . count($pids) . " поток\n";
    }
}