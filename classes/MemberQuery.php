<?php
/* This file is part of a copyrighted work; it is distributed with NO WARRANTY.
 * See the file COPYRIGHT.html for more details.
 */
 
require_once("../shared/global_constants.php");
require_once("../classes/Member.php");
require_once("../classes/Query.php");
require_once("../classes/School.php");
require_once("../classes/SchoolQuery.php");


/******************************************************************************
 * MemberQuery data access component for library members
 *
 * @author David Stevens <dave@stevens.name>;
 * @version 1.0
 * @access public
 ******************************************************************************
 */
class MemberQuery extends Query {
  var $_itemsPerPage = 1;
  var $_rowNmbr = 0;
  var $_currentRowNmbr = 0;
  var $_currentPageNmbr = 0;
  var $_rowCount = 0;
  var $_pageCount = 0;

  function setItemsPerPage($value) {
    $this->_itemsPerPage = $value;
  }
  function getCurrentRowNmbr() {
    return $this->_currentRowNmbr;
  }
  function getRowCount() {
    return $this->_rowCount;
  }
  function getPageCount() {
    return $this->_pageCount;
  }

  /****************************************************************************
   * Executes a query
   * @param string $type one of the global constants
   *               OBIB_SEARCH_BARCODE or OBIB_SEARCH_NAME
   * @param string $word String to search for
   * @param integer $page What page should be returned if results are more than one page
   * @access public
   ****************************************************************************
   */
  function execSearch($type, $word, $page) {
    # reset stats
    $this->_rowNmbr = 0;
    $this->_currentRowNmbr = 0;
    $this->_currentPageNmbr = $page;
    $this->_rowCount = 0;
    $this->_pageCount = 0;

    # Building sql statements
    if ($type == OBIB_SEARCH_BARCODE) {
      $col = "barcode_nmbr";
    } elseif ($type == OBIB_SEARCH_NAME) {
      $col = "last_name";
    }

    # Building sql statements
    $sql = $this->mkSQL("from member where %C like %Q ", $col, $word."%");
    $sqlcount = "select count(*) as rowcount ".$sql;
    $sql = "select * ".$sql;
    $sql .= " order by last_name, first_name";
    # setting limit so we can page through the results
    $offset = ($page - 1) * $this->_itemsPerPage;
    $limit = $this->_itemsPerPage;
    $sql .= $this->mkSQL(" limit %N, %N ", $offset, $limit);
    #echo "sql=[".$sql."]<br>\n";

    # Running row count sql statement
    $rows = $this->exec($sqlcount);
    if (count($rows) != 1) {
      Fatal::internalError("Wrong number of count rows");
    }
    # Calculate stats based on row count
    $this->_rowCount = $rows[0]["rowcount"];
    $this->_pageCount = ceil($this->_rowCount / $this->_itemsPerPage);

    # Running search sql statement
    $this->_exec($sql);
  }

  /****************************************************************************
   * Executes a query
   * @param string $mbrid Member id of library member to select
   * @return boolean returns false, if error occurs
   * @access public
   ****************************************************************************
   */
  function get($mbrid) {
    $sql = $this->mkSQL("select member.*, staff.username from member "
                        . "left join staff on member.last_change_userid = staff.userid "
                        . "where mbrid=%N ", $mbrid);
    $rows = $this->exec($sql);
    if (count($rows) != 1) {
      Fatal::internalError("Bad mbrid");
    }
    return $this->_mkObj($rows[0]);
  }
  
  function maybeGetByBarcode($bcode) {
    $sql = $this->mkSQL("select member.*, staff.username from member "
                        . "left join staff on member.last_change_userid = staff.userid "
                        . "where barcode_nmbr=%Q ", $bcode);
    $row = $this->select01($sql);
    if($row)
      return $this->_mkObj($row);
    return NULL;
  }

  /****************************************************************************
   * Fetches a row from the query result and populates the Member object.
   * @return Member returns library member or false if no more members to fetch
   * @access public
   ****************************************************************************
   */
  function fetchMember() {
    $array = $this->_conn->fetchRow();
    if ($array == false) {
      return false;
    }
    # increment rowNmbr
    $this->_rowNmbr = $this->_rowNmbr + 1;
    $this->_currentRowNmbr = $this->_rowNmbr + (($this->_currentPageNmbr - 1) * $this->_itemsPerPage);
    return $this->_mkObj($array);
  }
  function _mkObj($array) {
    $mbr = new Member();
    $mbr->setMbrid($array["mbrid"]);
    $mbr->setBarcodeNmbr($array["barcode_nmbr"]);
    $mbr->setLastChangeDt($array["last_change_dt"]);
    $mbr->setLastChangeUserid($array["last_change_userid"]);
    if (isset($array["username"])) {
      $mbr->setLastChangeUsername($array["username"]);
    }

    $mbr->setGender($array["gender"]);
    $mbr->setSchoolId($array["schoolid"]);
    $mbr->setParentName($array["parent_name"]);
    $mbr->setParentOccupation($array["parent_occupation"]);
    $mbr->setMotherTongue($array["mother_tongue"]);
    $mbr->setStandard($array["standard"]);
    $mbr->setSchoolTeacher($array["school_teacher"]);
	$mbr->setGrade($array["grade"]);
    $mbr->setLastName($array["last_name"]);
    $mbr->setFirstName($array["first_name"]);
    $mbr->setAddress($array["address"]);
    $mbr->setHomePhone($array["home_phone"]);
    $mbr->setWorkPhone($array["work_phone"]);
    $mbr->setEmail($array["email"]);
    $mbr->setClassification($array["classification"]);

    $mbr->_custom = $this->getCustomFields($array['mbrid']);
    return $mbr;
  }

  function getCustomFields($mbrid) {
    # KLUDGE to make sure we don't clobber the results handle
    # when we're called from fetchmember().
    # FIXME - redo query stuff to avoid this issue
    $q = new Query();
    $q->connect();
    $sql = $q->mkSQL('select * from member_fields where mbrid=%N', $mbrid);
    $rows = $q->exec($sql);
    $fields = array();
    foreach ($rows as $r) {
      $fields[$r['code']] = $r['data'];
    }
    return $fields;
  }
  function setCustomFields($mbrid, $fields) {
    $sql = $this->mkSQL('delete from member_fields where mbrid=%N', $mbrid);
    $this->exec($sql);
    foreach ($fields as $code => $data) {
      $sql = $this->mkSQL('insert into member_fields (mbrid, code, data) '
                          . 'values (%N, %Q, %Q)', $mbrid, $code, $data);
      $this->exec($sql);
    }
  }
  
  /****************************************************************************
   * Returns true if barcode number already exists
   * @param string $barcode Library member barcode number
   * @param string $mbrid Library member id
   * @return boolean returns true if barcode already exists
   * @access private
   ****************************************************************************
   */
  function DupBarcode($barcode, $mbrid=0) {
    $sql = $this->mkSQL("select count(*) as num from member "
                        . "where barcode_nmbr = %Q and mbrid <> %N",
                        $barcode, $mbrid);
    $rows = $this->exec($sql);
    if (count($rows) != 1) {
      Fatal::internalError('Bad number of rows');
    }
    if ($rows[0]['num'] > 0) {
      return true;
    }
    return false;
  }

  /****************************************************************************
   * Inserts a new library member into the member table.
   * @param Member $mbr library member to insert
   * @return integer the id number of the newly inserted member
   * @access public
   ****************************************************************************
   */
  function insert($mbr) {
    $sql = $this->mkSQL("insert into member "
                        . "(mbrid, barcode_nmbr, create_dt, last_change_dt, "
                        . " last_change_userid, last_name, first_name, address, schoolid ,"
                        . " standard, grade, roll_no, parent_name, parent_occupation, mother_tongue, "
						. " home_phone, work_phone, email, classification, gender, school_teacher) "
                        . "values (null, %Q, sysdate(), sysdate(), %N, "
                        . " %Q, %Q, %Q, %Q, %Q, %Q, %Q, %Q, %Q, %Q, %Q, %Q, %Q, %Q, %Q, %Q) ",
                        $mbr->getBarcodeNmbr(), $mbr->getLastChangeUserid(),
                        $mbr->getLastName(), $mbr->getFirstName(),
                        $mbr->getAddress(), $mbr->getSchoolId() ,
			$mbr->getStandard(),$mbr->getGrade(),$mbr->getRollNo(),
			$mbr->getParentName(),$mbr->getParentOccupation(),
			$mbr->getMotherTongue(),$mbr->getHomePhone(),
                        $mbr->getWorkPhone(), $mbr->getEmail(),
                        $mbr->getClassification(),$mbr->getGender(),$mbr->getSchoolTeacher());

    $this->exec($sql);
    $mbrid = $this->_conn->getInsertId();
    $this->setCustomFields($mbrid, $mbr->_custom);
    return $mbrid;
  }

  /****************************************************************************
   * get the Maximum count of the roll number of a perticular standard
  ****************************************************************************
  */
  function getSchoolCode($schoolid)
  {
	  $sclq = new SchoolQuery();
	  $sclq->connect();
	  $school=$sclq->get($schoolid);
	  return $school->getSchoolCode();
  }
  
  function getStandards($schoolid)
  {
  	$sql=$this->mkSQL("SELECT concat(standard,grade) as standard_grade,max(roll_no) as max" 
  			." FROM member where schoolid= %N  group by standard_grade order by max desc",$schoolid);
  	return $this->exec($sql);
  }
  
  function getRollNumber($schoolid,$standard,$grade)
  {
  	$sql=$this->mkSQL("select l.roll_no+1 as number from member as l left outer join" 
  			." member as r on l.roll_no+1=r.roll_no where r.roll_no is null" 
  			." and l.schoolid=%Q and l.standard=%Q and l.grade=%Q",$schoolid,$standard,$grade);
  	$result= $this->exec($sql);
  	return $result[0]['number'];
  }

  function updateStandards($schoolid,$standard,$grade)
  {
  	$sql=$this->mkSQL("update member set standard=standard+1" 
  			." where standard=%N and grade=%N and schoolid=%N",$standard,$grade,$schoolid);
  	return $this->exec($sql);
  }
  
  /*Function to check whether the sequence starts with 1 or not and if not, assigns 1 or the discarded number, else gives the max roll_number*/
  function rollCheck($schoolid,$standard,$grade)
  {
  	$sql=$this->mkSQL("select roll_no as roll from member" 
  			." where grade=%Q and schoolid=%Q and standard=%Q order by roll_no",$grade,$schoolid,$standard);
  	$result= $this->exec($sql);
  	$base=$result[0]['roll']-($result[0]['roll']%100);
//  	$roll_num='';
  	foreach($result as $mem)
  	{
  		if($mem['roll']-$base>1)
  		{
  			$roll_num=$base+1;
  			break;
  		}
  		else
  			$base=$mem['roll'];
  	}
  	if((!isset($roll_num))||($roll_num==''))
  		$roll_num=$base+1;
  	return $roll_num;
  }
  
  /* Returns the generated Barcode number as the combination of school id and roll number*/
  function assignRollNumber($mbr)
  {
		  $standardsList = $this->getStandards($mbr->getSchoolId());
		  $standards=array();
		  foreach ($standardsList as $standard)
		  {
		  	$standards[$standard['standard_grade']]=$standard['max'];
		  }
		  $std=$mbr->getStandard();
		  $stdGrade=$mbr->getGrade();
		  $standardGrade=$std."".$stdGrade;
		  /*If section does not exists*/
		  if(($standards[$standardGrade]==null))
		  {
		  	$prev_roll=$standardsList[0]['max'];
		  	if($prev_roll>0)
		  	{
		  		$roll=$prev_roll+100;
		  		$roll-=($prev_roll%100);
		  	}
		  	else
		  		$roll=0;
		  	$rollNumber=($roll+1);
		  }
		  /*If section exists*/
		  else
		  {
		  	$rollNumber=$this->rollCheck($mbr->getSchoolId(), $mbr->getStandard(), $mbr->getGrade());
		  }
		  $roll=$this->leading_zeros($rollNumber, 3);
		  $mbr->setRollNo($roll);
		  $schoolcode= $this->getSchoolCode($mbr->getSchoolId());
		  $mbr->setBarcodeNmbr($schoolcode."".$roll);
	  	return $mbr->getBarcodeNmbr();
  }

  #**************************************************************************
  #*  Function for Padding Zeros
  #**************************************************************************
  function leading_zeros($value, $places){
  	if(is_numeric($value)){
  		for($x = 1; $x <= $places; $x++){
  			$ceiling = pow(10, $x);
  			if($value < $ceiling){
  				$zeros = $places - $x;
  				for($y = 1; $y <= $zeros; $y++){
  					$leading .= "0";
  				}
  				$x = $places + 1;
  			}
  		}
  		$output = $leading . $value;
  	}
  	else{
  		$output = $value;
  	}
  	return $output;
  }
  
  /****************************************************************************
   * Update a library member in the member table.
   * @param Member $mbr library member to update
   * @access public
   ****************************************************************************
   */
  function update($mbr) {
    $sql = $this->mkSQL("update member set "
                        . " last_change_dt = sysdate(), last_change_userid=%N, barcode_nmbr=%Q, "
                        . " last_name=%Q,  first_name=%Q, address=%Q,"
						. " schoolid=%Q, standard=%Q, grade=%Q, roll_no=%Q , parent_name=%Q ,"
						. " parent_occupation=%Q , mother_tongue=%Q ,"
                        . " home_phone=%Q, work_phone=%Q,"
                        . " email=%Q, classification=%Q, gender=%Q, school_teacher=%Q "
                        . "where mbrid=%N",
                        $mbr->getLastChangeUserid(), $mbr->getBarcodeNmbr(),
                        $mbr->getLastName(), $mbr->getFirstName(),
                        $mbr->getAddress(), $mbr->getSchoolId() ,
						$mbr->getStandard(),$mbr->getGrade(),$mbr->getRollNo(),
						$mbr->getParentName(),$mbr->getParentOccupation(),
						$mbr->getMotherTongue(), $mbr->getHomePhone(),
                        $mbr->getWorkPhone(), $mbr->getEmail(),
                        $mbr->getClassification(),$mbr->getGender(),$mbr->getSchoolTeacher(),$mbr->getMbrid());

    $this->exec($sql);
    $this->setCustomFields($mbr->getMbrid(), $mbr->_custom);
  }

  /****************************************************************************
   * Deletes a library member from the member table.
   * @param string $mbrid Member id of library member to delete
   * @access public
   ****************************************************************************
   */
  function delete($mbrid) {
    $sql = $this->mkSQL("delete from member where mbrid = %N ", $mbrid);
    $this->exec($sql);
    $sql = $this->mkSQL("delete from member_fields where mbrid = %N ", $mbrid);
    $this->exec($sql);
  }
  function deleteCustomField($code) {
    $sql = $this->mkSQL("delete from member_fields where code = %Q ", $code);
    $this->exec($sql);
  }
}

?>
