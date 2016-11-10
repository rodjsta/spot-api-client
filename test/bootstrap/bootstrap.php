<?php

require __DIR__ . '/../../vendor/autoload.php';

/**
 * @param array ...$args
 */
function dd(...$args)
{
    var_dump(...$args);
    exit;
}
