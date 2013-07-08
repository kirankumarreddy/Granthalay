<?php
/* This file is part of a copyrighted work; it is distributed with NO WARRANTY.
 * See the file COPYRIGHT.html for more details.
 */
 
  require_once("../functions/formatFuncs.php");

/******************************************************************************
 * School represents a place for the library.  Contains business rules for
 * school data validation.
 *
 * @author 
 * @version 1.0
 * @access public
 ******************************************************************************
 */
 
 /******************************************************************************
 *                  CHANGE HISTORY
 *    #C6 - its a feature for bulk upload of members into library system in admin section
 *
 *    @author karthikeya 
 * 
 ******************************************************************************
 */
class School {
  var $_schoolid = 0;
  var $_schoolName = 0;
  var $_schoolNameError="";
  var $_schoolCodeError="";
  var $_schoolAddressError="";
  var $_schoolCode = "";
  var $_createDt = "";
  var $_lastChangeDt = "";
  var $_lastChangeUserid = "";
  var $_schoolAddress = "";
  var $_contactPerson = "";
  var $_contactNumber = "";
  

  /****************************************************************************
   * @return boolean true if data is valid, otherwise false.
   * @access public
   ****************************************************************************
   */
  function validateData() {
    $valid = true;
    if ($this->_schoolName == "") {
      $valid = false;
      $this->_schoolNameError = "School Name is required.";
    }
	
    if ($this->_schoolCode == "") {
      $valid = false;
      $this->_schoolCodeError = "School Code is required.";
    }
    if ($this->_schoolAddress == "") {
      $valid = false;
      $this->_schoolAddressError = "School Address is required.";
    }

    return $valid;
  }
  
  function getCustom($field) {
    if (isset($this->_custom[$field])) {
      return $this->_custom[$field];
    }
    return "";
  }
  function setCustom($field, $value) {
    $this->_custom[$field] = $value;
  }

  /****************************************************************************
   * Getter methods for all fields
   * @return string
   * @access public
   ****************************************************************************
   */
  function getSchoolid() {
    return $this->_schoolid;
  }
  function getSchoolName() {
    return $this->_schoolName;
  }
  function getSchoolNameError() {
    return $this->_schoolNameError;
  }
  function getSchoolCodeError() {
    return $this->_schoolCodeError;
  }
  function getSchoolAddressError() {
    return $this->_schoolAddressError;
  }

  function getSchoolCode() {
    return $this->_schoolAddressError;
  }

  function getCreateDt() {
    return $this->_createDt;
  }
  function getLastChangeDt() {
    return $this->_lastChangeDt;
  }
  function getLastChangeUserid() {
    return $this->_lastChangeUserid;
  }

  function getSchoolAddress() {
    return $this->_schoolAddress;
  }
  function getcontactPerson() {
    return $this->_contactPerson;
  }

  function getContactNumber() {
    return $this->_contactNumber;
  }
  
  /****************************************************************************
   * Setter methods for all fields
   * @param string $value new value to set
   * @return void
   * @access public
   ****************************************************************************
   */
   
   
  function setSchoolid($value) {
	$this->_schoolid = trim($value);
  }
  function setSchoolName($value) {
    $this->_schoolName = trim($value);
  }
  function setSchoolNameError($value) {
    $this->_schoolNameError = trim($value);
  }
  function setSchoolCodeError($value) {
    $this->_schoolCodeError = trim($value);
  }
  function setSchoolAddressError($value) {
    $this->_schoolAddressError = trim($value);
  }

  function setSchoolCode($value) {
    $this->_schoolAddressError = trim($value);
  }

  function setCreateDt($value) {
    $this->_createDt = trim($value);
  }
  function setLastChangeDt($value) {
    $this->_lastChangeDt = trim($value);
  }
  function setLastChangeUserid($value) {
    $this->_lastChangeUserid = trim($value);
  }

  function setSchoolAddress($value) {
    $this->_schoolAddress = trim($value);
  }
  function setcontactPerson($value) {
    $this->_contactPerson = trim($value);
  }

  function setContactNumber($value) {
    $this->_contactNumber = trim($value);
  }
}

?>
