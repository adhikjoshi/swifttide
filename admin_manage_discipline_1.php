<?php
//*
// admin_manage_discipline_1.php
// Admin Section
// Display discipline records for student
//*
//Version 1.01 April 4,2005

//Check if admin is logged in
session_start();
if(!session_is_registered('UserId') || $_SESSION['UserType'] != "A")
  {
    header ("Location: index.php?action=notauth");
	exit;
}

//Include global functions
include_once "common.php";
//Initiate database functions
include_once "ez_sql.php";
//Include paging class
include_once "ez_results.php";
// config
include_once "configuration.php";
$msgFormErr="";

$menustudent=1;
$current_year=$_SESSION['CurrentYear'];

//Get student id
$studentid=get_param("studentid");
if(!strlen($studentid)){
	$msgFormErr=_ADMIN_MANAGE_DISCIPLINE_1_FORM_ERROR . "<br>";
}else{
	//Get Student Name
	$sSQL="SELECT studentbio_lname, studentbio_fname FROM studentbio WHERE studentbio_id=$studentid";
	$student=$db->get_row($sSQL);
	$slname=$student->studentbio_lname;
	$sfname=$student->studentbio_fname;
	//Get discipline history
	$sSQL="SELECT discipline_history.discipline_history_id, 
	DATE_FORMAT(discipline_history.discipline_history_date, '" . _EXAMS_DATE . "') as disdate, 
	infraction_codes.infraction_codes_desc 
	FROM discipline_history 
	INNER JOIN infraction_codes ON discipline_history.discipline_history_code = infraction_codes.infraction_codes_id 
	WHERE discipline_history.discipline_history_student=$studentid 
	AND discipline_history.discipline_history_year=$current_year 
	ORDER BY discipline_history.discipline_history_date DESC";
	//Set paging appearence
	$ezr->results_open = "<table width=70% cellpadding=2 cellspacing=0 border=1>";
	$ezr->results_heading = "<tr class=tblhead>
		<td width=40%>" . _ADMIN_MANAGE_DISCIPLINE_1_DATE . "</td>
		<td width=40%>" . _ADMIN_MANAGE_DISCIPLINE_1_CODE . "</td>
		<td width=20%>" . _ADMIN_MANAGE_DISCIPLINE_1_DETAILS . "</td>
		</tr>"; 
	$ezr->results_close = "</table>";
	$ezr->results_row = "<tr>
		<td align=center class=paging width=40%>COL2</td>
		<td class=paging width=40% align=center>COL3</td>
		<td class=paging width=20% align=center><a href=admin_manage_discipline_2.php?studentid=$studentid&disid=COL1 class=aform>&nbsp;" . _ADMIN_MANAGE_DISCIPLINE_1_DETAILS . "</a></td>
		</tr>";
	$ezr->query_mysql($sSQL);
	$ezr->set_qs_val("studentid",$studentid);
};
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">

<head>
<meta http-equiv="content-type" content="text/html; charset=iso-8859-1" />
<title><?php echo _BROWSER_TITLE?></title>
<style type="text/css" media="all">@import "student-admin.css";</style>
<link rel="icon" href="favicon.ico" type="image/x-icon"><link rel="shortcut icon" href="favicon.ico" type="image/x-icon">

<script type="text/javascript" language="JavaScript" src="sms.js"></script>
</head>

<body><img src="images/<?php echo _LOGO?>" border="0">

<div id="Header">
<table width="100%">
  <tr>
    <td width="50%" align="left"><font size="2">&nbsp;&nbsp;<?php echo date(_DATE_FORMAT); ?></font></td>
    <td width="50%"><?php echo _ADMIN_MANAGE_DISCIPLINE_1_UPPER?></td>
  </tr>
</table>
</div>

<div id="Content">
	<?php
	if(!strlen($msgFormErr)){
	?>
	<h1><?php echo _ADMIN_MANAGE_DISCIPLINE_1_TITLE?></h1>
	<br>
	<h2><?php echo $sfname. " " .$slname; ?></h2>
	<br>
	<?php
	$ezr->display();
	?>
	<br>
	<table border="0" cellpadding="0" cellspacing="0" width="70%">
	  <tr>
	    <td width="50%"><a href="admin_edit_student_1.php?studentid=<?php echo $studentid; ?>" class="aform"><?php echo _ADMIN_MANAGE_DISCIPLINE_1_BACK?></a></td>
	    <td width="50%" align="right"><a href="admin_manage_discipline_3.php?studentid=<?php echo $studentid; ?>&action=new" class="aform"><?php echo _ADMIN_MANAGE_DISCIPLINE_1_ADD?></a></td>
	  </tr>
	</table>
	<?php
	}else{
	?>
	<h1><?php echo _ERROR?></h1>
	<br>
	<h3><?php echo $msgFormErr; ?></h3>
	<br>
	<?php
	};
	?>
</div>
<?php include "admin_menu.inc.php"; ?>
</body>

</html>
