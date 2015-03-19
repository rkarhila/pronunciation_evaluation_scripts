<?php
//error_reporting(E_ALL);
//ini_set("display_errors", 1);

$GLOBALS['DEBUGGING'] = False;


include_once 'pronunc_quality_conf.php';
include_once 'functions/getheader.php';
include_once 'functions/cleanlistenername.php';
include_once 'functions/filefunctions.php';
include_once 'functions/safeshuffle.php';




# Handle first the preparations, then render page accordingly;
# Has this listener evaluated something already?

$listener=cleanlistener($_GET["listener"]);
$sentencelist;

$wkey=cleanlistener($_GET["wkey"]);
$word=cleanlistener($_GET["word"]);

if ($GLOBALS['DEBUGGING'] == True) {
    print "<pre>";
    print_r ($_POST);
    print_r ($_GET);
    print "</pre>";
}



if ($listener) {


    $sqlcommand="SELECT l_id from listeners where name='$listener';";

    $listener_id= $db->querySingle($sqlcommand);

    if (!$listener_id && isset( $_GET["gender"] ) ) {

        $gender=$_GET["gender"];
        $agegroup=$_GET["agegroup"];
        $langgroup=$_GET["langgroup"];

        $timestamp=date('Y-m-d h:i:s', time());

        $sqlcommand="INSERT INTO listeners (name, gender, age, language_bg, timestamp) VALUES  ('$listener','$gender','$agegroup','$langgroup', '$timestamp'); ";

        $db->exec($sqlcommand);

        $sqlcommand="SELECT l_id from listeners where name='$listener';";

        $listener_id= $db->querySingle($sqlcommand);


    }



}

print getheader();


if ($GLOBALS['DEBUGGING']) {
    print "<pre>";
    print_r($_POST);
    print "</pre>";
    print "<div id=logging style='position: absolute;left: 2px;top: 2px; z-index: 100;background-color: bisque;opacity: 0.8;filter: alpha(opacity=80);font-size: x-small'>".  "PHP version ".phpversion()."<br></div>";
}




#######      Entry page    #######################


// We'll check first if we have an email or not:

if (!$listener) {

    // Check if the database exists:
    //print sqlite_libversion();
    //$dbhandle = sqlite_open($dbfile, 0666, $error);


    print "<div class=divmain> ";




    print "$introduction
   <br><br>
   <div class=divlistenerinfo >
   <h3><a href='#' onclick='returninglistener.hidden=true;newlistener.hidden=false;'>New listener</a></h3>
   <div  id=newlistener hidden>
   <p>

   Your identifier: <br> (For example email address or nickname;<br> Must contain at least 4 alphanumeric characters)<br>
   
   <form name='ff0' method=get action=$testurl>
    <input id=listenername type=text name=listener oninput='checkForm()'>
    <div id=listenerwarning></div><br>
    Gender:<br>
    <input id=r1 type='radio' name=gender value='f' onClick='checkForm();'>Female
    <input id=r2 type='radio' name=gender value='m' onClick='checkForm();'>Male
    <input id=r3 type='radio' name=gender value='o' onClick='checkForm();'>Other/unspecified
    <br><br>
    Age: <br>
    <select id=ageselect name=agegroup  onchange='checkForm()'>
    <option name=zero value=zero default> please select... </option>";
    foreach ($agegroups as $arr) {
        print "<option name=".$arr["val"]." value=".$arr["val"]."> ".$arr["label"]." </option>";
    };

    print "</select>
    <br><br>
    Choose the option that best describes your current English language background:<br>
    <select id=langselect name=langgroup  onchange='checkForm()'>
    <option name=zero value=zero default> please select... </option>";

    foreach ($languagebackground as $arr) {
        print "<option name=".$arr["val"]." value=".$arr["val"]."> ".$arr["label"]." </option>";
    };

    print "</select>
    <br><br> 

   <input id=submitbutton0 type=submit disabled>    </form> 
   </div>
   </div>
   <br>
   <div class=divlistenerinfo >
   <h3><a href='#' onclick='returninglistener.hidden=false;newlistener.hidden=true;'>Returning listener</a></h3>
   <div  id=returninglistener hidden>
<p>
    Your email address or nickname:<br>

    <form name='ff2' method=get action=$testurl>
       <input id=returninglistenername type=text name=listener oninput='checkForm()'><br><br>
       <input id=submitbutton1 type=submit disabled>    </form> 
    </div>
    </div>
<p>$visitortext
 </div>



 <script type='text/JavaScript'>

var xmlhttp;
xmlhttp=new XMLHttpRequest();
var listenersort;

 function checkForm() {

  listenerstatus=checklistener(listenername.value);

   if (listenerstatus=='old')
   { 
    listenerwarning.innerHTML='Nickname '+listenername.value+' taken';
   }
   else listenerwarning.innerHTML='';


  if ( (r1.checked || r2.checked || r3.checked ) && (ageselect.value != 'zero') && (langselect.value != 'zero' )  && listenerstatus == 'new')
  {
   submitbutton0.disabled=false;
  }
   else
  {
   submitbutton0.disabled=true;
  }
  if (checklistener(returninglistenername.value) == 'old')
  {
   submitbutton1.disabled=false;
  }
   else
  {
   submitbutton1.disabled=true;
  }
 }";

    print "function checklistener(lisname) {

   name=lisname.replace(/\W/g,'');";

    print "
   if (name.length > 3) 
   {
    xmlhttp.open('GET','${usercheckurl}?listener='+name ,false);
    xmlhttp.send();
    return xmlhttp.responseText;
   }
   else { return false; }
 }
 </script> ";


}

#######      Word selection page    #######################

elseif (!$wkey) {



    print "<table>";
    print "<tr><td colspan=11>
            <p>Select sample to evaluate from the list below. Please start with the 16 so-called interesting words.
            <p>If you are feeling bored and have already finished watching the paint dry and done with reading the phone book,
            you can also evaluate some of the other words.
            <p>You can quit the test at any time and return later with your identifier (<i>$listener</i>).
            <br><br>
            </td></tr>";

    print "<th colspan=11 bgcolor='#ffdddd'>The interesting words:</th>";
    print "<tr>";
    $ct = -1;

    $sqlcommand = "SELECT * FROM words WHERE interesting=1;";

    $queryres = $db->query($sqlcommand);
    #$foo = $queryres->fetcharray();


    $dd=0;
    while ($arr = $queryres->fetcharray()) {

        if ($dd++ < 145) {

        $word = $arr['word'];
        $wkey = $arr['w_id'];


        # Put the words in three columns ie. switch row after every 3 items displayed
        if ($ct++ % 4 == 3) {
            print "</tr><tr>";
        }

        print "<td>" . sprintf("%03u", ($wkey)) . " <a href='${testurl}?wkey=$wkey&word=$word&listener=$listener'>$word</a></td>";

        print "<td>";

        $sqlcommand = "SELECT count(*) FROM evaluations WHERE word='$wkey' AND listener='$listener_id' AND evaluation>0;";
        //print "<br>$sqlcommand";
        try {
            $num_evals = $db->querySingle($sqlcommand);
        } catch (Exception $exception) {
            print $exception->getMessage();
        }
        $sqlcommand = "SELECT count(*) FROM speakers_words WHERE word='$wkey';";
        //print "<br>$sqlcommand";
        try {
            $num_spoken = $db->querySingle($sqlcommand);
        } catch (Exception $exception) {
            print $exception->getMessage();
        }

        //print $num_evals . "/" . $num_spoken;

        if ($num_spoken > 0) {
            $done_bar = floor(50 * $num_evals / $num_spoken);
            //print "floor(50 * $num_evals / $num_spoken) = $done_bar";
            $to_be_done_bar = 50 - $done_bar;
            print "<table border=1 margin=0 cellspacing=0 cellpadding=0><tr>";
            if ($done_bar > 0) {
                print "<td width=$done_bar bgcolor='#98fb98'>&nbsp;</td>";
            }
            if ($to_be_done_bar > 0) {
                print "<td width=$to_be_done_bar>&nbsp;</td>";
            }

            print "</tr></table>";
        }
        print "</td><td width=50>&nbsp;</td>";
        }
    }
    print "</tr>";


    print "<th colspan=11 bgcolor='#ffdddd'>The not so interesting words:</th>";
    print "<tr>";
    $ct = -1;

    $sqlcommand = "SELECT * FROM words WHERE interesting=0;";

    $queryres = $db->query($sqlcommand);
    #$foo = $queryres->fetcharray();


    $dd=0;
    while ($arr = $queryres->fetcharray()) {

        if ($dd++ < 145) {

            $word = $arr['word'];
            $wkey = $arr['w_id'];


            # Put the words in three columns ie. switch row after every 3 items displayed
            if ($ct++ % 4 == 3) {
                print "</tr><tr>";
            }

            print "<td>" . sprintf("%03u", ($wkey)) . " <a href='${testurl}?wkey=$wkey&word=$word&listener=$listener'>$word</a></td>";

            print "<td>";

            $sqlcommand = "SELECT count(*) FROM evaluations WHERE word='$wkey' AND listener='$listener_id' AND evaluation>0;";
            //print "<br>$sqlcommand";
            try {
                $num_evals = $db->querySingle($sqlcommand);
            } catch (Exception $exception) {
                print $exception->getMessage();
            }
            $sqlcommand = "SELECT count(*) FROM speakers_words WHERE word='$wkey';";
            //print "<br>$sqlcommand";
            try {
                $num_spoken = $db->querySingle($sqlcommand);
            } catch (Exception $exception) {
                print $exception->getMessage();
            }

            //print $num_evals . "/" . $num_spoken;

            if ($num_spoken > 0) {
                $done_bar = floor(50 * $num_evals / $num_spoken);
                //print "floor(50 * $num_evals / $num_spoken) = $done_bar";
                $to_be_done_bar = 50 - $done_bar;
                print "<table border=1 margin=0 cellspacing=0 cellpadding=0><tr>";
                if ($done_bar > 0) {
                    print "<td width=$done_bar bgcolor='#98fb98'>&nbsp;</td>";
                }
                if ($to_be_done_bar > 0) {
                    print "<td width=$to_be_done_bar>&nbsp;</td>";
                }

                print "</tr></table>";
            }
            print "</td><td width=50>&nbsp;</td>";
        }
    }
    print "</tr></table>";


}

#######      Test page    #######################


else {

    #$listenerdir=cleanlistener($listener);

    $testurl .= "?listener=${listener}";


    print "<div class=divmain>";


    print "<p>Below you will find audio samples from a reference speaker and some amateurs attempting
to utter the same word either freely or by imitating an audio sample.

<p>
Please evaluate the quality of pronunciation for each sound file as follows:
<ul>

<li> In the column <b>Overall quality</b>, please select the overall quality of pronunciation on a scale of 1 (worst) to 5 (best).</li>
 <!--<li>Please select the overall quality of the pronunciation on a scale of 1 (worst) to 5 (best).</li>
  <li>All evaluations are immediately sent to the server. No form submissions are necessary.</li>
 <li>For some words there are several allowed pronunciations. You can point out the
 pronunciation variant by clicking on it. If the audio bears no resemblance to any of the pronunciations, you can select \"other\". </li>
  <li> You can also mark parts of the word where the pronunciation goes
  <span style='background-color:greenyellow'>right (default)</span>,
  <span style='background-color:yellow'>slightly wrong</span>   or
  <span style='background-color:orangered'> completely wrong</span>
 by clicking on the phoneme. Clicking on a phoneme will cycle the categories: <span class=goodphone>f</span>
  &#8658; <span class=badphone>f</span> &#8658; <span class=realbadphone>f</span> &#8658; <span class=goodphone>f</span>
  </li>-->
  <li>In the column <b>Pronunciation variant</b>, you can indicate the parts of the word where the pronunciation quality is
   <span style='background-color:greenyellow'>correct (default)</span>,
  <span style='background-color:yellow'>slightly wrong</span>   or
  <span style='background-color:orangered'> completely wrong</span>
  by clicking on the phoneme (written in <a href='http://upload.wikimedia.org/wikipedia/en/8/8f/IPA_chart_%28C%292005.pdf'>phonetic alphabet (pdf)</a>),
   such as ʃ, θ or iː. Clicking on a phoneme will cycle through the three categories:
  <span class=goodphone>f</span>
  &#8658; <span class=badphone>f</span> &#8658; <span class=realbadphone>f</span> &#8658; <span class=goodphone>f</span><br><br>
<li> Some words can be pronounced in  several different ways. However, if you feel that the audio does not resemble
enough the correct pronunciation, you can select <b>other</b> in the Pronunciation variant column.
</li>
<li>All evaluations are immediately sent to the server. Therefore, you need not separately submit the survey form.</li>
  </ul>


";


//    $intro = "<p>
//Please listen to them and mark which ones are unacceptably bad
//and need to improved, and categorise the most obvious problem in those sentences. After rating the utterances submit your answers with the submit button.";

    print "</p>";

    print "<p>";
    print "<form name='ff1' method='post'  action='$testurl' onsubmit='beforeSubmit();'>";

//    print "<datalist id=numbers>
//	        <option>1</option>
//	        <option>2</option>
//	        <option>3</option>
//	        <option>4</option>
//	        <option>5</option>
//        </datalist>";


    print "Current word: " . $word;

    print "<table align=center>";
    $n = 0;


    $phonearrays = Array();
    $pronids=Array();

    $sqlcommand = "SELECT pronunciation FROM word_to_pronunciation WHERE word=$wkey;";

    $queryres = $db->query($sqlcommand);

    while ($arr = $queryres->fetcharray()) {
        $pronkey = $arr['pronunciation'];
        $pron=Array();
        $sqlcommand2 = "SELECT phone FROM pronunciations WHERE pronunciation=$pronkey;";
        $queryres2 = $db->query($sqlcommand2);
        while ($arr2 = $queryres2->fetcharray()) {
            array_push($pron, $arr2['phone']);
        }
        array_push($phonearrays, Array('pronunc' => $pron, 'id' => $pronkey));
    }

    // Add the zero pronunciation option:
    array_push($phonearrays, Array('pronunc' => Array('Other'), 'id' => "-1"));

    $sqlcommand = "SELECT S.* FROM speakers_words S WHERE S.word='$wkey' AND S.speaker in (select s_id from speakers where interesting=1) ORDER BY RANDOM();";
    $queryres1 = $db->query($sqlcommand);

    $sqlcommand = "SELECT S.* FROM speakers_words S WHERE S.word='$wkey' AND S.speaker in (select s_id from speakers where interesting=0) ORDER BY RANDOM();";
    $queryres2 = $db->query($sqlcommand);

    $sqlcommand = "SELECT S.* FROM speakers_words S WHERE S.word='$wkey' AND S.speaker in (select s_id from speakers where interesting=-1) ORDER BY RANDOM();";
    $queryres3 = $db->query($sqlcommand);

    $queryress = Array($queryres1,$queryres2,$queryres3);
    $tableheader=Array("Speakers of primary interest", "Speakers of secondary interest", "Speakers of less interest");
    #$foo = $queryres->fetcharray();

    $javascript = "";

    //$dd=0;
    for ($foo=0;$foo<3;$foo++) {


        print "<tr><td colspan=7 bgcolor=#ffdddd align=center><b>".$tableheader[$foo]."</b></td></tr>";
        print "<tr><td colspan=2></td><td align=center> <small>Overall quality</small></td><td align=center><small>Pronunciation variants</small></td></tr>";
        $queryres=$queryress[$foo];

        while ($arr = $queryres->fetcharray()) {

            ++$n;
    //
    //        if ($n < 4) {
            if (true) {

                $radioid = "word" . $wkey . "_speaker" . $speaker;
                $sliderid= "word" . $wkey . "_speaker" . $speaker . "_slider";
                $sliderbox_id= "word" . $wkey . "_speaker" . $speaker . "_sliderbox";

                $filename = $arr['filename'];
                $speaker = $arr['speaker'];

                print "<tr class=samplerow><td>".$arr['speaker']." </td><td width=45><audio id='audio_$n' src=$filename onended='enable_playbutton_$n();' ></audio>";

                print "<button type=button id='playbutton_$n' onclick='playing_$n()'> &#9658; play </button> </td>";


                $sqlcommand3 = "SELECT evaluation FROM evaluations WHERE word='$wkey' AND speaker='$speaker' and listener='$listener_id';";

                $boxstyle="activebox";

                $def = $db->querySingle($sqlcommand3);
                if (!$def){
                    $def = 1;
                    $boxstyle="passivebox";
                }


                print "<td>
                           <table class='$boxstyle' id='${sliderbox_id}'><tr>
                           <td align=middle class='sliderbox1'>1</td>
                           <td align=middle class='sliderbox2'>2</td>
                           <td align=middle class='sliderbox3'>3</td>
                           <td align=middle class='sliderbox4'>4</td>
                           <td align=middle class='sliderbox5'>5</td></tr>";
                print "<tr><td colspan=5><input id=$sliderid type=range list=numbers min=1 max=5 step='0.1' value=$def onclick=\"updateSlider('$sliderid', '$speaker', '${sliderbox_id}');\"/></td></tr></table>
                           </td>
                           </span>";
                print "<td>
                           ";


                $nn = 0;
    //


                // Check if there's an active pronunciation for this speaker&word&listener combination:

                $sqlcommand4 = "SELECT pronunc_variant FROM evaluations WHERE word='$wkey' AND speaker='$speaker' and listener='$listener_id';";

                $pronvariant=$db->querySingle($sqlcommand4);


                foreach ($phonearrays as $arr3) {

                    $phonearray=$arr3['pronunc'];
                    $pronunc_id= $arr3['id'];

                    #print_r($phonearray);
    //                if (++$nn == 1)
    //                    $checked = "checked='checked'";
    //                else
    //                    $checked = "";

                    $pronboxid = "word" . $wkey . "_speaker" . $speaker . "_pr" . $pronunc_id ;
                    $phoneid = "word" . $wkey . "_speaker" . $speaker . "_pr" . $pronunc_id . "_ph1";


                    $nn++; // The counter is there for nice indexing;


                    if ($pronvariant == $pronunc_id) {
                        $activeclass='activepronunciation';
                        $active=true;
                        $sqlcommand5 = "SELECT * FROM phone_error WHERE word='$wkey' AND speaker='$speaker' and listener='$listener_id';";
                        $errorarray=$db->query($sqlcommand5);
                        $checked=' checked ';

                    }
                    else {
                        $activeclass='passivepronunciation';
                        $active=false;
                        $checked='';
                    }


                    print "\n<span class='$activeclass' id='${pronboxid}'>$nn";
                    print "<input type='radio' id='${pronboxid}_radio' name='${radioid}_radio' $checked";
                    print " value='${pronboxid}' onclick=\"activatepronunciation('$radioid','$phoneid','$speaker');\"/>";
                    $nnn = 0;
                    foreach ($phonearray as $phone) {
                        $nnn++;
                        $phoneid = $pronboxid ."_ph$nnn";

                        if ($active) {
                            $sqlcommand6 = "SELECT error_type FROM phone_error WHERE word='$wkey' AND speaker='$speaker' and listener='$listener_id' and word_phoneme='$nnn';";
                            $errtype=$db->querySingle($sqlcommand6);

                            if ($errtype==-2) {
                                $phoneclass='realbadphone';
                            }
                            else if ($errtype==-1) {
                                $phoneclass='badphone';
                            }
                            else {
                                $phoneclass='goodphone';
                            }

                        }else {
                            $phoneclass='passivephone';
                        }
                        print "\n<span id='$phoneid' class='$phoneclass' onclick=\"cyclecolor('$radioid','$phoneid','$speaker','$pronunc_id', '$nnn');\">" . $phonememap[$phone] . "</span>";

                    }

                    print "</span>";
                }
    //            $nn++;
    //            $pronunc_id= "0";
    //
    //            $radioid = "word" . $wkey . "_speaker" . $speaker;
    //            $pronboxid = "word" . $wkey . "_speaker" . $speaker . "_pr" . $pronunc_id ;
    //            $phoneid = "word" . $wkey . "_speaker" . $speaker . "_pr" . $pronunc_id . "_ph1";
    //
    //            print "\n<span class=passivepronunciation id='$pronboxid' >$nn<input type='radio' id='${pronboxid}_radio' name='${radioid}_radio'  onclick='activatepronunciation('$radioid','$phoneid')' />
    //                    <span  id='$phoneid' class='passivephone' onclick=\"cyclecolor('$radioid','$phoneid');\">Other</span> </span>";
    //
    //            print "</span>";

                print "</td>";

                print "</tr>";

                $javascript.= "
                    //////////// Sample $n handling /////////////////

                    function playing_$n() {
                       if (audio_$n.currentTime != 0) {
                        audio_$n.pause();
                        audio_$n.currentTime = 0;
                        enable_playbutton_$n() ;
                       }
                       else {
                         audio_$n.play();
                         playbutton_$n.innerHTML=' <font color=#00cc00>&#8718; stop</font> ';
                         playbutton_$n.disabled=false;
                      }
                    }

                    function enable_playbutton_$n() {
                       playbutton_$n.disabled=false;
                       audio_$n.currentTime = 0;
                       playbutton_$n.innerHTML='&#9658; play ';
                    }

                    ";

            }
        }
    }
    $samplesonthispage = $n;

    print "</table>";

//    print $intro;
//
//    print "<table>";
//    print "<tr><th></th><th>Sample</th><th>Quality</th></tr>";
//
//
//
//    print "</table>";
//    print "<p align=center>";
//    print "<input type=hidden name=submissiontag value=submitted>";
//    print "<input type=hidden name=timePassed value=0>";
//    print "<input type=submit name=submitbutton hidden></form>";



    print "<p align=\"center\"><a href=".$testurl.">Return to word selection page</a></p>";

    print "</div>";


    print "\n\n<script type='text/JavaScript'>

var green='<font color=#00cc00>';
var red='<font color=#cc0000>';
var endgreen='</font>';
var endred='</font>';";

    print $javascript;



    print "\n\n
    function logthis(\$morelog) {
        //document.getElementById('logging').innerHTML= document.getElementById('logging').innerHTML+\$morelog +'\\n<br>';
    }
    ";



    print "\n\n



    function updateSlider(\$sliderid, \$speaker_id, \$box_id) {


        if ( (document.getElementById(\$box_id)).className=='passivebox') {
            (document.getElementById(\$box_id)).className='activebox';
        }

        var xmlhttp;
        xmlhttp=new XMLHttpRequest();

        var \$sliderval = document.getElementById( \$sliderid ).value;

        xmlhttp.open('GET','${add_evaluation_url}?listener_id=${listener_id}&speaker_id='+\$speaker_id+'&word_id=${wkey}&rating='+\$sliderval ,false);
        xmlhttp.send();
        logthis( xmlhttp.responseText);

    }";


    print "\n\n

    function activatepronunciation(\$radioid, \$id, \$speaker_id,\$pronunc) {

        logthis('id: '+\$id);

        // Get all the pronunciation objects
        // by finding the children of the grandparent

        logthis('clicking: '+ document.getElementById(\$id).parentNode.id+'_radio');
        document.getElementById( document.getElementById(\$id).parentNode.id +'_radio').checked = true;;

        var \$childArray = document.getElementById( document.getElementById(\$id).parentNode.id ).parentNode.childNodes;


        // Cycle through (almost) all the pronunciations and mark them passive

        for(var i = 1; i < \$childArray.length; i++){

            // Just make sure not to set the current one passive...
            if (\$childArray[i].id && \$childArray[i].id != \$radioid ) {
                //logthis('passivating '+ \$childArray[i].id);
                document.getElementById(\$childArray[i].id).className='passivepronunciation';

                \$nn=1;

                // Mark all the phones of this pronunciation as passive:

                while ( !!document.getElementById(\$childArray[i].id+ '_ph'+\$nn) ) {
                    document.getElementById(\$childArray[i].id+ '_ph'+\$nn).className='passivephone';
                    \$nn++;
                }

            }
        }

        // Activate this pronunciation:
        logthis('activate '+document.getElementById(\$id).parentNode.id);
        document.getElementById(\$id).parentNode.className = 'activepronunciation';

        // Activating the pronunciation: Mark all the phones as 'good':

        var \$n = 1;
        var \$parentid = document.getElementById(\$id).parentNode.id;
        while ( !!document.getElementById(  \$parentid +'_ph'+\$n )) {
             document.getElementById( \$parentid +'_ph'+\$n).className ='goodphone';
             \$n++;
        }

        //Send information about the changed pronunciation to the server:
        var xmlhttp;
        xmlhttp=new XMLHttpRequest();

        xmlhttp.open('GET','${switch_pronunciation_url}?listener_id=${listener_id}&speaker_id='+\$speaker_id+'&word_id=${wkey}&pronunc='+\$pronunc ,false);
        xmlhttp.send();
        logthis( xmlhttp.responseText);

    }";


    print "\n\n

    function cyclecolor(\$radioid, \$id, \$speaker_id,\$pronunc, \$word_phoneme) {

        logthis('Has been called: cyclecolor('+\$radioid + ', '+\$id+', '+\$speaker_id+', '+\$pronunc+', '+\$word_phoneme+')');

        var \$n='foo';
        if (document.getElementById(\$id).parentNode.className == 'passivepronunciation') {
            logthis('Going to activatepronunciation('+\$radioid + ', '+\$id+')');
            activatepronunciation(\$radioid, \$id, \$speaker_id,\$pronunc);

        }
        else {
            var \$errortype, \$errordetail;

            if (document.getElementById(\$id).className == 'goodphone') {
                document.getElementById(\$id).className = 'badphone';
                \$errortype = '-1';
                \$errordetail = 'bad';
            }
            else {
                if (document.getElementById(\$id).className == 'badphone') {
                    document.getElementById(\$id).className = 'realbadphone';
                    \$errortype = '-2';
                    \$errordetail = 'really_bad';
                }
                else {
                    if (document.getElementById(\$id).className == 'realbadphone') {
                        document.getElementById(\$id).className = 'goodphone';
                        \$errortype = '0';
                        \$errordetail = 'ok';

                    }
                }
            }


        //Send information about the changed pronunciation to the server:

        var xmlhttp;
        xmlhttp=new XMLHttpRequest();

        xmlhttp.open('GET','${add_phone_error_url}?listener_id=${listener_id}&speaker_id='+\$speaker_id+'&word_id=${wkey}&pronunc_variant='+\$pronunc+'&word_phoneme='+\$word_phoneme+'&error_type='+\$errortype+'&error_detail='+\$errordetail, false);
        xmlhttp.send();
        logthis( xmlhttp.responseText);


        }

    }
    ";


    print "
function disable_playbuttons() {
";
    $n = 0;
    foreach ($speakers as $foo) {
        $n++;
        print "
        playbutton_$n.disabled=true;";
    }

    print "
    }



</script>";




}

print "<div class=spacer> </div>";

print "
<div class=divfooter>
<p class=divfooterp>$footertext
Last update to script: " . date('F d Y h:i A P T e', filemtime('pronunc_quality_test.php'));
print "</p></div>";

print "</body></hmtl>";





?>
