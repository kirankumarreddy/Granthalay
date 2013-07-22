<?php
/* This file is part of a copyrighted work; it is distributed with NO WARRANTY.
 * See the file COPYRIGHT.html for more details.
 */
 
  require_once("../shared/common.php");
  session_cache_limiter(null);

  $tab = "circulation";
  $nav = "searchResults";
  require_once("../shared/logincheck.php");
  require_once("../classes/School.php");
  require_once("../classes/SchoolQuery.php");
  require_once("../functions/searchFuncs.php");
  require_once("../classes/DmQuery.php");
  require_once("../classes/Localize.php");
  $loc = new Localize(OBIB_LOCALE,$tab);

  #****************************************************************************
  #*  Function declaration only used on this page.
  #****************************************************************************
  function printResultPages($currPage, $pageCount) {
    global $loc;
    $maxPg = 21;
    if ($currPage > 1) {
      echo "<a href=\"javascript:changePage(".H(addslashes($currPage-1)).")\">&laquo;".$loc->getText("mbrsearchprev")."</a> ";
    }
    for ($i = 1; $i <= $pageCount; $i++) {
      if ($i < $maxPg) {
        if ($i == $currPage) {
          echo "<b>".H($i)."</b> ";
        } else {
          echo "<a href=\"javascript:changePage(".H(addslashes($i)).")\">".H($i)."</a> ";
        }
      } elseif ($i == $maxPg) {
        echo "... ";
      }
    }
    if ($currPage < $pageCount) {
      echo "<a href=\"javascript:changePage(".($currPage+1).")\">".$loc->getText("mbrsearchnext")."&raquo;</a> ";
    }
  }

  #****************************************************************************
  #*  Checking for post vars.  Go back to form if none found.
  #****************************************************************************
  if (count($_POST) == 0) {
    header("Location: ../circ/index.php");
    exit();
  }

  #****************************************************************************
  #*  Retrieving post vars and scrubbing the data
  #****************************************************************************
  if (isset($_POST["page"])) {
    $currentPageNmbr = $_POST["page"];
  } else {
    $currentPageNmbr = 1;
  }
  $searchType = $_POST["searchType"];
  $searchText = trim($_POST["searchText"]);
  # remove redundant whitespace
  $searchText = preg_replace('/\s+/', " ", $searchText);

  #****************************************************************************
  #*  Search database
  #****************************************************************************
  $sclQ = new SchoolQuery();
  $sclQ->setItemsPerPage(OBIB_ITEMS_PER_PAGE);
  $sclQ->connect();
  $sclQ->execSearch($searchType,$searchText,$currentPageNmbr);

  #**************************************************************************
  #*  Show member view screen if only one result from barcode query
  #**************************************************************************
  if (($searchType == "schoolCode") && ($sclQ->getRowCount() == 1)) {
    $scl = $sclQ->fetchMember();
    $sclQ->close();
    header("Location: ../circ/scl_view.php?sclid=".U($scl->getSchoolid())."&reset=Y");
    exit();
  }

  #**************************************************************************
  #*  Show search results
  #**************************************************************************
  require_once("../shared/header.php");
  
  # Display no results message if no results returned from search.
  if ($sclQ->getRowCount() == 0) {
    $sclQ->close();
    echo $loc->getText("sclsearchNoResults");
    require_once("../shared/footer.php");
    exit();
  }
?>

<!--**************************************************************************
    *  Javascript to post back to this page
    ************************************************************************** -->
<script language="JavaScript" type="text/javascript">
<!--
function changePage(page)
{
  document.changePageForm.page.value = page;
  document.changePageForm.submit();
}
-->
</script>


<!--**************************************************************************
    *  Form used by javascript to post back to this page
    ************************************************************************** -->
<form name="changePageForm" method="POST" action="../circ/scl_search.php">
  <input type="hidden" name="searchType" value="<?php echo H($_POST["searchType"]);?>">
  <input type="hidden" name="searchText" value="<?php echo H($_POST["searchText"]);?>">
  <input type="hidden" name="page" value="1">
</form>

<!--**************************************************************************
    *  Printing result stats and page nav
    ************************************************************************** -->
<?php echo H($sclQ->getRowCount()); echo $loc->getText("sclsearchFoundResults");?><br>
<br>

<!--**************************************************************************
    *  Printing result table
    ************************************************************************** -->
<table class="primary">
  <tr>
    <th valign="top" nowrap="yes" align="left" colspan="2">
      <?php echo $loc->getText("sclsearchSearchResults");?>
    </th>
  </tr>
  <?php
    while ($scl = $sclQ->fetchMember()) {
  ?>
  <tr>
    <td nowrap="true" class="primary" valign="top">
      <?php echo H($sclQ->getCurrentRowNmbr());?>.
    </td>
    <td nowrap="true" class="primary">
       <a href="../circ/scl_view.php?sclid=<?php echo HURL($scl->getSchoolId());?>&amp;reset=Y"><?php echo H($scl->getSchoolName());?>, <?php echo H($scl->getSchoolAddress());?></a><br>
      <?php
        if ($scl->getSchoolAddress() != "")
          echo H($scl->getSchoolAddress());
      ?>
      <b><?php echo $loc->getText("sclFldsSchoolCode");?></b> <?php echo H($scl->getSchoolCode());?>
      <b><?php echo $loc->getText("sclFldsSchoolName");?></b> <?php echo H($scl->getSchoolName());?>
    </td>
  </tr>


  <?php
    }
    $sclQ->close();
  ?>
  </table><br>
<?php require_once("../shared/footer.php"); ?>
