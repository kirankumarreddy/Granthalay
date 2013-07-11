<?php
/* This file is part of a copyrighted work; it is distributed with NO WARRANTY.
 * See the file COPYRIGHT.html for more details.
 */
 
require_once("../shared/global_constants.php");
require_once("../classes/Query.php");
require_once("../classes/Localize.php");
require_once("../classes/BiblioCopy.php");
require_once("../classes/BiblioCopyQuery.php");

/******************************************************************************
 * Import data access component for library bibliographies
 *
 * @author Kiran Kumar Reddy and Saiteja Bogade
 * @version 1.0
 * @access public
 ******************************************************************************
 */
class ImportQuery extends Query {

//constructor
function import()	{
	$this->Query();	
}

function insertBiblio($data) {
 
 	$sql  = "INSERT INTO biblio(create_dt, last_change_dt, last_change_userid, collection_cd, material_cd, title, author, reading_level) ";
	$sql .= " VALUES ('" . date("Y-m-d H:i:s") . "','" . date("Y-m-d H:i:s")  . "','" . 995 .  "','"  . 13 .  "','" .  2 .  "','" . $data[1] .  "','" . $data[2] . "','";
	$sql .= $data[4] . "')";
   	$qShowStatusResult = $this->_act($sql);
	if ($qShowStatusResult==true)
  		return $this->getInsertID();
	else
		return 0;	
}
/**
 * Inserts data into the biblio table
 * @param $data
 * @return int last insterted id if succesefull
 */
function alreadyInDB($title) {
 	$sql  = "select bibid from biblio where strcmp(title,'" .$title . "')=0";
 	$qShowStatusResult = mysql_query($sql) or die(mysql_error());
	$row = mysql_fetch_assoc($qShowStatusResult);
	if ($row == 0)
		return 0;
	else				  
		return $row["bibid"];
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
function insertBiblioCopy($data, $lastInsertID) {
	$copy=new BiblioCopy();
	$copy->setBibid($lastInsertID);
	$copy->setCreateDt(date("Y-m-d H:i:s"));
	$copy->setBarcodeNmbr($data[0]);
	$copy->setStatusCd($data[3]);
	$copy->setStatusBeginDt(date("Y-m-d H:i:s"));
	$copy->setBasketNumber($data[5]);
	$copy->setCopyDesc($data[6]);
	
	$bibcopyQ=new BiblioCopyQuery();
 	if(($bibcopyQ->insert($copy))==false)
 	{
 		return $bibcopyQ->getError();
 	}
 	return null;
}

}
?>