<?php
/**
 * Vigan Shemsiu
 * mail@shemsiu.com
 * 2015-10-20
 */

require_once("URL.php");
use Shemsiu\URL;

if (isset($_GET['url'])) {
    if(!(new URL($_GET['url'], true))->validate())
        foreach(URL::$error_message as $error) print $error;
    else
        print "works!";
}