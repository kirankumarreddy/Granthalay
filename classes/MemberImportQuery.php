<?php
/* This file is part of a copyrighted work; it is distributed with NO WARRANTY.
 * See the file COPYRIGHT.html for more details.
 */
 
require_once("../shared/global_constants.php");
require_once("../classes/Query.php");
require_once("../classes/Localize.php");
require_once ("../classes/Member.php");
require_once ("../classes/MemberQuery.php");

/******************************************************************************
 * Import data access component for library members
 *
 *                    CHANGE HISTORY 
              #C6 -  its a  feature for bulk upload the members into library system in admin section
 * 
 *        @author Karthikeya, Kiran Kumar Reddy and Saiteja Bogade
 *
 ******************************************************************************
 */
class MemberImportQuery extends Query {

//constructor
function import()	{
	$this->Query();	
}

function insertMember($data) {
	$mbr=new Member();
	$mbrQ=new MemberQuery();
	$mbr->setFirstName($data[0]);
	$mbr->setLastName($data[1]);
	$mbr->setGender($data[2]);
	$mbr->setSchoolId($data[3]);
	$mbr->setStandard($data[4]);
	$mbr->setGrade($data[5]);
	$mbr->setRollNo($data[6]);
	$mbr->setParentName($data[7]);
	$mbr->setParentOccupation($data[8]);
	$mbr->setMotherTongue($data[9]);
	$barcode=$mbrQ->assignRollNumber($mbr);
	$mbr->setBarcodeNmbr($barcode);
	$mbr->setClassification(3);
	
	return $mbrQ->insert($mbr);
}
/**
 * Inserts data into the biblio table
 * @param $data
 * @return int last insterted id if succesefull
 */
function alreadyInDB($firstName,$lastName,$parentName,$motherTongue) {
 	$sql  = "select mbrid from member where strcmp(first_name,'" .$firstName . "')=0 and strcmp(last_name,'" .$lastName ."')=0 ";
	$sql .= "and strcmp(parent_name,'" .$parentName . "')=0 and strcmp(mother_tongue,'" .$motherTongue ."')=0 ";
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