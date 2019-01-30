<?php
//login.php
require_once('User.class.php');
require_once('DBC.php');
require_once('Common.php');
require_once('php/lang/LangVars-en.php');
require_once('php/AjaxTableEditor.php');

class LoginPage extends Common
{
    function __construct()
    {
        global $user, $username, $password, $message;
        
        session_start();
        ob_start();
        
        if(isset($_SESSION['logged_in'])) :
            $user = unserialize($_SESSION['user']);
            $user->isLoggedIn = 1;
        else :
            $user = new User('');
            $user->isLoggedIn = 0;
        endif;
        
        $this->logRequest();
        
        $username = "";
        $password = "";
        $message = "";

        //check to see if they've submitted the login form
        if(isset($_POST['submit-login'])) { 
            $username = $_POST['username'];
            $password = $_POST['password'];

            $userTools = new UserTools();
            if($userTools->login($username, $password)){ 
            //successful login, redirect them to a page
                header("Location: menu.php");
            }else{
                $message = "Incorrect username/e-mail or password. Please try again.";
            }
        }
        else if(isset($_POST['reset-password'])) { 
            header("Location: ../data/go/reset.php?sc=y");
        }
        else if(isset($_POST['guest-login'])) { 
            header("Location: menu.php");
        }

        $this->displayHeaderHtml();
        $this->displayHtml();
        $this->displayFooterHtml();
    }

    public function displayHtml()
    {
        global $user, $username, $password, $message;
        
        if($message != "")
        {
            echo $message."<br/>";
        }
        ?>
        <h2>Log in:</h2>
        <form action="login.php" target="go2menu" method="post">
            <table class="menu-box">
            <tr>
                <td>Username:</td><td><input type="text" name="username" value="<?php echo $username; ?>" /></td>
            </tr>
            <tr>
                <td></td><td>(or e-mail address)</td>
            </tr>
            <tr>
                <td>Password:</td><td><input type="password" name="password" value="<?php echo $password; ?>" /><td/>
            </tr>
            <tr></tr>
            <tr>
                <td align="left"><input type="submit" value="Login" name="submit-login" style="color:blue" /></td>
                <td align="right"><input type="submit" value="Continue as Guest" name="guest-login" /></td>
            </tr>
            </table>
            <hr>
            <!--<input type="submit" value="Forgot Password?" name="reset-password" />-->
        </form>
        <br/>
        <div class="button-box">
               <a target="_top" class="button" href="https://wvresearch.org/data/go/reset.php?sc=y">Forgot Password?</a>
        <h5>(This uses <i>GO! <b>v1.0</b></i> to reset your password.)</h5>
        </div>
        <?php
    }
}
$lte = new LoginPage();
?>

