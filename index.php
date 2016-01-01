<?php
function roundtime($timeval, $doceil=false, $blocks)
{
    if (!$doceil)
    {
        return floor($timeval / (3600 * $blocks))*(3600 * $blocks);
    } else {
        return ceil($timeval / (3600 * $blocks))*(3600 * $blocks);
    }
}

include 'settings.php';

$names = array();

?>

<!DOCTYPE html>
<html>
	<head>
		<title>
			PopTape
		</title>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="shortcut icon" href="img/favicon.ico"> 
        <link rel="apple-itouch-icon" href="img/favicon.png">
        <!-- <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script> -->
		<link rel="stylesheet" type="text/css" href="styles.css">
		<link rel="stylesheet" type="text/css" href="simplegrid.css">
        <link rel="stylesheet" href="uba/css/styles.css" />
	
	<!-- bin/jquery.slider.min.js -->        
		<link href='http://fonts.googleapis.com/css?family=Raleway:200,100,400' rel='stylesheet' type='text/css'>
		<!--[if gte IE 9]>
		<style type="text/css">
			.gradient {
				filter: none;
			}
		</style>
		<![endif]-->

        <script src="uba/js/jquery.min.js"></script>
        <script src="uba/js/jquery.ubaplayer.min.js"></script>

	</head>
	<body>
		<div class="header">
			<div class="headerContent grid grid-pad">
				<div class="col-1-2 header-content">
					<h1>
						PopTape
					</h1>
					<p>
						la tua cassettina di RadioPop 2.0.
					</p>
				</div>
				<div id="viewport"></div>
			</div>
		</div><!-- Grid 2/3 and 1/3-->
		<div class="grid grid-pad">
			<div class="features">
				<h1>
					Cassettine gi&agrave; pronte:
				</h1>
                <p>Queste sono le cassettine prodotte dagli utenti. Puoi scaricare una di queste o creare la tua pi&ugrave; sotto. Se possibile usa torrent!</p>
			</div>
        </div>
        <div class="grid grid-pad">
            <div class="col-1-1">
				<div class="content">
                    <ul class='tape'>        
<?php


//get all text files with a .txt extension.
$texts = glob($btdirectory . "*.torrent");

//print each file name
foreach($texts as $text)
{
?>
                        <li class='tape'>
                        <?php
                            $fname=preg_replace('/\.[^.]+$/','',$text);
                            $fname=substr($fname, strlen ($btdirectory.'POPTAPE_'));
                            $mp3name = 'POPTAPE_' . $fname . '.mp3';
                            $fname=str_replace('_', ' ', $fname);
                            $split = explode(' ', $fname,2);
                            if (count($split) > 1) {
                                $names[] = $split[1];
                            }
                        ?>
                            <?php echo $fname; ?> <br />
                            <a download href="<?php echo $text; ?>"><img class="tape" src="img/torrent.png">TORRENT</a> <a download href="torrents/<?php echo $mp3name; ?>"><img class="tape" src="img/mp3.png">MP3</a>
                        </li>
<?php
}
?>        
                    </ul>
                </div>
            </div>
        </div>
		<div class="grid grid-pad">
			<div class="features">
				<h1>
					Componi la tua cassettina!
				</h1>
                <p>Seleziona l'intervallo di tempo che vuoi registrare cliccando sul tasto REC. Devi cliccare due volte: con il primo click indichi l'inizio, con il secondo la fine. L'intervallo selezionato ha l'icona rossa! Puoi creare al massimo 2h di registrazione.</p>
			</div>
        </div>
<?php

//parse arguments

$iniTime = isset($_GET['start']) ? $_GET['start'] : null;
$endTime = isset($_GET['end']) ? $_GET['end'] : null;

$reasonableinit =  time() - (10 * 24 * 60 * 60);
$reasonableend =  time() + (1 * 24 * 60 * 60);

if (isset($iniTime) && !isset($endTime)) {

    if ($iniTime < $reasonableinit) {
        $iniTime = roundtime(time()-$hoursblocks*3600,false,$hoursblocks);
        $endTime = roundtime(time(),true,$hoursblocks);
    } else {
        $iniTime = roundtime($iniTime,false,$hoursblocks);
        $endTime = roundtime($iniTime+1,true,$hoursblocks);      
        echo "<!-- Init: " . $iniTime . ' end ' . $endTime .' -->';
    }
} elseif (isset($iniTime) && isset($endTime)) {
    if ($iniTime > $endTime)
    {
        $temp = $iniTime;
        $iniTime = $endTime;
        $endTime = $temp;
    }

    if ($iniTime < $reasonableinit || $endTime > $reasonableend) {
        $iniTime = roundtime(time()-$hoursblocks*3600,false,$hoursblocks);
        $endTime = roundtime(time(),true,$hoursblocks);
    } else {
        $iniTime = roundtime($iniTime,false,$hoursblocks);
        $endTime = roundtime($endTime,true,$hoursblocks);      
    }
} else {
    $iniTime = roundtime(time()-$hoursblocks*3600,false,$hoursblocks);
    $endTime = roundtime(time(),true,$hoursblocks);
}



//get all text files with a .txt extension.
$texts = glob($directory . "*.mp3");

$minepoch = -1;
$maxepoch = -1;

foreach($texts as $text)
{
    $epoch=preg_replace('/\.[^.]+$/','',$text);
    $epoch = (int) substr($epoch, strlen ('links/'));
    if ($minepoch == -1) {
        $minepoch=$epoch;
    } else {
        if ($epoch < $minepoch) {
            $minepoch=$epoch;
        }
    }
    if ($maxepoch == -1) {
        $maxepoch=$epoch;
    } else {
        if ($epoch > $maxepoch) {
            $maxepoch=$epoch;
        }
    }
}
$initmod6h = floor($minepoch / (3600 * $hoursblocks));
$endmod6h = ceil($maxepoch / (3600 * $hoursblocks));
$tics = 1+($maxepoch -$minepoch)/(3600 * $hoursblocks);

date_default_timezone_set('Europe/Rome');
    //echo date('d-m-Y H:i:s', $epoch);

?>
        <form method="get" action="index.php">
        <div class="grid grid-pad">
            <div class="col-1-3">
                Seleziona un intervallo: 
            </div>
            <div class="col-1-3">
                <div class="content">
                    <?php include 'select.php';?>                   
                </div>
            </div>
            <div class="col-1-3">
                <input type="submit" value="Aggiorna">
            </div>
        </div>
        </form>
        
<?php

date_default_timezone_set('Europe/Rome');
//print each file name
foreach($texts as $text)
{
$epoch=preg_replace('/\.[^.]+$/','',$text);
$epoch = substr($epoch, strlen ('links/'));    
if ($epoch < $iniTime || $epoch > $endTime) {
    continue;
}

?>
        <div class="grid grid-pad">
            <div class="col-1-3">
				<div class="content">
<?php
echo date('d-m-Y H:i:s', $epoch-600);
?>
                </div>
            </div>
            <div class="col-1-3">
                <div class="content">
                    <div id="ubaplayer"></div>
                    <ul class="ubaplayer-controls">
                        <li><a class="ubaplayer-button" href="<?php echo $text; ?>">Preview</a></li>
                    </ul>
                </div>
            </div>
            <div class="col-1-3">
                <div class="content ba button">
                    <img src="img/rec1.png" width="16" height="16" align="left"> <input type="hidden" name="point" value="<?php echo $epoch; ?>"> REC
                </div>
            </div>
        </div>
<?php
}
?>
        <form action="gen.php" method="post" id="form" name="form">
            <input type='hidden' name='interval' id='interval' value="">
            <div class="grid grid-pad">
                <div class="col-1-2">
                    <div class="content">
                        <input type="text" name="name" id="name" value="Nome cassettina" onFocus="value=''">
                    </div>
                </div>
                <div class="col-1-2">
                    <div class="content">
                        <input type="submit" value="Crea">
                    </div>
                </div>            
            </div>
        </form>
		<footer class="grid grid-pad">
			<div class="col-1-1">
				<div class="ftcontent">
					<p class="cred">Created by <a href="mailto:bonfus_chiocciolina_gmail_punto_com" title="Dallas">Bonfus</a><p class="ghcred">No copyright, no cookies! Hooray!</p>
				</div>
			</div>

<!--			
			<script type="text/javascript" src="prettify.js"></script>
			<script>prettyPrint();</script>
			<script src="http://cloud.github.com/downloads/Dhirajkumar/Demos/jquery-easing.min.js"></script>
-->
		</footer>
        <script>
            $(function(){
                $("#ubaplayer").ubaPlayer({
                codecs: [{name:"MP3", codec: 'audio/mpeg;'}]
                });
            });
        </script>

        <script type='text/javascript'>//<![CDATA[
        $(window).load(function(){
        $(".ba").click(function () {
        
        var clkval = $( this ).find("input").attr('value');
        
        var wasSelected=$(this).hasClass('selected');
        
        $(this).toggleClass('selected');
        if (!wasSelected) {
            $(this).find("img").attr("src", "img/rec2.png");
        } else {
            $(this).find("img").attr("src", "img/rec1.png");
        }
        
            $( ".ba" ).each(function( index ) {
                if ($( this ).find("input").attr('value') > clkval)
                {
                    
                    if (!wasSelected)
                    {
                        if (!$(this).hasClass('selected')) {
                            $(this).addClass('selected');
                            $( this ).find("img").attr("src", "img/rec2.png");
                        }
                    } else {
                        $(this).removeClass('selected');
                        $( this ).find("img").attr("src", "img/rec1.png");
                    }
                }
            });
        });
        });
        
$(document).ready(function() {

    $("form[name=form]").bind('submit',function() {
        var name = $("#name").val(); 
        if (name == "Nome cassettina" || name == "")
        {
            alert("Inserisci un nome!");
            return false;
        }
        
        var arr = <?php echo json_encode($names); ?>;
        
        name = name.toLowerCase(); 
        
        if ($.inArray(name, arr) !== -1)
        {
            alert("File gia' presente. Scegli un altro nome!");
            return false;            
        }
        
        var counter = 0;
        $('#interval').val('');
        
        $( ".ba" ).each(function( index ) {
            
            if ($(this).hasClass('selected')) {
                
                $('#interval').val($('#interval').val() + $( this ).find("input").attr('value') + ',' );
                counter = counter +1; 
            }
        });
        if (counter > 13)
        {
            alert("Intervallo troppo lungo!");
            return false;
        }          
        
        if ($('#interval').val() == "") {
            alert("Intervallo non selezionato!");
            return false;
        }
        return true;
    });
});
        
        //]]> 
        
        </script>
        <script src="select.js"></script>
	</body>
</html>
