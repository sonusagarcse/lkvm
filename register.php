<!doctype html>
<html lang="en-US">
<head>
<?php include('csslink.php');?>
<?php 
$id=13;
$sel=$con->prepare("select * from webpage where id=? order by id DESC");
$sel->bind_param("i", $id);
$sel->execute();
$res=$sel->get_result()->fetch_object();
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
<h1 class="entry-title">Register Now</h1>
<div class="breadcrumb-area"><div class="entry-breadcrumb"><!-- Breadcrumb NavXT 7.2.0 -->
<span property="itemListElement" typeof="ListItem"><a property="item" typeof="WebPage" title="Home" href="<?php echo $SITE_URL;?>" class="home" ><span property="name">Home</span></a><meta property="position" content="1"></span> &gt; <span property="itemListElement" typeof="ListItem"><span property="name" class="post post-page current-item">Register Now</span><meta property="url" content="#"><meta property="position" content="2"></span></div></div>
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
<div class="wpb_wrapper" style="margin:10px;">

<div class="row">
<div class="col-md-12">		   
		
			<?php
     if(isset($_REQUEST['msg'])){
      if($_REQUEST['msg']=='send'){
          ?>
		<h5 style="color:red; text-align:center;font-size:18px;">  Thank You! <br> <strong style="color:#000;">Your registration has been completed successfully  <br/> We will contact you soon.</strong> <a href="register_now" style="color:blue;">Click Here</a></h5>
			
		  <?php
      } elseif($_REQUEST['msg']=='password_not_matched'){?>
              <h5 style="color:red; text-align:center; font-size:18px;">  <strong style="color:#000;">Your confirm password was not matched, Please register again </strong> <a href="register" style="color:blue;">Click Here</a></h5>          
	<?php }}else{?>
	</div>
	<div class="form"> 
                            <form action="../code/manage_member?flag=add" method="post">
                            <div class="row">
						<?php
						   $sel=$con->prepare("select * from registration order by id DESC LIMIT 1");
						   $sel->execute(); 
						   $nres=$sel->get_result()->fetch_object(); 
						  ?>    								
								
				<div class="form-group col-md-6 col-sm-12 col-xs-12 col-left-padding">  
				<label>Branch </label> 
				<input type="hidden" class="form-control" name="username" value="<?php echo $uname=$nres->username+1;?>">
				<input type="hidden" class="form-control" name="srno" value="<?php echo $srno=$nres->srno+1;?>">
				<input type="hidden" class="form-control"  name="rollno" value="<?php echo $rollno=$nres->rollno+1;?>">
				<input type="hidden" class="form-control"  name="doj" value="<?php echo date('Y-m-d');?>">
				<input type="hidden" class="form-control"  name="asession" value="<?php echo date('Y');?>">
				<input type="hidden" class="form-control"  name="status" value="0">
				<input type="hidden" class="form-control"  name="reg_type" value="1">
								  
                 <select name="bid" class="form-control" required>
				 <option value="">--Select Branch--</option>
			<?php
				$sel=$con->prepare("select * from branch order by id desc");
				$sel->execute();
				$result=$sel->get_result();
				$i=1; 
				while($bres=$result->fetch_object())
				{?>
			<option value="<?php echo $bres->id;?>" <?php if($bres->id==$_REQUEST['bid']) echo "selected";?> >(<?php echo $bres->bcode;?>) <?php echo $bres->bname;?> - <?php echo $bres->dis;?></option>
				<?php  $i++;}?>
				</select>
				</div>
				
			
                                <div class="form-group col-md-6 col-sm-12 col-xs-12 col-left-padding"> 
                                   <label>Name </label> 
                                    <input type="text" class="form-control"  placeholder="Enter Your Name" name="name" required>
                                </div>   
								
								<div class="form-group col-md-6 col-sm-12 col-xs-12 col-left-padding">
                                   <label>Date of Birth </label> 
                                    <input type="date" class="form-control" name="dob">  
                                </div>
								
								<div class="form-group col-md-6 col-sm-12 col-xs-12 col-left-padding">
                                    <label>Father's Name </label> 
                                    <input type="text" class="form-control"  placeholder="Father's Name" name="father" required>  
                                </div>
								
                                <div class="form-group col-md-6 col-sm-12 col-xs-12 col-left-padding">
                                   <label>Mother's Name </label> 
                                    <input type="text" class="form-control"  placeholder="Mother's Name" name="mother">  
                                </div>
								
								
				<div class="form-group col-md-6 col-sm-12 col-xs-12 col-left-padding">
<label>Gender </label> 				
                 <select name="gender" class="form-control">
				 <option value="">--Select Gender--</option> 
				<option value="Male">Male</option>  
				<option value="Female">Female</option>
				</select>
				</div>
				
			<div class="form-group col-md-6 col-sm-12 col-xs-12 col-left-padding">
			<label>Category </label> 
               <select name="category" class="form-control">
			   <option value="">--Select Category--</option> 
				<option value="GEN">GEN</option>  
				<option value="OBC">OBC</option>
				<option value="SC">SC</option>
				<option value="ST">ST</option>
			  </select>
			</div>
			
			<div class="form-group col-md-6 col-sm-12 col-xs-12 col-left-padding">
			<label>Marital Status </label> 
               <select name="marital_status" class="form-control">
			   <option value="">--Select Marital Status--</option> 
				<option value="Married">Married</option>  
				<option value="Unmarried">Unmarried</option>
			  </select>
			</div>
			
			<div class="form-group col-md-6 col-sm-12 col-xs-12 col-left-padding">
			<label>Last Qualification </label> 
               <select name="qualification" class="form-control">
			   <option value="">--Select Qualification--</option> 
				<option value="Below 8th">Below 8th</option>  
				<option value="8th">8th</option>
				<option value="9th">9th</option>
				<option value="10th">10th</option>
				<option value="11th">11th</option>
				<option value="12th">12th</option>
				<option value="Graduation">Graduation</option>
				<option value="Post Graduation">Post Graduation</option>
				<option value="Diploma">Diploma</option>
				<option value="Other">Other</option>
			  </select>
			</div>
			 <!--<div class="form-group col-md-6 col-sm-12 col-xs-12 col-left-padding">
                                     <label>caste </label> 
                                    <input type="text" class="form-control"  placeholder="Enter Cast" name="caste" required>
                                </div>  -->
								
			  <div class="form-group col-md-6 col-sm-12 col-xs-12 col-left-padding">
                                   <label>Aadhar Number </label> 
                                    <input type="text" class="form-control"  placeholder="Aadhar Number" name="aadhar" maxlength="12">  
                                </div>
								
			
				<div class="form-group col-md-6 col-sm-12 col-xs-12 col-left-padding">  
				<label>State </label> 
                <select name="state" class="form-control">
				<option value="">--Select State--</option> 
				<option value="Andhra Pradesh">Andhra Pradesh</option>  
				<option value="Andaman and Nicobar Islands">Andaman and Nicobar Islands</option>
				<option value="Arunachal Pradesh">Arunachal Pradesh</option>
				<option value="Assam">Assam</option>
				<option value="Bihar">Bihar</option>
				<option value="Chandigarh">Chandigarh</option>
				<option value="Chhattisgarh">Chhattisgarh</option>
				<option value="Dadar and Nagar Haveli">Dadar and Nagar Haveli</option>
				<option value="Daman and Diu">Daman and Diu</option>
				<option value="Delhi">Delhi</option>
				<option value="Lakshadweep">Lakshadweep</option>
				<option value="Puducherry">Puducherry</option>
				<option value="Goa">Goa</option> 
				<option value="Gujarat">Gujarat</option>
				<option value="Haryana">Haryana</option>
				<option value="Himachal Pradesh">Himachal Pradesh</option>
				<option value="Jammu and Kashmir">Jammu and Kashmir</option>
				<option value="Jharkhand">Jharkhand</option>
				<option value="Karnataka">Karnataka</option>
				<option value="Kerala">Kerala</option>
				<option value="Madhya Pradesh">Madhya Pradesh</option>
				<option value="Maharashtra">Maharashtra</option>
				<option value="Manipur">Manipur</option>
				<option value="Meghalaya">Meghalaya</option>
				<option value="Mizoram">Mizoram</option>
				<option value="Nagaland">Nagaland</option>
				<option value="Odisha">Odisha</option>
				<option value="Punjab">Punjab</option>
				<option value="Rajasthan">Rajasthan</option> 
				<option value="Sikkim">Sikkim</option> 
				<option value="Tamil Nadu">Tamil Nadu</option> 
				<option value="Telangana">Telangana</option>
				<option value="Tripura">Tripura</option>
				<option value="Uttar Pradesh">Uttar Pradesh</option>
				<option value="Uttarakhand">Uttarakhand</option>
				<option value="West Bengal">West Bengal</option>
				</select>
                                </div>
                                 <div class="form-group col-md-6 col-sm-12 col-xs-12 col-left-padding">
                                  <label>District </label> 
                                    <input type="text" class="form-control" id="phnumber" placeholder="Enter Your District" name="dis">
                                </div>
								
								<div class="form-group col-md-6 col-sm-12 col-xs-12 col-left-padding">  
			<label>Pincode</label> 
                <input type="text" class="form-control"  placeholder="Enter Pincode" name="pincode"> 
			</div>
			
                                <div class="form-group col-md-6 col-sm-12 col-xs-12 col-left-padding">
                                    <label>Mobile Number </label> 
                                    <input type="text" class="form-control"  placeholder="Enter Mobile Number" name="mob" required>
                                </div>
                                <div class="form-group col-md-6 col-sm-12 col-xs-12 col-left-padding">
								   <label>WhatsApp Number</label> 
                                    <input type="text" class="form-control" id="phnumber" placeholder="Enter WhatsApp Number" name="wat_no">
                                </div>
								
								  <div class="form-group col-md-6 col-sm-12 col-xs-12 col-left-padding">
								   <label>Email ID</label> 
                                    <input type="text" class="form-control" id="email" placeholder="Enter Email Id" name="email">
                                </div>
								
									<div class="form-group col-md-6 col-sm-12 col-xs-12 col-left-padding">
									  <label>Program </label> 
				<select name="program" id="program" class="form-control" onChange="FetchCourse(this.value)" required>
				<option value="">Select Program</option>
				<?php
				$sel=$con->prepare("select * from course_category");
				$sel->execute();
				$result=$sel->get_result();
				$i=1; 
				while($res_1=$result->fetch_object())
				{?>
			<option value="<?php echo $res_1->id;?>" <?php if($res_1->id==$_REQUEST['program']) echo "selected";?> ><?php echo $res_1->name;?></option>
				<?php  $i++;}?>
				</select>
				</div>
									
				<div class="form-group col-md-6 col-sm-12 col-xs-12 col-left-padding">
				  <label>Course </label> 
				<select name="course" class="form-control" id="course" required>
				<option value="">Select Course</option>
				</select>
				</div>
									
				
                                <div class="form-group col-md-6 col-sm-12 col-xs-12 col-left-padding">
                                    <label>Password</label> 
                                    <input type="password" class="form-control"  placeholder="Enter Your Password" name="pass" required>
                                </div>
                                <div class="form-group col-md-6 col-sm-12 col-xs-12 col-left-padding">
                                    <label>Confirm Password </label> 
                                    <input type="password" class="form-control"  placeholder="Enter Your Confirm Password" name="c_pass" required>
                                </div>
                                
                                
                                <div class="form-group  col-sm-12 col-xs-12 col-left-padding">
                                    <label>Complete Address</label>
                                    <textarea name="address" class="form-control" rows="5" cols="5"></textarea>
                                </div>
                                
                                <div class="col-xs-12 offset-5">
                                    <div class="comment-btn">
                                     <button class="btn btn-dark" id="btncheck">Submit Now</button>  
                                    </div>
                                </div>
                            </div>
                        </form> 
                              </div>
	
	<?php }?>	     
			
		   
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
<script type="text/javascript">
  function FetchCourse(id){
  
      $('#course').html('');
    $.ajax({
      type:'post',
      url: 'ajaxdata.php',
      data : { program_id : id},
      success : function(data){
         $('#course').html(data);
      }

    })
  }
  
</script>

   <script>
	   $("input[type='file']").on("change", function () {
     if(this.files[0].size > 2000000) {
       alert("Please upload file less than 2MB. Thanks!!");
       $(this).val('');
     }
    });
</script>
<?php include('footer.php');?>
</body>
</html>