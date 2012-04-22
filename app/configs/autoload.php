<?php

/**
 * Расположение классов для автозагрузчика
 *
 * @todo сделать возможность указывать класы динамически - JLoader::register('FinderIndexer', dirname(__FILE__) . '/indexer.php');
 *
 */
return array(
	'class_name' => 'app/class.name.php',
    'joosArray' => 'core/libraries/array.php',
    'joosAttached' => 'core/libraries/attached.php',
    'joosBenchmark' => 'core/libraries/benchmark.php',
    'joosBreadcrumbs' => 'core/libraries/breadcrumbs.php',
    'joosCache' => 'core/libraries/cache.php',
    'joosConfig' => 'core/libraries/config.php',
    'joosDatabase' => 'core/libraries/database.php',
    'joosModel' => 'core/libraries/database.php',
    'joosDateTime' => 'core/libraries/datetime.php',
    'joosDebug' => 'core/libraries/debug.php',
    'joosEditor' => 'core/libraries/editor.php',
    'joosEvents' => 'core/libraries/events.php',
    'joosFile' => 'core/libraries/file.php',
    'joosFilter' => 'core/libraries/filter.php',
    'joosFlashMessage' => 'core/libraries/flashmessage.php',
    'joosHit' => 'core/libraries/hit.php',
    'joosHTML' => 'core/libraries/html.php',
    'joosImage' => 'core/libraries/image.php',
    'joosInflector' => 'core/libraries/inflector.php',
    'joosInputFilter' => 'core/libraries/inputfilter.php',
    'joosAutoadmin' => 'core/libraries/autoadmin.php',
    'joosNestedSet' => 'core/libraries/nestedset.php',
    'joosPager' => 'core/libraries/pager.php',
    'joosParams' => 'core/libraries/params.php',
    'joosRandomizer' => 'core/libraries/randomizer.php',
    'joosRequest' => 'core/libraries/request.php',
    'joosRobotLoader' => 'core/libraries/robotloader.php',
    'joosRoute' => 'core/libraries/route.php',
    'joosSession' => 'core/libraries/session.php',
    'joosSpoof' => 'core/libraries/spoof.php',
    'joosString' => 'core/libraries/string.php',
    'joosText' => 'core/libraries/text.php',
    'joosUpload'=>'core/libraries/upload.php',
    'joosTrash' => 'core/libraries/trash.php',
    'joosValidate' => 'core/libraries/validate.php',
    'joosValidateHelper' => 'core/libraries/validate.php',
    'joosVersion' => 'core/libraries/version.php',
    'joosLogging' => 'app/vendors/logging/logging.php',

    'modelUsersAclGroups' => 'app/components/acls/models/model.acls.php',
    'modelAdminUsersAclGroups'=>'app/components/acls/models/model.admin.acls.php',
    'modelUsersAclRolesGroups'=>'app/components/acls/models/model.admin.acls.php',
    'modelAdminUsersAclRules'=>'app/components/acls/models/model.admin.acls.php',
    'helperAcl'=> 'app/components/acls/models/model.acls.php',

    // Это старьё, надо переписать либо удалить
    'htmlTabs' => 'core/libraries/html.php',
    'forms' => 'app/vendors/forms/forms.php',
    // Библиотеки сторонних вендоров
    'JJevix' => 'app/vendors/text/jevix/jevix.php',
    // пока не адаптированные библиотеки
    'Thumbnail' => 'core/libraries/image.php',
    'modelAdminCoder_Faker' => 'app/components/coder/models/model.admin.coder.php',
    'helperTest'=>'app/components/test/helpers/helpers.test.php',

    /* блоги */
    'modelBlogs' => 'app/components/blogs/models/model.blogs.php',
    'modelBlogsCategory' => 'app/components/blogs/models/model.blogs.php',
    'modelAdminBlogs' => 'app/components/blogs/models/model.admin.blogs.php',
    'modelAdminBlogsCategory' => 'app/components/blogs/models/model.admin.blogs.php',
    
);