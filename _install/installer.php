<?php

require_once 'install.core.php';

$task        = joosInstallRequest::param( 'task' , false , $_POST );
$form_params = joosInstallRequest::param( 'form_params' , false , $_POST );

parse_str( $form_params );

switch ($task) {

    case 'check_db':

        $r = joosInstall::check_db( $db_host , $db_user , $db_password , $db_name );
        echo json_encode( $r );

        break;

    case 'install_db':

        $r = joosInstall::install_db( $db_host , $db_user , $db_password , $db_name );
        echo json_encode( $r );

        break;

    default:
        break;
}
