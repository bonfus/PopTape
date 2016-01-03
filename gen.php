<?php

include 'settings.php';


// define variables and set to empty values
$name = $interval = $files = $errormsg = "";

function test_input($data) {
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  return $data;
}


if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $name = test_input($_POST["name"]);
  $interval = test_input($_POST["interval"]);
}


$present_files = glob($btdirectory . '*.mp3');

if ( $present_files !== false )
{
    $filecount = count( $present_files );
    if ( $filecount > 50 )
    {
        $errormsg = "Spazio insufficiente.";
    }
}


if ($name != "" && $interval != "" && $errormsg=="") {
    $name =  preg_replace('/[^(\x20-\x7F)]*/', '', $name);
    // remove spaces
    $name = str_replace(' ', '_', $name);
    $name = strtolower($name);
    
    $count = 0;
    $files = str_replace(",", " ", $interval, $count);
    if ($count > 12)
    {
        $errormsg = "Too many files.";
    }
    if ($count == 0)
    {
        $errormsg = "No files.";
    }

    $pattern = '/^[0-9 ]+$/';

    if ( ! preg_match ($pattern, $files) )
    {
        $errormsg = "Invalid files!";
    }
    
    date_default_timezone_set('Europe/Rome');
    $data = date('d-m-Y', explode(',', $interval)[0]);

    $name = $filenameprefix . $data . '_' . $name;
    
} else {
    $errormsg = "Invalid query.";
}


if ($errormsg == "")
{
    $escname = escapeshellarg($name);
    $escfiles = escapeshellarg($files);
    
    $command = $gencmd . " " . $escname . " " . $escfiles;
    
    $command = escapeshellcmd($command);
    
    $message=shell_exec( $command . " 2>&1");
    //print_r($message);
}


?>

<!DOCTYPE html>
<html>
	<head>
		<title>
			PopTape
		</title>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
        <!-- <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script> -->
		<link rel="stylesheet" type="text/css" href="styles.css">
		<link rel="stylesheet" type="text/css" href="simplegrid.css">
        <link rel="stylesheet" href="uba/css/styles.css" />
		<link href='http://fonts.googleapis.com/css?family=Raleway:200,100,400' rel='stylesheet' type='text/css'>
		<!--[if gte IE 9]>
		<style type="text/css">
			.gradient {
				filter: none;
			}
		</style>
		<![endif]-->
	</head>
	<body>      
        <?php 
        if ($errormsg == "")
        {
            $torrentname = $name . ".torrent";
            $mp3name = $name . ".mp3";
        ?>
		<div class="grid grid-pad">
			<div class="features">
				<h1>
					Fatto!
				</h1>
                <p>Questa &egrave; la tua cassettina. Se possibile usa torrent!</p>
			</div>
        </div>        
        <div class="grid grid-pad">
            <div class="col-1-1">
				<div class="content">
                    <ul>
                        <li class='tape'>
                        <?php
                            $fname=substr($name, strlen ($btdirectory . $filenameprefix));
                            $fname=str_replace('_', ' ', $fname);
                            $split = explode(' ', $fname,2);
                            if (count($split) > 1) {
                                $names[] = $split[1];
                            }
                        ?>
                            <?php echo $fname; ?> <br />
                            <a download href="<?php echo $btdirectory . $torrentname; ?>"><img class="tape" src="img/torrent.png">TORRENT</a> <a download href="<?php echo $btdirectory . $mp3name; ?>"><img class="tape" src="img/mp3.png">MP3</a>
                        </li>                        
                    </ul>
                </div>
            </div>
        </div>  
        <?php
        } else {
        ?>
		<div class="grid grid-pad">
			<div class="features">
				<h1>
					Errore!
				</h1>
                <p><?php echo $errormsg; ?></p>
			</div>
        </div>         
        <?php
        }
        ?>
        <div class="grid grid-pad">
            <div class="col-1-1">
				<div class="content">
                    <a href="http://poptape.piki.si" class='button'>Torna indietro</a>
                </div>
            </div>
        </div>
    </body>
</html>
