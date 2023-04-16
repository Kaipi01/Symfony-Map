<?php 

$courses = array('Kurs: JAVA','Kurs: C++','Kurs: Excel','Kurs: Joomla','Kurs: PHP','Kurs: jQuery','Kurs: AJAX');

for ($i = 0; $i < sizeof($courses); $i++) {
    echo '<li>'.$courses[$i].'</li>';
}
?>