<?php
#
#
# Get a pronunciation via ajax and submit to the database;
#
# Return true if things work like they should.
#


include_once '../pronunc_quality_conf.php';

// print "<pre>";
// print_r(sqlite_escape_string($_GET));
// print "</pre>";

$listener=sqlite_escape_string($_GET["listener_id"]);

$speaker=sqlite_escape_string($_GET["speaker_id"]);

$word=sqlite_escape_string($_GET["word_id"]);

$pronunc_variant=sqlite_escape_string($_GET["pronunc"]);

$timestamp=date('Y-m-d h:i:s', time());


$sqlcommand ="SELECT count(*) FROM evaluations WHERE word='$word' AND listener='$listener' AND speaker='$speaker';";
if ($db->querySingle($sqlcommand) > 0) {
    $sqlcommand ="UPDATE evaluations SET pronunc_variant='$pronunc_variant', timestamp='$timestamp' ";
    $sqlcommand.="WHERE word='$word' AND listener='$listener' AND speaker='$speaker';";

    $sqlcommand.="DELETE FROM phone_error  ";
    $sqlcommand.="WHERE word='$word' AND listener='$listener' AND speaker='$speaker';";

}
else {
    $sqlcommand ="INSERT INTO evaluations (speaker, word, listener, evaluation, pronunc_variant, timestamp) ";
    $sqlcommand.="VALUES ('$speaker', '$word', '$listener', '0', '$pronunc_variant', '$timestamp'); ";

}

print $sqlcommand."<br>";


$trying=$db->exec($sqlcommand);

if ($trying)
    print 1;
else
    print 0;

$db->close();

?>