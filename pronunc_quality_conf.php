<?php


// From https://b3z13r.wordpress.com/2011/05/16/passing-values-from-the-commandline-to-php-by-getpost-method/
if (!isset($_SERVER["HTTP_HOST"])) {
    // script is not interpreted due to some call via http, so it must be called from the commandline
    parse_str($argv[1], $_GET); // use $_POST instead if you want to
}




# Include information about the URL where test is run, about samples etc...
# $testurl="http://users.ics.aalto.fi/rkarhila/training_data_quality_listening_test/pronunc_quality_test.php";

$base_url="http://localhost:63342/SIAK_subjective_pronunciation_scoring_test";

//$base_url="http://research.ics.aalto.fi/speech/listening_tests/siak/";

$testurl=$base_url."/pronunc_quality_test.php";

# Path for saving results; Obviously read/write permissions are required for this:
# $resultdir="/share/public_html/rkarhila/training_data_quality_listening_test/results/";

$resultdir="/data/scratch/rkarhila/SIAK_subjective_pronunciation_scoring_test_results/";
//$resultdir="/share/www/research/speech/listening_tests/siak";

# Url for the script snippet that checks if a nickname is in use or not
# $usercheckurl="http://users.ics.aalto.fi/rkarhila/training_data_quality_listening_test/check_user.php";

$usercheckurl=$base_url."/functions/check_user.php";

$add_evaluation_url=$base_url."/functions/add_to_database.php";

$switch_pronunciation_url=$base_url."/functions/switch_pronunciation_in_database.php";

$add_phone_error_url=$base_url."/functions/add_phone_error_to_database.php";

$dbfile="/data/scratch/rkarhila/PhpstormProjects/SIAK_subjective_pronunciation_scoring_test/db/pronunc.db";




$requiredevaluations=100;
$samplesperpage=8;
$allowedtime=600;
#$timertext="Time remaining:";

$personalorderfile="order.txt";
$personaldonefile="done.txt";


$footertext="Questions, comments etc to <i>reima &#9830; karhila <b>(attention)</b> aalto &#9830 fi</i><br>";



# How many sentences to be evaluated?

$numsent=200;

# How many times each sentence needs to be evaluated?

$min_evals=18;


$introduction="
   <p>Welcome to our listening test!
    <ul>
   <li>To keep track which utterances you've evaluated already, you are asked to provide a unique identifier.
   <li> You can do the test at your own pace, returning to it later with your identifier.
   <li> More instructions should be written, but not too many.
</ul>";

$visitortext="If you don't want to participate in the test but are curious how it is done, <a href=\"sampletestpage.html\"> click here for a sample page.</a>

";

$breaktext="You can have a break at any time. After your break, please return to the test via the 
<a href=$testurl>start page.</a>";


$agegroups=Array(
    Array("val" => "under20", "label" => "Under 20"),
    Array("val" => "20to29", "label" => "20 to 29"),
    Array("val" => "30to39", "label" => "30 to 39"),
    Array("val" => "40to49", "label" => "40 to 49"),
    Array("val" => "50to59", "label" => "50 to 59"),
    Array("val" => "60to69", "label" => "60 to 69"),
    Array("val" => "over70", "label" => "70 or over"),
);

$languagebackground=Array(
    Array("val" => "Fi_US", "label" => "Native Finnish with learned American accented English"),
    Array("val" => "Fi_UK", "label" => "Native Finnish with learned UK accented English"),
    Array("val" => "Fi_otherEng", "label" => "Native Finnish with learned English with other accent"),
    Array("val" => "En_US", "label" => "Native English with American accent"),
    Array("val" => "En_UK", "label" => "Native English with UK accent"),
    Array("val" => "En_otherEn", "label" => "Native English with other accent"),
);
//    <option name=southbrit value=southbrit > Native speaker, Southern British English </option>
//    <option name=scot value=scot > Native speaker, Scottish English </option>
//    <option name=otherukorie value=otherukorie > Native speaker, other UK or Irish dialects </option>
//    <option name=american value=american > Native speaker, American English dialects </option>
//    <option name=southasian value=southasian > Native speaker, South Asian English dialects </option>
//    <option name=australian value=australian > Native speaker, Australian or NZ dialects </option>
//    <option name=other value=other > Native speaker, other dialect </option>
//    <option name=nonnative value=nonnative > Non-native speaker </option>

$speakers=Array(
    "heikki_apina",
    "heikki_oma",
    "jan_m_apina",
    "jan_m_oma",
    "kalle_apina",
    "kalle_oma",
    "laura_apina",
    "laura_oma",
    "mikko_apina",
    "mikko_oma",
    "reima_apina",
    "reima_oma",
    "seppo_apina",
    "seppo_oma",
    "ulpu_apina",
    "ulpu_oma"
);


$words=Array(
    "001" => Array( "file" => "001_too", "word" => "too",	"phonemes" => Array( Array ("t", "uu"))),
    "002" => Array( "file" => "002_safe", "word" => "safe",	"phonemes" => Array( Array ("s", "ei", "f"))),
    "003" => Array( "file" => "003_pig", "word" => "pig",	"phonemes" => Array( Array ("p", "i", "g"))),
    "004" => Array( "file" => "004_pie", "word" => "pie",	"phonemes" => Array( Array ("p", "ai"))),
    "005" => Array( "file" => "005_teeth", "word" => "teeth",	"phonemes" => Array( Array ("t", "ii", "th"))),
    "006" => Array( "file" => "006_fan", "word" => "fan",	"phonemes" => Array( Array ("f", "a", "n"))),
    "007" => Array( "file" => "007_that", "word" => "that",	"phonemes" => Array( Array ("dh", "a", "t"), Array("dh", "@", "t"))),
    "008" => Array( "file" => "008_first", "word" => "first",	"phonemes" => Array( Array ("f", "@@r", "s", "t"))),
    "009" => Array( "file" => "009_fin", "word" => "fin",	"phonemes" => Array( Array ("f", "i", "n"))),
    "010" => Array( "file" => "010_art", "word" => "art",	"phonemes" => Array( Array ("aa", "t"))),
    "011" => Array( "file" => "011_full", "word" => "full",	"phonemes" => Array( Array ("f", "u", "lw"))),
    "012" => Array( "file" => "012_fur", "word" => "fur",	"phonemes" => Array( Array ("f", "@@r"))),
    "013" => Array( "file" => "013_van", "word" => "van",	"phonemes" => Array( Array ("v", "a", "n"))),
    "014" => Array( "file" => "014_were", "word" => "were",	"phonemes" => Array( Array ("w", "@@r"), Array("w", "@"))),
    "015" => Array( "file" => "015_ant", "word" => "ant",	"phonemes" => Array( Array ("a", "n", "t"))),
    "016" => Array( "file" => "016_thirst", "word" => "thirst",	"phonemes" => Array( Array ("th", "@@r", "s", "t"))),
    "017" => Array( "file" => "017_save", "word" => "save",	"phonemes" => Array( Array ("s", "ei", "v"))),
    "018" => Array( "file" => "018_fork", "word" => "fork",	"phonemes" => Array( Array ("f", "oo", "k"))),
    "019" => Array( "file" => "019_ship", "word" => "ship",	"phonemes" => Array( Array ("sh", "i", "p"))),
    "020" => Array( "file" => "020_wish", "word" => "wish",	"phonemes" => Array( Array ("w", "i", "sh"))),
    "021" => Array( "file" => "021_fox", "word" => "fox",	"phonemes" => Array( Array ("f", "o", "k", "s"))),
    "022" => Array( "file" => "022_off", "word" => "off",	"phonemes" => Array( Array ("o", "f"))),
    "023" => Array( "file" => "023_sees", "word" => "sees",	"phonemes" => Array( Array ("s", "ii", "z"))),
    "024" => Array( "file" => "024_arm", "word" => "arm",	"phonemes" => Array( Array ("aa", "m"))),
    "025" => Array( "file" => "025_cheese", "word" => "cheese",	"phonemes" => Array( Array ("ch", "ii", "z"))),
    "026" => Array( "file" => "026_wall", "word" => "wall",	"phonemes" => Array( Array ("w", "oo", "lw"))),
    "027" => Array( "file" => "027_vet", "word" => "vet",	"phonemes" => Array( Array ("v", "e", "t"))),
    "028" => Array( "file" => "028_run", "word" => "run",	"phonemes" => Array( Array ("r", "uh", "n"))),
    "029" => Array( "file" => "029_maths", "word" => "maths",	"phonemes" => Array( Array ("m", "a", "th", "s"))),
    "030" => Array( "file" => "030_page", "word" => "page",	"phonemes" => Array( Array ("p", "ei", "jh"))),
    "031" => Array( "file" => "031_mouth", "word" => "mouth",	"phonemes" => Array( Array ("m", "ow", "th"), Array("m", "ow", "dh"))),
    "032" => Array( "file" => "032_back", "word" => "back",	"phonemes" => Array( Array ("b", "a", "k"))),
    "033" => Array( "file" => "033_zoo", "word" => "zoo",	"phonemes" => Array( Array ("z", "uu"))),
    "034" => Array( "file" => "034_age", "word" => "age",	"phonemes" => Array( Array ("ei", "jh"))),
    "035" => Array( "file" => "035_chin", "word" => "chin",	"phonemes" => Array( Array ("ch", "i", "n"))),
    "036" => Array( "file" => "036_am", "word" => "am",	"phonemes" => Array( Array ("a", "m"), Array("am"))),
    "037" => Array( "file" => "037_bark", "word" => "bark",	"phonemes" => Array( Array ("b", "aa", "k"))),
    "038" => Array( "file" => "038_one", "word" => "one",	"phonemes" => Array( Array ("w", "uh", "n"))),
    "039" => Array( "file" => "039_sheep", "word" => "sheep",	"phonemes" => Array( Array ("sh", "ii", "p"))),
    "040" => Array( "file" => "040_do", "word" => "do",	"phonemes" => Array( Array ("d", "uu"), Array("d", "ou"))),
    "041" => Array( "file" => "041_chair", "word" => "chair",	"phonemes" => Array( Array ("ch", "eir"))),
    "042" => Array( "file" => "042_sea", "word" => "sea",	"phonemes" => Array( Array ("s", "ii"))),
    "043" => Array( "file" => "043_sheet", "word" => "sheet",	"phonemes" => Array( Array ("sh", "ii", "t"))),
    "044" => Array( "file" => "044_year", "word" => "year",	"phonemes" => Array( Array ("y", "i@"))),
    "045" => Array( "file" => "045_ten", "word" => "ten",	"phonemes" => Array( Array ("t", "e", "n"))),
    "046" => Array( "file" => "046_moose", "word" => "moose",	"phonemes" => Array( Array ("m", "uu", "s"))),
    "047" => Array( "file" => "047_wash", "word" => "wash",	"phonemes" => Array( Array ("w", "o", "sh"))),
    "048" => Array( "file" => "048_log", "word" => "log",	"phonemes" => Array( Array ("l", "o", "g"))),
    "049" => Array( "file" => "049_thin", "word" => "thin",	"phonemes" => Array( Array ("th", "i", "n"))),
    "050" => Array( "file" => "050_shine", "word" => "shine",	"phonemes" => Array( Array ("sh", "ai", "n"))),
    "051" => Array( "file" => "051_ice", "word" => "ice",	"phonemes" => Array( Array ("ai", "s"))),
    "052" => Array( "file" => "052_eat", "word" => "eat",	"phonemes" => Array( Array ("ii", "t"))),
    "053" => Array( "file" => "053_finn", "word" => "finn",	"phonemes" => Array( Array ("f", "i", "n"))),
    "054" => Array( "file" => "054_bag", "word" => "bag",	"phonemes" => Array( Array ("b", "a", "g"))),
    "055" => Array( "file" => "055_long", "word" => "long",	"phonemes" => Array( Array ("l", "o", "ng"))),
    "056" => Array( "file" => "056_set", "word" => "set",	"phonemes" => Array( Array ("s", "e", "t"))),
    "057" => Array( "file" => "057_zoos", "word" => "zoos",	"phonemes" => Array( Array ("z", "uu", "z"))),
    "058" => Array( "file" => "058_clothe", "word" => "clothe",	"phonemes" => Array( Array ("k", "l", "ou", "dh"))),
    "059" => Array( "file" => "059_thick", "word" => "thick",	"phonemes" => Array( Array ("th", "i", "k"))),
    "060" => Array( "file" => "060_pull", "word" => "pull",	"phonemes" => Array( Array ("p", "u", "lw"))),
    "061" => Array( "file" => "061_lead", "word" => "lead",	"phonemes" => Array( Array ("l", "ii", "d"), Array("l", "e", "d"))),
    "062" => Array( "file" => "062_seed", "word" => "seed",	"phonemes" => Array( Array ("s", "ii", "d"))),
    "063" => Array( "file" => "063_mats", "word" => "mats",	"phonemes" => Array( Array ("m", "a", "t", "s"))),
    "064" => Array( "file" => "064_read", "word" => "read",	"phonemes" => Array( Array ("r", "ii", "d"), Array("r", "e", "d"))),
    "065" => Array( "file" => "065_chip", "word" => "chip",	"phonemes" => Array( Array ("ch", "i", "p"))),
    "066" => Array( "file" => "066_west", "word" => "west",	"phonemes" => Array( Array ("w", "e", "s", "t"))),
    "067" => Array( "file" => "067_mouse", "word" => "mouse",	"phonemes" => Array( Array ("m", "ow", "s"))),
    "068" => Array( "file" => "068_fat", "word" => "fat",	"phonemes" => Array( Array ("f", "a", "t"))),
    "069" => Array( "file" => "069_with", "word" => "with",	"phonemes" => Array( Array ("w", "i", "dh"))),
    "070" => Array( "file" => "070_and", "word" => "and",	"phonemes" => Array( Array ("a", "n", "d"), Array("@", "n", "d"))),
    "071" => Array( "file" => "071_write", "word" => "write",	"phonemes" => Array( Array ("r", "ai", "t"))),
    "072" => Array( "file" => "072_these", "word" => "these",	"phonemes" => Array( Array ("dh", "ii", "z"))),
    "073" => Array( "file" => "073_move", "word" => "move",	"phonemes" => Array( Array ("m", "uu", "v"))),
    "074" => Array( "file" => "074_feet", "word" => "feet",	"phonemes" => Array( Array ("f", "ii", "t"))),
    "075" => Array( "file" => "075_she", "word" => "she",	"phonemes" => Array( Array ("sh", "ii"))),
    "076" => Array( "file" => "076_watch", "word" => "watch",	"phonemes" => Array( Array ("w", "o", "ch"))),
    "077" => Array( "file" => "077_right", "word" => "right",	"phonemes" => Array( Array ("r", "ai", "t"))),
    "078" => Array( "file" => "078_ride", "word" => "ride",	"phonemes" => Array( Array ("r", "ai", "d"))),
    "079" => Array( "file" => "079_once", "word" => "once",	"phonemes" => Array( Array ("w", "uh", "n", "s"))),
    "080" => Array( "file" => "080_walk", "word" => "walk",	"phonemes" => Array( Array ("w", "oo", "k"))),
    "081" => Array( "file" => "081_leaf", "word" => "leaf",	"phonemes" => Array( Array ("l", "ii", "f"))),
    "082" => Array( "file" => "082_was", "word" => "was",	"phonemes" => Array( Array ("w", "o", "z"), Array("w", "@", "z"))),
    "083" => Array( "file" => "083_shoe", "word" => "shoe",	"phonemes" => Array( Array ("sh", "uu"))),
    "084" => Array( "file" => "084_wrong", "word" => "wrong",	"phonemes" => Array( Array ("r", "o", "ng"))),
    "085" => Array( "file" => "085_coat", "word" => "coat",	"phonemes" => Array( Array ("k", "ou", "t"))),
    "086" => Array( "file" => "086_gold", "word" => "gold",	"phonemes" => Array( Array ("g", "ou", "lw", "d"))),
    "087" => Array( "file" => "087_choose", "word" => "choose",	"phonemes" => Array( Array ("ch", "uu", "z"))),
    "088" => Array( "file" => "088_chat", "word" => "chat",	"phonemes" => Array( Array ("ch", "a", "t"))),
    "089" => Array( "file" => "089_three", "word" => "three",	"phonemes" => Array( Array ("th", "r", "ii"))),
    "090" => Array( "file" => "090_pool", "word" => "pool",	"phonemes" => Array( Array ("p", "uu", "lw"))),
    "091" => Array( "file" => "091_cheap", "word" => "cheap",	"phonemes" => Array( Array ("ch", "ii", "p"))),
    "092" => Array( "file" => "092_piece", "word" => "piece",	"phonemes" => Array( Array ("p", "ii", "s"))),
    "093" => Array( "file" => "093_pea", "word" => "pea",	"phonemes" => Array( Array ("p", "ii"))),
    "094" => Array( "file" => "094_buy", "word" => "buy",	"phonemes" => Array( Array ("b", "ai"))),
    "095" => Array( "file" => "095_tree", "word" => "tree",	"phonemes" => Array( Array ("t", "r", "ii"))),
    "096" => Array( "file" => "096_vest", "word" => "vest",	"phonemes" => Array( Array ("v", "e", "s", "t"))),
    "097" => Array( "file" => "097_girl", "word" => "girl",	"phonemes" => Array( Array ("g", "@@r", "lw"))),
    "098" => Array( "file" => "098_fit", "word" => "fit",	"phonemes" => Array( Array ("f", "i", "t"))),
    "099" => Array( "file" => "099_close", "word" => "close",	"phonemes" => Array( Array ("k", "l", "ou", "s"), Array("k", "l", "ou", "z"))),
    "100" => Array( "file" => "100_peas", "word" => "peas",	"phonemes" => Array( Array ("p", "ii", "z"))),
    "101" => Array( "file" => "101_goat", "word" => "goat",	"phonemes" => Array( Array ("g", "ou", "t"))),
    "102" => Array( "file" => "102_those", "word" => "those",	"phonemes" => Array( Array ("dh", "ou", "z"))),
    "103" => Array( "file" => "103_seat", "word" => "seat",	"phonemes" => Array( Array ("s", "ii", "t"))),
    "104" => Array( "file" => "104_pack", "word" => "pack",	"phonemes" => Array( Array ("p", "a", "k"))),
    "105" => Array( "file" => "105_teas", "word" => "teas",	"phonemes" => Array( Array ("t", "ii", "z"))),
    "106" => Array( "file" => "106_sit", "word" => "sit",	"phonemes" => Array( Array ("s", "i", "t"))),
    "107" => Array( "file" => "107_then", "word" => "then",	"phonemes" => Array( Array ("dh", "e", "n"))),
    "108" => Array( "file" => "108_eyes", "word" => "eyes",	"phonemes" => Array( Array ("ai", "z"))),
    "109" => Array( "file" => "109_big", "word" => "big",	"phonemes" => Array( Array ("b", "i", "g"))),
    "110" => Array( "file" => "110_fall", "word" => "fall",	"phonemes" => Array( Array ("f", "oo", "lw"))),
    "111" => Array( "file" => "111_wheel", "word" => "wheel",	"phonemes" => Array( Array ("w", "ii", "lw"))),
    "112" => Array( "file" => "112_heart", "word" => "heart",	"phonemes" => Array( Array ("h", "aa", "t"))),
    "113" => Array( "file" => "113_which", "word" => "which",	"phonemes" => Array( Array ("w", "i", "ch"))),
    "114" => Array( "file" => "114_shows", "word" => "shows",	"phonemes" => Array( Array ("sh", "ou", "z"))),
    "115" => Array( "file" => "115_park", "word" => "park",	"phonemes" => Array( Array ("p", "aa", "k"))),
    "116" => Array( "file" => "116_curl", "word" => "curl",	"phonemes" => Array( Array ("k", "@@r", "lw"))),
    "117" => Array( "file" => "117_fine", "word" => "fine",	"phonemes" => Array( Array ("f", "ai", "n"))),
    "118" => Array( "file" => "118_yet", "word" => "yet",	"phonemes" => Array( Array ("y", "e", "t"))),
    "119" => Array( "file" => "119_wet", "word" => "wet",	"phonemes" => Array( Array ("w", "e", "t"))),
    "120" => Array( "file" => "120_socks", "word" => "socks",	"phonemes" => Array( Array ("s", "o", "k", "s"))),
    "121" => Array( "file" => "121_pays", "word" => "pays",	"phonemes" => Array( Array ("p", "ei", "z"))),
    "122" => Array( "file" => "122_hat", "word" => "hat",	"phonemes" => Array( Array ("h", "a", "t"))),
    "123" => Array( "file" => "123_light", "word" => "light",	"phonemes" => Array( Array ("l", "ai", "t"))),
    "124" => Array( "file" => "124_wool", "word" => "wool",	"phonemes" => Array( Array ("w", "u", "lw"))),
    "125" => Array( "file" => "125_chick", "word" => "chick",	"phonemes" => Array( Array ("ch", "i", "k"))),
    "126" => Array( "file" => "126_at", "word" => "at",	"phonemes" => Array( Array ("a", "t"), Array("@", "t"))),
    "127" => Array( "file" => "127_eight", "word" => "eight",	"phonemes" => Array( Array ("ei", "t"))),
    "128" => Array( "file" => "128_than", "word" => "than",	"phonemes" => Array( Array ("dh", "a", "n"), Array("dh", "@", "n"))),
    "129" => Array( "file" => "129_bee", "word" => "bee",	"phonemes" => Array( Array ("b", "ii"))),
    "130" => Array( "file" => "130_of", "word" => "of",	"phonemes" => Array( Array ("o", "v"), Array("@", "v"))),
    "131" => Array( "file" => "131_two", "word" => "two",	"phonemes" => Array( Array ("t", "uu"))),
    "132" => Array( "file" => "132_cold", "word" => "cold",	"phonemes" => Array( Array ("k", "ou", "lw", "d"))),
    "133" => Array( "file" => "133_soon", "word" => "soon",	"phonemes" => Array( Array ("s", "uu", "n"))),
    "134" => Array( "file" => "134_there", "word" => "there",	"phonemes" => Array( Array ("dh", "eir"))),
    "135" => Array( "file" => "135_fun", "word" => "fun",	"phonemes" => Array( Array ("f", "uh", "n"))),
    "136" => Array( "file" => "136_rest", "word" => "rest",	"phonemes" => Array( Array ("r", "e", "s", "t"))),
    "137" => Array( "file" => "137_ones", "word" => "ones",	"phonemes" => Array( Array ("w", "uh", "n", "z"))),
    "138" => Array( "file" => "138_leave", "word" => "leave",	"phonemes" => Array( Array ("l", "ii", "v"))),
    "139" => Array( "file" => "139_be", "word" => "be",	"phonemes" => Array( Array ("b", "ii"))),
    "140" => Array( "file" => "140_real", "word" => "real",	"phonemes" => Array( Array ("r", "i@", "lw"), Array("r", "ei", "aa", "lw"))),
    "141" => Array( "file" => "141_hard", "word" => "hard",	"phonemes" => Array( Array ("h", "aa", "d"))),
    "142" => Array( "file" => "142_june", "word" => "june",	"phonemes" => Array( Array ("jh", "uu", "n"))),
    "143" => Array( "file" => "143_it", "word" => "it",	"phonemes" => Array( Array ("i", "t"))),
);

$filekey=Array(


);





if (!file_exists($dbfile)) {

    $db = new SQLite3($dbfile);

    $db->exec("pragma synchronous = off;");

    $sqlcommand="BEGIN TRANSACTION;
CREATE TABLE words
(
    w_id INTEGER PRIMARY KEY AUTOINCREMENT,
    word TEXT NOT NULL
);
CREATE TABLE speakers
(
    s_id INTEGER PRIMARY KEY AUTOINCREMENT,
    name TEXT NOT NULL,
    gender TEXT,
    age TEXT,
    language_bg TEXT
);
CREATE TABLE speakers_words
(
    sw_id INTEGER PRIMARY KEY AUTOINCREMENT,
    speaker INTEGER NOT NULL,
    word INTEGER NOT NULL,
    filename TEXT NOT NULL
);
CREATE TABLE listeners
(
    l_id INTEGER PRIMARY KEY AUTOINCREMENT,
    name TEXT NOT NULL,
    gender TEXT NOT NULL,
    age TEXT NOT NULL,
    language_bg TEXT NOT NULL,
    timestamp TEXT NOT NULL
);
CREATE TABLE evaluations
(
    e_id INTEGER PRIMARY KEY AUTOINCREMENT,
    speaker INTEGER NOT NULL,
    word INTEGER NOT NULL,
    listener INTEGER NOT NULL,
    evaluation FLOAT NOT NULL,
    pronunc_variant INT,
    timestamp TEXT NOT NULL
);
CREATE TABLE pronunciations
(
    p_id INTEGER PRIMARY KEY AUTOINCREMENT,
    pronunciation INTEGER NOT NULL,
    phone TEXT NOT NULL
);
CREATE TABLE word_to_pronunciation
(
    wp_id INTEGER PRIMARY KEY AUTOINCREMENT,
    word INTEGER NOT NULL,
    pronunciation INTEGER NOT NULL
);
CREATE TABLE phone_error
(
    pe_id INTEGER PRIMARY KEY AUTOINCREMENT,
    speaker INTEGER NOT NULL,
    word INTEGER NOT NULL,
    listener INTEGER NOT NULL,
    pronunc_variant INTEGER NOT NULL,
    word_phoneme INTEGER NOT NULL,
    error_type INTEGER NOT NULL,
    error_detail TEXT,
    timestamp TEXT NOT NULL
);\n";
//COMMIT";
//    $sqlcommand.="BEGIN TRANSACTION;";
    $pronid=0;
    $wordcount=0;

    foreach ($speakers as $speaker) {
        $sqlcommand.="INSERT INTO speakers (name) VALUES ('$speaker');\n";
        //print "<br>$sqlcommand";
        //$dbcreation = $db->exec($sqlcommand);
        //if ($dbcreation) {
        //    print "<br>Speaker $speaker added successfully";
        //}
    }
    foreach ($words as $word) {
            $wordcount++;
            $sqlcommand.="INSERT INTO words (word) VALUES ('".$word["word"]."');\n";

            $speakercount=0;
            foreach ($speakers as $speaker) {
                $speakercount++;
                $wavfilename="wav/$speaker/".$word["file"].".wav";
                #print "checking $wavfilename\n";
                if (file_exists( $wavfilename )) {
                    $sqlcommand.="INSERT INTO speakers_words (speaker,word,filename) VALUES ('$speakercount','$wordcount','$wavfilename');\n";
                }
            }

            foreach ($word["phonemes"] as $pronunc) {

                $sqlcommand.="INSERT INTO word_to_pronunciation (word, pronunciation) VALUES ('$wordcount','".++$pronid."');\n";

                foreach ($pronunc as $ph) {
                    $sqlcommand.="INSERT INTO pronunciations (pronunciation, phone) VALUES ('".$pronid."','$ph');\n";
            }
            /*if ($dbcreation) {
                print "<br>Pronunciation"; print_r($pronunc); print  " added successfully";
            }*/

        }
   }


//    $speakers=Array(
//        "heikki_apina",
//        "heikki_oma", ...

    $sqlcommand.="COMMIT;";
    print "<pre>$sqlcommand </pre>";

    $dbcreation = $db->exec($sqlcommand);
    if ($dbcreation) {
        print "<br>Lots of stuff added successfully to the database";
        print "<br>Reload to start running the test.";
        exit (0);
    } else
    {
        print "<br>Filling DB did not go like in Stromsso";
    }

}

else {


    $db = new SQLite3($dbfile);
    $db->exec("pragma synchronous = off;");


}


?>