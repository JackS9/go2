<?php
/*
 * Mysql Ajax Table Editor
 *
 * Copyright (c) 2014 Chris Kitchen <info@mysqlajaxtableeditor.com>
 * All rights reserved.
 *
 * See COPYING file for license information.
 *
 * Download the latest version from
 * http://www.mysqlajaxtableeditor.com
 */
// Un-Comment for debugging
//error_reporting(E_ALL);
ini_set('display_errors', '1');
ini_set('error_log', "/home/annech2/westvirginiaresearch.org/go2/error_log");
ini_set('error_append_string',"\n");

class Common
{            
    protected $langVars;    
    protected $headerFiles = array();
    protected $showBackLink = false;
    protected $showColorLegend = true;
    public $logUri = false;
    public $parseGet = false;
    public $logPost = false;
    public $parseJson = false;
    public $decodeSessionData = false;

    public function goBack($id,$col,$info)
    {
        //$this->Editor->addJavascript('iframe.contentWindow.history.back()');
        $this->Editor->addJavascript('top.frames["go2main"].history.back()');
    }

    public function escapeData($data)
    {
        if (ini_get('magic_quotes_gpc'))
        {
            $data = stripslashes($data);
        }
    }
    
    public function logRequest()
    {     
        if($this->logUri)  
        {    
            if($this->parseGet)
            {
                error_log("REQUEST_URL: ".$_SERVER['PHP_SELF']);
                error_log("GET PARAMS: ".print_r($_GET,true));
            }
            else
                error_log("REQUEST_URI: ".$_SERVER['REQUEST_URI']);
        }
        if($this->logPost)
        {
            if($this->parseJson && isset($_POST['json']))
            {
                $json_string = json_decode($_POST['json'],true);
                error_log("JSON: ".json_encode($json_string,JSON_PRETTY_PRINT));
                if($this->decodeSessionData)
                {
                    $sessionData = $json_string['sessionData'];
                    error_log("SessionData (encoded): ".$sessionData);
                    $sessionData = (string) base64_decode($sessionData);
                    if($sessionData != NULL)
                    {
                        error_log("SessionData (decoded): ".$sessionData);
                        $sessionArray = json_decode($sessionData);
                        error_log("Session Data: ".print_r($sessionArray,true));
                    }
                    else
                        error_log("Decoded SessionData was NULL");
                }
            }
            else
                error_log("POST: ".print_r($_POST,true));
        }
    }
        
    public function logError($message, $file, $line)
    {
        $message = sprintf('An error occurred in script %s on line %s: %s',$file,$line,$message);
        throw new Exception($message);
        echo '<span style="color: red;">'.$message.'</span>';
        error_log($message);
        //var_dump($message);
        exit();
    }
    
    protected function setHeaderFiles()
    {
        $this->headerFiles[] = '<script type="text/javascript" src="//cdn.jsdelivr.net/ckeditor/4.0.1/ckeditor.js"></script>';
    }

    protected function displayHeaderHtml()
    {
        ?>
        <!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
        "http://www.w3.org/TR/html4/loose.dtd">
        <html>
        <head>
        <title>GO! Grant Opportunities (v2.0)</title>
        <base target="go2main" />
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <link href="css/table_styles.css" rel="stylesheet" type="text/css" />
        <link href="css/icon_styles.css" rel="stylesheet" type="text/css" />
        <link href="css/menu_styles.css" rel="stylesheet" type="text/css" />
        
        <link href="js/jquery/css/redmond/jquery-ui-1.9.2.custom.css" rel="stylesheet" type="text/css" />
        <script type="text/javascript" src="js/jquery/js/jquery-1.8.3.js"></script>
        <script type="text/javascript" src="js/jquery/js/jquery-ui-1.9.2.custom.min.js"></script>
        <script type="text/javascript" src="js/jquery/js/jquery.json.min.js"></script>

        <!-- Only needed if using cookie storage -->
        <script type="text/javascript" src="js/jquery/js/jquery.cookie.js"></script>

        <script type="text/javascript" src="js/jquery/js/jquery.storageapi.min.js"></script>
        
        <script type="text/javascript" src="js/ajax_table_editor.js"></script>

        <?php echo implode("\n",$this->headerFiles); ?>
        

        </head>    
        <body>
        <?php
    }    
    
    protected function getMateHtml($mateInstance)
    {
        $html = '
            <br />
    
            <div class="mateAjaxLoaderDiv"><div id="ajaxLoader1"><img src="images/ajax_loader.gif" alt="Loading..." /></div></div>
            
            <br />
            
            <div id="'.$mateInstance.'information">
            </div>
            
            <div id="mateTooltipErrorDiv" style="display: none;"></div>
            
            <div id="'.$mateInstance.'titleLayer" class="mateTitleDiv">
            </div>
            
            <br />

            <div id="'.$mateInstance.'recordLayer" class="mateRecordLayerDiv">
            </div>
            
            <div id="'.$mateInstance.'searchButtonsLayer" class="mateSearchBtnsDiv">
            </div>
            
            <br />

            <div id="'.$mateInstance.'tableLayer" class="mateTableDiv">
            </div>
            
            <div id="'.$mateInstance.'updateInPlaceLayer" class="mateUpdateInPlaceDiv">
            </div>
        ';

        return $html;
    }
                
    protected function getMateJavaScript($mateInstance,$defaultSessionData,$history,$action,$action_info)
    {
        $javascript = '
            <script type="text/javascript">                
                var ' . $mateInstance . ' = new mate("' . $this->mateInstances[0] . '");
                ' . $mateInstance . '.setAjaxInfo({url: "' . $_SERVER['REQUEST_URI'] . '", history: '.$history.'});
                ' . $mateInstance . '.init("' . $defaultSessionData . '");
                if('.$mateInstance.'.ajaxInfo.history == false) 
                {
                    '.$mateInstance.'.toAjaxTableEditor("'.$action.'","'.$action_info.'");
                }

                function addCkEditor(id)
                {
                    if(CKEDITOR.instances[id])
                    {
                       CKEDITOR.remove(CKEDITOR.instances[id]);
                    }
                    CKEDITOR.replace(id);
                }

                function populateColFilter(column)
                {
                    var header = $("td[filtercol=\""+column+"\"]");
                    var filterType = header.attr("filtertype");
                    if(filterType === "menu") {
                        $.getJSON("'.$_SERVER['REQUEST_URI'].'", { col: column }, function(result){
                            var select = $("select#'.$mateInstance.'filter_"+column);
                            var curValue = header.attr("filterstr");
                            if(typeof(curValue) === "undefined") curValue = "";
                            $.each(result, function(i,val){
                                if(val === curValue) {
                                    select.append($("<option></option>").attr("value",val).prop("selected",true).text(val));
                                } else {
                                    select.append($("<option></option>").attr("value",val).text(val));
                                }
                            });
                        });
                    }
                }
            </script>';
            
        return $javascript;
    }
    
    protected function displayFooterHtml()
    {
        ?>
        <?php if($this->showBackLink): ?>
            <br /><br /><div align="center"><a href="index.php">Back To Examples</a></div><br /><br />
        <?php endif; ?>
        <?php if($this->showColorLegend): ?>
            <br /><br /><div id="color-legend" style="display:none" align="left">
            <table>
            <tr>
            <td style="border: 1px solid #222; padding: 2px" bgcolor="lightCyan">Yours</td>
            <td style="border: 1px solid #222; padding: 2px" bgcolor="paleGreen">Active</td>
            <td style="border: 1px solid #222; padding: 2px" bgcolor="lime">Complete</td>
            <td style="border: 1px solid #222; padding: 2px" bgcolor="mistyRose">Incomplete</td>
            <td style="border: 1px solid #222; padding: 2px" bgcolor="lightYellow">Pending Action</td>
            </tr>
            </table>
            </div><br /><br />
        <?php endif; ?>
        </body>
        </html>
        <?php
    }    
    
    protected function hideColorLegend()
    {
        $this->Editor->addJavascript('document.getElementById("color-legend").style.display = "none";');
    }
    
    protected function showColorLegend()
    {
        $this->Editor->addJavascript('document.getElementById("color-legend").style.display = "block";');
    }

    public function tableScreenFun()
    {
        $this->addColFilters();
        $this->showColorLegend();
    }

    public function viewScreenFun()
    {
        $this->hideColorLegend();
    }

    public function addScreenFun()
    {
        $this->addCkEditor();
        $this->hideColorLegend();
    }

    public function editScreenFun()
    {
        $this->addCkEditor();
        $this->hideColorLegend();
    }
    
    protected function getAjaxUrl()
    {
        $ajaxUrl = $_SERVER['PHP_SELF'];
        if(count($_GET) > 0)
        {
            $queryStrArr = array();
            foreach($_GET as $var => $val)
            {
                $queryStrArr[] = $var.'='.urlencode($val);
            }
            $ajaxUrl .= '?'.implode('&',$queryStrArr);
        }
        return $ajaxUrl;
    }

    protected function getColVals($col)
    {
        $colVals = array();
        $tblName = $this->Editor->tableName;
    
        if(isset($this->Editor->tableColumns[$col]['display_mask']))
            $colName = $this->Editor->tableColumns[$col]['display_mask'];
        else
            $colName = $col;
        
        if(isset($this->Editor->tableColumns[$col]['join']))
        {
            $tblName = $this->Editor->tableColumns[$col]['join']['table'];
        
            if(isset($this->Editor->tableColumns[$col]['join']['display_mask']))
                $colName = $this->Editor->tableColumns[$col]['join']['display_mask'];
        
            if(isset($this->Editor->tableColumns[$col]['join']['alias']))
                $tblName = $tblName." AS ".$this->Editor->tableColumns[$col]['join']['alias'];
        
            $query = "SELECT DISTINCT ".$colName." AS myCol";
            $query .= " FROM ".$tblName;
            $query .= " ORDER BY ".$colName;
        }
        else if($this->Editor->customJoin)
        {
            $query = "SELECT DISTINCT ".$colName." AS myCol";
            $query .= " FROM ".$tblName;
            $query .= " ".$this->Editor->customJoin;
            $query .= " ORDER BY ".$colName;
        }
        else
        {
            $query = "SELECT DISTINCT ".$colName." AS myCol";
            $query .= " FROM ".$tblName;
            $query .= " ORDER BY ".$colName;
        }
    
        $result = $this->Editor->doQuery($query);
    
        while($row = $result->fetch())
        {
            $colVals[] = $row['myCol'];
        }
    
        echo $this->Editor->jsonEncode($colVals);
    }

    protected function addColFilters()
    {
        foreach($this->Editor->tableColumns as $col => $info)
        {
            if(isset($info['filter_type']) && $info['filter_type'] == 'menu')
            {
                $this->Editor->addJavaScript('populateColFilter("'.$col.'");');
            }
        }
    }
    
    public function centerColumn($col,$val,$row)
    {
        $html = '<div style="text-align: center;">'; 
        $html .= $val;
        $html .= '</div>';
        return $html;
    }   
    
    public function formatDocument($col,$val,$row)
    {
        $html = '';
        $displayName_col = $col;
        if(strstr($displayName_col,"DisplayName"))
        {
            $docPrefix = substr($displayName_col,0,strrpos($displayName_col,'_'));
            $storedName_col = $docPrefix."_StoredName";
        }
        else
        {
            $storedName_col = $displayName_col;
        }
        $storedDocName = $row[$storedName_col];
        if(strlen($val) > 0)
        {
            if($this->Editor->action == 'update_html')
                $html .= '<div style="text-align: center;">'; 
            else
                $html .= '<div style="text-align: left;">'; 
            $html .= '<a target="_blank" href="'.$this->dataDir.$storedDocName.'"><img style="border: none;" src="images/OpenDocument.png" alt="'.$val.'" title="Click to view document" width="16" /></a>';
            $html .= '</div>';
        }
        return $html;
    }

    public function formatFileSize($col,$size,$row)
    {
        $sizes = array('B', 'kB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB');
        $retstring = '%01.2f %s';
        $lastsizestring = end($sizes);
        foreach ($sizes as $sizestring) 
        {
                if ($size < 1024) { break; }
                if ($sizestring != $lastsizestring) { $size /= 1024; }
        }
        if ($sizestring == $sizes[0]) { $retstring = '%01d %s'; }
        return sprintf($retstring, $size, $sizestring);
    }   
    
    public function handleDocumentUpload($col,$id,$filesArr,$valErrors)
    {
        $displayName_col = $col;
        if(strstr($displayName_col,"DisplayName"))
        {
            $docPrefix = substr($displayName_col,0,strrpos($displayName_col,'_'));
            $storedName_col = $docPrefix."_StoredName";
        }
        else
        {
            $storedName_col = $displayName_col;
        }
        
        if($filesArr['size'] > 0)
        {
            // Delete document file if the report already had one
            $query = "select ".$storedName_col." from ".$this->Editor->tableName." where ".$this->Editor->primaryKeyCol." = ".$id;
            $result = $this->Editor->doQuery($query);
            
            if($row = $result->fetch())
            {
                if(!empty($row[$storedName_col]))
                {
                    $storedDocName = $row[$storedName_col];
                    unlink($this->dataDir.$storedDocName);
                }
            }
        
            $colNameParts = explode("_",$displayName_col);
            $docNamePrefix = ucfirst(substr(current($colNameParts),0,2));
            end($colNameParts);
            $docNamePrefix .= ucfirst(substr(prev($colNameParts),0,2));
            
            // Copy file to data directory and update database with the file name.
            $documentFileName = $docNamePrefix.$id.'_'.$filesArr['name'];
            //$this->Editor->warnings[] = 'About to upload: '.$documentFileName;
            
            if(move_uploaded_file($filesArr['tmp_name'],$this->dataDir.$documentFileName))
            {
                $query = "update ".$this->Editor->tableName." set ".$displayName_col." = '".$filesArr['name']."', ".$storedName_col." = '".$documentFileName."' where ".$this->Editor->primaryKeyCol." = ".$id;
                $result = $this->Editor->doQuery($query);
                
                if(!$result)
                {
                    $valErrors[] = 'There was an error updating the database.';
                    unlink($this->dataDir.$documentFileName);
                }
            }
            else
            {
                $valErrros[] = 'The file could not be moved';
            }
        }
        
        return $valErrors;
    }
    
    public function deleteDocumentFile($info)
    {
        $col = $info['col'];
        $displayName_col = $col;
        
        if(strstr($displayName_col,"DisplayName"))
        {
            $docPrefix = substr($displayName_col,0,strrpos($displayName_col,'_'));
            $storedName_col = $docPrefix."_StoredName";
        }
        else
        {
            $storedName_col = $displayName_col;
        }
        
        $query = "select ".$storedName_col." from ".$this->Editor->tableName." where ".$this->Editor->primaryKeyCol." = ".$info['id'];
        $result = $this->Editor->doQuery($query);
        
        if($row = $result->fetch())
        {
            $storedDocName = $row[$storedName_col];
        }
        else
        {
            $this->Editor->warnings[] = 'Problem finding stored document name';
            return false;
        }
        
        if(@unlink($this->dataDir.$storedDocName))
        {
            $query = "update ".$this->Editor->tableName." set ".$storedName_col." = '', ".$displayName_col." = '' where ".$this->Editor->primaryKeyCol." = ".$info['id']." limit 1";
            $result = $this->Editor->doQuery($query);
            if($result)
            {
                return true;
            }
        }
        
        $this->Editor->warnings[] = 'There was an error deleting the document file.';
        return false;
    }    
    
    public function formatImage($col,$val,$row)
    {
        $html = '';
        if(strlen($val) > 0)
        {
            $html .= '<div style="text-align: center;">'; 
            $html .= '<a target="_blank" href="'.$this->dataDir.$val.'"><img style="border: none;" src="'.$this->dataDir.$val.'" alt="'.$val.'" title="Click to view full image" width="120" /></a>';
            $html .= '</div>';
        }
        return $html;
    }
    
    public function formatVideo($col,$val,$row)
    {
        $htmo = '';
        if(strlen($val) > 0)
        {
            $html .= '<div style="text-align: center;">'; 
            $filename_prefix = explode('.',$val,2);
            $html .= '<a target="_blank" href="'.$this->dataDir.$val.'">';
            if (strlen($row['image_file_name']) > 0)
            {
                $html .= '<video width="160" height="120" controls poster="'.$this->dataDir.$row['image_file_name'].'" title="Click play (arrow) button to watch video. Click button in lower right corner (if present) for full screen view, or click elsewhere in image for full-window view. If you have trouble playing this video, try a different browser. You can also right-click on this message, choose \'Save video as...\', and play it in an external media player.">';
            }
            else
            {
                $html .= '<video width="160" height="120" controls title="Click play (arrow) button to watch video. Click button in lower right corner (if present) for full screen view, or click elsewhere in image for full-window view. If you have trouble playing this video, try a different browser. You can also right-click on this message, choose \'Save video as...\', and play it in an external media player.">';
            }
            $html .= '<source src="'.$this->dataDir.$filename_prefix[0].'.mp4" type="video/mp4">';
            $html .= '<source src="'.$this->dataDir.$filename_prefix[0].'.ogv" type="video/ogg">';
            $html .= '<source src="'.$this->dataDir.$filename_prefix[0].'.webm" type="video/webm">';
            $html .= 'Your browser does not appear to be able to play this video format. Try clicking on this message to see if it will open an external media player';
            $html .= '</video>';
            $html .= '</a>';
            $html .= '</div>';
        }
        return $html;
    }
    
    public function formatLink($col,$val,$row) 
    { 
         $html = ''; 
         if(strlen($val) > 0) 
         { 
          $html .= '<div style="text-align: center;">'; 
          if (strpos($val,'youtu.be') !== false)
              $html .= '<a target="_blank" href="'.$val.'"><img style="border: none;" src="images/YouTube.jpg" alt="'.$val.'" title="Click to view on YouTube" width="64" /></a>'; 
          else
              $html .= '<a target="_blank" href="'.$val.'"><img style="border: none;" src="images/link_icon.jpg" alt="'.$val.'" title="Click to follow link" width="64" /></a>'; 
          $html .= '</div>';
         } 
         return $html; 
    }
    
    public function handleImageUpload($id,$col,$filesArr,$valErrors)
    {
        if(count($valErrors) == 0 && $filesArr['size'] > 0)
        {
            // Delete image file if the participant already had one
            $query = "select image_file_name from ".$this->Editor->tableName." where ".$this->Editor->primaryKeyCol." = ".$id;
            $result = $this->Editor->doQuery($query);
            if($row = $result->fetch())
            {
                unlink($this->dataDir.$row['image_file_name']);
            }
            // Copy file to data directory and update database with the file name.
            if(move_uploaded_file($filesArr['tmp_name'],$this->dataDir.$filesArr['name']))
            {
                $query = "update ".$this->Editor->tableName." set image_file_name = '".$filesArr['name']."' where ".$this->Editor->primaryKeyCol." = ".$id;
                $result = $this->Editor->doQuery($query);
                if(!$result)
                {
                    $valErrors[] = 'There was an error updating the database.';
                    unlink($this->dataDir.$filesArr['name']);
                }
            }
            else
            {
                $valErrros[] = 'The file could not be moved';
            }
        }
        return $valErrors;
    }

    public function handleVideoUpload($id,$col,$filesArr,$valErrors)
    {
        if(count($valErrors) == 0 && $filesArr['size'] > 0)
        {
            // Delete video file if the participant already had one
            $query = "select video_file_name from ".$this->Editor->tableName." where ".$this->Editor->primaryKeyCol." = ".$id;
            $result = $this->Editor->doQuery($query);
            if($row = $result->fetch())
            {
                unlink($this->dataDir.$row['video_file_name']);
            }
            // Copy file to data directory and update database with the file name.
            if(move_uploaded_file($filesArr['tmp_name'],$this->dataDir.$filesArr['name']))
            {
                $query = "update ".$this->Editor->tableName." set video_file_name = '".$filesArr['name']."' where ".$this->Editor->primaryKeyCol." = ".$id;
                $result = $this->Editor->doQuery($query);
                if(!$result)
                {
                    $valErrors[] = 'There was an error updating the database.';
                    unlink($this->dataDir.$filesArr['name']);
                }
            }
            else
            {
                $valErrros[] = 'The file could not be moved';
            }
        }
        return $valErrors;
    }
    
    public function deleteImageFile($info)
    {
        if(@unlink($this->dataDir.$info['image_file_name']))
        {
            $query = "update ".$this->Editor->tableName." set imgage_file_name = '' where ".$this->Editor->primaryKeyCol." = ".$info['id']." limit 1";
            $result = $this->Editor->doQuery($query);
            if($result)
            {
                return true;
            }
        }
        $this->Editor->warnings[] = 'There was an error deleting the image file.';
        return false;
    }

    public function deleteVideoFile($info)
    {
        if(@unlink($this->dataDir.$info['video_file_name']))
        {
            $query = "update ".$this->Editor->tableName." set video_file_name = '' where ".$this->Editor->primaryKeyCol." = ".$info['id']." limit 1";
            $result = $this->Editor->doQuery($query);
            if($result)
            {
                return true;
            }
        }
        $this->Editor->warnings[] = 'There was an error deleting the video file.';
        return false;
    }
}
?>
