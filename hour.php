<?php

setlocale(LC_ALL, "es_CO");

date_default_timezone_set('America/Bogota');

echo '<span style="color:#2e89b6;font-size:95%;font-family: Varela Round, sans-serif;font-weight: 700; ">' .  strftime("%A %d/%b/%Y %R", mktime()) . '</span>';

