<?php
#
#
# Get a review via ajax and submit to the database;
#
# Return true if things work like they should.
#


include_once '../pronunc_quality_conf.php';

//TABLE phone_error
//(
//    pe_id INTEGER PRIMARY KEY AUTOINCREMENT,
//    speaker INTEGER NOT NULL,
//    word INTEGER NOT NULL,
//    listener INTEGER NOT NULL,
//    pronunc_variant INTEGER NOT NULL,
//    word_phoneme INTEGER NOT NULL,
//    error_type INTEGER NOT NULL,
//    error_detail TEXT,
//    timestamp TEXT NOT NULL
//);

//print "<pre>";
//print_r($_GET);
//print "</pre>";

$listener=sqlite_escape_string($_GET["listener_id"]);

$speaker=sqlite_escape_string($_GET["speaker_id"]);

$word=sqlite_escape_string($_GET["word_id"]);

$pronunc_variant=sqlite_escape_string($_GET["pronunc"]);

$word_phoneme=sqlite_escape_string($_GET["word_phoneme"]);

$error_type=sqlite_escape_string($_GET["error_type"]);

$error_detail=sqlite_escape_string($_GET["error_detail"]);

$timestamp=date('Y-m-d h:i:s', time());



// $sqlcommand="INSERT INTO phone_error (speaker, word, listener, pronunc_variant, word_phoneme, error_type, error_detail, timestamp) ";
// $sqlcommand.=  "VALUES ('$speaker', '$word', '$listener','$pronunc_variant', '$word_phoneme', '$error_type', '$error_detail','$timestamp'); ";


$sqlcommand ="SELECT count(*) FROM phone_error WHERE word='$word' AND listener='$listener' AND speaker='$speaker' AND word_phoneme='$word_phoneme';";
if ($db->querySingle($sqlcommand) > 0) {
    $sqlcommand ="UPDATE phone_error SET error_type='$error_type',error_detail='$error_detail',timestamp='$timestamp' ";
    $sqlcommand.="WHERE word='$word' AND listener='$listener' AND speaker='$speaker' AND word_phoneme='$word_phoneme'";

}
else {
    $sqlcommand ="INSERT INTO phone_error (speaker, word, listener, pronunc_variant, word_phoneme, error_type, error_detail, timestamp) ";
    $sqlcommand.="VALUES ('$speaker', '$word', '$listener', '$pronunc_variant', '$word_phoneme', '$error_type','$error_detail','$timestamp'); ";
}


$trying=$db->exec($sqlcommand);

print $sqlcommand."<br>";

if ($trying)
    print 1;
else
    print 0;

$db->close();

?>