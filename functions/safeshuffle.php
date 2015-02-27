<?php
/**
 * Created by PhpStorm.
 * User: rkarhila
 * Date: 11/27/14
 * Time: 12:02 PM
 */



/* tweaked from http://www.php.net/manual/en/function.shuffle.php#105931 */
/* $seed variable is optional */
function SEOshuffle(&$items, $seed=false) {
    $original = md5(serialize($items));
    //mt_srand(crc32(($seed) ? $seed : $items[0]));
    for ($i = count($items) - 1; $i > 0; $i--){
        $j = crc32(($seed+$i)) % $i; //@mt_rand(0, $i);
        list($items[$i], $items[$j]) = array($items[$j], $items[$i]);
    }
    if ($original == md5(serialize($items))) {
        list($items[count($items) - 1], $items[0]) = array($items[0], $items[count($items) - 1]);
    }
}


