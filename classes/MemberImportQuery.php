<?php
/* This file is part of a copyrighted work; it is distributed with NO WARRANTY.
 * See the file COPYRIGHT.html for more details.
 */
 
require_once("../shared/global_constants.php");
require_once("../classes/Query.php");
require_once("../classes/Localize.php");

/******************************************************************************
 * Import data access component for library members
 *
 *                    CHANGE HISTORY 
              #C6 -  its a  feature for bulk upload the members into library system in admin section
 * 
 *        @author Kiran Kumar Reddy and Saiteja Bogade
 *
 ******************************************************************************
 */
class MemberImportQuery extends Query {

//constructor
function import()	{
	$this->Query();	
}

function insertMember($data) {
 	$sql  = "INSERT INTO member(first_name, last_name, school_name, standard, roll_no, parent_name," ;
	$sql .= "parent_occupation, mother_tongue, create_dt, last_change_dt, last_change_userid, classification) ";
	$sql .= " VALUES ('" . $data[0]  . "','" . $data[1] .  "','"  ;    
	$sql .= str_replace("'","\'",$data[2]) .  "','" . $data[3] .  "','" . $data[4] .  "','" . $data[5] . "','";
	$sql .= str_replace("'","\'",$data[6]) . "','" . $data[7] . "','" . date("Y-m-d H:i:s") . "','" . date("Y-m-d H:i:s") . "','";
	$sql .= 995 . "','" . 3 . "')";
   	$qShowStatusResult = $this->_act($sql);
	if ($qShowStatusResult==true)	{
		$thisid = $this->getInsertID();
		$sql = "UPDATE member set barcode_nmbr = mbrid where mbrid = " . $thisid;
		$qShowStatusResult = $this->_act($sql);
		if ($qShowStatusResult==true)	
			return $thisid;
		else
			return 0;
	} else
		return 0;	
}
/**
 * Inserts data into the biblio table
 * @param $data
 * @return int last insterted id if succesefull
 */
function alreadyInDB($firstName,$lastName,$parentName) {
 	$sql  = "select mbrid from member where strcmp(first_name,'" .$firstName . "')=0 and strcmp(last_name,'" .$lastName ."')=0 ";
	$sql .= "and strcmp(parent_name,'" .$parentName . "')=0";
 	$qShowStatusResult = mysql_query($sql) or die(mysql_error());
	$row = mysql_fetch_assoc($qShowStatusResult);
	if ($row == 0)
		return 0;
	else				  
		return $row["mbrid"];
}

/**
 * Inserts data into the biblio_field table
 * @param $data
 * @param $lastInsertID the last inserted id from the biblio table
 * @return nothing
 */

/**
 * inserts data into the biblio_copy table
 * and create barcodes for the insterted titles
 * barcode is a 12 char code based on the book id
 * @param $data
 * @param $lastInsertID the last inserted id from the biblio table
 * @return nothing
 */

}
?>