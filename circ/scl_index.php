<?php
/* This file is part of a copyrighted work; it is distributed with NO WARRANTY.
 * See the file COPYRIGHT.html for more details.
 */
 
  require_once("../shared/common.php");
  session_cache_limiter(null);

  $tab = "circulation";
  $nav = "schoolSearch";
  $helpPage = "circulation";
  
  require_once("../shared/logincheck.php");
  require_once("../shared/header.php");
  require_once("../classes/Localize.php");
  $loc = new Localize(OBIB_LOCALE,$tab);

  if (isset($_REQUEST['msg'])) {
    echo '<font class="error">'.H($_REQUEST['msg']).'</font>';
  }
?>

<h1><img src="../images/circ.png" border="0" width="30" height="30" align="top"> <?php echo $loc->getText("sclIndexHeading"); ?></h1>
<form name="schoolcodesearch" method="POST" action="../circ/scl_search.php">
<table class="primary">
  <tr>
    <th valign="top" nowrap="yes" align="left">
      <?php echo $loc->getText("sclCodeSearch"); ?>
    </th>
  </tr>
  <tr>
    <td nowrap="true" class="primary">
      <?php echo $loc->getText("sclCode"); ?>
      <input type="text" name="searchText" size="20" maxlength="20">
      <input type="hidden" name="searchType" value="schoolCode">
      <input type="submit" value="<?php echo $loc->getText("sclIndexSearch"); ?>" class="button">
    </td>
  </tr>
</table>
</form>


<form name="schoolnamesearch" method="POST" action="../circ/scl_search.php">
<table class="primary">
  <tr>
    <th valign="top" nowrap="yes" align="left">
      <?php echo $loc->getText("sclSearchName"); ?>
    </td>
  </tr>
  <tr>
    <td nowrap="true" class="primary">
      <?php echo $loc->getText("sclName"); ?>
      <input type="text" name="searchText" size="30" maxlength="80">
      <input type="hidden" name="searchType" value="schoolName">
      <input type="submit" value="<?php echo $loc->getText("sclIndexSearch"); ?>" class="button">
    </td>
  </tr>
</table>
</form>

<?php include("../shared/footer.php"); ?>
