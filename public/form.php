<?php 

// $email = $_POST['email'];
// $name = $_POST['name'];
$email = $_GET['email'];
$name = $_GET['name'];

echo '<p>Dziękujemy za zapisanie się do naszego newslettera.</p>';
echo '<p>Twoje dane:</p>';

echo 'Email: '.$email;
echo '<br>';
echo 'Imie: '.$name;

?>