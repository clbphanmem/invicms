<?php

function dump($input)
{
    echo sprintf('<pre>%s</pre>', print_r($input, true));
}