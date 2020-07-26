<?php
session_start();

    $segundos = 5;
    header("Refresh:".$segundos);

if(isset($_SESSION['name'])){
    $text = $_POST['text'];
     
    $fp = fopen("log.html", 'a');
    fwrite($fp, "<div class='msgln'>(".date("d-m-y, g:i a").") <b>".$_SESSION['name']."</b>: ".stripslashes(htmlspecialchars($text))."<br></div>");
    fclose($fp);
}
?>