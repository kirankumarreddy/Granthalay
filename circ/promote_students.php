<?php
/* This file is part of a copyrighted work; it is distributed with NO WARRANTY.
 * See the file COPYRIGHT.html for more details.
 */
 
  require_once("../shared/common.php");
  $tab = "circulation";
  $nav = "promote";
  $helpPage = "promote";
  
  require_once("../functions/inputFuncs.php");
  require_once("../functions/formatFuncs.php");
  require_once("../shared/logincheck.php");
  require_once("../classes/School.php");
  require_once("../classes/SchoolQuery.php");
  require_once("../classes/Member.php");
  require_once("../classes/MemberQuery.php");
  require_once("../classes/DmQuery.php");
  require_once("../shared/get_form_vars.php");
  require_once("../classes/Localize.php");
  $loc = new Localize(OBIB_LOCALE,$tab);

  #****************************************************************************
  #*  Checking for get vars.  Go back to form if none found.
  #****************************************************************************
  if (count($_GET) == 0) {
    header("Location: ../circ/index.php");
    exit();
  }

  #****************************************************************************
  #*  Retrieving get var
  #****************************************************************************
  $sclid = $_GET["sclid"];
  if (isset($_GET["msg"])) {
    $msg = "<font class=\"error\">".H($_GET["msg"])."</font><br><br>";
  } else {
    $msg = "";
  }

  $schoolid=$_POST['sclid'];
  $standard=$_POST['standardLevel'];
  $grade=$_POST['standardGrade'];
  $mbrQ = new MemberQuery();
  $mbrQ->connect();
  $mbrQ->updateStandards($schoolid, $standard, $grade);
  
  #****************************************************************************
  #*  Search database for school
  #****************************************************************************  
  $sclQ = new SchoolQuery();
  $sclQ->connect();
  $scl= $sclQ->get($sclid);
  $sclQ->close();
  
  #**************************************************************************
  #*  Show school information
  #**************************************************************************
  require_once("../shared/header.php");
?>

<table class="primary" align="center" >
  <tr><td class="noborder" valign="top">
  <br>
<table class="primary" border="0" >
  <tr>
    <th align="center" colspan="2" nowrap="yes">
      <?php echo $loc->getText("sclInformation"); ?>
    </th>
  </tr>
  <tr>
    <td nowrap="true" class="primary" valign="top">
      <?php echo $loc->getText("sclFldsSchoolName"); ?>
    </td>
    <td valign="top" class="primary">
      <?php echo H($scl->getSchoolName());?>
    </td>
  </tr>
    <tr>
    <td class="primary" valign="top">
      <?php echo $loc->getText("sclFldsSchoolCode"); ?>
    </td>
    <td valign="top" class="primary">
      <?php
        echo H($scl->getSchoolCode());
      ?>
    </td>
  </tr>
  
    <?php 
		  $standardLevel=array(0=>0,1=>1,2=>2,3=>3,4=>4,5=>5,6=>6,7=>7,8=>8,9=>9);
		  $standardGrade=array(''=>'none','A'=>'A','B'=>'B','C'=>'C','D'=>'D','E'=>'E',
							'F'=>'F','G'=>'G','H'=>'H','I'=>'I','J'=>'J','K'=>'K','L'=>'L','M'=>'M',
							'N'=>'N','O'=>'O','P'=>'P','Q'=>'Q','R'=>'R','S'=>'S','T'=>'T',
							'U'=>'U','V'=>'V','W'=>'W','X'=>'X','Y'=>'Y','Z'=>'Z');
		  $fields = array(
			"sclStandard" => inputField('select', 'standardLevel', null, NULL, $standardLevel),
			"sclGrade" => inputField('select', 'standardGrade', null, NULL, $standardGrade)
		);
  ?>  
	  <?php
		  foreach ($fields as $title => $html) {
	?>
	  <tr>
	    <td nowrap="true" class="primary" valign="top">
	     <?php echo $loc->getText($title); ?>
	    </td>
	    <td valign="top" class="primary">
	      <?php echo $html; ?>
	    </td>
	  </tr>
	<?php
	  }
	?>	
	  <tr>
    <td align="center" colspan="2" class="primary">
      <input type="submit" value="<?php echo $loc->getText("sclFldsPromote"); ?>" class="button">
      <input type="button" onClick="self.location='<?php echo H(addslashes($cancelLocation));?>'" value="<?php echo $loc->getText("sclFldsCancel"); ?>" class="button">
    </td>
  </tr>
</table>
<?php require_once("../shared/footer.php"); ?>
