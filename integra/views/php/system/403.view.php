<?php

use Nikacrm\Core\Container;

http_response_code(403);

$page        = base_url().main_page();
$refreshTime = Container::get('config')->refresh_timeout;
//header("Refresh: $refreshTime; url=$page");
?>
<style>
    body {
        font-family: Arial;
        color: #555;
        margin: 40px;
    }

    h1 {
        font-size: 5em;
    }

    h2 {
        color: #f03d3d;
    }

    h3 {

    }
</style>
<h1>403</h1>
<h2><?= $message ?? 'error: access denied' ?></h2>