<?php
class InterestsManager{
	
	//InterestsManager.class.php
	
	private $connection;
	private $user_id;
	
	//kui tekitan new siis käivitatakse see funktsioon
	function __construct($mysqli, $user_id_from_session){
		
		// selle klassi muutuja
		$this->connection = $mysqli;
		$this->user_id = $user_id_from_session;
		
		echo "Huvialade haldus käivitatud, kasutaja=".$this->user_id;
		
	}
	
	function addInterest(){
		
		$response = new StdClass();
		
		//kas selline email on juba olemas
		$stmt = $this->connection->prepare("SELECT id FROM interests Where name=?");
		$stmt->bind_param("s", $new_interest);
		$stmt->bind_result($id);
		$stmt->execute();
		
		
		//kas sain rea andmeid
		if($stmt->fetch()){
			
			//annan errori, et selline email on olemas
			$error = new StdClass();
			$error->id = 0;
			$error->message = "Huviala <strong>".$new_interest."</strong> on juba olemas!";
			
			$response->error = $error;
			
			return $response;
			
		}
		
		//panen eelmise päringu kinni
		$stmt->close;
		
		$stmt = $this->connection->prepare("INSERT INTO interests (name) VALUES (?)");
		$stmt->bind_param("s", $new_interest);
		
		//sai edukalt salvestatud
		if($stmt->execute()){
			$success = new StdClass();
			$success->message = "Huviala edukalt lisatud!";
			
			$response->success = $success;
			
			
		}else{
			
			//midagi läks katki
			$error = new StdClass();
			$error->id = 1;
			$error->message = "Midagi läks katki!";
			
			$response->error = $error;
			
		
			
			}
		}
}

?>