<?php

# These are the truly local settings:
#Include information about the URL where test is run:

#$base_url="http://localhost:8008/";
$base_url="http://spa1.org.aalto.fi/Sites/pronunciation_evaluation/";

# and where is the database file for the results:
#$dbfile="/l/rkarhila/scratch/PhpstormProjects/SIAK_subjective_pronunciation_scoring_test/db/pronunc.db";
$dbfile="/var/www/db/pronunc.db";


# Path for saving results; Obviously read/write permissions are required for this:
# I don't think this is used for anything at the moment?
$resultdir="/l/rkarhila/scratch/SIAK_subjective_pronunciation_scoring_test_results/";


# For a debug run on the command line:
# (From https://b3z13r.wordpress.com/2011/05/16/passing-values-from-the-commandline-to-php-by-getpost-method/)
if (!isset($_SERVER["HTTP_HOST"])) {
    # script is not interpreted due to some call via http, so it must be called from the commandline
    parse_str($argv[1], $_GET); // use $_POST instead if you want to
}



$testurl=$base_url."/pronunc_quality_test.php";


# Url for the script snippet that checks if a nickname is in use or not
$usercheckurl=$base_url."/functions/check_user.php";


# Urls of the database access functions:

$add_evaluation_url=$base_url."/functions/add_to_database.php";

$switch_pronunciation_url=$base_url."/functions/switch_pronunciation_in_database.php";

$add_phone_error_url=$base_url."/functions/add_phone_error_to_database.php";




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




$phonememap= Array( "@" => "ə",
    "a" => "æ",
    "aa" => "ɑː",
    "aa" => "ɑr",
    "ai" => "aɪ",
    "b" => "b",
    "ch" => "tʃ",
    "d" => "d",
    "dh" => "ð",
    "e" => "ɛ",
    "ei" => "eɪ",
    "eir" => "ɛər",
    "f" => "f",
    "g" => "ɡ ",
    "h" => "h",
    "i" => "ɪ",
    "i@" => "ɪər",
    "ii" => "iː",
    "jh" => "dʒ",
    "k" => "k",
    "l" => "l",
    "lw" => "l",
    "m" => "m",
    "n" => "n",
    "ng" => "ŋ",
    "o" => "ɒ",
    "oo" => "ɔː",
    "ou" => "oʊ",
    "ow" => "aʊ",
    "p" => "p",
    "r" => "r",
    "@@r" => "ɜː",
    "s" => "s",
    "sh" => "ʃ",
    "t" => "t",
    "th" => "θ",
    "u" => "ʊ",
    "uh" => "ʌ",
    "uu" => "uː",
    "v" => "v",
    "w" => "w",
    "y" => "j",
    "z" => "z",
    "zh" => "ʒ",
    "l!" => "əl",
    "Other" => "Other"  );


if (!file_exists($dbfile)) {


$speakers=Array(
    "heiap",
    "heiom",
    "janap",
    "janom",
    "kalap",
    "kalom",
    "kasap",
    "kasom",
    "lauap",
    "lauom",
    "leiap",
    "leiom",
    "marap",
    "marom",
    "merap",
    "merom",
    "mikap",
    "mikom",
    "minap",
    "minom",
    "reiap",
    "reiom",
    "sepap",
    "sepom",
    "timap",
    "timom",
    "tuuap",
    "tuuom",
    "ullap",
    "ullom",
    "ulpap",
    "ulpom",
    "robom",
    "petom"
);


$words=Array(
    "001" => Array( "file" => "01", "word" => "too",	"interesting" => 0,  "phonemes" => Array( Array ("t", "uu"))),
    "002" => Array( "file" => "02", "word" => "safe",	"interesting" => 0,  "phonemes" => Array( Array ("s", "ei", "f"))),
    "003" => Array( "file" => "03", "word" => "pig",	"interesting" => 0,  "phonemes" => Array( Array ("p", "i", "g"))),
    "004" => Array( "file" => "04", "word" => "pie",	"interesting" => 0,  "phonemes" => Array( Array ("p", "ai"))),
    "005" => Array( "file" => "05", "word" => "teeth",	"interesting" => 0,  "phonemes" => Array( Array ("t", "ii", "th"))),
    "006" => Array( "file" => "06", "word" => "fan",	"interesting" => 0,  "phonemes" => Array( Array ("f", "a", "n"))),
    "007" => Array( "file" => "07", "word" => "that",	"interesting" => 1,  "phonemes" => Array( Array ("dh", "a", "t"), Array("dh", "@", "t"))),
    "008" => Array( "file" => "08", "word" => "first",	"interesting" => 0,  "phonemes" => Array( Array ("f", "@@r", "s", "t"))),
    "009" => Array( "file" => "09", "word" => "fin",	"interesting" => 0,  "phonemes" => Array( Array ("f", "i", "n"))),
    "010" => Array( "file" => "10", "word" => "art",	"interesting" => 1,  "phonemes" => Array( Array ("aa", "t"))),
    "011" => Array( "file" => "11", "word" => "full",	"interesting" => 0,  "phonemes" => Array( Array ("f", "u", "lw"))),
    "012" => Array( "file" => "12", "word" => "fur",	"interesting" => 0,  "phonemes" => Array( Array ("f", "@@r"))),
    "013" => Array( "file" => "13", "word" => "van",	"interesting" => 0,  "phonemes" => Array( Array ("v", "a", "n"))),
    "014" => Array( "file" => "14", "word" => "were",	"interesting" => 0,  "phonemes" => Array( Array ("w", "@@r"), Array("w", "@"))),
    "015" => Array( "file" => "15", "word" => "ant",	"interesting" => 0,  "phonemes" => Array( Array ("a", "n", "t"))),
    "016" => Array( "file" => "16", "word" => "thirst",	"interesting" => 1,  "phonemes" => Array( Array ("th", "@@r", "s", "t"))),
    "017" => Array( "file" => "17", "word" => "save",	"interesting" => 0,  "phonemes" => Array( Array ("s", "ei", "v"))),
    "018" => Array( "file" => "18", "word" => "fork",	"interesting" => 0,  "phonemes" => Array( Array ("f", "oo", "k"))),
    "019" => Array( "file" => "19", "word" => "ship",	"interesting" => 1,  "phonemes" => Array( Array ("sh", "i", "p"))),
    "020" => Array( "file" => "20", "word" => "wish",	"interesting" => 0,  "phonemes" => Array( Array ("w", "i", "sh"))),
    "021" => Array( "file" => "21", "word" => "fox",	"interesting" => 0,  "phonemes" => Array( Array ("f", "o", "k", "s"))),
    "022" => Array( "file" => "22", "word" => "off",	"interesting" => 0,  "phonemes" => Array( Array ("o", "f"))),
    "023" => Array( "file" => "23", "word" => "sees",	"interesting" => 0,  "phonemes" => Array( Array ("s", "ii", "z"))),
    "024" => Array( "file" => "24", "word" => "arm",	"interesting" => 0,  "phonemes" => Array( Array ("aa", "m"))),
    "025" => Array( "file" => "25", "word" => "cheese",	"interesting" => 0,  "phonemes" => Array( Array ("ch", "ii", "z"))),
    "026" => Array( "file" => "26", "word" => "wall",	"interesting" => 0,  "phonemes" => Array( Array ("w", "oo", "lw"))),
    "027" => Array( "file" => "27", "word" => "vet",	"interesting" => 1,  "phonemes" => Array( Array ("v", "e", "t"))),
    "028" => Array( "file" => "28", "word" => "run",	"interesting" => 0,  "phonemes" => Array( Array ("r", "uh", "n"))),
    "029" => Array( "file" => "29", "word" => "maths",	"interesting" => 0,  "phonemes" => Array( Array ("m", "a", "th", "s"))),
    "030" => Array( "file" => "30", "word" => "page",	"interesting" => 1,  "phonemes" => Array( Array ("p", "ei", "jh"))),
    "031" => Array( "file" => "31", "word" => "mouth",	"interesting" => 0,  "phonemes" => Array( Array ("m", "ow", "th"), Array("m", "ow", "dh"))),
    "032" => Array( "file" => "32", "word" => "back",	"interesting" => 0,  "phonemes" => Array( Array ("b", "a", "k"))),
    "033" => Array( "file" => "33", "word" => "zoo",	"interesting" => 0,  "phonemes" => Array( Array ("z", "uu"))),
    "034" => Array( "file" => "34", "word" => "age",	"interesting" => 0,  "phonemes" => Array( Array ("ei", "jh"))),
    "035" => Array( "file" => "35", "word" => "chin",	"interesting" => 0,  "phonemes" => Array( Array ("ch", "i", "n"))),
    "036" => Array( "file" => "36", "word" => "am",	"interesting" => 0,  "phonemes" => Array( Array ("a", "m"), Array("am"))),
    "037" => Array( "file" => "37", "word" => "bark",	"interesting" => 0,  "phonemes" => Array( Array ("b", "aa", "k"))),
    "038" => Array( "file" => "38", "word" => "one",	"interesting" => 0,  "phonemes" => Array( Array ("w", "uh", "n"))),
    "039" => Array( "file" => "39", "word" => "sheep",	"interesting" => 0,  "phonemes" => Array( Array ("sh", "ii", "p"))),
    "040" => Array( "file" => "40", "word" => "do",	"interesting" => 0,  "phonemes" => Array( Array ("d", "uu"), Array("d", "ou"))),
    "041" => Array( "file" => "41", "word" => "chair",	"interesting" => 1,  "phonemes" => Array( Array ("ch", "eir"))),
    "042" => Array( "file" => "42", "word" => "sea",	"interesting" => 0,  "phonemes" => Array( Array ("s", "ii"))),
    "043" => Array( "file" => "43", "word" => "sheet",	"interesting" => 1,  "phonemes" => Array( Array ("sh", "ii", "t"))),
    "044" => Array( "file" => "44", "word" => "year",	"interesting" => 0,  "phonemes" => Array( Array ("y", "i@"))),
    "045" => Array( "file" => "45", "word" => "ten",	"interesting" => 0,  "phonemes" => Array( Array ("t", "e", "n"))),
    "046" => Array( "file" => "46", "word" => "moose",	"interesting" => 0,  "phonemes" => Array( Array ("m", "uu", "s"))),
    "047" => Array( "file" => "47", "word" => "wash",	"interesting" => 0,  "phonemes" => Array( Array ("w", "o", "sh"))),
    "048" => Array( "file" => "48", "word" => "log",	"interesting" => 0,  "phonemes" => Array( Array ("l", "o", "g"))),
    "049" => Array( "file" => "49", "word" => "thin",	"interesting" => 0,  "phonemes" => Array( Array ("th", "i", "n"))),
    "050" => Array( "file" => "50", "word" => "shine",	"interesting" => 0,  "phonemes" => Array( Array ("sh", "ai", "n"))),
    "051" => Array( "file" => "51", "word" => "ice",	"interesting" => 0,  "phonemes" => Array( Array ("ai", "s"))),
    "052" => Array( "file" => "52", "word" => "eat",	"interesting" => 0,  "phonemes" => Array( Array ("ii", "t"))),
    "053" => Array( "file" => "53", "word" => "finn",	"interesting" => 0,  "phonemes" => Array( Array ("f", "i", "n"))),
    "054" => Array( "file" => "54", "word" => "bag",	"interesting" => 0,  "phonemes" => Array( Array ("b", "a", "g"))),
    "055" => Array( "file" => "55", "word" => "long",	"interesting" => 0,  "phonemes" => Array( Array ("l", "o", "ng"))),
    "056" => Array( "file" => "56", "word" => "set",	"interesting" => 0,  "phonemes" => Array( Array ("s", "e", "t"))),
    "057" => Array( "file" => "57", "word" => "zoos",	"interesting" => 1,  "phonemes" => Array( Array ("z", "uu", "z"))),
    "058" => Array( "file" => "58", "word" => "clothe",	"interesting" => 1,  "phonemes" => Array( Array ("k", "l", "ou", "dh"))),
    "059" => Array( "file" => "59", "word" => "thick",	"interesting" => 0,  "phonemes" => Array( Array ("th", "i", "k"))),
    "060" => Array( "file" => "60", "word" => "pull",	"interesting" => 0,  "phonemes" => Array( Array ("p", "u", "lw"))),
    "061" => Array( "file" => "61", "word" => "lead",	"interesting" => 0,  "phonemes" => Array( Array ("l", "ii", "d"), Array("l", "e", "d"))),
    "062" => Array( "file" => "62", "word" => "seed",	"interesting" => 0,  "phonemes" => Array( Array ("s", "ii", "d"))),
    "063" => Array( "file" => "63", "word" => "mats",	"interesting" => 0,  "phonemes" => Array( Array ("m", "a", "t", "s"))),
    "064" => Array( "file" => "64", "word" => "read",	"interesting" => 0,  "phonemes" => Array( Array ("r", "ii", "d"), Array("r", "e", "d"))),
    "065" => Array( "file" => "65", "word" => "chip",	"interesting" => 0,  "phonemes" => Array( Array ("ch", "i", "p"))),
    "066" => Array( "file" => "66", "word" => "west",	"interesting" => 0,  "phonemes" => Array( Array ("w", "e", "s", "t"))),
    "067" => Array( "file" => "67", "word" => "mouse",	"interesting" => 0,  "phonemes" => Array( Array ("m", "ow", "s"))),
    "068" => Array( "file" => "68", "word" => "fat",	"interesting" => 0,  "phonemes" => Array( Array ("f", "a", "t"))),
    "069" => Array( "file" => "69", "word" => "with",	"interesting" => 0,  "phonemes" => Array( Array ("w", "i", "dh"))),
    "070" => Array( "file" => "70", "word" => "and",	"interesting" => 0,  "phonemes" => Array( Array ("a", "n", "d"), Array("@", "n", "d"))),
    "071" => Array( "file" => "71", "word" => "write",	"interesting" => 0,  "phonemes" => Array( Array ("r", "ai", "t"))),
    "072" => Array( "file" => "72", "word" => "these",	"interesting" => 0,  "phonemes" => Array( Array ("dh", "ii", "z"))),
    "073" => Array( "file" => "73", "word" => "move",	"interesting" => 0,  "phonemes" => Array( Array ("m", "uu", "v"))),
    "074" => Array( "file" => "74", "word" => "feet",	"interesting" => 0,  "phonemes" => Array( Array ("f", "ii", "t"))),
    "075" => Array( "file" => "75", "word" => "she",	"interesting" => 0,  "phonemes" => Array( Array ("sh", "ii"))),
    "076" => Array( "file" => "76", "word" => "watch",	"interesting" => 1,  "phonemes" => Array( Array ("w", "o", "ch"))),
    "077" => Array( "file" => "77", "word" => "right",	"interesting" => 0,  "phonemes" => Array( Array ("r", "ai", "t"))),
    "078" => Array( "file" => "78", "word" => "ride",	"interesting" => 0,  "phonemes" => Array( Array ("r", "ai", "d"))),
    "079" => Array( "file" => "79", "word" => "once",	"interesting" => 0,  "phonemes" => Array( Array ("w", "uh", "n", "s"))),
    "080" => Array( "file" => "80", "word" => "walk",	"interesting" => 0,  "phonemes" => Array( Array ("w", "oo", "k"))),
    "081" => Array( "file" => "81", "word" => "leaf",	"interesting" => 0,  "phonemes" => Array( Array ("l", "ii", "f"))),
    "082" => Array( "file" => "82", "word" => "was",	"interesting" => 0,  "phonemes" => Array( Array ("w", "o", "z"), Array("w", "@", "z"))),
    "083" => Array( "file" => "83", "word" => "shoe",	"interesting" => 0,  "phonemes" => Array( Array ("sh", "uu"))),
    "084" => Array( "file" => "84", "word" => "wrong",	"interesting" => 0,  "phonemes" => Array( Array ("r", "o", "ng"))),
    "085" => Array( "file" => "85", "word" => "coat",	"interesting" => 0,  "phonemes" => Array( Array ("k", "ou", "t"))),
    "086" => Array( "file" => "86", "word" => "gold",	"interesting" => 0,  "phonemes" => Array( Array ("g", "ou", "lw", "d"))),
    "087" => Array( "file" => "87", "word" => "choose",	"interesting" => 1,  "phonemes" => Array( Array ("ch", "uu", "z"))),
    "088" => Array( "file" => "88", "word" => "chat",	"interesting" => 0,  "phonemes" => Array( Array ("ch", "a", "t"))),
    "089" => Array( "file" => "89", "word" => "three",	"interesting" => 1,  "phonemes" => Array( Array ("th", "r", "ii"))),
    "090" => Array( "file" => "90", "word" => "pool",	"interesting" => 0,  "phonemes" => Array( Array ("p", "uu", "lw"))),
    "091" => Array( "file" => "91", "word" => "cheap",	"interesting" => 0,  "phonemes" => Array( Array ("ch", "ii", "p"))),
    "092" => Array( "file" => "92", "word" => "piece",	"interesting" => 0,  "phonemes" => Array( Array ("p", "ii", "s"))),
    "093" => Array( "file" => "93", "word" => "pea",	"interesting" => 0,  "phonemes" => Array( Array ("p", "ii"))),
    "094" => Array( "file" => "94", "word" => "buy",	"interesting" => 0,  "phonemes" => Array( Array ("b", "ai"))),
    "095" => Array( "file" => "95", "word" => "tree",	"interesting" => 0,  "phonemes" => Array( Array ("t", "r", "ii"))),
    "096" => Array( "file" => "96", "word" => "vest",	"interesting" => 0,  "phonemes" => Array( Array ("v", "e", "s", "t"))),
    "097" => Array( "file" => "97", "word" => "girl",	"interesting" => 0,  "phonemes" => Array( Array ("g", "@@r", "lw"))),
    "098" => Array( "file" => "98", "word" => "fit",	"interesting" => 0,  "phonemes" => Array( Array ("f", "i", "t"))),
    "099" => Array( "file" => "99", "word" => "close",	"interesting" => 0,  "phonemes" => Array( Array ("k", "l", "ou", "s"), Array("k", "l", "ou", "z"))),
    "100" => Array( "file" => "100", "word" => "peas",	"interesting" => 0,  "phonemes" => Array( Array ("p", "ii", "z"))),
    "101" => Array( "file" => "101", "word" => "goat",	"interesting" => 0,  "phonemes" => Array( Array ("g", "ou", "t"))),
    "102" => Array( "file" => "102", "word" => "those",	"interesting" => 0,  "phonemes" => Array( Array ("dh", "ou", "z"))),
    "103" => Array( "file" => "103", "word" => "seat",	"interesting" => 0,  "phonemes" => Array( Array ("s", "ii", "t"))),
    "104" => Array( "file" => "104", "word" => "pack",	"interesting" => 0,  "phonemes" => Array( Array ("p", "a", "k"))),
    "105" => Array( "file" => "105", "word" => "teas",	"interesting" => 0,  "phonemes" => Array( Array ("t", "ii", "z"))),
    "106" => Array( "file" => "106", "word" => "sit",	"interesting" => 0,  "phonemes" => Array( Array ("s", "i", "t"))),
    "107" => Array( "file" => "107", "word" => "then",	"interesting" => 1,  "phonemes" => Array( Array ("dh", "e", "n"))),
    "108" => Array( "file" => "108", "word" => "eyes",	"interesting" => 0,  "phonemes" => Array( Array ("ai", "z"))),
    "109" => Array( "file" => "109", "word" => "big",	"interesting" => 0,  "phonemes" => Array( Array ("b", "i", "g"))),
    "110" => Array( "file" => "110", "word" => "fall",	"interesting" => 0,  "phonemes" => Array( Array ("f", "oo", "lw"))),
    "111" => Array( "file" => "111", "word" => "wheel",	"interesting" => 0,  "phonemes" => Array( Array ("w", "ii", "lw"))),
    "112" => Array( "file" => "112", "word" => "heart",	"interesting" => 0,  "phonemes" => Array( Array ("h", "aa", "t"))),
    "113" => Array( "file" => "113", "word" => "which",	"interesting" => 0,  "phonemes" => Array( Array ("w", "i", "ch"))),
    "114" => Array( "file" => "114", "word" => "shows",	"interesting" => 0,  "phonemes" => Array( Array ("sh", "ou", "z"))),
    "115" => Array( "file" => "115", "word" => "park",	"interesting" => 0,  "phonemes" => Array( Array ("p", "aa", "k"))),
    "116" => Array( "file" => "116", "word" => "curl",	"interesting" => 0,  "phonemes" => Array( Array ("k", "@@r", "lw"))),
    "117" => Array( "file" => "117", "word" => "fine",	"interesting" => 0,  "phonemes" => Array( Array ("f", "ai", "n"))),
    "118" => Array( "file" => "118", "word" => "yet",	"interesting" => 0,  "phonemes" => Array( Array ("y", "e", "t"))),
    "119" => Array( "file" => "119", "word" => "wet",	"interesting" => 0,  "phonemes" => Array( Array ("w", "e", "t"))),
    "120" => Array( "file" => "120", "word" => "socks",	"interesting" => 0,  "phonemes" => Array( Array ("s", "o", "k", "s"))),
    "121" => Array( "file" => "121", "word" => "pays",	"interesting" => 0,  "phonemes" => Array( Array ("p", "ei", "z"))),
    "122" => Array( "file" => "122", "word" => "hat",	"interesting" => 0,  "phonemes" => Array( Array ("h", "a", "t"))),
    "123" => Array( "file" => "123", "word" => "light",	"interesting" => 0,  "phonemes" => Array( Array ("l", "ai", "t"))),
    "124" => Array( "file" => "124", "word" => "wool",	"interesting" => 0,  "phonemes" => Array( Array ("w", "u", "lw"))),
    "125" => Array( "file" => "125", "word" => "chick",	"interesting" => 1,  "phonemes" => Array( Array ("ch", "i", "k"))),
    "126" => Array( "file" => "126", "word" => "at",	"interesting" => 0,  "phonemes" => Array( Array ("a", "t"), Array("@", "t"))),
    "127" => Array( "file" => "127", "word" => "eight",	"interesting" => 0,  "phonemes" => Array( Array ("ei", "t"))),
    "128" => Array( "file" => "128", "word" => "than",	"interesting" => 0,  "phonemes" => Array( Array ("dh", "a", "n"), Array("dh", "@", "n"))),
    "129" => Array( "file" => "129", "word" => "bee",	"interesting" => 0,  "phonemes" => Array( Array ("b", "ii"))),
    "130" => Array( "file" => "130", "word" => "of",	"interesting" => 0,  "phonemes" => Array( Array ("o", "v"), Array("@", "v"))),
    "131" => Array( "file" => "131", "word" => "two",	"interesting" => 0,  "phonemes" => Array( Array ("t", "uu"))),
    "132" => Array( "file" => "132", "word" => "cold",	"interesting" => 0,  "phonemes" => Array( Array ("k", "ou", "lw", "d"))),
    "133" => Array( "file" => "133", "word" => "soon",	"interesting" => 0,  "phonemes" => Array( Array ("s", "uu", "n"))),
    "134" => Array( "file" => "134", "word" => "there",	"interesting" => 0,  "phonemes" => Array( Array ("dh", "eir"))),
    "135" => Array( "file" => "135", "word" => "fun",	"interesting" => 0,  "phonemes" => Array( Array ("f", "uh", "n"))),
    "136" => Array( "file" => "136", "word" => "rest",	"interesting" => 0,  "phonemes" => Array( Array ("r", "e", "s", "t"))),
    "137" => Array( "file" => "137", "word" => "ones",	"interesting" => 0,  "phonemes" => Array( Array ("w", "uh", "n", "z"))),
    "138" => Array( "file" => "138", "word" => "leave",	"interesting" => 0,  "phonemes" => Array( Array ("l", "ii", "v"))),
    "139" => Array( "file" => "139", "word" => "be",	"interesting" => 0,  "phonemes" => Array( Array ("b", "ii"))),
    "140" => Array( "file" => "140", "word" => "real",	"interesting" => 0,  "phonemes" => Array( Array ("r", "i@", "lw"), Array("r", "ei", "aa", "lw"))),
    "141" => Array( "file" => "141", "word" => "hard",	"interesting" => 0,  "phonemes" => Array( Array ("h", "aa", "d"))),
    "142" => Array( "file" => "142", "word" => "june",	"interesting" => 1,  "phonemes" => Array( Array ("jh", "uu", "n"))),
    "143" => Array( "file" => "143", "word" => "it",	"interesting" => 0,  "phonemes" => Array( Array ("i", "t"))),
);







    $db = new SQLite3($dbfile);

    $db->exec("pragma synchronous = off;");

    $sqlcommand="BEGIN TRANSACTION;
CREATE TABLE words
(
    w_id INTEGER PRIMARY KEY AUTOINCREMENT,
    word TEXT NOT NULL,
    interesting INTEGER NOT NULL
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
            $sqlcommand.="INSERT INTO words (word,interesting) VALUES ('".$word["word"]."',".$word["interesting"].");\n";

            $speakercount=0;
            foreach ($speakers as $speaker) {
                $speakercount++;
                $wavfilename="wav/$speaker-".$word["file"].".wav";
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
