<?php
// Membuat variabel, ubah sesuai dengan nama host dan database pada hosting 
$host	= "localhost";
$user	= "userdatabase";
$pass	= "passdatabas";
$db	= "anticonnectinet";

// mysqli untuk membuat koneksi 	
$mysqli = new mysqli($host, $user, $pass, $db);

?>
