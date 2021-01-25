<?php
require_once('User.class.php');
require_once('DBC.php');
require_once('Common.php');
require_once('php/lang/LangVars-en.php');
require_once('php/AjaxTableEditor.php');

class UserTools {

    function __construct()
    {
        global $user;
    }

    //Log the user in. First checks to see if the 
    //username and password match a row in the database.
    //If it is successful, set the session variables
    //and store the user object within.
    public function login($username, $password)
    {
        global $user;
        $hashedUsername = sha1($username);
        $hashedPassword = sha1($password);
        $query = "SELECT sec_ID, sec_uname, sec_pword, people_ID, people_Email, people_FirstName, people_LastName, inst_Name, people_Dept2, sec_granted, sec_SysRole, people_datetimestamp FROM tbl_MARS_Sec JOIN tbl_MARS_People ON tbl_MARS_Sec.sec_source_ID = tbl_MARS_People.people_ID JOIN tbl_MARS_Inst ON tbl_MARS_Inst.inst_ID = tbl_MARS_People.people_Dept1 WHERE (sec_uname = '".$hashedUsername."' OR sec_uname = '".$username."' OR tbl_MARS_People.people_Email = :email) AND sec_pword = '".$hashedPassword."'";
        $queryParams = array('email' => $username);
        $stmt = DBC::get()->prepare($query);
        $result = $stmt->execute($queryParams);
        if($result && $stmt->rowCount()==1)
        {
            $row = $stmt->fetch();
            error_log("User query data: ".print_r($row,true),3,"/home/annech2/westvirginiaresearch.org/go2/error_log");
            $user = new User($row);
            error_log("User data: ".print_r($user,true),3,"/home/annech2/westvirginiaresearch.org/go2/error_log");
            $_SESSION["user"] = serialize($user);
            $_SESSION["login_name"] = $username;
            $_SESSION["login_time"] = time();
            $_SESSION["logged_in"] = 1;
            return true;
        }else{
            error_log("User ".$username." not found or password incorrect.\n",3,"/home/annech2/westvirginiaresearch.org/go2/error_log");
            return false;
        }
    }
    
    //Log the user out. Destroy the session variables.
    public function logout() 
    {
        global $user;
        unset($_SESSION["user"]);
        unset($_SESSION["login_name"]);
        unset($_SESSION["login_time"]);
        unset($_SESSION["logged_in"]);
        $user = '';
    }

    //Check to see if a username exists.
    //This is called during registration to make sure all user names are unique.
    public function checkUsernameExists($username) 
    {
        $hashedUsername = sha1($username);
        $query = "SELECT sec_ID FROM tbl_MARS_Sec WHERE sec_uname = '".$hashedUsername."' OR sec_uname = :uname)";
        $queryParams = array('uname' => $username);
        $stmt = DBC::get()->prepare($query);
        $result = $stmt->execute($queryParams);
        if($result && $stmt->rowCount()>=1)
        {
            return false;
        }else{
            return true;
        }
    }
    
    //Get a user
    //Returns a User object. Takes the users id as an input
    public function get($id)
    {
        global $user;
        
        if(empty($id))
        {
            if(empty($user))
                $user = unserialize($_SESSION['user']);
            $requested_user = $user;
        }
        else
        {
            $query = "SELECT sec_ID, sec_uname, sec_pword, people_ID, people_Email, people_FirstName, people_LastName, inst_Name, people_Dept2, sec_granted, sec_SysRole, people_datetimestamp FROM tbl_MARS_Sec JOIN tbl_MARS_People ON tbl_MARS_Sec.sec_source_ID = tbl_MARS_People.people_ID JOIN tbl_MARS_Inst ON tbl_MARS_Inst.inst_ID = tbl_MARS_People.people_Dept1 WHERE people_ID = :id"; 
            $queryParams = array('id' => $id);
            $stmt = DBC::get()->prepare($query);
            $result = $stmt->execute($queryParams);
            if($result && $stmt->rowCount()==1)
            {
                $row = $stmt->fetch();
                $requested_user = new User($row);

            } else { // returns current user 
                $requested_user = unserialize($_SESSION['user']);
            }
        }
        return $requested_user;
    }    
}
?>
