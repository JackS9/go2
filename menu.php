<?php
/*
 * Mysql Ajax Table Editor
 *
 * Copyright (c) 2008 Chris Kitchen <info@mysqlajaxtableeditor.com>
 * All rights reserved.
 *
 * See COPYING file for license information.
 *
 * Download the latest version from
 * http://www.mysqlajaxtableeditor.com
 */
require_once('DBC.php');
require_once('Common.php');
require_once('php/lang/LangVars-en.php');
require_once('php/AjaxTableEditor.php');
require_once('User.class.php');

class MenuPage extends Common
{
    function __construct()
    {
        global $user;
        
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
        
        $this->displayHeaderHtml();
        $this->displayHtml();
        //$this->displayFooterHtml();
    }
    
    public function displayHtml()
    {
        global $user;
        
        if(isset($_SESSION['logged_in'])) : 
            $javascript = '<script>
                $(document).ready(function(){
                    $("a").click(function(event){
                        event.preventDefault();
                        window.open($(this).attr("href"),$(this).attr("target"));
                    });
                    $("#isManager").click(function(){
                        if (this.checked) {
                            //alert("Re-enablng Program Manager options");
                            document.getElementById("managerMenu").style.display = "block";
                        } else {
                            //alert("Disablng Program Manager options");
                            document.getElementById("managerMenu").style.display = "none";
                        }
                    });
                    $("#isOfficer").click(function(){
                        if (this.checked) {
                            document.getElementById("officerMenu").style.display = "block";
                        } else {
                            document.getElementById("officerMenu").style.display = "none";
                        }
                    });
                    $("#isAdmin").click(function(){
                        if (this.checked) {
                            document.getElementById("adminMenu").style.display = "block";
                        } else {
                            document.getElementById("adminMenu").style.display = "none";
                        }
                    });
                    //$("#isAdmin").click(function(){
                    //    $.post("SetUserRole.php",
                    //    {
                    //        role:"Admin",
                    //        value:$("#isAdmin").is(":checked")
                    //    },
                    //    function(data,status){
                    //        alert("Result: "+data);
                    //        location.reload();
                    //    });
                    //});
                    $("#registerMe").click(function(){
                        alert("Until further notice, please use version 1.0 of GO! to register.");
                        window.location.replace("https://wvresearch.org/data/go/index.html");
                    });
                    $("#reqManager").click(function(){
                        $.post("SendMailToAdmin.php",
                        {
                            subject:"Request to be a Program Manager",
                            message:"'.$user->firstName.' '.$user->lastName.' ('.$user->email.') wants to be a Program Manager"
                        });
                        alert("Request sent");
                    });
                    $("#reqOfficer").click(function(){
                        $.post("SendMailToAdmin.php",
                        {
                            subject:"Request to be an Institutional Officer",
                            message:"'.$user->firstName.' '.$user->lastName.' ('.$user->email.') wants to be an Institutional Officer"
                        });
                        alert("Request sent");
                    });
                    $("#reqAdmin").click(function(){
                        $.post("SendMailToAdmin.php",
                        {
                            subject:"Request to be an System Administrator",
                            message:"'.$user->firstName.' '.$user->lastName.' ('.$user->email.') wants to be an System Administrator"
                        });
                        alert("Request sent");
                    });
                });
                </script>';
            echo $javascript;
            //echo "<script> document.getElementById('go2main').src += ''; </script>";
            //echo "<script> $('go2main').attr('src',$('go2main').attr('src')); </script>";
            //error_log("\n[menu] User: ",3,"/home/annech2/westvirginiaresearch.org/go2/error_log");
            //error_log(print_r(unserialize($_SESSION['user']),true),3,"/home/annech2/westvirginiaresearch.org/go2/error_log");
            echo '<h3>'.$user->firstName.' '.$user->lastName.'</h3>';
            echo '<p>(Logged in as: <i>'.$_SESSION['login_name'].'</i>)</p>';
            if ($user->isRegistered) :
                echo '<input id="isRegistered" type="checkbox" checked> Registered User';
            endif;
            if ($user->isManager) :
                echo '<input id="isManager" type="checkbox" checked> Program Manager';
            endif;
            if ($user->isOfficer) :
                echo '<input id="isOfficer" type="checkbox" checked> Institutional Officer';
            endif;
            if ($user->isAdmin) :
                echo '<input id="isAdmin" type="checkbox" checked> System Administrator';
            endif;
            echo '<br/><br/>';
            echo '<div class="button-box"><a target="_parent" class="button-center" href="logout.php" title="Click here to log out"><b>log out</b></a></div>';
            ?>
            <hr>
            <h4>As a <i>Registered User</i>, you may:</h4>
            <table class="menu-box">
            <tr><td><a target="go2main" class="button-tall" href="OpenAnnouncements.php?asUser=T&read-only=T" title="Click here to view and respond (submit a Proposal) to open Announcements.">View <i>Open</i> <b>Announcements (RFPs)</b><br/>&nbsp;<span style="color:blue">>> Upload <b>New Proposals</b></span></a></td></tr>
            <tr><td><a target="go2main" class="button" href="Announcements.php?asUser=T&read-only=T" title="Click here to view all past Announcements.">View All <b>Announcements</b></a></td></tr>
            <tr><td><hr></td></tr>
            <tr><td><a target="go2main" class="button" href="Proposals.php?asUser=T&read-only=T&awards-only=T&active-only=T" title="Click here to view all currently active Awards.">View All <b>Active Awards</b></a></td></tr>
            <tr><td><a target="go2main" class="button" href="Proposals.php?asUser=T&read-only=T&awards-only=T" title="Click here to view all current and past Awards.">View All <b>Awards</b></a></td></tr>
            <tr><td><hr></td></tr>
            <tr><td><a target="go2main" class="button-tall" href="Increments.php?asUser=T&active-only=T" title="Click here to view all your Active Awards.">View <i>Your</i> <b>Active Awards</b><br/>&nbsp;<span style="color:blue">>> Upload <b>New Reports</b></span></a></td></tr>
            <tr><td><a target="go2main" class="button" href="Proposals.php?asUser=T&awards-only=T" title="Click here to view your current and past Awards.">View All <i>Your</i> <b>Awards</b></a></td></tr>
            <tr><td><hr></td></tr>
            <tr><td><a target="go2main" class="button" href="Proposals.php?asUser=T" title="Click here to view, edit, or check status of your Proposals.">View, Edit, Submit <i>Your</i> <b>Proposals</b></a></td></tr>
            <tr><td><a target="go2main" class="button" href="Reports.php?asUser=T" title="Click here to view and submit Reports to your Awards.">View, Edit, Submit <i>Your</i> <b>Reports</b></a></td></tr>
            <tr><td><hr></td></tr>
            <tr><td><a target="go2main" class="button" href="Profiles.php?asUser=T&sec_id=<?php echo $user->id ?>" title="Click here to view or edit your User profile.">Edit <i>Your</i> <b>User Profile</b> (Use GO! v1.0)</a></td></tr>
            </table>
            <div id="officerMenu">
            <hr>
            <?php
            if($user->isOfficer) : 
            ?>
                <h4>As an <i>Institutional Officer</i>, you may:</h4>
                <table class="menu-box">
                <tr><td><a target="go2main" class="button" href="Proposals.php?asOfficer=T&read-only=T" title="Click here to view Proposals from your institition.">View <b>Proposals</b></a></td></tr>
                <tr><td><a target="go2main" class="button" href="Proposals.php?asOfficer=T&read-only=T&awards-only=T" title="Click here to view Awards for your institition.">View <b>Awards</b></a></td></tr>                
                <tr><td><a target="go2main" class="button" href="Increments.php?asOfficer=T&read-only=T&active-only=T" title="Click here to view Active Awards for your institition.">View <b>Active Awards</b></a></td></tr>
                <tr><td><a target="go2main" class="button" href="Reports.php?asOfficer=T&read-only=T" title="Click here to view Reports from your institition.">View <b>Reports</b></a></td></tr>
                </table>
            <?php
            else :
            ?>
                <h5>You must be an <i>Institutional Officer</i> to view all Proposals, Awards, or Reports for your Institution or to edit your institution&apos;s profile.</h5>
                <table class="button-box">
                <tr><td id="reqOfficer"><a target="go2main" class="button-center-small" href="#" title="Click here to send an e-mail request to become an Institutional Officer.">Request to be an <b>Institutional Officer</b></a></td></tr>
                </table>
            <?php
            endif;
            echo '</div>';
            echo '<div id="managerMenu">';
            echo '<hr>';
            if($user->isManager) : 
            ?>
                <h4>As a <i>Program Manager</i>, you may:</h4>
                <table class="menu-box">
                <tr><td><a target="go2main" class="button" href="Announcements.php?asManager=T">Add, Edit <b>Announcements</b></a></td></tr>
                <tr><td><hr></td></tr>
                <tr><td><a target="go2main" class="button-tall" href="Proposals.php?asManager=T&pending-only=T">View <b>Pending Proposals</b><br/>&nbsp;<span style="color:blue">>> Make <b>New Awards</b></span></a></td></tr>
                <tr><td><a target="go2main" class="button" href="Proposals.php?asManager=T&final-only=T">View All <i>Submitted</i> <b>Proposals</b></a></td></tr>
                <tr><td><a target="go2main" class="button" href="Proposals.php?asManager=T&quietly=T">View, Edit <i>All</i> <b>Proposals</b></a></td></tr>
                <tr><td><hr></td></tr>
                <tr><td><a target="go2main" class="button" href="Awards.php?asManager=T&quietly=T">View, Edit <b>Awards</b></td></tr>
                <tr><td><a target="go2main" class="button" href="Increments.php?asManager=T&quietly=T">View, Edit <b>Increments</b></td></tr>
                <tr><td><hr></td></tr>
                <tr><td><a target="go2main" class="button-tall" href="Increments.php?asManager=T&pending-only=T">View Awards <b>Without Reports</b><br/>&nbsp;<span style="color:blue">>> Submit <b>Reports</b> on behalf of PI</span></a></td></tr>
                <tr><td><a target="go2main" class="button-tall" href="Reports.php?asManager=T&pending-only=T">Approve <b>Pending Reports</b><br/>&nbsp;<span style="color:blue">>> Award <b>New Increments</b></span></a></td></tr>
                <tr><td><a target="go2main" class="button" href="Reports.php?asManager=T&quietly=T">View, Edit <i>All</i> <b>Reports</b></a></td></tr>
                <tr><td><hr></td></tr>
                <tr><td><a target="go2main" class="button" href="Users.php?asManager=T&pending_only=T" title="Click here to view and approve pending User Registration requests.">Approve <b>New Users</b> (Use GO! v1.0)</a></td></tr>
                <tr><td><a target="go2main" class="button" href="Users.php?asManager=T&read-only=T" title="Click here to view all Registered Users.">View <i>All</i> <b>Users</b></a></td></tr>
                </table>
            <?php
            endif;
            echo '</div>';
            echo '<div id="adminMenu">';
            echo '<hr>';
            if($user->isAdmin) : 
            ?>
                <h4>As a <i>System Administator</i>, you may:</h4>
                <table class="menu-box">
                <tr><td><a target="go2main" class="button" href="Users.php?asAdmin=T">Approve a <b>User Registration</b></a></td></tr>
                <tr><td><a target="go2main" class="button" href="Users.php?asAdmin=T&includeRoles=T">Assign User <b>Roles and Privileges</b></a></td></tr>
                </table>
            <?php
            endif;
            echo '</div>';
        else :
            ?>
            <h3>Guest</h3>
            <hr>
            <h4>As a <i>Guest</i>, you may only:</h4>
            <table class="menu-box">
            <tr><td><a target="go2main" class="button" href="OpenAnnouncements.php?asGuest&read-only=T" title="Click here to view open Announcements (you must be registered and logged in to respond (submit a Proposal) to an Announcement).">View <i>Open</i> <b>RFPs</b></a></td></tr>
            <tr><td><a target="go2main" class="button" href="Proposals.php?asGuest&read-only=T&awards-only=T" title="Click here to view past awarded proposals.">View Past <b>Awards</b></a></td></tr>
            </table class="button-box">
            <hr>
            <h4>If you are a Registered User, you may:</h4>
            <table class="button-box">
            <tr><td><a target="go2menu" class="button-center" href="login.php" title="Click here to log in (if your are already registered)."><b>Log in</b></a><br/>
            To:
            <ul>
            <li>Submit a <b>Proposal</b></li>
            <li>View your <b>Awards</b></li>
            <li>Submit a <b>Report</b></li>
            <li>Edit your <b>User profile</b></li>
            </ul></td></tr>
            </table>
            <hr>
            <h4>If you are NOT a Registered User, you may:</h4>
            <table class="button-box">
            <tr><td><a target="_top" class="button-center" href="https://wvresearch.org/data/go/goregister.php" title="Click here to register as a User."><b>Register</b></a></td></tr>
            </table>
            <?php
        endif;
        ?>
        <hr>
        <h4>Miscellaneous documents:</h4>
        <table class="button-box">
        <tr><td><a target="_top" class="button-center" href="https://wvresearch.org/wp-content/uploads/2017/12/Copy-of-Single-year-Budget-Template.xls"> Download Budget template (Excel)</a></td></tr>
        </table>
        <hr>
        <h4>For more information, visit:</h4>
        <table class="button-box">
        <tr><td><a target="_top" class="button-center" href="https://wvresearch.org/funding"> DSR Grant Opportunities page</a></td></tr>
        <tr><td><a target="_top" class="button-center" href="https://wvresearch.org">DSR website</a></td></tr>
        <tr><td><a target="_top" class="button-center" href="http://www.wvhepc.edu/">WV HEPC website</a></td></tr>
        </table>
        <hr>
        <h4>Having problems? Try &#8594;&nbsp;</h4>
        <table class="button-box">
        <tr><td><a target="_top" class="button-center" href="https://wvresearch.org/data/go/index.php"><i>GO! <b>v1.0</b></i></a></td></tr>
        </table>
        <br/>
        <div class="menu-box">
        <a class="button-center" href="mailto:jack.smith@wvresearch.org?subject=Problem-with-GO2.0" title="Click here to report by e-mail to the administrator any problems with this application or suggestions for improvements.">Report Problems</a>
        </div>
        <hr>
        <?php
    }
}
$lte = new MenuPage();
?>
