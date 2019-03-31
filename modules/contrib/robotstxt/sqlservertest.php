<?php
$serverName = "NARMADA\BEC"; //serverName\instanceName
$connectionInfo = array( "Database"=>"umbraco_bec", "UID"=>"umbraco.bec", "PWD"=>"umbraco2016");
$conn = sqlsrv_connect( $serverName, $connectionInfo);

if( $conn ) {
     echo "Conexióstablecida.<br />";
}else{
     echo "Conexióo se pudo establecer.<br />";
     die( print_r( sqlsrv_errors(), true));
}

