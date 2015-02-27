<?php
/**
 * Created by PhpStorm.
 * User: rkarhila
 * Date: 11/27/14
 * Time: 11:59 AM
 */




/* Remove special characters from the listener name */


function cleanlistener($listener) {

    $cleanlistener = str_replace("'", '', $listener);
    $cleanlistener = str_replace('"', '', $cleanlistener);
    $cleanlistener=preg_replace( "[@]", "_at_", $cleanlistener);
    $cleanlistener=preg_replace('~[^\p{L}\p{N}]~u', '_',$cleanlistener);
    return $cleanlistener;
}

?>