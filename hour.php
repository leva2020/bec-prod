<?php
setlocale(LC_ALL, "es_CO");

date_default_timezone_set('America/Bogota');

$date = strftime("%A %d/%b/%Y %R", mktime());
echo '<span style="color:#2e89b6;font-size:100%;font-family: Varela Round, sans-serif;font-weight: 700; ">' . utf8_encode($date) . '</span>';
