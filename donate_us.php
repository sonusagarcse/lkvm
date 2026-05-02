<!doctype html>
<html lang="en-US">
<head>
<?php include('csslink.php');?>
<?php 
// Include database connection
require_once 'connection.php';

$id = 31;
$sel = $con->prepare("SELECT * FROM webpage WHERE id = ? ORDER BY id DESC");
$exe = $sel->execute([$id]);

if ($exe) {
    $result = $sel->get_result();
    $res = $result->fetch_assoc();
} else {
    $res = null;
}

// Get Razorpay keys from settings
$rzp_key_id = $settings['razorpay_key_id'] ?? '';
$rzp_org    = $settings['site_name'] ?? 'Donate Us';
$rzp_email  = $settings['email'] ?? '';
?> 
<style id='eikra-style-inline-css' type='text/css'>
.entry-banner {
background: url(images/banner.jpg) no-repeat scroll center center / cover;
}
.content-area {
padding-top: 100px;
padding-bottom: 0px;
}
#learn-press-block-content span {
background-image: url("images/preloader.gif");
}
		
</style>
</head>
<body class="home page-template-default page page-id-54 wp-embed-responsive theme-eikra woocommerce-no-js Eikra-version-4.4.6 header-style-9 footer-style-2 has-topbar topbar-style-7 no-sidebar rt-course-grid-view product-grid-view wpb-js-composer js-comp-ver-6.9.0 vc_responsive">
<?php//php include('loader.php');?>
<div id="page" class="site">
<?php include('header.php');?>

 <div id="content" class="site-content">
	<div class="entry-banner">
		<div class="container">
			<div class="entry-banner-content">
				<h1 class="entry-title"><?php echo $res ? $res['name'] : 'Donate Us';?></h1>
								<div class="breadcrumb-area"><div class="entry-breadcrumb"><!-- Breadcrumb NavXT 7.2.0 -->
<span property="itemListElement" typeof="ListItem"><a property="item" typeof="WebPage" title="Go to Home Page." href="<?php echo $SITE_URL;?>" class="home" ><span property="name">Home</span></a><meta property="position" content="1"></span> &gt; <span property="itemListElement" typeof="ListItem"><span property="name" class="post post-page current-item"><?php echo $res ? $res['name'] : 'Donate Us';?></span><meta property="url" content="#"><meta property="position" content="2"></span></div></div>					</div>
		</div>
	</div>
<div id="primary" class="content-area">
	<div class="container">
		<div class="row">
					<div class="col-sm-12 col-12">
			<main id="main" class="site-main">
						<article id="post-1111" class="post-1111 page type-page status-publish hentry">
		<div class="entry-content">
		<div class="vc_row wpb_row vc_row-fluid">
		<div class="wpb_column vc_column_container vc_col-sm-6">
		<div class="vc_column-inner vc_custom_1509100691886">
		<div class="wpb_wrapper">
		<?php if ($res && $res['des']): ?>
<p><?php echo $res['des']; ?></p>
<?php else: ?>
<p>Content for this page is coming soon. Please check back later for updated information.</p>
<?php endif; ?>
		 </div></div></div>

<div class="wpb_column vc_column_container vc_col-sm-6">
<div class="vc_column-inner"><div class="wpb_wrapper">
<h2 style="font-size: 30px;color: #002147;line-height: 1.5;text-align: justify" class="vc_custom_heading" >Donate Us</h2>
<div class="vc_separator wpb_content_element vc_separator_align_center vc_sep_width_10 vc_sep_border_width_3 vc_sep_pos_align_left vc_separator_no_text" ><span class="vc_sep_holder vc_sep_holder_l"><span  style="border-color:#fdc800;" class="vc_sep_line"></span></span><span class="vc_sep_holder vc_sep_holder_r"><span  style="border-color:#fdc800;" class="vc_sep_line"></span></span>
</div>

<?php if (!empty($rzp_key_id)): ?>
<!-- ── Razorpay Donation Form ───────────────────────────────────────── -->
<div id="rzp-success-msg" class="alert alert-success" style="display:none;">
    <i class="fas fa-check-circle"></i> Thank you! Your donation has been received.<br>
    <small id="rzp-payment-id-display"></small>
</div>

<form id="donate-form" class="contact-us-form" autocomplete="off">
	<div class="row">
		<div class="col-lg-6 col-md-6 col-sm-6">
			<div class="form-group">
				<p><input class="wpcf7-form-control form-control" placeholder="Full Name*" type="text" name="name" required /></p>
			</div>
		</div>
		<div class="col-lg-6 col-md-6 col-sm-6">
			<div class="form-group">
				<p><input class="wpcf7-form-control form-control" placeholder="Email*" type="email" name="email" required /></p>
			</div>
		</div>
		<div class="col-lg-6 col-md-6 col-sm-6">
			<div class="form-group">
				<p><input class="wpcf7-form-control form-control" placeholder="Phone*" type="tel" name="contact" required /></p>
			</div>
		</div>
		<div class="col-lg-6 col-md-6 col-sm-6">
			<div class="form-group">
				<p><input class="wpcf7-form-control form-control" placeholder="PAN Number*" type="text" name="pan" /></p>
			</div>
		</div>
		<div class="col-lg-6 col-md-6 col-sm-6">
			<div class="form-group">
				<select class="wpcf7-form-control form-control" name="country">
					<option value="India">India</option>
					<option value="Foreign">Foreign</option>
				</select>
			</div>
		</div>
		<div class="col-lg-6 col-md-6 col-sm-6">
			<div class="form-group">
				<p><input class="wpcf7-form-control form-control" placeholder="City*" type="text" name="city" /></p>
			</div>
		</div>
		<div class="col-lg-6 col-md-6 col-sm-6">
			<div class="form-group">
				<p><input class="wpcf7-form-control form-control" placeholder="Pincode*" type="text" name="pincode" /></p>
			</div>
		</div>
		<div class="col-lg-6 col-md-6 col-sm-6">
			<div class="form-group">
				<p><input class="wpcf7-form-control form-control" placeholder="Address*" type="text" name="address" /></p>
			</div>
		</div>
		<div class="col-lg-12 col-md-12 col-sm-12">
			<div class="form-group">
				<p><input class="wpcf7-form-control form-control" placeholder="Amount in Rs.*" type="number" name="amount" min="1" step="1" required /></p>
			</div>
		</div>
		<div class="col-lg-12 col-md-12 col-sm-12">
			<p>
				<button type="submit" id="rzp-donate-btn" class="wpcf7-form-control has-spinner wpcf7-submit rdtheme-button-2" style="cursor:pointer; border:none;">
					PAY NOW <i class="fas fa-lock" style="font-size:12px;"></i>
				</button>
			</p>
			<p style="font-size:12px; color:#888;">
                <i class="fas fa-shield-alt"></i> Secured by Razorpay
            </p>
		</div>
	</div>
</form>

<script src="https://checkout.razorpay.com/v1/checkout.js"></script>
<script>
document.getElementById('donate-form').addEventListener('submit', function(e) {
    e.preventDefault();
    var form = this;
    var btn  = document.getElementById('rzp-donate-btn');
    var data = new FormData(form);
    data.append('action', 'create_order');

    btn.disabled = true;
    btn.innerHTML = 'Processing... <i class="fas fa-spinner fa-spin"></i>';

    fetch('process_donation.php', { method: 'POST', body: data })
    .then(r => r.json())
    .then(res => {
        if (res.status !== 'success') {
            alert(res.message || 'Something went wrong. Please try again.');
            btn.disabled = false;
            btn.innerHTML = 'PAY NOW <i class="fas fa-lock" style="font-size:12px;"></i>';
            return;
        }

        var options = {
            key:         '<?php echo $rzp_key_id; ?>',
            amount:      res.amount,
            currency:    'INR',
            name:        '<?php echo addslashes($rzp_org); ?>',
            description: 'Donation',
            order_id:    res.order_id,
            prefill: {
                name:    data.get('name'),
                email:   data.get('email'),
                contact: data.get('contact')
            },
            theme: { color: '#6600cc' },
            handler: function(response) {
                // Verify payment server-side
                var vData = new FormData(form);
                vData.append('action', 'verify_payment');
                vData.append('razorpay_order_id',   response.razorpay_order_id);
                vData.append('razorpay_payment_id',  response.razorpay_payment_id);
                vData.append('razorpay_signature',   response.razorpay_signature);
                vData.append('amount_inr', parseFloat(form.querySelector('[name=amount]').value));

                fetch('process_donation.php', { method: 'POST', body: vData })
                .then(r => r.json())
                .then(v => {
                    if (v.status === 'success') {
                        form.style.display = 'none';
                        document.getElementById('rzp-success-msg').style.display = 'block';
                        document.getElementById('rzp-payment-id-display').textContent = 'Payment ID: ' + v.payment_id;
                    } else {
                        alert('Payment recorded but verification failed: ' + v.message);
                    }
                });
            },
            modal: {
                ondismiss: function() {
                    btn.disabled = false;
                    btn.innerHTML = 'PAY NOW <i class="fas fa-lock" style="font-size:12px;"></i>';
                }
            }
        };

        var rzp = new Razorpay(options);
        rzp.open();
    })
    .catch(() => {
        alert('Network error. Please try again.');
        btn.disabled = false;
        btn.innerHTML = 'PAY NOW <i class="fas fa-lock" style="font-size:12px;"></i>';
    });
});
</script>

<?php else: ?>
<!-- ── Fallback if Razorpay not configured ─────────────────────────── -->
<div class="alert alert-warning">
    <i class="fas fa-exclamation-triangle"></i>
    Payment gateway is not configured yet. Please contact the administrator.
</div>
<?php endif; ?>

<p style="text-align:center;">As per the Indian Income Tax Department's rules, a donor is required to add their Full Name, Address and PAN number in case they wish to claim tax exemption.</p>
</div>
</div></div></div></div>
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