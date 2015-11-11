<?php
	require_once("functions.php");
	require_once("InterestsManager.class.php");
	
	if(!isset($_SESSION["logged_in_user_id"])){
		header("Location: login.php");
		
		//see katkestab faili edasise lugemise
		exit();
	}
	
	//kasutaja tahab välja logida
	if(isset($_GET["logout"])){
		//aadressireal on olemas muutuja logout
		
		//kustutame kõik session muutujad ja peatame sessiooni
		session_destroy();
		
		header("Location: login.php");
	}
	
	//uus instants klassist
	$InterestsManager = new InterestsManager($mysqli, $_SESSION["logged_in_user_id"]);
	
	//aadressirealt muutuja
	if(isset($_GET["new_interest"])){
	
		$add_new_response = $InterestsManager->addInterest($_GET["new_interest"]);
		
	}
	
	
 ?>
 
 
 
<p>
	Tere, <?=$_SESSION["logged_in_user_email"];?> 
	<a href="?logout=1"> Logi välja <a> 
</p>

<h2>Lisa huviala</h2>

  <?php if(isset($add_new_response->error)): ?>
  
  <p style="color:red;"><?=$add_new_response->error->message;?></p>
  
  <?php elseif(isset($add_new_response->success)): ?>
  
  <p style="color:green;"><?=$add_new_response->success->message;?></p>
  
  <?php endif; ?>
<form>
<p>
	<input name="new_interest" > 
	<input type="submit" >
 
 </p>
 </form>