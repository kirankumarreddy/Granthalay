<?php
/* This file is part of a copyrighted work; it is distributed with NO WARRANTY.
 * See the file COPYRIGHT.html for more details.
 */
 
  require_once("../functions/formatFuncs.php");

/******************************************************************************
 * Member represents a library member.  Contains business rules for
 * member data validation.
 *
 * @author David Stevens <dave@stevens.name>;
 * @version 1.0
 * @access public
 ******************************************************************************
 */
 
 /******************************************************************************
 *                  CHANGE HISTORY
 *    #C6 - its a feature for bulk upload of members into library system in admin section
 *
 *    @author Karthikeya, Kiran Kumar Reddy and Bogade Saiteja
 * 
 ******************************************************************************
 */
class Member {
  var $_mbrid = 0;
  var $_barcodeNmbr = 0;
  var $_barcodeNmbrError = "";
  var $_createDt = "";
  var $_lastChangeDt = "";
  var $_lastChangeUserid = "";
  var $_lastChangeUsername = "";
  var $_classification = "";
  var $_lastName = "";
  var $_lastNameError = "";
  var $_firstName = "";
  // #C6 - begin
  var $_schoolId ="";
  var $_standard ="";
  var $_rollNo ="";
  var $_parentName ="";
  var $_parentOccupation ="";
  var $_motherTongue ="";
  var $_gender="";
  var $_schoolTeacher="";
  // #C6 - end
  var $_firstNameError = "";
  var $_email = "";
  var $_address = "";
  var $_homePhone = "";
  var $_workPhone = "";
  var $_custom = array();
  

  /****************************************************************************
   * @return boolean true if data is valid, otherwise false.
   * @access public
   ****************************************************************************
   */
  function validateData() {
    $valid = true;
    if ($this->_lastName == "") {
      $valid = false;
      $this->_lastNameError = "Last name is required.";
    }
    if ($this->_firstName == "") {
      $valid = false;
      $this->_firstNameError = "First name is required.";
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
  function getMbrid() {
    return $this->_mbrid;
  }
  function getBarcodeNmbr() {
    return $this->_barcodeNmbr;
  }
  function getBarcodeNmbrError() {
    return $this->_barcodeNmbrError;
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
  function getLastChangeUsername() {
    return $this->_lastChangeUsername;
  }
  function getLastName() {
    return $this->_lastName;
  }
  function getLastNameError() {
    return $this->_lastNameError;
  }
  function getFirstName() {
    return $this->_firstName;
  }
  // #C6 - begin
  function getSchoolId() {
    return $this->_schoolId;
  }
  
  function getGender(){
    return $this->_gender;
  }
  
  function getStandard() {
    return $this->_standard;
  }
  
  function getRollNo() {
    return $this->_rollNo;
  }
  function getParentName() {
    return $this->_parentName;
  }
  function getParentOccupation() {
    return $this->_parentOccupation;
  }
  function getMotherTongue() {
    return $this->_motherTongue;
  }
  function getSchoolTeacher(){
    return $this->_schoolTeacher;
  }
  // #C6 - end
  function getFirstLastName() {
    return $this->_firstName." ".$this->_lastName;
  }
  function getLastFirstName() {
    return $this->_lastName.",".$this->_firstName;
  }
  function getAddress() {
    return $this->_address;
  }
  function getHomePhone() {
    return $this->_homePhone;
  }
  function getWorkPhone() {
    return $this->_workPhone;
  }
  function getEmail() {
    return $this->_email;
  }
  function getClassification() {
    return $this->_classification;
  }

  /****************************************************************************
   * Setter methods for all fields
   * @param string $value new value to set
   * @return void
   * @access public
   ****************************************************************************
   */
  function setMbrid($value) {
    $this->_mbrid = trim($value);
  }
  function setBarcodeNmbr($value) {
    $this->_barcodeNmbr = trim($value);
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
  function setLastChangeUsername($value) {
    $this->_lastChangeUsername = trim($value);
  }
  function setLastName($value) {
    $this->_lastName = trim($value);
  }
  function setLastNameError($value) {
    $this->_lastNameError = trim($value);
  }
  function setFirstName($value) {
    $this->_firstName = trim($value);
  }
  // # C5 - begin
   function setSchoolId($value) {
    $this->_schoolId = trim($value);
  }
  function setGender($value)
  {
    $this->_gender = trim($value);  	
  }
   function setStandard($value) {
    $this->_standard = trim($value);
  }
   function setSchoolTeacher($value){
    $this->_schoolTeacher = trim($value);  	
  }
   function setRollNo($value) {
    $this->_rollNo = trim($value);
  }
   function setParentName($value) {
    $this->_parentName = trim($value);
  }
   function setParentOccupation($value) {
    $this->_parentOccupation = trim($value);
  }
   function setMotherTongue($value) {
    $this->_motherTongue = trim($value);
  }
  // #C6- end
  function setFirstNameError($value) {
    $this->_firstNameError = trim($value);
  }
  function setAddress($value) {
    $this->_address = trim($value);
  }
  function setHomePhone($value) {
    $this->_homePhone = trim($value);
  }
  function setWorkPhone($value) {
    $this->_workPhone = trim($value);
  }
  function setEmail($value) {
    $this->_email = trim($value);
  }
  function setClassification($value) {
    $this->_classification = trim($value);
  }
}

?>
