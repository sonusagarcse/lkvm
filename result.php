<!doctype html>
<html lang="en-US">
<head>
<?php include('csslink.php');?>

<?php 
if(isset($_REQUEST['submit'])){
$regno=$_REQUEST['regno'];
$status=1;
$sel=$con->prepare("select * from registration where regno=? and status=?");
$exe=$sel->execute([$regno,$status]);
$resn=$sel->fetch();

$rollno=$resn->rollno;
$mstatus=1;
$sel=$con->prepare("select * from m_marksheet where rollno=? and status=?");
$exe=$sel->execute([$rollno,$mstatus]);
$resm=$sel->fetch();

$pid=$resn->program; 
$cid=$resn->course;
$selp=$con->prepare("select * from courses where id=?");
$exep=$selp->execute([$cid]);
$resp=$selp->fetch();

$bid=$resn->bid;
$selp=$con->prepare("select * from branch where id=?");
$exep=$selp->execute([$bid]);
$resplt=$selp->fetch();
}
?>
<style id='eikra-style-inline-css' type='text/css'>
.entry-banner {
background: url(images/banner.jpg) no-repeat scroll center center / cover;
}
.content-area {
padding-top: 50px;
padding-bottom: 0px;
}
#learn-press-block-content span {
background-image: url("images/preloader.gif");
}
		
</style>
</head>
<body class="home page-template-default page page-id-54 wp-embed-responsive theme-eikra woocommerce-no-js Eikra-version-4.4.6 header-style-9 footer-style-2 has-topbar topbar-style-7 no-sidebar rt-course-grid-view product-grid-view wpb-js-composer js-comp-ver-6.9.0 vc_responsive">
<?//php include('loader.php');?>
<div id="page" class="site">
<?php include('header.php');?>

<div id="content" class="site-content">
<div class="entry-banner">
<div class="container">
<div class="entry-banner-content">
<h1 class="entry-title">Result</h1>
<div class="breadcrumb-area"><div class="entry-breadcrumb"><!-- Breadcrumb NavXT 7.2.0 -->
<span property="itemListElement" typeof="ListItem"><a property="item" typeof="WebPage" title="Home" href="<?php echo $SITE_URL;?>" class="home" ><span property="name">Home</span></a><meta property="position" content="1"></span> &gt; <span property="itemListElement" typeof="ListItem"><span property="name" class="post post-page current-item">Result</span><meta property="url" content="#"><meta property="position" content="2"></span></div></div>
</div>
</div>
</div>

<div id="primary" class="content-area">
<div class="container">
<div class="row">
<div class="col-sm-12 col-12">
<main id="main" class="site-main">
<article id="post-1225" class="post-1225 page type-page status-publish hentry">
<div class="entry-content">
<div class="vc_row wpb_row vc_row-fluid vc_custom_1510748400939">
<div class="wpb_column vc_column_container vc_col-sm-12">
<div class="vc_column-inner">
<div class="wpb_wrapper">
<div class="wpb_text_column wpb_content_element " >
<div class="wpb_wrapper">


<div class="row">
                 <div class="col-sm-3">
                </div>
                <div class="col-sm-6 col-sm-6 col-xs-12">
				<style>
				.login-form form {
    text-align: left;
    padding: 30px;
    border: 1px solid #f1f1f1;
    box-shadow: 1px 1px 20px #cccccc57;
}
.form-title {
    text-align: center;
    margin-bottom: 30px;
}
.form-title h2, .form-title h3 {
    position: relative;
    margin-bottom: 20px;
    padding-bottom: 15px;
}
h2 {
    font-size: 32px;
}
h1, h2, h3, h4, h5, h6 {
    font-weight: 800;
    color: #212121;
    font-family: 'Raleway', sans-serif;
    margin-top: 0;
    line-height: 1.5;
    margin-bottom: 15px;
   
}
label {
    display: inline-block;
    color: #666;
    margin-bottom: 8px;
    font-weight: 400;
    font-size: 15px;
}
input[type=text], input[type=email], input[type=number], input[type=search], input[type=password], input[type=tel], textarea, select {
    font-size: 14px;
    font-weight: 300;
    background-color: #fff;
    border: 1px solid #060606;
    border-radius: 0;
    padding: 10px 15px;
    width: 100%;
    color: #444444;
    margin-bottom: 15px;
    font-family: 'Poppins', sans-serif;
    height: 42px;
    box-shadow: none;
    margin-bottom: 0;
}
.form-control {
    display: block;
    width: 100%;
    height: 34px;
    padding: 6px 12px;
    font-size: 14px;
    line-height: 1.42857143;
    color: #555;
    background-color: #fff;
    background-image: none;
    border: 1px solid #2b2929;
    border-radius: 4px;
    -webkit-box-shadow: inset 0 1px 1px rgb(0 0 0 / 8%);
    box-shadow: inset 0 1px 1px rgb(0 0 0 / 8%);
    -webkit-transition: border-color ease-in-out .15s,-webkit-box-shadow ease-in-out .15s;
    -o-transition: border-color ease-in-out .15s,box-shadow ease-in-out .15s;
    transition: border-color ease-in-out .15s,box-shadow ease-in-out .15s;
}
.form-group {
    margin-bottom: 15px;
}
				</style>
                    <div class="login-form">
                        <form  method="post">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-title">
                                        <h2>Result Verification</h2>
                                         
                                    </div>
                                </div>
                                <div class="form-group col-md-12">
					<?php
     				if(isset($_REQUEST['submit'])){
     				if($resn->regno!=$_REQUEST['regno'] || $resn->status!=1 || $resm->status!=1){
					?>
		  			<strong style="color:#FF0000;">"Oops!", "Your Details  Are Wrong!", "error"</strong><br/>
	  				<?php } }?> 
					<label>Enrollment Number</label>
							<input type="text" class="form-control" id="Name1" placeholder="Enter Enrollment Number" name="regno" value="<?php echo $_REQUEST['regno'];?>" required>
                                </div>
                               
                                <div class="col-md-12">
                                    <div class="comment-btn">
                                        <button class="wpcf7-form-control has-spinner wpcf7-submit rdtheme-button-2" type="submit" name="submit">Verify</button>
                                    </div>
                                </div>
                        </form>
                    </div>
                </div>
                <div class="col-sm-3 ">
                    
                </div>
            </div>


<div class="row">
		   <div class="col-sm-12">
		   <style>
		   .textdb{
		   color:#000000; 
		   font-size:16px;
		   }
		   .table>tbody>tr>td{
		   padding:2px;
		   vertical-align:middle;
		   }
		   </style>
		   <div class="textdb">
		
				
<?php 
if($resn->regno==$_REQUEST['regno']){
if(isset($_REQUEST['submit'])){
if($resn->rollno==$resm->rollno){
?>			


<script>
function printPageArea(areaID){
var printContent = document.getElementById(areaID);
var WinPrint = window.open('', '', 'width=1100,height=750');
WinPrint.document.write(printContent.innerHTML);
WinPrint.document.close();
WinPrint.focus();
WinPrint.print();
WinPrint.close();
}
</script>

<a href="javascript:void(0);" onClick="printPageArea('printableArea')" style="text-align:right; padding:10px; background:#990066; color:#FFFFFF; margin:10px; text-decoration:none; border-radius:10px;">Print Result</a>
<br/><br/>
<div id="printableArea">
<div style="border: solid 5px #000;">
<div style="padding:15px;">
<?php $doj=$resm->issue; $changeDate= date("d-m-Y", strtotime($doj));?>
<h2 style="text-align:center; font-weight:800; margin:0px; text-transform: uppercase; font-size:23px;"><?php echo $resplt->bname;?></h2>
<h5 style="text-align:center;margin:0px;"><?php echo $resplt->baddress;?></h5>
<h5 style="text-align:center;margin:0px;">Email: <strong style="color:#FF0000;"><?php echo $resplt->bemail;?></strong>, Contact No.: <strong style="color:#FF0000;"><?php echo $resplt->bcontact;?></strong></h5>
<h3 style="text-align:center; font-weight:800; margin:0px; text-transform: uppercase; font-size:18px;">Associated with : <?php echo $SITE_NAME;?></h3>
<h5 style="text-align:center;margin:0px;">Website: <strong style="color:#FF0000;"><?php echo $SITE_URL;?></strong></h5>
<br/>
<h3 style="text-align:center; font-weight:800; margin:0px; color:#990033;">Result Verification</h3>
<hr style="height:5px; background:#000000;">

<div style="padding-left:30px; padding-right:30px; padding-top:20px;">
<style>
td{
padding:2px;
font-size:15px;
}
</style>
<table width="100%" cellpadding="0" cellspacing="0">

<tr>
<td width="25%" ><strong>ENROLLMENT NO.</strong> </td>
<td width="5%" ><strong>:</strong></td>
<td width="70%" ><?php echo $resn->regno;?></td>
</tr>

<tr>
<td><strong>ROLL NO.</strong> </td>
<td><strong>:</strong></td>
<td><?php echo $resn->rollno;?></td>
</tr>


<tr>
<td><strong>STUDENT'S NAME</strong>  </td>
<td><strong>:</strong></td>
<td><?php echo $resn->name;?></td>
</tr>

<tr>
<td ><strong>FATHER'S NAME</strong>  </td>
<td><strong>:</strong></td>
<td ><?php echo $resn->father;?></td>
</tr>

<tr>
<td><strong>SESSION</strong> </td>
<td><strong>:</strong></td>
<td><?php echo $resn->asession;?></td>
</tr>

<tr>
<td><strong>COURSE NAME</strong> </td>
<td><strong>:</strong></td>
<td>
<?php echo $resp->name;?><strong style="color:#FF0000;"><?//php echo $resp->name;?></strong>
</td>
</tr>

</table>
</div>

<div style="padding-left:10px; padding-right:10px; padding-top:10px;">
<h3 style="font-weight:600;margin:0px; font-size:18px;">Statement of Marks</h3>
<table class="table table-bordered" id="tbl" border="1" width="100%" cellpadding="0" cellspacing="0" style="background:#fff;">
<tr style="color:#fff; background-color:#066dc2;font-weight:bold;">
<td rowspan="2" style="text-align:center;">SL. NO.</td>
<td rowspan="2">COURSE/SUBJECT/MODULES</td>
<td colspan="2" style="text-align:center;">THEORY MARKS</td>
<td colspan="2" style="text-align:center;">PRACTICAL MARKS</td>
<td rowspan="2" style="text-align:center;">TOTAL MARKS</td>
</tr>
<tr style="color:#fff; background-color:#066dc2;font-weight:bold;">
<td style="text-align:center;">MAX.</td>
<td style="text-align:center;">OBTD.</td>
<td style="text-align:center;">MAX.</td>
<td style="text-align:center;">OBTD.</td>
</tr>
<?php
$sel=$con->prepare("select * from subjects where pid=? and cid=? order by id ASC");
$exe=$sel->execute([$pid,$cid]);
$k=1;
while($rest=$sel->fetch())
{
?>
<tr>
<td style="text-align:center;"><?php echo $k;?></td>
<td><?php echo $rest->name;?></td>
<td style="text-align:center;"><?php echo $total=$rest->total; $nettotal=$total+$nettotal;?></td>
<td style="text-align:center;">
<?php $theory=$resm->theory; $the=(explode(",",$theory)); $theo=$the[$k-1];?>
<?php echo $theo; $ttotal=$ttotal+$theo; ?></td>

<td style="text-align:center;"><?php echo $ptotal=$rest->passing; $netpractical=$ptotal+$netpractical;?></td>
<td style="text-align:center;">
<?php $practical=$resm->practical; $pr=(explode(",",$practical)); $pract=$pr[$k-1];?>
<?php echo $pract; $pmtotal=$pmtotal+$pract; ?></td>
<td style="text-align:center;"><?php echo $totaltp=$theo+$pract; $gtotal=$gtotal+$totaltp;?></td>
</tr>
<?php $k++;}?>
<tr>
<td style="text-align:center; font-weight:800;" colspan="2">Grand Total</td>
<td style="text-align:center; font-weight:800;"><?php echo $nettotal;?></td>
<td style="text-align:center; font-weight:800;"><?php echo $ttotal;?></td>
<td style="text-align:center; font-weight:800;"><?php echo $netpractical;?></td>
<td style="text-align:center; font-weight:800;"><?php echo $pmtotal; $gt=$nettotal+$netpractical;?></td>
<td style="text-align:center; font-weight:800;"><?php echo $nt=$gtotal;?></td>
</tr>
</table>
<br/>
<div style="float:left; width:49%;">
<table class="table table-bordered" id="tbl" border="1" width="100%" cellpadding="0" cellspacing="0" style="float:left; height:186px; background:#fff;">
<tr>
<td style="width:50%; font-weight:800; color:#660099;">TOTAL MARKS</td>
<td style="width:50%; font-weight:800; color:#3399CC;"><?php echo $nt;?>/<?php echo $gt;?></td>
</tr>
<tr>
<td style="width:50%; font-weight:800; color:#660099;">PERCENTAGE</td>
<td style="width:50%; font-weight:800; color:#3399CC;"><? $per=($nt*100)/$gt; echo round($per,2);?>%</td>
</tr>
<tr>
<td style="width:50%; font-weight:800; color:#660099;">RESULT</td>
<td style="width:50%; font-weight:800; color:#3399CC;"><?php if($per>=33){ echo "PASS";} elseif($per<33) { echo "FAIL";}?></td>
</tr>
<tr>
<td style="width:50%; font-weight:800; color:#660099;">GRADE</td>
<td style="width:50%; font-weight:800; color:#3399CC;"><?php if($per>=80){ echo "A+++";}  elseif($per<80 && $per>=60){ echo "A++";}elseif($per<60 && $per>=40){ echo "A+";} elseif($per<40 && $per>=33){ echo "A";} elseif($per<33) { echo "Not Applicable";}?></td>
</tr>
</table>
</div>
<div style="float:left; width:49%;">
<table class="table table-bordered" id="tbl" border="1" width="100%" cellpadding="0" cellspacing="0" style="float:left;margin-left:17px;background:#fff;">
<tr>
<td style="width:50%; font-weight:800; color:#660099; text-align:center;" colspan="2">GRADE PATTERN</td>
</tr>
<tr>
<td style="width:50%; font-weight:800; color:#660099;">PERCENTAGE RANGE</td>
<td style="width:50%; font-weight:800; color:#3399CC;">GRADE</td>
</tr>
<tr>
<td style="width:50%; font-weight:800; color:#660099;">100 - 81</td>
<td style="width:50%; font-weight:800; color:#3399CC;">A+++</td>
</tr>

<tr>
<td style="width:50%; font-weight:800; color:#660099;">80 - 61</td>
<td style="width:50%; font-weight:800; color:#3399CC;">A++</td>
</tr>

<tr>
<td style="width:50%; font-weight:800; color:#660099;">60 - 41</td>
<td style="width:50%; font-weight:800; color:#3399CC;">A+</td>
</tr>

<tr>
<td style="width:50%; font-weight:800; color:#660099;">40 - 33</td>
<td style="width:50%; font-weight:800; color:#3399CC;">A</td>
</tr>

<tr>
<td style="width:50%; font-weight:800; color:#660099;">32 - 0</td>
<td style="width:50%; font-weight:800; color:#3399CC;">Not Applicable</td>
</tr>
</table>
</div>
<h3 style="font-weight:600;margin:0px; padding-top:215px; font-size:18px;">Date of Issue : <strong style="color:#660099;"><?php  echo $changeDate;?></strong></h3>

<!--<div style="margin-right:80px; padding-top:15px;">
<img style="padding:0px;background:none;" src="https://chart.googleapis.com/chart?chs=110x110&cht=qr&chld=L|0&&chl=Enrollment No.: <?php echo $resn->regno;?>, Name: <?php echo $resn->name;?>, Father Name: <?php echo $resn->father;?>, Course: <?php echo $resp->name;?>, Date of Issue: <?php echo $changeDate;?>, Institute Name : <?php echo $SITE_NAME;?> - Marksheet Verified. " title="Verification Of Marksheet" align="right">
</div>-->

</div>
</div>
</div>
</div>

<?php }}} ?>
			
			
			
			</div>
           </div>
		   </div>

</div>
</div>

</div>
</div>
</div>
</div>

<div class="vc_row-full-width vc_clearfix"></div>
</div>

</article>
</main>

</div>
</div>
</div>
</div>
</div>
<!-- #content -->

<?php include('footer.php');?>
</body>
</html>