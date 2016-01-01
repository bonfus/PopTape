Da: 
<select name="start" id="start">
    <?php
    $day='';
    for ($t = 0; $t < $tics; $t++) {
        $v = ($endmod6h-$t)*(3600 * $hoursblocks);
        if ($day != date('d-m-Y', $v))
        {
            if ($day == '') {
                $day = date('d-m-Y', $v);
                echo "<optgroup label='" . $day . "'>\n";
            } else {
                $day = date('d-m-Y', $v);
                echo "</optgroup>";
                echo "<optgroup label='" . $day . "'>\n";
            }
        }
        if ($v == $iniTime) {
            echo "<option value='$v' selected>" . date('d-m-Y H:i', $v) ."</option>\n";
        } else {
            echo "<option value='$v'>" . date('d-m-Y H:i', $v) ."</option>\n";
        }
    }
    if ($day != '') {
        echo "</optgroup>";
    }
    ?>
</select>
<br />
A: 
<select name="end" id="end" disabled>
    <option value='0' disabled>Seleziona un intervallo</option>
    <?php
    $day='';
    for ($t = 0; $t < $tics; $t++) {
        $v = ($endmod6h-$t)*(3600 * $hoursblocks);
        if ($day != date('d-m-Y', $v))
        {
            if ($day == '') {
                $day = date('d-m-Y', $v);
                echo "<optgroup label='" . $day . "'>\n";
            } else {
                $day = date('d-m-Y', $v);
                echo "</optgroup>";
                echo "<optgroup label='" . $day . "'>\n";
            }
        }        
        if ($v == $endTime) {
            echo "<option value='$v' disabled selected>" . date('d-m-Y H:i', $v) ."</option>\n";
        } else {
            echo "<option value='$v' disabled>" . date('d-m-Y H:i', $v) ."</option>\n";
        }
    }
    if ($day != '') {
        echo "</optgroup>";
    }
    ?>
</select>  
