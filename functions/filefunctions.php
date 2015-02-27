<?php
/**
 * Created by PhpStorm.
 * User: rkarhila
 * Date: 11/27/14
 * Time: 12:00 PM
 */


/* File functions for bookkeeping */


function getorderfiledir($resultdir) {
    $orderdir=$resultdir ."orderfiles/";
    if ( ! file_exists( $orderdir ) ) {
        mkdir($orderdir, 0777, true);
    }
    return $orderdir;
}

function getstatfiledir($resultdir) {
    $statdir=$resultdir ."listenerstatfiles/";
    if ( ! file_exists( $statdir ) ) {
        mkdir($statdir, 0777, true);
    }
    return $statdir;
}


function getlockdir($resultdir,$sample) {
    $lockdir =  $resultdir."locks/".$sample[0].$sample[1]."/".$sample."/";
    if ( ! file_exists(  $lockdir  ) ) {
        mkdir($lockdir, 0777, true);
    }
    return $lockdir;
}

function getallresultsdir($resultdir,$sample) {
    $resdir=$resultdir."results_all/".$sample[0].$sample[1]."/".$sample."/";
    if ( ! file_exists(  $resdir ) ) {
        mkdir($resdir, 0777, true);
    }
    return $resdir;
}

function getlistenerresultsdir($resultdir,$listener) {
    $lisresdir=$resultdir."/listeners/".$listener."/";
    if ( ! file_exists( $lisresdir  ) ) {
        mkdir( $lisresdir, 0777, true);
    }
    return $lisresdir;
}

function checklocks($resultdir, $sample,$listener, $timeout) {
    $lockdir=getlockdir($resultdir, $sample);
    $locks=array_diff(scandir($lockdir), array('..', '.'));
    $lockcount=0;
    foreach ($locks as $l) {
        if ( filemtime(  $lockdir . $l ) >  time()-$timeout  ) {
            if ( $l != $listener) {
                $lockcount++;
            }
            else { unlink($lockdir . $l); };
        }
    }
    return $lockcount;
}

function checkresults($resultdir,$sample) {
    $resdir=getallresultsdir($resultdir, $sample);
    $answers=array_diff(scandir($resdir), array('..', '.'));

    $answercount=0;
    foreach ($answers as $a) {
        $answercount++;
    }
    return $answercount;

}

function checklistenerresults($resultdir,$listener,$sample) {

    $lisresdir=getlistenerresultsdir($resultdir,$listener);

    if ($GLOBALS['DEBUGGING'] ) {print  "<pre>checking \n". $lisresdir . $sample."</pre>";}

    if (! file_exists(  $lisresdir . $sample ) ) {
        return True;
    }
    else return False;

}

function makelock($resultdir,$sample,$listener) {

    $lockdir=getlockdir($resultdir,$sample);
    $fh = fopen(  $lockdir . $listener, 'w');
    fwrite($fh, "locked to ".$listener." on ".date('F d Y h:i A P T e')."\n");
    fclose($fh);
    return true;
}


/* Writing results from the POST submission data */

function writeresults($resultdir,$listener, $data) {

    foreach ($data as $key => $value) {
        if ($key[0] == 'e') {
            $sample=$key[5].$key[6].$key[7].$key[8];


            if ($GLOBALS['DEBUGGING']) {
                print "<pre>". $resdir ."\n". $listener ."</pre>";
            }

            /* Collect the important results and put them into a string */

            $resstring = "result: ". $data['eval_'.$sample][5]."\nlistener: ". $listener."\ndate: ".date('F d Y h:i A P T e')."\n";

            /* Get the timestamps from listening and rating events:  */

            $ct=0;
            while (array_key_exists("sample_".$sample."_listenstamps_".$ct, $data) ) {
                $resstring .= "listenstamps_".$ct.": ".$data["sample_".$sample."_listenstamps_".$ct]."\n";
                $ct++;
            }
            $ct=0;
            while (array_key_exists("sample_".$sample."_answerstamps_".$ct, $data) ) {
                $resstring .= "answerstamps_".$ct.": ".$data["sample_".$sample."_answerstamps_".$ct]."\n";
                $ct++;
            }

            /* Write results into two places just to be sure */

            $resdir=getallresultsdir($resultdir,$sample);

            $fh = fopen(  $resdir . $listener, 'w');
            fwrite($fh, $resstring);
            fclose($fh);

            $lisresdir=getlistenerresultsdir($resultdir,$listener);


            $fh = fopen(  $lisresdir . $sample, 'w');
            fwrite($fh, $resstring);
            fclose($fh);

            $lockdir=getlockdir($resultdir,$sample);

            if ( file_exists(  $lockdir . $listener  ) ) {
                unlink($lockdir . $listener );
            }
        }
    }
}



?>