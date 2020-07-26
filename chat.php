<?php
session_start();
?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "https://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="https://www.w3.org/1999/xhtml">
<head>
<title>IGLUUP</title>
<link type="text/css" rel="stylesheet" href="styles.css" />
<link rel="icon" type="image/png" href="img/igluu.png" />
</head>
<div id="portada"><img id="banner" src="img/bannerIgluupmediano.png"></div>
<div id="menu">
					<div id="menu_list">
						<ul>
							<li>
								<a href="index.html">Inicio</a>
							</li>
							<li>
								<a href="articulos.html">Articulos</a>
							</li>
							<li>
								<a href="">Tutoriales</a>
							</li>
							<li>
								<a href="index.php">Chat</a>
							</li>
							<li>
								<a href="">Acerca de</a>
							</li>
						</ul>
					</div>
				</div>
<?php

 
function loginForm(){
    echo'
    <div id="loginform">
    <form action="chat.php" method="post">
        <p>Ingrese un nombre para continuar:</p>
        <label for="name">Nombre:</label>
        <input type="text" name="name" id="name" />
        <input type="submit" name="enter" id="enter" value="Entrar" />
    </form>
    </div>';
}
 
if(isset($_POST['enter'])){
    if($_POST['name'] != ""){
        $_SESSION['name'] = stripslashes(htmlspecialchars($_POST['name']));
    }
    else{
        echo '<span class="error">Please type in a name</span>';
    }
}



if(!isset($_SESSION['name'])){
    loginForm();
}

else{

?>

<div id="wrapper">
    <div id="menu">
        <p class="welcome">Bienvenido, <b><?php echo $_SESSION['name']; ?></b></p>
        <p class="logout"><a id="exit" href="#">Salir del Chat</a></p>
        <div style="clear:both"></div>
    </div>    
    <div id="chatbox"> 

    <?php
    if(file_exists("log.html") && filesize("log.html") > 0){
        $handle = fopen("log.html", "r");
        $contents = fread($handle, filesize("log.html"));
        fclose($handle);
         
        echo $contents;
    }
    ?>
        
    </div>
     
    <form name="message" action="post.php">
        <input name="usermsg" type="text" id="usermsg" size="63" />
        <input name="submitmsg" type="submit"  id="submitmsg" value="Enviar" />
    </form>
</div>



<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.3/jquery.min.js"></script>
<script type="text/javascript">

// jQuery Document
$(document).ready(function(){
    //If user wants to end session
    $("#exit").click(function(){
        var exit = confirm("Are you sure you want to end the session?");
        if(exit==true){window.location = 'chat.php?logout=true';}      
    });
});

//If user submits the form
    $("#submitmsg").click(function(){   
        var clientmsg = $("#usermsg").val();
        $.post("post.php", {text: clientmsg});              
        $("#usermsg").attr("value", "");
        return false;
    });

//Load the file containing the chat log
    function loadLog(){     
        var oldscrollHeight = $("#chatbox").attr("scrollHeight") - 20; //Scroll height before the request
        $.ajax({
            url: "log.html",
            cache: false,
            success: function(html){        
                $("#chatbox").html(html); //Insert chat log into the #chatbox div   
                
                //Auto-scroll           
                var newscrollHeight = $("#chatbox").attr("scrollHeight") - 20; //Scroll height after the request
                if(newscrollHeight > oldscrollHeight){
                    $("#chatbox").animate({ scrollTop: newscrollHeight }, 'normal'); //Autoscroll to bottom of div
                }               
            },
        });
    }    
setInterval (loadLog, 1500);    
</script>
<?php
}
if(isset($_GET['logout'])){ 
     
    //Simple exit message
    $fp = fopen("log.html", 'a');
    fwrite($fp, "<div class='msgln'><i>El usuario ". $_SESSION['name'] ." Abandono el chat.</i><br></div>");
    fclose($fp);
     
    session_destroy();
    echo "<script>location.href='chat.php';</script>"; //Redirect the user
}

?>



</body>
</html>