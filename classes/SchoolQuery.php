<?php
/* This file is part of a copyrighted work; it is distributed with NO WARRANTY.
 * See the file COPYRIGHT.html for more details.
 */
 
require_once("../shared/global_constants.php");
require_once("../classes/School.php");
require_once("../classes/Query.php");

/******************************************************************************
 * SchoolQuery data access component for library members
 *
 * @author karthikeya
 * @version 1.0
 * @access public
 ******************************************************************************
 */
class SchoolQuery extends Query {
	
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
		if ($type == "schoolCode") {
			$col = "school_code";
		} elseif ($type == "schoolName") {
			$col = "school_name";
		}
	
		# Building sql statements
		$sql = $this->mkSQL("from school where %C like %Q ", $col, $word."%");
		$sqlcount = "select count(*) as rowcount ".$sql;
    $sql = "select * ".$sql;
    $sql .= " order by school_name";
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
  function get($schoolid) {
    $sql = $this->mkSQL("select school.*, school.school_name from school "
                        . "left join staff on school.last_change_userid = staff.userid "
                        . "where schoolid=%N ", $schoolid);
    $rows = $this->exec($sql);
    if (count($rows) != 1) {
      Fatal::internalError("Bad Schoolid");
    }
    return $this->_mkObj($rows[0]);
  }
  
  function getSchoolName($schoolid){
  	$sql = $this->mkSQL("select school_name from school where schoolid= %N",$schoolid);
  	$schoolList = $this->exec($sql);
  	$school=$schoolList[0];
  	return $school['school_name'];  	 
  }
  
  function getSchoolList() {
  	$assoc = array();
  	$sql = $this->mkSQL("select schoolid, school_name from school ");
  	$schoolList = $this->exec($sql);
  	 
  	foreach ($schoolList as $school) {
  		$assoc[$school['schoolid']] = $school['school_name'];
  	}
  	return $assoc;
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
    $scl = new School();
    $scl->setSchoolid($array["schoolid"]);
    $scl->setLastChangeDt($array["last_change_dt"]);
    $scl->setLastChangeUserid($array["last_change_userid"]);
	$scl->setContactNumber($array["contact_number"]);
	$scl->setcontactPerson($array["contact_person"]);
    $scl->setSchoolAddress($array["school_address"]);
    $scl->setSchoolName($array["school_name"]);
    $scl->setSchoolCode($array["school_code"]);
    $scl->setEmail($array["email"]);
    return $scl;
  }

  /****************************************************************************
   * Inserts a new school into the school table.
   * @param School $scl  library school to insert
   * @return integer the id number of the newly inserted school
   * @access public
   ****************************************************************************
   */
  function insert($scl) {
    $sql = $this->mkSQL("insert into school "
                        . "(schoolid, create_dt, last_change_dt, "
                        . " last_change_userid, school_name, school_code, school_address, contact_person,"
						. " contact_number, email) "
                        . "values (null, sysdate(), sysdate(), %N, "
                        . " %Q, null, %Q, %Q, %Q, %Q) ",
                        $scl->getLastChangeUserid(),
                        $scl->getSchoolName(), $scl->getSchoolAddress(), $scl->getcontactPerson(),
						$scl->getContactNumber(),$scl->getEmail());

    $this->exec($sql);
    $schoolid = $this->_conn->getInsertId();
    $scl->setSchoolid($schoolid);
    $scl->setSchoolCode($schoolid);
    $this->update($scl);
    return $schoolid;
  }

  /****************************************************************************
   * Update a library school in the member table.
   * @param School $scl library school to update
   * @access public
   ****************************************************************************
   */
  function update($scl) {
    $sql = $this->mkSQL("update school set "
                        . " last_change_dt = sysdate(), last_change_userid=%N, "
  						. " school_name=%Q, school_code=%Q, school_address=%Q, contact_person=%Q, "
						. " contact_number=%Q, email=%Q"
                        . " where schoolid=%N",
                        $scl->getLastChangeUserid(),$scl->getSchoolName(), $scl->getSchoolCode(),
                        $scl->getSchoolAddress(), $scl->getcontactPerson(),
						$scl->getContactNumber(),$scl->getEmail(), $scl->getSchoolid());

    $this->exec($sql);
  }

  /****************************************************************************
   * Deletes a library member from the member table.
   * @param string $mbrid Member id of library member to delete
   * @access public
   ****************************************************************************
   */
  function delete($schoolid) {
    $sql = $this->mkSQL("delete from school where schoolid = %N ", $schoolid);
    $this->exec($sql);
  }
  
  function deleteConfirm($schoolid)
  {
  	$sql = $this->mkSQL("select c.*	from biblio_copy c, member m, school s"
	." where c.mbrid = m.mbrid and m.schoolid = s.schoolid"
	." and c.status_cd = 'out' and s.schoolid = %N ", $schoolid);
  	if(!$this->exec($sql))
  		return true;
  	return false;
  }
}

?>
