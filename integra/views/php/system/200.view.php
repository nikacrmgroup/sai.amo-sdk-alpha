<?php

/*http_response_code(204);*/
$message = $param['message'] ?? '✅';
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
        color: #83d9c3;
    }
</style>
<h1><?= $logo ?? '200' ?></h1>
<h2><?= $message ?></h2>