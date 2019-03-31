<?php
$serverName = "NARMADA\BEC"; //serverName\instanceName
$connectionInfo = array( "Database"=>"umbraco_bec", "UID"=>"umbraco.bec", "PWD"=>"umbraco2016");
try  
{  
$conn = new PDO( "sqlsrv:server=$serverName ; Database=umbraco_bec", "umbraco.bec", "umbraco2016");  
print_r($conn);
}  
catch(Exception $e)  
{   
die( print_r( $e->getMessage() ) );   
}

