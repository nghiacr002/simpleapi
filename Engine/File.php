<?php
namespace API\Engine;
class File
{
    private $_aFile = array();
    public function upload($sFormItem , $sFolderPath = "", $sNewFileName = "",$iPerm = 0644)
    {
        if(empty($sFolderPath))
        {
            $sFolderPath = API_UPLOAD_PATH;
        }
        $sUploadFilePath = $this->buildPath($sFolderPath);
        $this->getFile($sFormItem);
        if(empty($sNewFileName))
        {
            $sNewFileName = md5(uniqid().time());
        }
        $sExt = $this->getExt($this->_aFile['name']);
        $sNewFileName = $sNewFileName.'%s.'.$sExt;
        $sNewUploadPath = $sUploadFilePath.$sNewFileName;
        if(move_uploaded_file($this->_aFile['tmp_name'],sprintf($sNewUploadPath,'')))
        {
            return str_replace($sFolderPath,'',$sNewUploadPath);
        }
        return false;
    }
    private function getFile($sFormItem)
    {
       if (strpos($sFormItem, ']') === false)
        {
            $this->_aFile = $_FILES[$sFormItem];
        }
        elseif (preg_match('/^(.+)\[(.+)\]$/', $sFormItem, $aM))
        {
            $this->_aFile['name']     = $_FILES[$aM[1]]['name'][$aM[2]];
            $this->_aFile['type']     = $_FILES[$aM[1]]['type'][$aM[2]];
            $this->_aFile['tmp_name'] = $_FILES[$aM[1]]['tmp_name'][$aM[2]];
            $this->_aFile['error']    = $_FILES[$aM[1]]['error'][$aM[2]];
            $this->_aFile['size']     = $_FILES[$aM[1]]['size'][$aM[2]];
        }
        return false;
    }
    public function getExt($sFileName)
    {
        $sFileName = strtolower($sFileName);
        $aParts = explode('.',$sFileName);
        $iCnt = count($aParts) - 1;
        $sExt = $aParts[$iCnt];
        if(strlen($sExt) > 4){
            return substr($sExt,0,4);
        }
        return $sExt;
    }
    public function getLimit($iMaxSize)
    {
        $iUploadMaxFileSize = (ini_get('upload_max_filesize') * 1048576);
        $iPostMaxSize = (ini_get('post_max_size') * 1048576);
        
        if ( $iUploadMaxFileSize > 0 && $iUploadMaxFileSize < ($iMaxSize * 1048576))
        {
            return ini_get('upload_max_filesize');
        }
        
        if ($iPostMaxSize > 0 && $iPostMaxSize < ($iMaxSize * 1048576))
        {
            return ini_get('post_max_size');
        }
        
        return $iMaxSize . 'MB';
    }
    public function buildPath($sFolderPath)
    {
        $sReturnPath = $sFolderPath;
        $sDate = date('Y-m-d');
        $aParts = explode('-',$sDate);
        foreach($aParts as $iKey => $sPart)
        {
            $sReturnPath.= $sPart.APP_DS;
            if(!is_dir($sReturnPath))
            {
                @mkdir($sReturnPath, 0777);
                @chmod($sReturnPath, 0777);
            }
        }
        return $sReturnPath;
    }
    public function read($sFileName = "")
    {
        $oHandle = fopen($sFileName, "r");
        if(!$oHandle)
        {
            return false;
        }
        $sContent = fread($oHandle,filesize($sFileName));
        fclose($oHandle);
        return $sContent;
    }
    public function write($sFileName, $sContent = "")
    {
        $oHandle = fopen($sFileName, "r");
        if(!$oHandle)
        {
            return false;
        }
        $sContent = fwrite($sFileName,$sContent);
        fclose($oHandle);
        return $sContent;
    }
    public function scanFolder($sFolderPath = "",$bRecursive = false)
    {
        if(is_file($sFolderPath)){
            return array($sFolderPath);
        }
        if(!is_dir($sFolderPath))
        {
            return array();
        }
        $aListFiles = array();
        $oHandle = opendir($sFolderPath);
        while (false !== ($sFile = readdir($oHandle))) {
            
            if($bRecursive == true && is_dir($sFile))
            {
                $aListFiles = array_merge($aListFiles,$this->scanFolder($sFile,$bRecursive));
            }elseif(strpos($sFile,'.php') !== false){
                $aListFiles[] = $sFile;
            }
        }
        closedir($oHandle);
        return $aListFiles;
    }
    public function download($sFileName, $sPathFile, $sContentType = 'application/force-download')
    {
        if(ini_get('zlib.output_compression')) 
        {
            ini_set('zlib.output_compression', 'Off'); 
        } 
        ob_clean();   
        ob_end_flush();
        header('Content-Description: File Transfer');
        header("Pragma: public"); // required
        header("Expires: 0");
        header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
        header("Cache-Control: private", false); // required for certain browsers
        header("Content-Transfer-Encoding: binary");
        header("Content-Type: " . $sContentType);
        header("Content-Length: " . filesize($sPathFile));
        header("Content-Disposition: attachment; filename=\"" . $sFileName . "\";" );
        $fd = fopen ($sPathFile, "rb");
        if($fd)
        {
            while(!feof($fd)) {
                $buffer = fread($fd, 1024);
                echo $buffer;
            }
        }
        @fclose($fd);
        die();
    }
}
?>