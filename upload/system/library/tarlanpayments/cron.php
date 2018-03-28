<?php
$tarlanpayments_dir = dirname(__FILE__);

require_once $tarlanpayments_dir . DIRECTORY_SEPARATOR . 'cron_functions.php';

if ($index = tarlanpayments_init($tarlanpayments_dir)) {
    require_once $index;
}
