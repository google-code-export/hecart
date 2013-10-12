<?php
$str = '<img src="/img/data/prd/k2/ru/{pic}" />';
echo('<div style="text-align:center">');
for ($i = 1; $i <= 24; $i++)
{
	$num = $i < 10 ? "0{$i}" : $i;
	echo str_replace('{pic}', "i_{$num}.jpg", $str), "\n";
}
echo('</div>');
?>