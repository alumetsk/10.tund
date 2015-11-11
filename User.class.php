<?php

class User{
	
	private $connection; // saab kasutada ainult klassi sees (private)
	
		//klassi loomisel (new User)
		function __construct($mysqli) {
		
		// this tähendab selle klassi muutujat
		$this->connection = $mysqli;
	}
		
	function createUser($create_email, $hash){
		
		//teen objekti
		//seal on error, ->id ja -> message
		//või success ja sellel on ->message
		$response = new StdClass();
		
		//kas selline email on juba olemas
		$stmt = $this->connection->prepare("SELECT id FROM user_sample Where email=?");
		$stmt->bind_param("s", $create_email);
		$stmt->bind_result($id);
		$stmt->execute();
		
		
		//kas sain rea andmeid
		if($stmt->fetch()){
			
			//annan errori, et selline email on olemas
			$error = new StdClass();
			$error->id = 0;
			$error->message = "Sellise e-postiga kasutaja on juba olemas!";
			
			$response->error = $error;
			
			return $response;
			
		}
		
		//panen eelmise päringu kinni
		$stmt->close;
		
		$stmt = $this->connection->prepare("INSERT INTO user_sample (email, password) VALUES (?,?)");
		$stmt->bind_param("ss", $create_email, $hash);
		
		//sai edukalt salvestatud
		if($stmt->execute()){
			$success = new StdClass();
			$success->message = "Kasutaja edukalt loodud!";
			
			$response->success = $success;
			
			
		}else{
			
			//midagi läks katki
			$error = new StdClass();
			$error->id = 1;
			$error->message = "Midagi läks katki!";
			
			$response->error = $error;
			
			
		}
		
		
		$stmt->close();
		return $response;
		
		
	}
	function loginUser($email, $hash){
		
		//teen objekti
		//seal on error, ->id ja -> message
		//või success ja sellel on ->message
		$response = new StdClass();
		
		//kas selline email on juba olemas
		$stmt = $this->connection->prepare("SELECT id FROM user_sample Where email=?");
		$stmt->bind_param("s", $email);
		$stmt->bind_result($id);
		$stmt->execute();
		
		// ei olnud sellist e-posti
		//!- tähjendab ei ole
		if(!$stmt->fetch()){
			$error = new StdClass();
			$error->id = 0;
			$error->message = "Sellise e-postiga kasutajat ei ole!";
			
			$response->error = $error;
			return $response;
			
		}
		
		//***********************
		//*******OLULINE*********
		//***********************
		$stmt->close();
		
		
		$stmt = $this->connection->prepare("SELECT id, email FROM user_sample WHERE email=? AND password=?");
		$stmt->bind_param("ss", $email, $hash);
		$stmt->bind_result($id_from_db, $email_from_db);
		$stmt->execute();
		
		if($stmt->fetch()){
			// kõik õige
			$success = new StdClass();
			$success->message = "Kasutaja edukalt sisse logitud!";
	
			$response->success = $success;
			
			$user = new StdClass();
			$user->id = $id_from_db;
			$user->email = $email_from_db;
			
			$response->user = $user;
			
		}else{
			// parool vale
			$error = new StdClass();
			$error->id = 1;
			$error->message = "Parool on vale!";
			$response->error = $error;
		}
		return $response;
		
		$stmt->close();
	}
	
} ?>
	