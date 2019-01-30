<?php
require_once('UserTools.class.php');

class User {

	public $id;
	public $username;
	public $hashedPassword;
	public $peopleId;
	public $email;
	public $firstName;
	public $lastName;
	public $organization;
	public $department;
	public $isRegistered;
	public $isApproved;
	public $isManager;
	public $isOfficer;
	public $isAdmin;
	public $joinDate;
	public $isLoggedIn;

	//Constructor is called whenever a new object is created.
	//Takes an associative array with the DB row as an argument.
	function __construct($data) {
		if(empty($data)) {
			$this->isLoggedIn = 0;
      //error_log("User data was empty.\n",3,"/home/annech2/westvirginiaresearch.org/go2/error_log");
		}else {
      //error_log("User data: ".print_r($data,true),3,"/home/annech2/westvirginiaresearch.org/go2/error_log");
			$this->id = (isset($data['sec_ID'])) ? $data['sec_ID'] : "999";
			$this->username = (isset($data['sec_uname'])) ? $data['sec_uname'] : "name@institution.edu";
			$this->hashedPassword = (isset($data['sec_pword'])) ? $data['sec_pword'] : "";
			$this->peopleId = (isset($data['people_ID'])) ? $data['people_ID'] : "";
			$this->email = (isset($data['people_Email'])) ? $data['people_Email'] : "";
			$this->firstName = (isset($data['people_FirstName'])) ? $data['people_FirstName'] : "";
			$this->lastName = (isset($data['people_LastName'])) ? $data['people_LastName'] : "";
			$this->organization = (isset($data['inst_Name'])) ? $data['inst_Name'] : "";
			$this->department = (isset($data['people_Dept2'])) ? $data['people_Dept2'] : "";
			$this->isRegistered = (isset($data['is_registered']) && $data['is_registered'] == 1) ? 1 : 0;
			$this->isApproved = (isset($data['sec_granted']) && $data['sec_granted'] == 1) ? 1 : 0;
			$this->isOfficer = (isset($data['sec_SysRole']) && $data['sec_SysRole'] == 2) ? 1 : 0;
			$this->isManager = (isset($data['sec_SysRole']) && $data['sec_SysRole'] == 3) ? 1 : 0;
			$this->isAdmin = (isset($data['sec_SysRole']) && $data['sec_SysRole'] == 4) ? 1 : 0;
			$this->joinDate = (isset($data['people_datetimestamp'])) ? $data['people_datetimestamp'] : "";
			$this->isLoggedIn = 1;
		}
	}

	public function save($isNewUser = false) {
		//if the user is already registered and we're
		//just updating their info.
		if(!$isNewUser) {
			//set the user data array
			//$userData = array(
			//	"username" => "'$this->username'",
			//	"password" => "'$this->hashedPassword'"
			//);
			//update the row in the database
			//$db->update($userData, 'users', 'id = '.$this->id);

			//set the participant data array
			$participantData = array(
				"username" => "'$this->username'",
				"password" => "'$this->hashedPassword'",
				"first_name" => "'$this->firstName'",
				"last_name" => "'$this->lastName'",
				"email" => "'$this->email'",
				"organization" => "'$this->organization'",
				"department" => "'$this->department'",
				"is_registered" => "'$this->isRegistered'",
				"is_approved" => "'$this->isApproved'",
				"is_manager" => "'$this->isManager'",
				"is_officer" => "'$this->isOfficer'",
				"is_admin" => "'$this->isAdmin'",
			);
			//update the row in the database
			//$db->update($participantData, 'participants', 'id = '.$this->participantId);
		}else {
		//if the user is being registered for the first time.
			$participantData = array(
				"username" => "'$this->username'",
				"password" => "'$this->hashedPassword'",
				"first_name" => "'$this->firstName'",
				"last_name" => "'$this->lastName'",
				"email" => "'$this->email'",
				"organization" => "'$this->organization'",
				"department" => "'$this->department'",
				"is_registered" => "'$this->isRegistered'",
				"is_approved" => "'$this->isApproved'",
				"is_manager" => "'$this->isManager'",
				"is_officer" => "'$this->isOfficer'",
				"is_admin" => "'$this->isAdmin'",
			);
			//$this->participandId = $db->insert($ParticipantData, 'participants');

			//$userData = array(
			//	"username" => "'$this->username'",
			//	"password" => "'$this->hashedPassword'",
			//	"participant_id" => "'$this->participant_id'",
			// 	"join_date" => "'".date("Y-m-d H:i:s",time())."'"
			//);
			
			//$this->id = $db->insert($userData, 'users');
			$this->joinDate = time();
		}
		return true;
	}
}

?>
