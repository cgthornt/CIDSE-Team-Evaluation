<?php
/*
 error_reporting(E_ALL);
 set_time_limit(0);

 date_default_timezone_set('Europe/London');
 $path=dirname(__FILE__);
 set_include_path(get_include_path().PATH_SEPARATOR.$path.'/../../phpExcel/Classes/');
 //echo get_include_path();
 include_once 'PHPExcel/IOFactory.php'; */

// YiiBase::import('ext.PHPExcel.IOFactory');


//require_once(dirname(__FILE__) . '/../../vendor/PHPExcel.php');
/*
 *$teamInfo data store stucture like this
 *
 *$teamInfo[group_name][user_name][f_name]
 *								 [l_name]
 *$teamInfo will be wrapped in an array, which will label if there's error user_name;
 * 
 * */
class ExcelFileReader
{
	function readFromExcel($path,$fileName)
	{

		//get the file extention name
		strtok($fileName, '.');
		$inputFileExtent = strtok('.');
		$inputFileType=null;
		if ($inputFileExtent=='xls') {
			$inputFileType = 'Excel5';
		}elseif ($inputFileExtent=='xlsx')
		{
			$inputFileType='Excel2007';
		}
		//$inputFileExtent=null;
		if ($inputFileExtent!=null) {
			$objReader = PHPExcel_IOFactory::createReader($inputFileType);
			$objPHPExcel = $objReader->load($path);
			$sheetData = $objPHPExcel->getActiveSheet()->toArray(null,true,true,true);

			$errorRow=array();
			$teamInfo = array();
			$returnedValue=array();
			if($sheetData[1]['D']=='GROUP_NAME')
			{
				foreach($sheetData as $row)
				{
					if($row['D']!='GROUP_NAME')/*skip the first line*/
					{
						$groupName = $row['D'];
						if(empty($teamInfo[$groupName]))
						{
							//if this group is not exist then create one
							$teamInfo[$groupName] = array();
						}
						//put students into the group array
						$teamInfo[$groupName][$row['A']]= array('f_name'=>$row['B'],'l_name'=>$row['C']);
						
					}
				}
			}
			
			$returnedValue['normal']=$teamInfo;

			$ASUidentity = new CASUserIdentity();
			// check all the user_name get from file through ASU this may not necessary if ASU authentication is not applied
			foreach($teamInfo as $groupName=>$group)
			{
				foreach($group as $UserName=>$userInfo)
				{
					try {
						$ASUidentity->findOrCreateUserByUsername($UserName);
					} catch (Exception $e) {
						//echo 'unvalid user '.$UserName;
						$errorRow[] = $UserName;
						$returnedValue['error']=$errorRow;
					}
				}
			}
			
		return 	$returnedValue;
		}

	}

}

?>