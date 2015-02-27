<?php
#
#
# Get a review via ajax and submit to the database;
#
# Return true if things work like they should.
#


include_once '../pronunc_quality_conf.php';

print "<pre>";
print_r($_GET);
print "</pre>";

$listener=SQLite3::escapeString($_GET["listener_id"]);

$speaker=SQLite3::escapeString($_GET["speaker_id"]);

$word=SQLite3::escapeString($_GET["word_id"]);

$rating=SQLite3::escapeString($_GET["rating"]);

#$pronuncn_variant=$_POST["pronunciation_id"];
#$phones=$_POST["phones"];

$timestamp=date('Y-m-d h:i:s', time());

$sqlcommand ="SELECT count(*) FROM evaluations WHERE word='$word' AND listener='$listener' AND speaker='$speaker'";
if ($db->querySingle($sqlcommand) > 0) {
    $sqlcommand ="UPDATE evaluations SET evaluation='$rating', timestamp='$timestamp' ";
    $sqlcommand.="WHERE word='$word' AND listener='$listener' AND speaker='$speaker'";

}
else {
    $sqlcommand ="INSERT INTO evaluations (speaker, word, listener, evaluation, timestamp) ";
    $sqlcommand.="VALUES ('$speaker', '$word', '$listener', '$rating', '$timestamp'); ";
}

$trying=$db->exec($sqlcommand);

if ($trying)
    print $rating;
else {
    print $sqlcommand;
}


$db->close();

?>