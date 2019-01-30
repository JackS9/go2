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

class Profiles extends Common
{
    protected $Editor;
    protected $dataDir = '../godocs/';
    protected $mateInstances = array('mate1_');

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
        
        $this->initiateEditor();
        
        if(isset($_POST['json']))
        {
            if(ini_get('magic_quotes_gpc'))
            {
                $_POST['json'] = stripslashes($_POST['json']);
            }
            $this->Editor->data = $this->Editor->jsonDecode($_POST['json'],true);
            $this->Editor->setDefaults();
            $this->Editor->main();
        }
        else if(isset($_GET['mate_export']))
        {
            $this->Editor->data['sessionData'] = $_GET['session_data'];
            $this->Editor->setDefaults();
            ob_end_clean();
            header('Cache-Control: no-cache, must-revalidate');
            header('Pragma: no-cache');
            header('Content-type: application/x-msexcel');
            header('Content-Type: text/csv');
            header('Content-Disposition: attachment; filename="'.$this->Editor->tableName.'.csv"');
            // Add utf-8 signature for windows/excel
            echo chr(0xEF).chr(0xBB).chr(0xBF);
            echo $this->Editor->exportInfo();
            exit();
        }
        else if(isset($_GET['col']))
        {
            $this->getColVals($_GET['col']);
        }
        else if(isset($_POST) && count($_POST) > 0)
        {
            $this->Editor->setDefaults();
            $this->Editor->handleFileUpload();
        }
        else
        {
            $this->setHeaderFiles();
            $this->displayHeaderHtml();
            $this->displayHtml();
            $this->displayFooterHtml();
        }
    }

    protected function initiateEditor()
    {
        global $user;

        $tableName = 'tbl_MARS_People';
        $primaryCol = 'people_ID';
        $errorFun = array(&$this,'logError');

        if($user->isLoggedIn)
        {
            if(isset($_GET['read-only']))
            {
                $permissions = 'VXQSHOIUTF';
            }
            else if($user->isAdmin && isset($_GET['asAdmin']))
            {
                $permissions = 'EAVDXQSHOIUTF';
                // Edit, Add, Copy, View, Delete, eXport, Quick search, advanced Search, Hide, Order, Icons, User set rows to display, Table, Filter
            }
            else if($user->isManager && isset($_GET['asManager']))
            {
                $permissions = 'VXQSHOIUTF';
            }
            else if(isset($_GET['new-user']))
            {
                $permissions = 'AVXQSHOIUTF';
            }
            else
            {
                $permissions = 'EVXQSHOIUTF';
            }
        }
        else
        {
            $permissions = 'AVXQSHOIUTF';
        }

// People:  [people_] ID, Title, LastName, FirstName, Preferred_Name, MI, Suffix, Degree (->Degree), Dept1 (->Inst), Dept2, 
//                    Add1, Add2, Add3, City, State (->State), Country (->Country), Zip, Zip_4, 
//                    OfficePhone, OfficePhoneExt, Fax, FaxExt, HomePhone, 
//                    Email, Alt_Email, URL, submittedby (->People), datetimestamp (timestamp)
// Bio: bio_ID, people_ID, [bio_] Narrative, Attachment_DisplayName, Attachment_StoredName, datetimestamp (datetime)
// Demographic_Data: [demog_] id, source_ID (->People), disability (->Disability), disability_other, ethnicity (->Ethnicity), 
//                   race (->Race), citizenship (->Citizenship), gender (->Gender), datetimestamp (datetime), submittedby (var)
// Sec: [sec_] ID, source_ID (->People), uname, pword, unique_ID, q1, r1, SysRole, FMRRole, reg_date (datetime), granted (0/1),
//             last_login (datetime)

        $tableColumns['people_ID'] = array(
            'display_text' => 'Name', 
            'display_mask' => "concat(people_LastName,', ',people_FirstName)",
            'perms' => 'EVTAXQSFHO',
            'filter_type' => 'menu',
            'req' => 'true'
        );

        $tableColumns['inst_Name'] = array(
            'display_text' => 'Institution', 
            'display_mask' => 'inst.inst_Name',
            'perms' => 'EVTXQSFHO',
            'input_info' => 'size=30',
            'filter_type' => 'menu'
        );
        if($user->isLoggedIn && (!isset($_GET['read-only']) || $user->isAdmin || $user->isManager))
        {
            $tableColumns['sec_ID'] = array(
                'display_text' => 'User ID', 
                'display_mask' => 'sec.sec_ID',
                'perms' => 'VX'
            );
            $tableColumns['sec_uname'] = array(
                'display_text' => 'Username', 
                'display_mask' => 'sec.sec_uname',
                'perms' => 'EAV', 
                'input_info' => 'size=30',
                'req' => 'true'
            );
            $tableColumns['sec_pword']= array(
                'display_text' => 'Password',
                'display_mask' => 'sec.sec_pword',
                'perms' => 'AEV',
                'req' => 'true',
                'view_fun' => array(&$this,'hidePassword'),
                'add_fun' => array(&$this,'blankPassword'),
                'edit_fun' => array(&$this,'blankPassword'),
                'on_add_fun' => array(&$this,'storePassword'),
                'on_edit_fun' => array(&$this,'storePassword'),
                //'format_input_fun' => array(&$this,'formatPassword')
            );
            if($user->isAdmin && isset($_GET['asAdmin']))
            {
                $tableColumns['sec_SysRole'] = array(
                    'display_text' => 'Role', 
                    'display_mast' => 'sec.sec_SysRole',
                    'perms' => 'EAV', 
                    'default' => '1',
                    'req' => 'true'
                );
                $tableColumns['sec_granted'] = array(
                    'display_text' => 'Security Granted', 
                    'display_mask' => 'sec.sec_granted',
                    'perms' => 'EAV', 
                    'default' => '0',
                    'checkbox' => array('checked_value' => '1', 'un_checked_value' => '0'),
                    'req' => 'true'
                );
            }
            $tableColumns['people_Email'] = array(
                'display_text' => 'E-mail',
                'perms' => 'EVAXQSFHO',
                'input_info' => 'size=30',
                'req' => 'true'
            );
            $tableColumns['people_OfficePhone'] = array(
                'display_text' => 'Office Phone',
                'perms' => 'EVAXQSFHO'
            );
            $tableColumns['bio_Narrative'] = array(
                'display_text' => 'Bio Narrative',
                'display_mask' => 'bio.bio_Narrative',
                'perms' => 'EVAXQSFHO',
                'textarea' => array('rows' => 15, 'cols' => 100)
            );
            $tableColumns['bio_Attachment_DisplayName'] = array(
                'display_text' => 'Bio', 
                'display_mask' => 'bio.bio_Attachment_DisplayName',
                'perms' => 'EVAXTQSHO', 
                'file_upload' => array(
                    'upload_fun' => array(&$this,'handleDocumentUpload'), 
                    'delete_fun' => array(&$this,'deleteDocumentFile')
                ), 
                'table_fun' => array(&$this,'formatDocument'), 
                'view_fun' => array(&$this,'formatDocument')
            );
            $tableColumns['bio_Attachment_StoredName'] = array(
                'display_text' => 'Bio Stored Name', 
                'display_mask' => 'bio.bio_Attachment_StoredName',
                'perms' => 'EVAXH', 
                'input_info' => 'readonly size=100'
            );
            $tableColumns['bio_datetimestamp'] = array(
                'display_text' => 'Bio Submitted On', 
                'perms' => 'VQSFXHO', 
                'display_mask' => 'date_format(bio.bio_datetimestamp,"%b %d, %Y")', 
                'order_mask' => 'date_format(bio.bio_datetimestamp,"%Y-%m-%d %T")',
                'range_mask' => 'date_format(bio.bio_datetimestamp,"%Y-%m-%d %T")',
                'calendar' => array('js_format' => 'MM dd, y'),
                'mysql_add_fun' => "NOW()",
                'mysql_edit_fun' => "NOW()"
            );
        }

        $this->Editor = new AjaxTableEditor($tableName,$primaryCol,$errorFun,$permissions,$tableColumns);
        $this->Editor->setConfig('tableInfo','cellpadding="1" style="width:100%" align="center" class="mateTable"');
        $this->Editor->setConfig('orderByColumn','people_ID');
        $this->Editor->setConfig('ascOrDesc','desc');
        $this->Editor->setConfig('tableTitle','Users');
        $this->Editor->setConfig('iconTitle','Actions');
        $this->Editor->setConfig('addRowTitle','Add User');
        $this->Editor->setConfig('editRowTitle','Edit User');
        $this->Editor->setConfig('viewRowTitle','View User');
        $this->Editor->setConfig('tableScreenFun',array(&$this,'tableScreenFun'));
        $this->Editor->setConfig('viewScreenFun',array(&$this,'viewScreenFun'));
        $this->Editor->setConfig('addScreenFun',array(&$this,'addScreenFun'));
        $this->Editor->setConfig('editScreenFun',array(&$this,'editScreenFun'));
        $this->Editor->setConfig('paginationLinks',true);
        $this->Editor->setConfig('displayNum','8');
        $this->Editor->setConfig('displayNumInc','8');
        $this->Editor->setConfig('instanceName',$this->mateInstances[0]);
        $this->Editor->setConfig('modifyRowSets',array(&$this,'changeBgColor'));
        $this->Editor->setConfig('filterPosition','top');
        
        $this->Editor->setConfig('customJoin',"LEFT JOIN tbl_MARS_Sec AS sec ON tbl_MARS_People.people_ID = sec.sec_source_ID LEFT JOIN tbl_MARS_Inst AS inst ON people_Dept1 = inst.inst_ID LEFT JOIN tbl_MARS_Bio AS bio ON tbl_MARS_People.people_ID = bio.people_ID LEFT JOIN tbl_MARS_Demographic_Data AS demog ON tbl_MARS_People.people_ID = demog.demog_source_ID");

        if(isset($_GET['new-user']))
        {
            $this->Editor->setConfig('afterAddFun',array(&$this,'loginNewUser'));
            $this->Editor->setConfig('afterEditFun',array(&$this,'goBack'));
        }

        if($user->isLoggedIn)
        {
            if(isset($_GET['sec_id']))
            {
                $this->Editor->setConfig('sqlFilters',"sec.sec_ID = '".(isset($_GET['sec_id']) ? $_GET['sec_id'] : NULL)."'");
            }
            else if(!isset($_GET['asAdmin']) && !isset($_GET['asManager']))
            {
                $this->Editor->setConfig('sqlFilters',"sec.sec_ID = '".$user->id."'");
            }
        }
    }
    
    public function displayHtml()
    {
        $html = $this->getMateHtml($this->mateInstances[0]);
        echo $html;
            
        // Set default session configuration variables here

        $defaultSessionData['orderByColumn'] = 'people_ID';

        $defaultSessionData = base64_encode($this->Editor->jsonEncode($defaultSessionData));

        if(isset($_GET['sec_id']))
        {
            $history = 'false';
            $action = 'edit_row';
            $action_info = $_GET['sec_id'];
        }
        else if(isset($_GET['new-user']) && $_GET['new-user'] == true)
        {
            $history = 'false';
            $action = 'add_row';
            $action_info = '';
        }
        else
        {
            $history = 'true';
            $action = 'update_html';
            $action_info = '';
        }

        $javascript = $this->getMateJavaScript($this->mateInstances[0],$defaultSessionData,$history,$action,$action_info);
        echo $javascript;
    }

    public function addCkEditor()
    {
        //$this->Editor->addJavascript('addCkEditor("'.$this->mateInstances[0].'sec_Summary");');
    }

    public function changeBgColor($rowSets,$rowInfo,$rowNum)
    {
        global $user;

        if(!$user->isLoggedIn || isset($_GET['read-only']))
            return $rowSets;
        if($rowInfo['sec_ID'] == $user->id)
        {
            $rowSets['bgcolor'] = 'LightCyan';
        }
        return $rowSets;
    }
    
    public function storePassword($col,$val,$row)
    {
       if(strlen($val) == 0)
       {
           return $_SESSION['old_password'];
       }
       else
       {
          return sha1($val);
       }
    }

    public function formatPassword($col,$val,$row)
    {
      return '<input type="password" id="'.$col.' value="'.val.'" />';
    }

    public function hidePassword($col,$val,$row)
    {
       return '*********';
    }

    public function blankPassword($col,$val,$row)
    {
       //store old password in case the user does not change value
       $_SESSION['old_password'] = (isset($row['password']) ? $row['password'] : NULL);
       return '';
    }

    public function loginNewUser($id,$col,$info)
    {
        //$this->Editor->doQuery("UPDATE participants SET is_approved = 1, is_registered = 1 WHERE id = '$id'");
        // TODO: add empty/default records for People, Bio, Demographic_Data
        $query = "SELECT * FROM tbl_MARS_Sec JOIN tbl_MARS_People ON people_ID = sec_source_ID JOIN tbl_MARS_Inst ON inst_ID = people_Dept1_ID WHERE sec_ID = :id";
        $result = $this->Editor->doQuery($query,array('id' => $id));
        if($row = $result->fetch())
        {
            $user = new User($row);
            var_dump($user);
            $_SESSION["user"] = serialize($user);
            $_SESSION["login_time"] = time();
            $_SESSION["logged_in"] = 1;
        }
        $userTools = new UserTools();
        if ($userTools->login($info['sec_uname'], $info['sec_pword']))
        {
            $user = unserialize($_SESSION['user']);
            var_dump($user);
            $user->isLoggedIn = 1;
        }
        else
        {
            $user = new User('');
            $user->isLoggedIn = 0;
        }
        mail("jack.smith@wvresearch.org","Registration Request",$user->firstName." ".$user->lastName." <".$user->email."> has requested to be a Registered User");
        $this->Editor->addJavascript('iframe.contentWindow.history.back()');
    }
}
$lte = new Profiles();
?>
