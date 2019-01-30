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
require_once('Common.php');
require_once('User.class.php');

class WelcomePage extends Common
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
        
        $this->displayHeaderHtml();
        $this->displayHtml();
        //$this->displayFooterHtml();
    }

    function displayHtml()
    {
        if(isset($_SESSION['logged_in'])) : 
            $user = unserialize($_SESSION['user']);
            echo '<h1 style="text-align:center;">Welcome to <i>'.$user->firstName.' '.$user->lastName.'</i> @ '.$user->organization.'!</h1>';
        else :
            echo '<h1 style="text-align:center;">Welcome to GO! (<i>v2.0</i>)</h1>';
            echo '<h2 style="text-align:center;">Grant Management System</h2>';
            echo '<h3 style="text-align:center;">for<br/>';
            echo 'Division of Science and Research<br/>';
            echo 'WV Higher Education Policy Commission</h3>';
        endif;
        echo '<div class="menu-box">';
        echo '<h3>&#8592; Please use the menus and instructions on the left to proceed</h3>';
        echo '</div>';
        echo '<br/><br/>';
        echo '<h4 style="text-align:center;">This is still a beta version of GO! with limited functionality. If you wish to use the original version, click here: </h4>';
        echo '<div class="button-box">';
        echo '<a target="_top" class="button-center" href="https://wvresearch.org/data/go">GO! v1.0</a>';
        echo '</div>';
    }
}
$lte = new WelcomePage();
?>
