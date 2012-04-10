<?php
return array(
  'mp3files'=>array(
      'accept_file_types'=>'/(\.|\/)(gif|jpe?g|png)$/i',// регулярное выражение для разрешённых типов/имён файлов
      'max_file_size'=>1024*3,// 3 мегабайта
      'location'=>'audio',// каталог куда складывать загруженные файлы
      'drop'=>true, // разрешить перетаскивание файлов мышом
      'limit_multi_file_uploads'=>5, // число файлов для мульти выбора ( 1 для отключения мультивыбора )
      'callbacks'=>array( // функции вызываемые при наступлении событий загрузки
          'fileuploadadd'=>'alert(111)',
          'fileuploadfail'=>'joostinaCore.noty("Ошибка загрузки")',
          /* ... */
      ),
      'style'=>array(
          'class'=>'' // class свойство кнопки выбора файла
      )
      
  )  
);