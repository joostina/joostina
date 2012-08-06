<?php
return array(

  'test_images'=>array(
      'accept_file_types'=>'/(\.|\/)(jpe?g|png)$/i',// регулярное выражение для разрешённых типов/имён файлов
      'accept_mime_content_types'=> array('image/jpeg'), // mime типы разрешённых для загрузки файлов
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
      ),
      'upload_location'=> JPATH_BASE.'/cache/tmp/' ,
      'actions_before'=>'', // действие до начала загрузки, принимает на вход массив параметров полученных из формы
      'actions_after'=>'helperTest::upload_file', // действия после загрузки файла, принимает на вход все данные о загруженном файле

  )
);
