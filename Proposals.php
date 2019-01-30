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
require_once('User.class.php');
require_once('DBC.php');
require_once('Common.php');
require_once('php/lang/LangVars-en.php');
require_once('php/AjaxTableEditor.php');

class Proposals extends Common
{
    protected $Editor;
    protected $dataDir = '../godocs/proposals/';
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
        
        // Initiate MATE editor
        $this->initiateEditor();
        
        if(isset($_POST['json']))  // Handle Asynchronous POST with JSON input
        {
            if(ini_get('magic_quotes_gpc'))
            {
                $_POST['json'] = stripslashes($_POST['json']);
            }
            $this->Editor->data = $this->Editor->jsonDecode($_POST['json'],true);
            $this->Editor->setDefaults();
            $this->Editor->main();
        }
        else if(isset($_GET['mate_export']))  // Handle Export Request
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
        else if(isset($_GET['col']))  // Handle GET Column Values Request
        {
            $this->getColVals($_GET['col']);
        }
        else if(isset($_POST) && count($_POST) > 0)  // Handle File Upload Requests
        {
            $this->Editor->setDefaults();
            $this->Editor->handleFileUpload();
        }
        else  // Handle Normal Display Mode
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

        $tableName = 'tbl_MARS_Proposals';
        $primaryCol = 'proposal_ID';
        $errorFun = array(&$this,'logError');

        // Set table permissions (column permissons below)
        if($user->isLoggedIn)
        {
            if(isset($_GET['read-only']))
            {
                $permissions = 'VXQSHOIUTF';
            }
            else if($user->isAdmin && isset($_GET['asAdmin']))
            {
                $permissions = 'EAVDXQSHOIUTF';
                // Edit, Add, [Copy, ]View, Delete, eXport, Quick search, advanced Search, Hide columns, Order columns, action Icons, User set # of rows to display, Table, Filter
            }
            else if($user->isManager && isset($_GET['asManager']))
            {
                $permissions = 'EAVXQSHOIUTF';
            }
            else if($user->isOfficer && isset($_GET['asOfficer'])) // SQL filter for Institution will apply
            {
                $permissions = 'EAVXQSHOIUTF';
            }
            else if(isset($_GET['awards-only'])) // Registered User, simple read-only mode, SQL filter for User only
            {
                $permissions = 'VIT';
            }
            else // Registered User, edit-mode, but SQL filter for User will apply
            {
                $permissions = 'EAVDIT';
            }
        }
        else // Guest, read-only and no actions (like viewing records)
        {
            $permissions = 'XQSHOUTF';
        }

        // Set up Table Columns
        $tableColumns['proposal_ID'] = array(
            'display_text' => 'Proposal ID', 
            'input_info' => 'readonly',
            'perms' => 'EVX'
        );
        $tableColumns['people_ID'] = array(
            'display_text' => 'PI', 
            //'display_mask' => "concat(people.people_LastName,', ',people.people_FirstName)",
            'join' => array('table' => 'tbl_MARS_People', 'column' => 'people_ID',
                'display_mask' => "concat(people_pi.people_LastName,', ',people_pi.people_FirstName)",
                'alias' => 'people_pi',
                'type' => 'left'
            ),
            'perms' => 'EVTAXQSFHO',
            'filter_type' => 'menu',
            'default' => $user->peopleId,
            'req' => 'true'
        );
        $tableColumns['proposal_CoPI'] = array(
            'display_text' => 'Co-PI', 
            //'display_mask' => "concat(people.people_LastName,', ',people.people_FirstName)",
            'join' => array('table' => 'tbl_MARS_People', 'column' => 'people_ID',
                'display_mask' => "concat(people_copi.people_LastName,', ',people_copi.people_FirstName)",
                'alias' => 'people_copi',
                'type' => 'left'
            ),
            'perms' => 'EVAXQSFHO',
            'filter_type' => 'menu'
        );
        $tableColumns['people_Dept1'] = array(
            'display_text' => 'Institution', 
            'display_mask' => 'inst.inst_Name',
            'perms' => 'VTXQSFHO',
            'input_info' => 'readonly size=100',
            'filter_type' => 'menu'
        );
        $tableColumns['ann_ID'] = array(
            'display_text' => 'Announcement', 
            //'display_mask' => "LEFT(announce.ann_Public_Name,100)",
            'join' => array('table' => 'tbl_MARS_Announce', 'column' => 'ann_ID',
                'display_mask' => "LEFT(ann_name.ann_Public_Name,40)",
                'alias' => 'ann_name',
                'type' => 'left'
            ),
            'perms' => 'EVTAXQSFHO',
            'filter_type' => 'menu',
            'default' => (isset($_GET['ann_ID']) ? $_GET['ann_ID'] : NULL),
            'req' => 'true'
        );
        $tableColumns['ann_Date_Open'] = array(
            'display_text' => 'Open Date', 
            'display_mask' => 'date_format(announce.ann_Date_Open,"%b %d, %Y")',
            //'join' => array('table' => 'tbl_MARS_Announce', 'column' => 'ann_Date_Open',
            //    'display_mask' => 'date_format(ann_date_open.ann_Date_Open,"%b %d, %Y")', 
            //    'alias' => 'ann_date_open',
            //    'type' => 'left'
            //),
            'perms' => 'XQSFHO',
            'hidden' => 'true'
        );
        $tableColumns['ann_Date_Close'] = array(
            'display_text' => 'Close Date', 
            'display_mask' => 'date_format(announce.ann_Date_Close,"%b %d, %Y")',
            //'join' => array('table' => 'tbl_MARS_Announce', 'column' => 'ann_Date_Close',
            //    'display_mask' => 'date_format(ann_date_close.ann_Date_Close,"%b %d, %Y")', 
            //    'alias' => 'ann_date_close',
            //    'type' => 'left'
            //),
            'perms' => 'XQSFHO',
            'hidden' => 'true'
        );
        $tableColumns['proposal_Name'] = array(
            'display_text' => 'Title',
            'perms' => 'EVTAXQSFHO',
            'input_info' => 'size=100',
            'req' => 'true'
        );
        
        if($user->isLoggedIn && (!isset($_GET['read-only']) || ($user->isAdmin && isset($_GET['asAdmin'])) || ($user->isManager && isset($_GET['asManager']))))
        {
            $tableColumns['proposal_Attachment_DisplayName'] = array(
                'display_text' => 'Proposal', 
                'perms' => 'EVAXTQSHO', 
                //'req' => 'true',
                'file_upload' => array(
                    'upload_fun' => array(&$this,'handleDocumentUpload'), 
                    'delete_fun' => array(&$this,'deleteDocumentFile')
                ), 
                'table_fun' => array(&$this,'formatDocument'), 
                'view_fun' => array(&$this,'formatDocument')
            );
            $tableColumns['proposal_Attachment_StoredName'] = array(
                'display_text' => 'Proposal File Name', 
                'perms' => 'EVXH', 
                'input_info' => 'readonly size=100'
            );
            $tableColumns['proposal_Summary'] = array(
                'display_text' => 'Summary', 
                'perms' => 'VXQSFHO',
                'hidden' => true,
                'textarea' => array('rows' => 15, 'cols' => 100) 
            );
            $tableColumns['proposal_Description'] = array(
                'display_text' => 'Description', 
                'perms' => 'VXQSFHO',
                'hidden' => true,
                'textarea' => array('rows' => 15, 'cols' => 100) 
            );
            $tableColumns['proposal_Budget_DisplayName'] = array(
                'display_text' => 'Budget', 
                'perms' => 'EVAXQSHO', 
                'file_upload' => array(
                    'upload_fun' => array(&$this,'handleDocumentUpload'), 
                    'delete_fun' => array(&$this,'deleteDocumentFile')
                ), 
                'table_fun' => array(&$this,'formatDocument'), 
                'view_fun' => array(&$this,'formatDocument')
            );
            $tableColumns['proposal_Budget_StoredName'] = array(
                'display_text' => 'Budget File Name', 
                'perms' => 'EVXH', 
                'input_info' => 'readonly size=100'
            );
            $tableColumns['proposal_Quotes_DisplayName'] = array(
                'display_text' => 'Quotes', 
                'perms' => 'EVAXQSHO', 
                'file_upload' => array(
                    'upload_fun' => array(&$this,'handleDocumentUpload'), 
                    'delete_fun' => array(&$this,'deleteDocumentFile')
                ), 
                'table_fun' => array(&$this,'formatDocument'), 
                'view_fun' => array(&$this,'formatDocument')
            );
            $tableColumns['proposal_Quotes_StoredName'] = array(
                'display_text' => 'Quotes File Name', 
                'perms' => 'EVXH', 
                'input_info' => 'readonly size=100'
            );
            $tableColumns['proposal_LoS_DisplayName'] = array(
                'display_text' => 'Letter of Support', 
                'perms' => 'EVAXQSHO', 
                'file_upload' => array(
                    'upload_fun' => array(&$this,'handleDocumentUpload'), 
                    'delete_fun' => array(&$this,'deleteDocumentFile')
                ), 
                'table_fun' => array(&$this,'formatDocument'), 
                'view_fun' => array(&$this,'formatDocument')
            );
            $tableColumns['proposal_LoS_StoredName'] = array(
                'display_text' => 'Letter of Support File Name', 
                'perms' => 'EVXH', 
                'input_info' => 'readonly size=100'
            );
            $tableColumns['proposal_MentorBio_DisplayName'] = array(
                'display_text' => 'Mentor Bio', 
                'perms' => 'EVAXQSHO', 
                'file_upload' => array(
                    'upload_fun' => array(&$this,'handleDocumentUpload'), 
                    'delete_fun' => array(&$this,'deleteDocumentFile')
                ), 
                'table_fun' => array(&$this,'formatDocument'), 
                'view_fun' => array(&$this,'formatDocument')
            );
            $tableColumns['proposal_MentorBio_StoredName'] = array(
                'display_text' => 'Mentor Bio File Name', 
                'perms' => 'EVXH', 
                'input_info' => 'readonly size=100'
            );
            $tableColumns['proposal_OtherDocs_DisplayName'] = array(
                'display_text' => 'Other Documents', 
                'perms' => 'EVAXQSHO', 
                'file_upload' => array(
                    'upload_fun' => array(&$this,'handleDocumentUpload'), 
                    'delete_fun' => array(&$this,'deleteDocumentFile')
                ), 
                'table_fun' => array(&$this,'formatDocument'), 
                'view_fun' => array(&$this,'formatDocument')
            );
            $tableColumns['proposal_OtherDocs_StoredName'] = array(
                'display_text' => 'Other Documents File Name', 
                'perms' => 'EVXH', 
                'input_info' => 'readonly size=100'
            );
            $tableColumns['proposal_FBBG'] = array(
                'display_text' => 'Supports RII FBBG Research',
                'perms' => 'EVAXQSFHO',
                'default' => '0',
                'checkbox' => array('checked_value' => '1', 'un_checked_value' => '0')
            );
            $tableColumns['proposal_Certified'] = array(
                'display_text' => 'Has Institutional Approval',
                'perms' => 'EVAXQSFHO',
                'default' => '0',
                'checkbox' => array('checked_value' => '1', 'un_checked_value' => '0')
            );
            $tableColumns['proposal_SubmitFinal'] = array(
                'display_text' => 'Final',
                'perms' => 'EVATXQSFHO',
                'default' => '0',
                'table_fun' => array(&$this,'centerColumn'), 
                'checkbox' => array('checked_value' => '1', 'un_checked_value' => '0')
            );
            $tableColumns['proposal_datetimestamp'] = array(
                'display_text' => 'Submit Date', 
                'perms' => 'EVATXQSFHO',
                'input_info' => 'readonly',
                'display_mask' => 'date_format(proposal_datetimestamp,"%b %d, %Y")', 
                'order_mask' => 'date_format(proposal_datetimestamp,"%Y-%m-%d %T")',
                'range_mask' => 'date_format(proposal_datetimestamp,"%Y-%m-%d %T")',
                'calendar' => array('js_format' => 'MM dd, yy'),
                'table_fun' => array(&$this,'centerColumn'), 
                'mysql_add_fun' => "NOW()",
                'mysql_edit_fun' => "NOW()"
            );
        }
        $tableColumns['award_id'] = array(
            'display_text' => 'Award ID',
            'display_mask' => 'award.award_Number',
            'perms' => 'XQSFHO'
        );
        $tableColumns['award_number'] = array(
            'display_text' => 'Award Number',
            'display_mask' => 'award.award_Number',
            'perms' => 'VXQSFHO'
        );
        $tableColumns['award_start_date'] = array(
            'display_text' => 'Start Date',
            'display_mask' => 'date_format(award.award_StartDate,"%b %d, %Y")', 
            'order_mask' => 'date_format(award.award_StartDate,"%Y-%m-%d %T")',
            'range_mask' => 'date_format(award.award_StartDate,"%Y-%m-%d %T")',
            'calendar' => array('js_format' => 'MM dd, yy'),
            'table_fun' => array(&$this,'centerColumn'), 
            'perms' => 'TVXQSFHO'
        );
        $tableColumns['award_end_date'] = array(
            'display_text' => 'End Date',
            'display_mask' => 'date_format(award.award_EndDate,"%b %d, %Y")', 
            'order_mask' => 'date_format(award.award_EndDate,"%Y-%m-%d %T")',
            'range_mask' => 'date_format(award.award_EndDate,"%Y-%m-%d %T")',
            'calendar' => array('js_format' => 'MM dd, yy'),
            'table_fun' => array(&$this,'centerColumn'), 
            'perms' => 'TVXQSFHO'
        );
        $tableColumns['award_amount'] = array(
            'display_text' => 'Amount',
            'display_mask' => "concat('$',format(award.award_amount,0))",
            'table_fun' => array(&$this,'centerColumn'), 
            'perms' => 'TVXQSHO'
        );
        
        if($user->isLoggedIn && (($user->isAdmin && isset($_GET['asAdmin'])) || ($user->isManager && isset($_GET['asManager']))) && isset($_GET['awards-only'])) 
        {
            $tableColumns['submitted_reports'] = array(
                'display_text' => 'Submitted Reports',
                'perms' => 'VXQSHO',
                'display_mask' => "GROUP_CONCAT(report.report_attachment_DisplayName SEPARATOR \"&#13;&#10\")",
                'table_fun' => array(&$this,'formatReports'),
                'view_fun' => array(&$this,'formatReports')
            );
        }

        // Instantiate MATE Editor
        $this->Editor = new AjaxTableEditor($tableName,$primaryCol,$errorFun,$permissions,$tableColumns);
        
        // Configure Editor
        $this->Editor->setConfig('tableInfo','cellpadding="1" style="width:100%" align="center" class="mateTable"');
        $this->Editor->setConfig('iconTitle','Actions');
        $this->Editor->setConfig('addRowTitle','Add Proposal');
        $this->Editor->setConfig('editRowTitle','Edit Proposal');
        
        if(isset($_GET['awards-only']))
        {
            $this->Editor->setConfig('orderByColumn','award_start_date');
            $this->Editor->setConfig('ascOrDesc','desc');
            $this->Editor->setConfig('viewRowTitle','View Award');
        }
        else
        {
            $this->Editor->setConfig('orderByColumn','proposal_datetimestamp');
            $this->Editor->setConfig('ascOrDesc','desc');
            $this->Editor->setConfig('viewRowTitle','View Proposal');
        }
        
        $this->Editor->setConfig('viewScreenFun',array(&$this,'viewScreenFun'));
        $this->Editor->setConfig('tableScreenFun',array(&$this,'tableScreenFun'));
        $this->Editor->setConfig('addScreenFun',array(&$this,'addScreenFun'));
        $this->Editor->setConfig('editScreenFun',array(&$this,'editScreenFun'));
        $this->Editor->setConfig('paginationLinks',true);
        $this->Editor->setConfig('displayNum','8');
        $this->Editor->setConfig('displayNumInc','8');
        $this->Editor->setConfig('instanceName',$this->mateInstances[0]);
        $this->Editor->setConfig('modifyRowSets',array(&$this,'changeBgColor'));
        $this->Editor->setConfig('filterPosition','top');

        $this->Editor->setConfig('customJoin',"LEFT JOIN tbl_MARS_Announce AS announce ON tbl_MARS_Proposals.ann_ID = announce.ann_ID LEFT JOIN tbl_MARS_People AS people ON tbl_MARS_Proposals.people_ID = people.people_ID LEFT JOIN tbl_MARS_Inst AS inst ON people.people_Dept1 = inst.inst_ID LEFT JOIN tbl_MARS_Awards AS award ON tbl_MARS_Proposals.proposal_ID = award.proposal_ID");

        if(isset($_GET['ann_ID']))
        {
            $this->Editor->setConfig('afterEditFun',array(&$this,'goBack'));
        }

        if($user->isLoggedIn)
        {
            if((($user->isAdmin && isset($_GET['asAdmin'])) || ($user->isManager && isset($_GET['asManager']))) && isset($_GET['awards-only']))
            {
                $this->Editor->setConfig('customJoin',"LEFT JOIN tbl_MARS_Announce AS announce ON tbl_MARS_Proposals.ann_ID = announce.ann_ID LEFT JOIN tbl_MARS_People AS people ON tbl_MARS_Proposals.people_ID = people.people_ID LEFT JOIN tbl_MARS_Inst AS inst ON people.people_Dept1 = inst.inst_ID LEFT JOIN tbl_MARS_Awards AS award ON tbl_MARS_Proposals.proposal_ID = award.proposal_ID LEFT JOIN tbl_MARS_Report_Data AS report ON award.award_ID = report.award_ID");
                $this->Editor->setConfig('groupByClause',"GROUP BY report.award_ID");
            }

            if(!isset($_GET['quietly']) && $user->isManager && isset($_GET['asManager']))
            {
               $this->Editor->setConfig('afterAddFun',array(&$this,'savedProposal'));
               $this->Editor->setConfig('afterEditFun',array(&$this,'savedProposal'));
            }

            if(isset($_GET['pending-only']) && !isset($_GET['read-only']))
            {
                if(($user->isAdmin && isset($_GET['asAdmin'])) || ($user->isManager && isset($_GET['asManager'])))
                {
                    $userActions['award_proposal'] = array(&$this,'awardProposal');
                }
                else 
                {
                    $userActions['submit_report'] = array(&$this,'submitReport');
                }
                
                $this->Editor->setConfig('userActions',$userActions);
                $userIcons[] = array('format_fun' => array(&$this,'getUserIcons'));
                $this->Editor->setConfig('userIcons',$userIcons);
            }

            if(($user->isAdmin && isset($_GET['asAdmin'])) || ($user->isManager && isset($_GET['asManager']))) // is a Manager or Admin
            {
                if(isset($_GET['pending-only']))
                {
                    $this->Editor->setConfig('sqlFilters',"proposal_SubmitFinal = 1 AND award.award_ID IS NULL AND DATEDIFF(NOW(),proposal_datetimestamp) < 60");
                    $this->Editor->setConfig('tableTitle','Proposals Pending Award');
                }
                else if(isset($_GET['active-only']))
                {
                    $this->Editor->setConfig('sqlFilters',"award.award_EndDate > NOW()");
                    $this->Editor->setConfig('tableTitle','Active Awards');
                }
                else if(isset($_GET['awards-only']))
                {
                    $this->Editor->setConfig('sqlFilters',"award.award_ID > 0");
                    $this->Editor->setConfig('tableTitle','Awarded Proposals');
                }
                else if(isset($_GET['final-only']))
                {
                    $this->Editor->setConfig('sqlFilters',"proposal_SubmitFinal = 1");
                    $this->Editor->setConfig('tableTitle','All Submitted Proposals');
                }
                else 
                {
                    $this->Editor->setConfig('tableTitle','All Proposals');
                }
            }
            else // is NOT a Manager or Admin -- can only see awarded proposals
            {
                if(isset($_GET['read-only'])) //Show Awarded proposal only
                {
                    if(isset($_GET['active-only']))
                    {
                        $this->Editor->setConfig('sqlFilters',"award.award_EndDate > NOW()");
                        $this->Editor->setConfig('tableTitle','Active Awards');
                    }
                    else 
                    {
                        $this->Editor->setConfig('sqlFilters',"award.award_ID > 0");
                        $this->Editor->setConfig('tableTitle','Awarded Proposals');
                    }
                }
                else if(isset($_GET['awards-only'])) // Awarded Proposals - User or Instiitution specific
                {
                    if(isset($_GET['active-only']))
                    {
                        if($user->isOfficer && isset($_GET['asOfficer']))
                        {
                            $this->Editor->setConfig('sqlFilters',"inst.inst_Name = '".$user->organization."'");
                            $this->Editor->setConfig('tableTitle','Active Awards at Your Institution');
                        }
                        else
                        {
                            $this->Editor->setConfig('sqlFilters',"(tbl_MARS_Proposals.people_ID = '".$user->peopleId."' OR proposal_CoPI = '".$user->peopleId."') AND award.award_EndDate > NOW()");
                            $this->Editor->setConfig('tableTitle','Your Active Awards');
                        }
                    }
                    else
                    {
                        if($user->isOfficer && isset($_GET['asOfficer']))
                        {
                            $this->Editor->setConfig('sqlFilters',"inst.inst_Name = '".$user->organization."' AND award.award_ID > 0");
                            $this->Editor->setConfig('tableTitle','Awards at Your Institution');
                        }
                        else
                        {
                            $this->Editor->setConfig('sqlFilters',"(tbl_MARS_Proposals.people_ID = '".$user->peopleId."' OR proposal_CoPI = '".$user->peopleId."') AND award.award_ID > 0");
                            $this->Editor->setConfig('tableTitle','Your Awards');
                        }
                    }
                }
                else // All Proposals (both Awarded and not) - User or Institutional spcecific
                {
                    if($user->isOfficer && isset($_GET['asOfficer']))
                    {
                        $this->Editor->setConfig('sqlFilters',"inst.inst_Name = '".$user->organization."' AND proposal_SubmitFinal = 1");
                        $this->Editor->setConfig('tableTitle','Proposal Submitted by Your Institution');
                    }
                    else
                    {
                        $this->Editor->setConfig('sqlFilters',"(tbl_MARS_Proposals.people_ID = '".$user->peopleId."' OR proposal_CoPI = '".$user->peopleId."')");
                        $this->Editor->setConfig('tableTitle','Proposals Submitted by You');
                    }
                }
            }
        }
        else // Is a Guest
        {
            $this->Editor->setConfig('sqlFilters',"award.award_ID > 0");
            $this->Editor->setConfig('tableTitle','Awarded Proposals');
        }
    }
    
    public function displayHtml()
    {
        // Generate HTML - Basic page layout with empty DIV sections
        $html = $this->getMateHtml($this->mateInstances[0]);
        echo $html;
            
        // Set default session configuration variables passed to MATE instance as JSON
        $defaultSessionData['orderByColumn'] = 'proposal_ID';
        $defaultSessionData = base64_encode($this->Editor->jsonEncode($defaultSessionData));

        // Set up other PHP variables used in generating JavaScript
        if(isset($_GET['proposal_ID']))
        {
            $history = 'false';
            $action = 'edit_row';
            $action_info = $_GET['proposal_ID'];
        }
        else if(isset($_GET['ann_ID']))
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

        // Generate JavaScript
        $javascript = $this->getMateJavaScript($this->mateInstances[0],$defaultSessionData,$history,$action,$action_info);
        echo $javascript;
    }

    public function addCkEditor()
    {
        //$this->Editor->addJavascript('addCkEditor("'.$this->mateInstances[0].'proposal_Summary");');
        //$this->Editor->addJavascript('addCkEditor("'.$this->mateInstances[0].'proposal_Description");');
    }

    public function formatReports($col,$val,$row)
    {
        global $user;
        $html = '';
        if (strlen($val) > 0)
        {
            $html .= '<textarea readonly="readonly" rows="10" cols="120">'.$val.'</textarea>';
            if ($user->isLoggedIn && $data->action == 'view_row')
            {
                $html .= '<hr>';
                $html .= '<img style="border: none;" src="images/apply.jpg" width="64" onclick="'.$this->mateInstances[0].'.toAjaxTableEditor(\'submit_report\',\''.$row['award_ID'].'\');" />';
            }
        }
        else if ($user->isLoggedIn && $data->action == 'view_row')
        {
            $html .= '<div style="text-align: center;">';
            $html .= '<img style="border: none;" src="images/apply.jpg" width="64" onclick="'.$this->mateInstances[0].'.toAjaxTableEditor(\'submit_report\',\''.$row['award_ID'].'\');" />';
            $html .= '</div>';
        }
        return $html;
    }

    public function changeBgColor($rowSets,$rowInfo,$rowNum)
    {
        global $user;

        if($user->isLoggedIn && $user->isOfficer && ($rowInfo['inst.inst_ID'] === $user->organization))
        {
            $rowSets['bgcolor'] = 'LightCyan';
        }
        else if($user->isLoggedIn && ($rowInfo['people_ID'] === $user->peopleId || $rowInfo['proposal_CoPI'] === $user->peopleId))
        {
            $rowSets['bgcolor'] = 'LightCyan';
        }

        if(isset($_GET['awards-only']))
        {
            if(!isset($_GET['active-only']) && !empty($rowInfo['award_end_date']) && (time() < strtotime($rowInfo['award_end_date'])))
            {
                $rowSets['bgcolor'] = 'PaleGreen';
            }
        }
        else 
        {
            if(isset($rowInfo['award_id']))
            {
                $rowSets['bgcolor'] = 'PaleGreen';
                
                if(!empty($rowInfo['award_end_date']) && (time() < strtotime($rowInfo['award_end_date'])))
                {
                    $rowSets['bgcolor'] = 'Lime';
                }
            }
            else if(!empty($rowInfo['announce.ann_Date_Close']) && time() > strtotime($rowInfo['announce.ann_Date_Close']))
            {
                $rowSets['bgcolor'] = 'MistyRose';
            }

            if($rowInfo['proposal_SubmitFinal'] != 1)
            {
                $rowSets['bgcolor'] = 'LightYellow';
            }
        }

        return $rowSets;
    }

    public function savedProposal($id,$col,$info)
    {
        if ($info['proposal_SubmitFinal'] == 1)
        {
            global $user;
            $query = "SELECT proposal_Attachment_DisplayName, proposal_Attachment_StoredName, ann_Public_Name, proposal_Name, people_FirstName, people_LastName, people_Email, inst_Name, inst_FO_Contact FROM tbl_MARS_Proposals AS proposal LEFT JOIN tbl_MARS_Announce AS announce ON proposal.ann_ID = announce.ann_ID LEFT JOIN tbl_MARS_People AS people ON proposal.people_ID = people.people_ID LEFT JOIN tbl_MARS_Inst AS inst ON people.people_Dept1 = inst.inst_ID WHERE proposal.proposal_ID = '".$info['proposal_ID']."'";
            $result = $this->Editor->doQuery($query);
            $row = $result->fetch();
            
            $to = $row['people_Email'];
            if ($row['inst_FO_Contact'] != "") { $to = $to.",".$row['inst_FO_Contact']; };
    
            $headers = "From: go@wvresearch.org\r\n" .
                       "CC: jan.taylor@wvresearch.org, annette.carpenter@wvresearch.org,\r\n" .
                       "Bcc: jack.smith@wvresearch.org\r\n" .
                       "X-mailer: php";            
            $subject = "[GO!] Grant Proposal Submitted for Review from ".$row[people_FirstName]." ".$row['people_LastName']." at ".$row['inst_Name'];
            $body = "The following grant proposal has been received for review:\r\n\r\n" .
                    "   Announcement:    ".$row['ann_Public_Name']."\r\n" .
                    "   Proposal Title:  ".$row['proposal_Name']."\r\n" .
                    "   PI:  ".$row[people_FirstName]." ".$row['people_LastName']."\r\n" .
                    "   Institution:  ".$row['inst_Name']."\r\n" .
                    "   Document Name:   ".$row['proposal_Attachment_DisplayName']."\r\n" .
                    "   [ File:  http://wvresearch.org/godocs/proposals/".rawurlencode($row['proposal_Attachment_StoredName'])." ]\r\n\r\n" .
                    "GO! System:  http://wvresearch.org/go2/\r\n"; 
    
            mail($to, $subject, $body, $headers);
        }

        if(isset($_GET['ann_ID'])) //TODO: what ID should this really be?
        {
            $this->goBack($id,$col,$info);
        }
    }

    public function getUserIcons($info)
    {
        global $user;
        
        $iconHtml = '';
        $numIcons = 0;
        if(($user->isAdmin && isset($_GET['asAdmin'])) || ($user->isManager && isset($_GET['asManager']))) 
            $iconHtml .= '<li class="award-proposal"><a href="javascript: void(0);" onclick="'.$this->mateInstances[0].'.toAjaxTableEditor(\'award_proposal\',\''.$info['proposal_ID'].'\');" title="Award Proposal"></a></li>';
        else
            $iconHtml .= '<li class="submit-report"><a href="javascript: void(0);" onclick="'.$this->mateInstances[0].'.toAjaxTableEditor(\'submit_report\',\''.$info['award_ID'].'\');" title="Submit Report"></a></li>';
        $numIcons++;
        return array('icon_html' => $iconHtml, 'num_icons' => $numIcons);
    }
    
//    public function awardProposal($proposal_id)
//    {
//        $this->Editor->addJavascript('window.location.href = "Awards.php?asManager=T&proposal_ID='.$proposal_id.'"');
//    }

    public function awardProposal($proposal_ID)
    {
        global $user;
        
        $query = "SELECT * FROM tbl_MARS_Proposals WHERE proposal_ID = '".$proposal_ID."'";
        $result = $this->Editor->doQuery($query);
        
        if($row = $result->fetch())
        {
            $query = "INSERT INTO tbl_MARS_Awards (proposal_ID, ann_ID, award_Date, award_submittedby, award_StartDate, award_EndDate) VALUES (".$row['proposal_ID'].",".$row['ann_ID'].",NOW(),".$user->peopleId.",NOW(),DATE_ADD(NOW(),INTERVAL 1 YEAR))";
            error_log($query);
            $result = $this->Editor->doQuery($query);
                
            if($result)
            {
                $query = "SELECT LAST_INSERT_ID() AS last_id FROM DUAL";
                $result = $this->Editor->doQuery($query);
                if($row = $result->fetch()) 
                {              
                    $this->Editor->addJavascript('window.location.href = "Awards.php?asManager=T&award_ID='.$row['last_id'].'"');
                }
                else
                    $this->logError('There was an error getting last inserted Award record ID: '.$query,__FILE__,__LINE__);
            }
            else
                $this->logError('There was an error inserting a new Award record: '.$query,__FILE__,__LINE__);
        }
        else
            $this->logError('There was an error selecting the Proposal record: '.$query,__FILE__,__LINE__);
    }

    public function submitReport($award_id)
    {
        $this->Editor->addJavascript('window.location.href = "Reports.php?award_ID='.$award_id.'"');
    }

}
$lte = new Proposals();
?>
