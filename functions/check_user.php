<?php

include_once '../pronunc_quality_conf.php';

$listener=cleanlistener(SQLite3::escapeString($_GET["listener"]));

$sqlcommand="SELECT l_id from listeners where name='$listener';";

$listener_id= $db->querySingle($sqlcommand);

if ($listener_id) {
    print "old";
}
else {
    print "new";
}






function cleanlistener($listener) {

    $cleanlistener = str_replace("'", '', $listener);
    $cleanlistener = str_replace('"', '', $cleanlistener);
    $cleanlistener=preg_replace( "[@]", "_at_", $cleanlistener);
    $cleanlistener=preg_replace('~[^\p{L}\p{N}]~u', '_',$cleanlistener);
    return $cleanlistener;
}



?>
