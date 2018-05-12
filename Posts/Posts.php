<?php
include("../config.php");
 mysqli_query($mysqli,"set names 'utf8'");
$result = mysqli_query($mysqli,"SELECT * FROM Posts ORDER BY time DESC LIMIT 0 , 5");
function humanTiming ($time)
{

    $time = time() - $time; // to get the time since that moment
    $time = ($time<1)? 1 : $time;
    $tokens = array (
        31536000 => 'year',
        2592000 => 'month',
        604800 => 'week',
        86400 => 'day',
        3600 => 'hour',
        60 => 'minute',
        1 => 'second'
    );

    foreach ($tokens as $unit => $text) {
        if ($time < $unit) continue;
        $numberOfUnits = floor($time / $unit);
        return $numberOfUnits.' '.$text.(($numberOfUnits>1)?'s':'')." ago";
    }

}
  while ($row=mysqli_fetch_row($result))
    {
	echo '<a href="#" class="list-group-item">
                                    <i class="'.$row[1].'"></i> '.$row[2].'
                                    <span class="pull-right text-muted small" dir="ltr"><em>&nbsp;&nbsp;'.humanTiming($row[3]).'</em>
                                    </span>
                                </a>';
    }
	mysqli_close($mysqli);
?>