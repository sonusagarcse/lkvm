<?php require("connection.php"); ?> 
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="profile" href="https://gmpg.org/xfn/11"/>

<!-- Dynamic SEO Tags -->
<title><?php echo(isset($page_title) ? htmlspecialchars($page_title) . " | " : "") . htmlspecialchars($SITE_NAME); ?></title>
<meta name="description" content="<?php echo htmlspecialchars($settings['meta_description'] ?? ($SITE_NAME . ' provides classes for computer education and vocational training.')); ?>">
<meta name="keywords" content="<?php echo htmlspecialchars($settings['meta_keywords'] ?? 'computer classes, vocational training, KYP, coding center'); ?>">
<meta name="author" content="<?php echo htmlspecialchars($SITE_NAME); ?>">

<!-- Open Graph / Facebook -->
<meta property="og:type" content="website">
<meta property="og:url" content="<?php echo $SITE_URL; ?>">
<meta property="og:title" content="<?php echo(isset($page_title) ? htmlspecialchars($page_title) . " | " : "") . htmlspecialchars($SITE_NAME); ?>">
<meta property="og:description" content="<?php echo htmlspecialchars($settings['meta_description'] ?? ''); ?>">
<meta property="og:image" content="<?php echo !empty($settings['og_image']) ? $settings['og_image'] : $SITE_URL . '/images/logo.png'; ?>">

<!-- Twitter -->
<meta property="twitter:card" content="summary_large_image">
<meta property="twitter:url" content="<?php echo $SITE_URL; ?>">
<meta property="twitter:title" content="<?php echo(isset($page_title) ? htmlspecialchars($page_title) . " | " : "") . htmlspecialchars($SITE_NAME); ?>">
<meta property="twitter:description" content="<?php echo htmlspecialchars($settings['meta_description'] ?? ''); ?>">
<meta property="twitter:image" content="<?php echo !empty($settings['og_image']) ? $settings['og_image'] : $SITE_URL . '/images/logo.png'; ?>">

<!-- Core Scripts & Styles -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<link rel="stylesheet" href="css/owl.carousel.min.css">
<link rel="stylesheet" href="css/owl.theme.default.min.css">
<script src="js/owl.carousel.min.js"></script>

<!-- Favicon -->
<?php if (!empty($settings['favicon'])): ?>
    <link rel="icon" type="image/x-icon" href="<?php echo htmlspecialchars($settings['favicon']); ?>">
<?php
endif; ?>

<script>document.documentElement.className = document.documentElement.className + ' yes-js js_active js'</script>
<noscript><style>#preloader{display:none;}</style></noscript>
<link rel='dns-prefetch' href='//client.crisp.chat' />
<link rel='dns-prefetch' href='//fonts.googleapis.com' />
<link rel='preconnect' href='https://fonts.gstatic.com' crossorigin />
<style type="text/css">
img.wp-smiley,
img.emoji {
	display: inline !important;
	border: none !important;
	box-shadow: none !important;
	height: 1em !important;
	width: 1em !important;
	margin: 0 0.07em !important;
	vertical-align: -0.1em !important;
	background: none !important;
	padding: 0 !important;
}
</style>
<link rel='stylesheet' id='course-review-css' href='css/course-review.css?ver=6.1.1' type='text/css' media='all' />
<link rel='stylesheet' id='lp-course-wishlist-css' href='css/wishlist.min.css?ver=4.0.5' type='text/css' media='all' />
<link rel='stylesheet' id='layerslider-css' href='css/layerslider.css?ver=7.2.5' type='text/css' media='all' />
<link rel='stylesheet' id='ls-user-css' href='css/layerslider.custom.css?ver=7.2.5' type='text/css' media='all' />
<link rel='stylesheet' id='wp-block-library-css' href='css/style.min.css?ver=6.1.1' type='text/css' media='all' />
<style id='wp-block-library-theme-inline-css' type='text/css'>
.wp-block-audio figcaption{color:#555;font-size:13px;text-align:center}.is-dark-theme .wp-block-audio figcaption{color:hsla(0,0%,100%,.65)}.wp-block-audio{margin:0 0 1em}.wp-block-code{border:1px solid #ccc;border-radius:4px;font-family:Menlo,Consolas,monaco,monospace;padding:.8em 1em}.wp-block-embed figcaption{color:#555;font-size:13px;text-align:center}.is-dark-theme .wp-block-embed figcaption{color:hsla(0,0%,100%,.65)}.wp-block-embed{margin:0 0 1em}.blocks-gallery-caption{color:#555;font-size:13px;text-align:center}.is-dark-theme .blocks-gallery-caption{color:hsla(0,0%,100%,.65)}.wp-block-image figcaption{color:#555;font-size:13px;text-align:center}.is-dark-theme .wp-block-image figcaption{color:hsla(0,0%,100%,.65)}.wp-block-image{margin:0 0 1em}.wp-block-pullquote{border-top:4px solid;border-bottom:4px solid;margin-bottom:1.75em;color:currentColor}.wp-block-pullquote__citation,.wp-block-pullquote cite,.wp-block-pullquote footer{color:currentColor;text-transform:uppercase;font-size:.8125em;font-style:normal}.wp-block-quote{border-left:.25em solid;margin:0 0 1.75em;padding-left:1em}.wp-block-quote cite,.wp-block-quote footer{color:currentColor;font-size:.8125em;position:relative;font-style:normal}.wp-block-quote.has-text-align-right{border-left:none;border-right:.25em solid;padding-left:0;padding-right:1em}.wp-block-quote.has-text-align-center{border:none;padding-left:0}.wp-block-quote.is-large,.wp-block-quote.is-style-large,.wp-block-quote.is-style-plain{border:none}.wp-block-search .wp-block-search__label{font-weight:700}.wp-block-search__button{border:1px solid #ccc;padding:.375em .625em}:where(.wp-block-group.has-background){padding:1.25em 2.375em}.wp-block-separator.has-css-opacity{opacity:.4}.wp-block-separator{border:none;border-bottom:2px solid;margin-left:auto;margin-right:auto}.wp-block-separator.has-alpha-channel-opacity{opacity:1}.wp-block-separator:not(.is-style-wide):not(.is-style-dots){width:100px}.wp-block-separator.has-background:not(.is-style-dots){border-bottom:none;height:1px}.wp-block-separator.has-background:not(.is-style-wide):not(.is-style-dots){height:2px}.wp-block-table{margin:"0 0 1em 0"}.wp-block-table thead{border-bottom:3px solid}.wp-block-table tfoot{border-top:3px solid}.wp-block-table td,.wp-block-table th{word-break:normal}.wp-block-table figcaption{color:#555;font-size:13px;text-align:center}.is-dark-theme .wp-block-table figcaption{color:hsla(0,0%,100%,.65)}.wp-block-video figcaption{color:#555;font-size:13px;text-align:center}.is-dark-theme .wp-block-video figcaption{color:hsla(0,0%,100%,.65)}.wp-block-video{margin:0 0 1em}.wp-block-template-part.has-background{padding:1.25em 2.375em;margin-top:0;margin-bottom:0}
</style>
<link rel='stylesheet' id='wc-blocks-vendors-style-css' href='css/wc-blocks-vendors-style.css?ver=9.4.4' type='text/css' media='all' />
<link rel='stylesheet' id='wc-blocks-style-css' href='css/wc-blocks-style.css?ver=9.4.4' type='text/css' media='all' />
<link rel='stylesheet' id='classic-theme-styles-css' href='css/classic-themes.min.css?ver=1' type='text/css' media='all' />
<style id='global-styles-inline-css' type='text/css'>
body{--wp--preset--color--black: #000000;--wp--preset--color--cyan-bluish-gray: #abb8c3;--wp--preset--color--white: #ffffff;--wp--preset--color--pale-pink: #f78da7;--wp--preset--color--vivid-red: #cf2e2e;--wp--preset--color--luminous-vivid-orange: #ff6900;--wp--preset--color--luminous-vivid-amber: #fcb900;--wp--preset--color--light-green-cyan: #7bdcb5;--wp--preset--color--vivid-green-cyan: #00d084;--wp--preset--color--pale-cyan-blue: #8ed1fc;--wp--preset--color--vivid-cyan-blue: #0693e3;--wp--preset--color--vivid-purple: #9b51e0;--wp--preset--gradient--vivid-cyan-blue-to-vivid-purple: linear-gradient(135deg,rgba(6,147,227,1) 0%,rgb(155,81,224) 100%);--wp--preset--gradient--light-green-cyan-to-vivid-green-cyan: linear-gradient(135deg,rgb(122,220,180) 0%,rgb(0,208,130) 100%);--wp--preset--gradient--luminous-vivid-amber-to-luminous-vivid-orange: linear-gradient(135deg,rgba(252,185,0,1) 0%,rgba(255,105,0,1) 100%);--wp--preset--gradient--luminous-vivid-orange-to-vivid-red: linear-gradient(135deg,rgba(255,105,0,1) 0%,rgb(207,46,46) 100%);--wp--preset--gradient--very-light-gray-to-cyan-bluish-gray: linear-gradient(135deg,rgb(238,238,238) 0%,rgb(169,184,195) 100%);--wp--preset--gradient--cool-to-warm-spectrum: linear-gradient(135deg,rgb(74,234,220) 0%,rgb(151,120,209) 20%,rgb(207,42,186) 40%,rgb(238,44,130) 60%,rgb(251,105,98) 80%,rgb(254,248,76) 100%);--wp--preset--gradient--blush-light-purple: linear-gradient(135deg,rgb(255,206,236) 0%,rgb(152,150,240) 100%);--wp--preset--gradient--blush-bordeaux: linear-gradient(135deg,rgb(254,205,165) 0%,rgb(254,45,45) 50%,rgb(107,0,62) 100%);--wp--preset--gradient--luminous-dusk: linear-gradient(135deg,rgb(255,203,112) 0%,rgb(199,81,192) 50%,rgb(65,88,208) 100%);--wp--preset--gradient--pale-ocean: linear-gradient(135deg,rgb(255,245,203) 0%,rgb(182,227,212) 50%,rgb(51,167,181) 100%);--wp--preset--gradient--electric-grass: linear-gradient(135deg,rgb(202,248,128) 0%,rgb(113,206,126) 100%);--wp--preset--gradient--midnight: linear-gradient(135deg,rgb(2,3,129) 0%,rgb(40,116,252) 100%);--wp--preset--duotone--dark-grayscale: url('#wp-duotone-dark-grayscale');--wp--preset--duotone--grayscale: url('#wp-duotone-grayscale');--wp--preset--duotone--purple-yellow: url('#wp-duotone-purple-yellow');--wp--preset--duotone--blue-red: url('#wp-duotone-blue-red');--wp--preset--duotone--midnight: url('#wp-duotone-midnight');--wp--preset--duotone--magenta-yellow: url('#wp-duotone-magenta-yellow');--wp--preset--duotone--purple-green: url('#wp-duotone-purple-green');--wp--preset--duotone--blue-orange: url('#wp-duotone-blue-orange');--wp--preset--font-size--small: 13px;--wp--preset--font-size--medium: 20px;--wp--preset--font-size--large: 36px;--wp--preset--font-size--x-large: 42px;}.has-black-color{color: var(--wp--preset--color--black) !important;}.has-cyan-bluish-gray-color{color: var(--wp--preset--color--cyan-bluish-gray) !important;}.has-white-color{color: var(--wp--preset--color--white) !important;}.has-pale-pink-color{color: var(--wp--preset--color--pale-pink) !important;}.has-vivid-red-color{color: var(--wp--preset--color--vivid-red) !important;}.has-luminous-vivid-orange-color{color: var(--wp--preset--color--luminous-vivid-orange) !important;}.has-luminous-vivid-amber-color{color: var(--wp--preset--color--luminous-vivid-amber) !important;}.has-light-green-cyan-color{color: var(--wp--preset--color--light-green-cyan) !important;}.has-vivid-green-cyan-color{color: var(--wp--preset--color--vivid-green-cyan) !important;}.has-pale-cyan-blue-color{color: var(--wp--preset--color--pale-cyan-blue) !important;}.has-vivid-cyan-blue-color{color: var(--wp--preset--color--vivid-cyan-blue) !important;}.has-vivid-purple-color{color: var(--wp--preset--color--vivid-purple) !important;}.has-black-background-color{background-color: var(--wp--preset--color--black) !important;}.has-cyan-bluish-gray-background-color{background-color: var(--wp--preset--color--cyan-bluish-gray) !important;}.has-white-background-color{background-color: var(--wp--preset--color--white) !important;}.has-pale-pink-background-color{background-color: var(--wp--preset--color--pale-pink) !important;}.has-vivid-red-background-color{background-color: var(--wp--preset--color--vivid-red) !important;}.has-luminous-vivid-orange-background-color{background-color: var(--wp--preset--color--luminous-vivid-orange) !important;}.has-luminous-vivid-amber-background-color{background-color: var(--wp--preset--color--luminous-vivid-amber) !important;}.has-light-green-cyan-background-color{background-color: var(--wp--preset--color--light-green-cyan) !important;}.has-vivid-green-cyan-background-color{background-color: var(--wp--preset--color--vivid-green-cyan) !important;}.has-pale-cyan-blue-background-color{background-color: var(--wp--preset--color--pale-cyan-blue) !important;}.has-vivid-cyan-blue-background-color{background-color: var(--wp--preset--color--vivid-cyan-blue) !important;}.has-vivid-purple-background-color{background-color: var(--wp--preset--color--vivid-purple) !important;}.has-black-border-color{border-color: var(--wp--preset--color--black) !important;}.has-cyan-bluish-gray-border-color{border-color: var(--wp--preset--color--cyan-bluish-gray) !important;}.has-white-border-color{border-color: var(--wp--preset--color--white) !important;}.has-pale-pink-border-color{border-color: var(--wp--preset--color--pale-pink) !important;}.has-vivid-red-border-color{border-color: var(--wp--preset--color--vivid-red) !important;}.has-luminous-vivid-orange-border-color{border-color: var(--wp--preset--color--luminous-vivid-orange) !important;}.has-luminous-vivid-amber-border-color{border-color: var(--wp--preset--color--luminous-vivid-amber) !important;}.has-light-green-cyan-border-color{border-color: var(--wp--preset--color--light-green-cyan) !important;}.has-vivid-green-cyan-border-color{border-color: var(--wp--preset--color--vivid-green-cyan) !important;}.has-pale-cyan-blue-border-color{border-color: var(--wp--preset--color--pale-cyan-blue) !important;}.has-vivid-cyan-blue-border-color{border-color: var(--wp--preset--color--vivid-cyan-blue) !important;}.has-vivid-purple-border-color{border-color: var(--wp--preset--color--vivid-purple) !important;}.has-vivid-cyan-blue-to-vivid-purple-gradient-background{background: var(--wp--preset--gradient--vivid-cyan-blue-to-vivid-purple) !important;}.has-light-green-cyan-to-vivid-green-cyan-gradient-background{background: var(--wp--preset--gradient--light-green-cyan-to-vivid-green-cyan) !important;}.has-luminous-vivid-amber-to-luminous-vivid-orange-gradient-background{background: var(--wp--preset--gradient--luminous-vivid-amber-to-luminous-vivid-orange) !important;}.has-luminous-vivid-orange-to-vivid-red-gradient-background{background: var(--wp--preset--gradient--luminous-vivid-orange-to-vivid-red) !important;}.has-very-light-gray-to-cyan-bluish-gray-gradient-background{background: var(--wp--preset--gradient--very-light-gray-to-cyan-bluish-gray) !important;}.has-cool-to-warm-spectrum-gradient-background{background: var(--wp--preset--gradient--cool-to-warm-spectrum) !important;}.has-blush-light-purple-gradient-background{background: var(--wp--preset--gradient--blush-light-purple) !important;}.has-blush-bordeaux-gradient-background{background: var(--wp--preset--gradient--blush-bordeaux) !important;}.has-luminous-dusk-gradient-background{background: var(--wp--preset--gradient--luminous-dusk) !important;}.has-pale-ocean-gradient-background{background: var(--wp--preset--gradient--pale-ocean) !important;}.has-electric-grass-gradient-background{background: var(--wp--preset--gradient--electric-grass) !important;}.has-midnight-gradient-background{background: var(--wp--preset--gradient--midnight) !important;}.has-small-font-size{font-size: var(--wp--preset--font-size--small) !important;}.has-medium-font-size{font-size: var(--wp--preset--font-size--medium) !important;}.has-large-font-size{font-size: var(--wp--preset--font-size--large) !important;}.has-x-large-font-size{font-size: var(--wp--preset--font-size--x-large) !important;}
.wp-block-navigation a:where(:not(.wp-element-button)){color: inherit;}
:where(.wp-block-columns.is-layout-flex){gap: 2em;}
.wp-block-pullquote{font-size: 1.5em;line-height: 1.6;}
</style>
<style id='extendify-gutenberg-patterns-and-templates-utilities-inline-css' type='text/css'>
.ext-absolute {
  position: absolute !important;
}

.ext-relative {
  position: relative !important;
}

.ext-top-base {
  top: var(--wp--style--block-gap, 1.75rem) !important;
}

.ext-top-lg {
  top: var(--extendify--spacing--large, 3rem) !important;
}

.ext--top-base {
  top: calc(var(--wp--style--block-gap, 1.75rem) * -1) !important;
}

.ext--top-lg {
  top: calc(var(--extendify--spacing--large, 3rem) * -1) !important;
}

.ext-right-base {
  right: var(--wp--style--block-gap, 1.75rem) !important;
}

.ext-right-lg {
  right: var(--extendify--spacing--large, 3rem) !important;
}

.ext--right-base {
  right: calc(var(--wp--style--block-gap, 1.75rem) * -1) !important;
}

.ext--right-lg {
  right: calc(var(--extendify--spacing--large, 3rem) * -1) !important;
}

.ext-bottom-base {
  bottom: var(--wp--style--block-gap, 1.75rem) !important;
}

.ext-bottom-lg {
  bottom: var(--extendify--spacing--large, 3rem) !important;
}

.ext--bottom-base {
  bottom: calc(var(--wp--style--block-gap, 1.75rem) * -1) !important;
}

.ext--bottom-lg {
  bottom: calc(var(--extendify--spacing--large, 3rem) * -1) !important;
}

.ext-left-base {
  left: var(--wp--style--block-gap, 1.75rem) !important;
}

.ext-left-lg {
  left: var(--extendify--spacing--large, 3rem) !important;
}

.ext--left-base {
  left: calc(var(--wp--style--block-gap, 1.75rem) * -1) !important;
}

.ext--left-lg {
  left: calc(var(--extendify--spacing--large, 3rem) * -1) !important;
}

.ext-order-1 {
  order: 1 !important;
}

.ext-order-2 {
  order: 2 !important;
}

.ext-col-auto {
  grid-column: auto !important;
}

.ext-col-span-1 {
  grid-column: span 1 / span 1 !important;
}

.ext-col-span-2 {
  grid-column: span 2 / span 2 !important;
}

.ext-col-span-3 {
  grid-column: span 3 / span 3 !important;
}

.ext-col-span-4 {
  grid-column: span 4 / span 4 !important;
}

.ext-col-span-5 {
  grid-column: span 5 / span 5 !important;
}

.ext-col-span-6 {
  grid-column: span 6 / span 6 !important;
}

.ext-col-span-7 {
  grid-column: span 7 / span 7 !important;
}

.ext-col-span-8 {
  grid-column: span 8 / span 8 !important;
}

.ext-col-span-9 {
  grid-column: span 9 / span 9 !important;
}

.ext-col-span-10 {
  grid-column: span 10 / span 10 !important;
}

.ext-col-span-11 {
  grid-column: span 11 / span 11 !important;
}

.ext-col-span-12 {
  grid-column: span 12 / span 12 !important;
}

.ext-col-span-full {
  grid-column: 1 / -1 !important;
}

.ext-col-start-1 {
  grid-column-start: 1 !important;
}

.ext-col-start-2 {
  grid-column-start: 2 !important;
}

.ext-col-start-3 {
  grid-column-start: 3 !important;
}

.ext-col-start-4 {
  grid-column-start: 4 !important;
}

.ext-col-start-5 {
  grid-column-start: 5 !important;
}

.ext-col-start-6 {
  grid-column-start: 6 !important;
}

.ext-col-start-7 {
  grid-column-start: 7 !important;
}

.ext-col-start-8 {
  grid-column-start: 8 !important;
}

.ext-col-start-9 {
  grid-column-start: 9 !important;
}

.ext-col-start-10 {
  grid-column-start: 10 !important;
}

.ext-col-start-11 {
  grid-column-start: 11 !important;
}

.ext-col-start-12 {
  grid-column-start: 12 !important;
}

.ext-col-start-13 {
  grid-column-start: 13 !important;
}

.ext-col-start-auto {
  grid-column-start: auto !important;
}

.ext-col-end-1 {
  grid-column-end: 1 !important;
}

.ext-col-end-2 {
  grid-column-end: 2 !important;
}

.ext-col-end-3 {
  grid-column-end: 3 !important;
}

.ext-col-end-4 {
  grid-column-end: 4 !important;
}

.ext-col-end-5 {
  grid-column-end: 5 !important;
}

.ext-col-end-6 {
  grid-column-end: 6 !important;
}

.ext-col-end-7 {
  grid-column-end: 7 !important;
}

.ext-col-end-8 {
  grid-column-end: 8 !important;
}

.ext-col-end-9 {
  grid-column-end: 9 !important;
}

.ext-col-end-10 {
  grid-column-end: 10 !important;
}

.ext-col-end-11 {
  grid-column-end: 11 !important;
}

.ext-col-end-12 {
  grid-column-end: 12 !important;
}

.ext-col-end-13 {
  grid-column-end: 13 !important;
}

.ext-col-end-auto {
  grid-column-end: auto !important;
}

.ext-row-auto {
  grid-row: auto !important;
}

.ext-row-span-1 {
  grid-row: span 1 / span 1 !important;
}

.ext-row-span-2 {
  grid-row: span 2 / span 2 !important;
}

.ext-row-span-3 {
  grid-row: span 3 / span 3 !important;
}

.ext-row-span-4 {
  grid-row: span 4 / span 4 !important;
}

.ext-row-span-5 {
  grid-row: span 5 / span 5 !important;
}

.ext-row-span-6 {
  grid-row: span 6 / span 6 !important;
}

.ext-row-span-full {
  grid-row: 1 / -1 !important;
}

.ext-row-start-1 {
  grid-row-start: 1 !important;
}

.ext-row-start-2 {
  grid-row-start: 2 !important;
}

.ext-row-start-3 {
  grid-row-start: 3 !important;
}

.ext-row-start-4 {
  grid-row-start: 4 !important;
}

.ext-row-start-5 {
  grid-row-start: 5 !important;
}

.ext-row-start-6 {
  grid-row-start: 6 !important;
}

.ext-row-start-7 {
  grid-row-start: 7 !important;
}

.ext-row-start-auto {
  grid-row-start: auto !important;
}

.ext-row-end-1 {
  grid-row-end: 1 !important;
}

.ext-row-end-2 {
  grid-row-end: 2 !important;
}

.ext-row-end-3 {
  grid-row-end: 3 !important;
}

.ext-row-end-4 {
  grid-row-end: 4 !important;
}

.ext-row-end-5 {
  grid-row-end: 5 !important;
}

.ext-row-end-6 {
  grid-row-end: 6 !important;
}

.ext-row-end-7 {
  grid-row-end: 7 !important;
}

.ext-row-end-auto {
  grid-row-end: auto !important;
}

.ext-m-0:not([style*="margin"]) {
  margin: 0 !important;
}

.ext-m-auto:not([style*="margin"]) {
  margin: auto !important;
}

.ext-m-base:not([style*="margin"]) {
  margin: var(--wp--style--block-gap, 1.75rem) !important;
}

.ext-m-lg:not([style*="margin"]) {
  margin: var(--extendify--spacing--large, 3rem) !important;
}

.ext--m-base:not([style*="margin"]) {
  margin: calc(var(--wp--style--block-gap, 1.75rem) * -1) !important;
}

.ext--m-lg:not([style*="margin"]) {
  margin: calc(var(--extendify--spacing--large, 3rem) * -1) !important;
}

.ext-mx-0:not([style*="margin"]) {
  margin-left: 0 !important;
  margin-right: 0 !important;
}

.ext-mx-auto:not([style*="margin"]) {
  margin-left: auto !important;
  margin-right: auto !important;
}

.ext-mx-base:not([style*="margin"]) {
  margin-left: var(--wp--style--block-gap, 1.75rem) !important;
  margin-right: var(--wp--style--block-gap, 1.75rem) !important;
}

.ext-mx-lg:not([style*="margin"]) {
  margin-left: var(--extendify--spacing--large, 3rem) !important;
  margin-right: var(--extendify--spacing--large, 3rem) !important;
}

.ext--mx-base:not([style*="margin"]) {
  margin-left: calc(var(--wp--style--block-gap, 1.75rem) * -1) !important;
  margin-right: calc(var(--wp--style--block-gap, 1.75rem) * -1) !important;
}

.ext--mx-lg:not([style*="margin"]) {
  margin-left: calc(var(--extendify--spacing--large, 3rem) * -1) !important;
  margin-right: calc(var(--extendify--spacing--large, 3rem) * -1) !important;
}

.ext-my-0:not([style*="margin"]) {
  margin-top: 0 !important;
  margin-bottom: 0 !important;
}

.ext-my-auto:not([style*="margin"]) {
  margin-top: auto !important;
  margin-bottom: auto !important;
}

.ext-my-base:not([style*="margin"]) {
  margin-top: var(--wp--style--block-gap, 1.75rem) !important;
  margin-bottom: var(--wp--style--block-gap, 1.75rem) !important;
}

.ext-my-lg:not([style*="margin"]) {
  margin-top: var(--extendify--spacing--large, 3rem) !important;
  margin-bottom: var(--extendify--spacing--large, 3rem) !important;
}

.ext--my-base:not([style*="margin"]) {
  margin-top: calc(var(--wp--style--block-gap, 1.75rem) * -1) !important;
  margin-bottom: calc(var(--wp--style--block-gap, 1.75rem) * -1) !important;
}

.ext--my-lg:not([style*="margin"]) {
  margin-top: calc(var(--extendify--spacing--large, 3rem) * -1) !important;
  margin-bottom: calc(var(--extendify--spacing--large, 3rem) * -1) !important;
}

.ext-mt-0:not([style*="margin"]) {
  margin-top: 0 !important;
}

.ext-mt-auto:not([style*="margin"]) {
  margin-top: auto !important;
}

.ext-mt-base:not([style*="margin"]) {
  margin-top: var(--wp--style--block-gap, 1.75rem) !important;
}

.ext-mt-lg:not([style*="margin"]) {
  margin-top: var(--extendify--spacing--large, 3rem) !important;
}

.ext--mt-base:not([style*="margin"]) {
  margin-top: calc(var(--wp--style--block-gap, 1.75rem) * -1) !important;
}

.ext--mt-lg:not([style*="margin"]) {
  margin-top: calc(var(--extendify--spacing--large, 3rem) * -1) !important;
}

.ext-mr-0:not([style*="margin"]) {
  margin-right: 0 !important;
}

.ext-mr-auto:not([style*="margin"]) {
  margin-right: auto !important;
}

.ext-mr-base:not([style*="margin"]) {
  margin-right: var(--wp--style--block-gap, 1.75rem) !important;
}

.ext-mr-lg:not([style*="margin"]) {
  margin-right: var(--extendify--spacing--large, 3rem) !important;
}

.ext--mr-base:not([style*="margin"]) {
  margin-right: calc(var(--wp--style--block-gap, 1.75rem) * -1) !important;
}

.ext--mr-lg:not([style*="margin"]) {
  margin-right: calc(var(--extendify--spacing--large, 3rem) * -1) !important;
}

.ext-mb-0:not([style*="margin"]) {
  margin-bottom: 0 !important;
}

.ext-mb-auto:not([style*="margin"]) {
  margin-bottom: auto !important;
}

.ext-mb-base:not([style*="margin"]) {
  margin-bottom: var(--wp--style--block-gap, 1.75rem) !important;
}

.ext-mb-lg:not([style*="margin"]) {
  margin-bottom: var(--extendify--spacing--large, 3rem) !important;
}

.ext--mb-base:not([style*="margin"]) {
  margin-bottom: calc(var(--wp--style--block-gap, 1.75rem) * -1) !important;
}

.ext--mb-lg:not([style*="margin"]) {
  margin-bottom: calc(var(--extendify--spacing--large, 3rem) * -1) !important;
}

.ext-ml-0:not([style*="margin"]) {
  margin-left: 0 !important;
}

.ext-ml-auto:not([style*="margin"]) {
  margin-left: auto !important;
}

.ext-ml-base:not([style*="margin"]) {
  margin-left: var(--wp--style--block-gap, 1.75rem) !important;
}

.ext-ml-lg:not([style*="margin"]) {
  margin-left: var(--extendify--spacing--large, 3rem) !important;
}

.ext--ml-base:not([style*="margin"]) {
  margin-left: calc(var(--wp--style--block-gap, 1.75rem) * -1) !important;
}

.ext--ml-lg:not([style*="margin"]) {
  margin-left: calc(var(--extendify--spacing--large, 3rem) * -1) !important;
}

.ext-block {
  display: block !important;
}

.ext-inline-block {
  display: inline-block !important;
}

.ext-inline {
  display: inline !important;
}

.ext-flex {
  display: flex !important;
}

.ext-inline-flex {
  display: inline-flex !important;
}

.ext-grid {
  display: grid !important;
}

.ext-inline-grid {
  display: inline-grid !important;
}

.ext-hidden {
  display: none !important;
}

.ext-w-auto {
  width: auto !important;
}

.ext-w-full {
  width: 100% !important;
}

.ext-max-w-full {
  max-width: 100% !important;
}

.ext-flex-1 {
  flex: 1 1 0% !important;
}

.ext-flex-auto {
  flex: 1 1 auto !important;
}

.ext-flex-initial {
  flex: 0 1 auto !important;
}

.ext-flex-none {
  flex: none !important;
}

.ext-flex-shrink-0 {
  flex-shrink: 0 !important;
}

.ext-flex-shrink {
  flex-shrink: 1 !important;
}

.ext-flex-grow-0 {
  flex-grow: 0 !important;
}

.ext-flex-grow {
  flex-grow: 1 !important;
}

.ext-list-none {
  list-style-type: none !important;
}

.ext-grid-cols-1 {
  grid-template-columns: repeat(1, minmax(0, 1fr)) !important;
}

.ext-grid-cols-2 {
  grid-template-columns: repeat(2, minmax(0, 1fr)) !important;
}

.ext-grid-cols-3 {
  grid-template-columns: repeat(3, minmax(0, 1fr)) !important;
}

.ext-grid-cols-4 {
  grid-template-columns: repeat(4, minmax(0, 1fr)) !important;
}

.ext-grid-cols-5 {
  grid-template-columns: repeat(5, minmax(0, 1fr)) !important;
}

.ext-grid-cols-6 {
  grid-template-columns: repeat(6, minmax(0, 1fr)) !important;
}

.ext-grid-cols-7 {
  grid-template-columns: repeat(7, minmax(0, 1fr)) !important;
}

.ext-grid-cols-8 {
  grid-template-columns: repeat(8, minmax(0, 1fr)) !important;
}

.ext-grid-cols-9 {
  grid-template-columns: repeat(9, minmax(0, 1fr)) !important;
}

.ext-grid-cols-10 {
  grid-template-columns: repeat(10, minmax(0, 1fr)) !important;
}

.ext-grid-cols-11 {
  grid-template-columns: repeat(11, minmax(0, 1fr)) !important;
}

.ext-grid-cols-12 {
  grid-template-columns: repeat(12, minmax(0, 1fr)) !important;
}

.ext-grid-cols-none {
  grid-template-columns: none !important;
}

.ext-grid-rows-1 {
  grid-template-rows: repeat(1, minmax(0, 1fr)) !important;
}

.ext-grid-rows-2 {
  grid-template-rows: repeat(2, minmax(0, 1fr)) !important;
}

.ext-grid-rows-3 {
  grid-template-rows: repeat(3, minmax(0, 1fr)) !important;
}

.ext-grid-rows-4 {
  grid-template-rows: repeat(4, minmax(0, 1fr)) !important;
}

.ext-grid-rows-5 {
  grid-template-rows: repeat(5, minmax(0, 1fr)) !important;
}

.ext-grid-rows-6 {
  grid-template-rows: repeat(6, minmax(0, 1fr)) !important;
}

.ext-grid-rows-none {
  grid-template-rows: none !important;
}

.ext-flex-row {
  flex-direction: row !important;
}

.ext-flex-row-reverse {
  flex-direction: row-reverse !important;
}

.ext-flex-col {
  flex-direction: column !important;
}

.ext-flex-col-reverse {
  flex-direction: column-reverse !important;
}

.ext-flex-wrap {
  flex-wrap: wrap !important;
}

.ext-flex-wrap-reverse {
  flex-wrap: wrap-reverse !important;
}

.ext-flex-nowrap {
  flex-wrap: nowrap !important;
}

.ext-items-start {
  align-items: flex-start !important;
}

.ext-items-end {
  align-items: flex-end !important;
}

.ext-items-center {
  align-items: center !important;
}

.ext-items-baseline {
  align-items: baseline !important;
}

.ext-items-stretch {
  align-items: stretch !important;
}

.ext-justify-start {
  justify-content: flex-start !important;
}

.ext-justify-end {
  justify-content: flex-end !important;
}

.ext-justify-center {
  justify-content: center !important;
}

.ext-justify-between {
  justify-content: space-between !important;
}

.ext-justify-around {
  justify-content: space-around !important;
}

.ext-justify-evenly {
  justify-content: space-evenly !important;
}

.ext-justify-items-start {
  justify-items: start !important;
}

.ext-justify-items-end {
  justify-items: end !important;
}

.ext-justify-items-center {
  justify-items: center !important;
}

.ext-justify-items-stretch {
  justify-items: stretch !important;
}

.ext-gap-0 {
  gap: 0 !important;
}

.ext-gap-base {
  gap: var(--wp--style--block-gap, 1.75rem) !important;
}

.ext-gap-lg {
  gap: var(--extendify--spacing--large, 3rem) !important;
}

.ext-gap-x-0 {
  -moz-column-gap: 0 !important;
       column-gap: 0 !important;
}

.ext-gap-x-base {
  -moz-column-gap: var(--wp--style--block-gap, 1.75rem) !important;
       column-gap: var(--wp--style--block-gap, 1.75rem) !important;
}

.ext-gap-x-lg {
  -moz-column-gap: var(--extendify--spacing--large, 3rem) !important;
       column-gap: var(--extendify--spacing--large, 3rem) !important;
}

.ext-gap-y-0 {
  row-gap: 0 !important;
}

.ext-gap-y-base {
  row-gap: var(--wp--style--block-gap, 1.75rem) !important;
}

.ext-gap-y-lg {
  row-gap: var(--extendify--spacing--large, 3rem) !important;
}

.ext-justify-self-auto {
  justify-self: auto !important;
}

.ext-justify-self-start {
  justify-self: start !important;
}

.ext-justify-self-end {
  justify-self: end !important;
}

.ext-justify-self-center {
  justify-self: center !important;
}

.ext-justify-self-stretch {
  justify-self: stretch !important;
}

.ext-rounded-none {
  border-radius: 0px !important;
}

.ext-rounded-full {
  border-radius: 9999px !important;
}

.ext-rounded-t-none {
  border-top-left-radius: 0px !important;
  border-top-right-radius: 0px !important;
}

.ext-rounded-t-full {
  border-top-left-radius: 9999px !important;
  border-top-right-radius: 9999px !important;
}

.ext-rounded-r-none {
  border-top-right-radius: 0px !important;
  border-bottom-right-radius: 0px !important;
}

.ext-rounded-r-full {
  border-top-right-radius: 9999px !important;
  border-bottom-right-radius: 9999px !important;
}

.ext-rounded-b-none {
  border-bottom-right-radius: 0px !important;
  border-bottom-left-radius: 0px !important;
}

.ext-rounded-b-full {
  border-bottom-right-radius: 9999px !important;
  border-bottom-left-radius: 9999px !important;
}

.ext-rounded-l-none {
  border-top-left-radius: 0px !important;
  border-bottom-left-radius: 0px !important;
}

.ext-rounded-l-full {
  border-top-left-radius: 9999px !important;
  border-bottom-left-radius: 9999px !important;
}

.ext-rounded-tl-none {
  border-top-left-radius: 0px !important;
}

.ext-rounded-tl-full {
  border-top-left-radius: 9999px !important;
}

.ext-rounded-tr-none {
  border-top-right-radius: 0px !important;
}

.ext-rounded-tr-full {
  border-top-right-radius: 9999px !important;
}

.ext-rounded-br-none {
  border-bottom-right-radius: 0px !important;
}

.ext-rounded-br-full {
  border-bottom-right-radius: 9999px !important;
}

.ext-rounded-bl-none {
  border-bottom-left-radius: 0px !important;
}

.ext-rounded-bl-full {
  border-bottom-left-radius: 9999px !important;
}

.ext-border-0 {
  border-width: 0px !important;
}

.ext-border-t-0 {
  border-top-width: 0px !important;
}

.ext-border-r-0 {
  border-right-width: 0px !important;
}

.ext-border-b-0 {
  border-bottom-width: 0px !important;
}

.ext-border-l-0 {
  border-left-width: 0px !important;
}

.ext-p-0:not([style*="padding"]) {
  padding: 0 !important;
}

.ext-p-base:not([style*="padding"]) {
  padding: var(--wp--style--block-gap, 1.75rem) !important;
}

.ext-p-lg:not([style*="padding"]) {
  padding: var(--extendify--spacing--large, 3rem) !important;
}

.ext-px-0:not([style*="padding"]) {
  padding-left: 0 !important;
  padding-right: 0 !important;
}

.ext-px-base:not([style*="padding"]) {
  padding-left: var(--wp--style--block-gap, 1.75rem) !important;
  padding-right: var(--wp--style--block-gap, 1.75rem) !important;
}

.ext-px-lg:not([style*="padding"]) {
  padding-left: var(--extendify--spacing--large, 3rem) !important;
  padding-right: var(--extendify--spacing--large, 3rem) !important;
}

.ext-py-0:not([style*="padding"]) {
  padding-top: 0 !important;
  padding-bottom: 0 !important;
}

.ext-py-base:not([style*="padding"]) {
  padding-top: var(--wp--style--block-gap, 1.75rem) !important;
  padding-bottom: var(--wp--style--block-gap, 1.75rem) !important;
}

.ext-py-lg:not([style*="padding"]) {
  padding-top: var(--extendify--spacing--large, 3rem) !important;
  padding-bottom: var(--extendify--spacing--large, 3rem) !important;
}

.ext-pt-0:not([style*="padding"]) {
  padding-top: 0 !important;
}

.ext-pt-base:not([style*="padding"]) {
  padding-top: var(--wp--style--block-gap, 1.75rem) !important;
}

.ext-pt-lg:not([style*="padding"]) {
  padding-top: var(--extendify--spacing--large, 3rem) !important;
}

.ext-pr-0:not([style*="padding"]) {
  padding-right: 0 !important;
}

.ext-pr-base:not([style*="padding"]) {
  padding-right: var(--wp--style--block-gap, 1.75rem) !important;
}

.ext-pr-lg:not([style*="padding"]) {
  padding-right: var(--extendify--spacing--large, 3rem) !important;
}

.ext-pb-0:not([style*="padding"]) {
  padding-bottom: 0 !important;
}

.ext-pb-base:not([style*="padding"]) {
  padding-bottom: var(--wp--style--block-gap, 1.75rem) !important;
}

.ext-pb-lg:not([style*="padding"]) {
  padding-bottom: var(--extendify--spacing--large, 3rem) !important;
}

.ext-pl-0:not([style*="padding"]) {
  padding-left: 0 !important;
}

.ext-pl-base:not([style*="padding"]) {
  padding-left: var(--wp--style--block-gap, 1.75rem) !important;
}

.ext-pl-lg:not([style*="padding"]) {
  padding-left: var(--extendify--spacing--large, 3rem) !important;
}

.ext-text-left {
  text-align: left !important;
}

.ext-text-center {
  text-align: center !important;
}

.ext-text-right {
  text-align: right !important;
}

.ext-leading-none {
  line-height: 1 !important;
}

.ext-leading-tight {
  line-height: 1.25 !important;
}

.ext-leading-snug {
  line-height: 1.375 !important;
}

.ext-leading-normal {
  line-height: 1.5 !important;
}

.ext-leading-relaxed {
  line-height: 1.625 !important;
}

.ext-leading-loose {
  line-height: 2 !important;
}

.ext-aspect-square img {
  aspect-ratio: 1 / 1 !important;
  -o-object-fit: cover !important;
     object-fit: cover !important;
}

.ext-aspect-landscape img {
  aspect-ratio: 4 / 3 !important;
  -o-object-fit: cover !important;
     object-fit: cover !important;
}

.ext-aspect-landscape-wide img {
  aspect-ratio: 16 / 9 !important;
  -o-object-fit: cover !important;
     object-fit: cover !important;
}

.ext-aspect-portrait img {
  aspect-ratio: 3 / 4 !important;
  -o-object-fit: cover !important;
     object-fit: cover !important;
}

.ext-aspect-square .components-resizable-box__container,
.ext-aspect-landscape .components-resizable-box__container,
.ext-aspect-landscape-wide .components-resizable-box__container,
.ext-aspect-portrait .components-resizable-box__container {
  height: auto !important;
}

.clip-path--rhombus img {
  -webkit-clip-path: polygon(15% 6%, 80% 29%, 84% 93%, 23% 69%) !important;
          clip-path: polygon(15% 6%, 80% 29%, 84% 93%, 23% 69%) !important;
}

.clip-path--diamond img {
  -webkit-clip-path: polygon(5% 29%, 60% 2%, 91% 64%, 36% 89%) !important;
          clip-path: polygon(5% 29%, 60% 2%, 91% 64%, 36% 89%) !important;
}

.clip-path--rhombus-alt img {
  -webkit-clip-path: polygon(14% 9%, 85% 24%, 91% 89%, 19% 76%) !important;
          clip-path: polygon(14% 9%, 85% 24%, 91% 89%, 19% 76%) !important;
}

/*
The .ext utility is a top-level class that we use to target contents within our patterns.
We use it here to ensure columns blocks display well across themes.
*/

.wp-block-columns[class*="fullwidth-cols"] {
  /* no suggestion */
  margin-bottom: unset !important;
}

.wp-block-column.editor\:pointer-events-none {
  /* no suggestion */
  margin-top: 0 !important;
  margin-bottom: 0 !important;
}

.is-root-container.block-editor-block-list__layout
    > [data-align="full"]:not(:first-of-type)
    > .wp-block-column.editor\:pointer-events-none,
.is-root-container.block-editor-block-list__layout
    > [data-align="wide"]
    > .wp-block-column.editor\:pointer-events-none {
  /* no suggestion */
  margin-top: calc(-1 * var(--wp--style--block-gap, 28px)) !important;
}

.is-root-container.block-editor-block-list__layout
    > [data-align="full"]:not(:first-of-type)
    > .ext-my-0,
.is-root-container.block-editor-block-list__layout
    > [data-align="wide"]
    > .ext-my-0:not([style*="margin"]) {
  /* no suggestion */
  margin-top: calc(-1 * var(--wp--style--block-gap, 28px)) !important;
}

/* Some popular themes use padding instead of core margin for columns; remove it */

.ext .wp-block-columns .wp-block-column[style*="padding"] {
  /* no suggestion */
  padding-left: 0 !important;
  padding-right: 0 !important;
}

/* Some popular themes add double spacing between columns; remove it */

.ext
    .wp-block-columns
    + .wp-block-columns:not([class*="mt-"]):not([class*="my-"]):not([style*="margin"]) {
  /* no suggestion */
  margin-top: 0 !important;
}

[class*="fullwidth-cols"] .wp-block-column:first-child,
[class*="fullwidth-cols"] .wp-block-group:first-child {
  /* no suggestion */
}

[class*="fullwidth-cols"] .wp-block-column:first-child, [class*="fullwidth-cols"] .wp-block-group:first-child {
  margin-top: 0 !important;
}

[class*="fullwidth-cols"] .wp-block-column:last-child,
[class*="fullwidth-cols"] .wp-block-group:last-child {
  /* no suggestion */
}

[class*="fullwidth-cols"] .wp-block-column:last-child, [class*="fullwidth-cols"] .wp-block-group:last-child {
  margin-bottom: 0 !important;
}

[class*="fullwidth-cols"] .wp-block-column:first-child > * {
  /* no suggestion */
  margin-top: 0 !important;
}

[class*="fullwidth-cols"] .wp-block-column > *:first-child {
  /* no suggestion */
  margin-top: 0 !important;
}

[class*="fullwidth-cols"] .wp-block-column > *:last-child {
  /* no suggestion */
  margin-bottom: 0 !important;
}

.ext .is-not-stacked-on-mobile .wp-block-column {
  /* no suggestion */
  margin-bottom: 0 !important;
}

/* Add base margin bottom to all columns */

.wp-block-columns[class*="fullwidth-cols"]:not(.is-not-stacked-on-mobile)
    > .wp-block-column:not(:last-child) {
  /* no suggestion */
  margin-bottom: var(--wp--style--block-gap, 1.75rem) !important;
}

@media (min-width: 782px) {
  .wp-block-columns[class*="fullwidth-cols"]:not(.is-not-stacked-on-mobile)
        > .wp-block-column:not(:last-child) {
    /* no suggestion */
    margin-bottom: 0 !important;
  }
}

/* Remove margin bottom from "not-stacked" columns */

.wp-block-columns[class*="fullwidth-cols"].is-not-stacked-on-mobile
    > .wp-block-column {
  /* no suggestion */
  margin-bottom: 0 !important;
}

@media (min-width: 600px) and (max-width: 781px) {
  .wp-block-columns[class*="fullwidth-cols"]:not(.is-not-stacked-on-mobile)
        > .wp-block-column:nth-child(even) {
    /* no suggestion */
    margin-left: var(--wp--style--block-gap, 2em) !important;
  }
}

/*
    The `tablet:fullwidth-cols` and `desktop:fullwidth-cols` utilities are used
    to counter the core/columns responsive for at our breakpoints.
*/

@media (max-width: 781px) {
  .tablet\:fullwidth-cols.wp-block-columns:not(.is-not-stacked-on-mobile) {
    flex-wrap: wrap !important;
  }

  .tablet\:fullwidth-cols.wp-block-columns:not(.is-not-stacked-on-mobile)
        > .wp-block-column {
    margin-left: 0 !important;
  }

  .tablet\:fullwidth-cols.wp-block-columns:not(.is-not-stacked-on-mobile)
        > .wp-block-column:not([style*="margin"]) {
    /* no suggestion */
    margin-left: 0 !important;
  }

  .tablet\:fullwidth-cols.wp-block-columns:not(.is-not-stacked-on-mobile)
        > .wp-block-column {
    flex-basis: 100% !important; /* Required to negate core/columns flex-basis */
  }
}

@media (max-width: 1079px) {
  .desktop\:fullwidth-cols.wp-block-columns:not(.is-not-stacked-on-mobile) {
    flex-wrap: wrap !important;
  }

  .desktop\:fullwidth-cols.wp-block-columns:not(.is-not-stacked-on-mobile)
        > .wp-block-column {
    margin-left: 0 !important;
  }

  .desktop\:fullwidth-cols.wp-block-columns:not(.is-not-stacked-on-mobile)
        > .wp-block-column:not([style*="margin"]) {
    /* no suggestion */
    margin-left: 0 !important;
  }

  .desktop\:fullwidth-cols.wp-block-columns:not(.is-not-stacked-on-mobile)
        > .wp-block-column {
    flex-basis: 100% !important; /* Required to negate core/columns flex-basis */
  }

  .desktop\:fullwidth-cols.wp-block-columns:not(.is-not-stacked-on-mobile)
        > .wp-block-column:not(:last-child) {
    margin-bottom: var(--wp--style--block-gap, 1.75rem) !important;
  }
}

.direction-rtl {
  direction: rtl !important;
}

.direction-ltr {
  direction: ltr !important;
}

/* Use "is-style-" prefix to support adding this style to the core/list block */

.is-style-inline-list {
  padding-left: 0 !important;
}

.is-style-inline-list li {
  /* no suggestion */
  list-style-type: none !important;
}

@media (min-width: 782px) {
  .is-style-inline-list li {
    margin-right: var(--wp--style--block-gap, 1.75rem) !important;
    display: inline !important;
  }
}

.is-style-inline-list li:first-child {
  /* no suggestion */
}

@media (min-width: 782px) {
  .is-style-inline-list li:first-child {
    margin-left: 0 !important;
  }
}

.is-style-inline-list li:last-child {
  /* no suggestion */
}

@media (min-width: 782px) {
  .is-style-inline-list li:last-child {
    margin-right: 0 !important;
  }
}

.bring-to-front {
  position: relative !important;
  z-index: 10 !important;
}

.text-stroke {
  -webkit-text-stroke-width: var(
        --wp--custom--typography--text-stroke-width,
        2px
    ) !important;
  -webkit-text-stroke-color: var(--wp--preset--color--background) !important;
}

.text-stroke--primary {
  -webkit-text-stroke-width: var(
        --wp--custom--typography--text-stroke-width,
        2px
    ) !important;
  -webkit-text-stroke-color: var(--wp--preset--color--primary) !important;
}

.text-stroke--secondary {
  -webkit-text-stroke-width: var(
        --wp--custom--typography--text-stroke-width,
        2px
    ) !important;
  -webkit-text-stroke-color: var(--wp--preset--color--secondary) !important;
}

.editor\:no-caption .block-editor-rich-text__editable {
  display: none !important;
}

.editor\:no-inserter > .block-list-appender,
.editor\:no-inserter .wp-block-group__inner-container > .block-list-appender {
  display: none !important;
}

.editor\:no-inserter .wp-block-cover__inner-container > .block-list-appender {
  display: none !important;
}

.editor\:no-inserter .wp-block-column:not(.is-selected) > .block-list-appender {
  display: none !important;
}

.editor\:no-resize .components-resizable-box__handle::after,
.editor\:no-resize .components-resizable-box__side-handle::before,
.editor\:no-resize .components-resizable-box__handle {
  display: none !important;
  pointer-events: none !important;
}

.editor\:no-resize .components-resizable-box__container {
  display: block !important;
}

.editor\:pointer-events-none {
  pointer-events: none !important;
}

.is-style-angled {
  /* no suggestion */
  align-items: center !important;
  justify-content: flex-end !important;
}

.ext .is-style-angled > [class*="_inner-container"] {
  align-items: center !important;
}

.is-style-angled .wp-block-cover__image-background,
.is-style-angled .wp-block-cover__video-background {
  /* no suggestion */
  -webkit-clip-path: polygon(0 0, 30% 0%, 50% 100%, 0% 100%) !important;
          clip-path: polygon(0 0, 30% 0%, 50% 100%, 0% 100%) !important;
  z-index: 1 !important;
}

@media (min-width: 782px) {
  .is-style-angled .wp-block-cover__image-background,
    .is-style-angled .wp-block-cover__video-background {
    /* no suggestion */
    -webkit-clip-path: polygon(0 0, 55% 0%, 65% 100%, 0% 100%) !important;
            clip-path: polygon(0 0, 55% 0%, 65% 100%, 0% 100%) !important;
  }
}

.has-foreground-color {
  /* no suggestion */
  color: var(--wp--preset--color--foreground, #000) !important;
}

.has-foreground-background-color {
  /* no suggestion */
  background-color: var(--wp--preset--color--foreground, #000) !important;
}

.has-background-color {
  /* no suggestion */
  color: var(--wp--preset--color--background, #fff) !important;
}

.has-background-background-color {
  /* no suggestion */
  background-color: var(--wp--preset--color--background, #fff) !important;
}

.has-primary-color {
  /* no suggestion */
  color: var(--wp--preset--color--primary, #4b5563) !important;
}

.has-primary-background-color {
  /* no suggestion */
  background-color: var(--wp--preset--color--primary, #4b5563) !important;
}

.has-secondary-color {
  /* no suggestion */
  color: var(--wp--preset--color--secondary, #9ca3af) !important;
}

.has-secondary-background-color {
  /* no suggestion */
  background-color: var(--wp--preset--color--secondary, #9ca3af) !important;
}

/* Ensure themes that target specific elements use the right colors */

.ext.has-text-color p,
.ext.has-text-color h1,
.ext.has-text-color h2,
.ext.has-text-color h3,
.ext.has-text-color h4,
.ext.has-text-color h5,
.ext.has-text-color h6 {
  /* no suggestion */
  color: currentColor !important;
}

.has-white-color {
  /* no suggestion */
  color: var(--wp--preset--color--white, #fff) !important;
}

.has-black-color {
  /* no suggestion */
  color: var(--wp--preset--color--black, #000) !important;
}

.has-ext-foreground-background-color {
  /* no suggestion */
  background-color: var(
        --wp--preset--color--foreground,
        var(--wp--preset--color--black, #000)
    ) !important;
}

.has-ext-primary-background-color {
  /* no suggestion */
  background-color: var(
        --wp--preset--color--primary,
        var(--wp--preset--color--cyan-bluish-gray, #000)
    ) !important;
}

/* Fix button borders with specified background colors */

.wp-block-button__link.has-black-background-color {
  /* no suggestion */
  border-color: var(--wp--preset--color--black, #000) !important;
}

.wp-block-button__link.has-white-background-color {
  /* no suggestion */
  border-color: var(--wp--preset--color--white, #fff) !important;
}

.has-ext-small-font-size {
  /* no suggestion */
  font-size: var(--wp--preset--font-size--ext-small) !important;
}

.has-ext-medium-font-size {
  /* no suggestion */
  font-size: var(--wp--preset--font-size--ext-medium) !important;
}

.has-ext-large-font-size {
  /* no suggestion */
  font-size: var(--wp--preset--font-size--ext-large) !important;
  line-height: 1.2 !important;
}

.has-ext-x-large-font-size {
  /* no suggestion */
  font-size: var(--wp--preset--font-size--ext-x-large) !important;
  line-height: 1 !important;
}

.has-ext-xx-large-font-size {
  /* no suggestion */
  font-size: var(--wp--preset--font-size--ext-xx-large) !important;
  line-height: 1 !important;
}

/* Line height */

.has-ext-x-large-font-size:not([style*="line-height"]) {
  /* no suggestion */
  line-height: 1.1 !important;
}

.has-ext-xx-large-font-size:not([style*="line-height"]) {
  /* no suggestion */
  line-height: 1.1 !important;
}

.ext .wp-block-group > * {
  /* Line height */
  margin-top: 0 !important;
  margin-bottom: 0 !important;
}

.ext .wp-block-group > * + * {
  margin-top: var(--wp--style--block-gap, 1.75rem) !important;
  margin-bottom: 0 !important;
}

.ext h2 {
  margin-top: var(--wp--style--block-gap, 1.75rem) !important;
  margin-bottom: var(--wp--style--block-gap, 1.75rem) !important;
}

.has-ext-x-large-font-size + p,
.has-ext-x-large-font-size + h3 {
  margin-top: 0.5rem !important;
}

.ext .wp-block-buttons > .wp-block-button.wp-block-button__width-25 {
  width: calc(25% - var(--wp--style--block-gap, 0.5em) * 0.75) !important;
  min-width: 12rem !important;
}

/* Classic themes use an inner [class*="_inner-container"] that our utilities cannot directly target, so we need to do so with a few */

.ext .ext-grid > [class*="_inner-container"] {
  /* no suggestion */
  display: grid !important;
}

/* Unhinge grid for container blocks in classic themes, and < 5.9 */

.ext > [class*="_inner-container"] > .ext-grid:not([class*="columns"]),
.ext
    > [class*="_inner-container"]
    > .wp-block
    > .ext-grid:not([class*="columns"]) {
  /* no suggestion */
  display: initial !important;
}

/* Grid Columns */

.ext .ext-grid-cols-1 > [class*="_inner-container"] {
  /* no suggestion */
  grid-template-columns: repeat(1, minmax(0, 1fr)) !important;
}

.ext .ext-grid-cols-2 > [class*="_inner-container"] {
  /* no suggestion */
  grid-template-columns: repeat(2, minmax(0, 1fr)) !important;
}

.ext .ext-grid-cols-3 > [class*="_inner-container"] {
  /* no suggestion */
  grid-template-columns: repeat(3, minmax(0, 1fr)) !important;
}

.ext .ext-grid-cols-4 > [class*="_inner-container"] {
  /* no suggestion */
  grid-template-columns: repeat(4, minmax(0, 1fr)) !important;
}

.ext .ext-grid-cols-5 > [class*="_inner-container"] {
  /* no suggestion */
  grid-template-columns: repeat(5, minmax(0, 1fr)) !important;
}

.ext .ext-grid-cols-6 > [class*="_inner-container"] {
  /* no suggestion */
  grid-template-columns: repeat(6, minmax(0, 1fr)) !important;
}

.ext .ext-grid-cols-7 > [class*="_inner-container"] {
  /* no suggestion */
  grid-template-columns: repeat(7, minmax(0, 1fr)) !important;
}

.ext .ext-grid-cols-8 > [class*="_inner-container"] {
  /* no suggestion */
  grid-template-columns: repeat(8, minmax(0, 1fr)) !important;
}

.ext .ext-grid-cols-9 > [class*="_inner-container"] {
  /* no suggestion */
  grid-template-columns: repeat(9, minmax(0, 1fr)) !important;
}

.ext .ext-grid-cols-10 > [class*="_inner-container"] {
  /* no suggestion */
  grid-template-columns: repeat(10, minmax(0, 1fr)) !important;
}

.ext .ext-grid-cols-11 > [class*="_inner-container"] {
  /* no suggestion */
  grid-template-columns: repeat(11, minmax(0, 1fr)) !important;
}

.ext .ext-grid-cols-12 > [class*="_inner-container"] {
  /* no suggestion */
  grid-template-columns: repeat(12, minmax(0, 1fr)) !important;
}

.ext .ext-grid-cols-13 > [class*="_inner-container"] {
  /* no suggestion */
  grid-template-columns: repeat(13, minmax(0, 1fr)) !important;
}

.ext .ext-grid-cols-none > [class*="_inner-container"] {
  /* no suggestion */
  grid-template-columns: none !important;
}

/* Grid Rows */

.ext .ext-grid-rows-1 > [class*="_inner-container"] {
  /* no suggestion */
  grid-template-rows: repeat(1, minmax(0, 1fr)) !important;
}

.ext .ext-grid-rows-2 > [class*="_inner-container"] {
  /* no suggestion */
  grid-template-rows: repeat(2, minmax(0, 1fr)) !important;
}

.ext .ext-grid-rows-3 > [class*="_inner-container"] {
  /* no suggestion */
  grid-template-rows: repeat(3, minmax(0, 1fr)) !important;
}

.ext .ext-grid-rows-4 > [class*="_inner-container"] {
  /* no suggestion */
  grid-template-rows: repeat(4, minmax(0, 1fr)) !important;
}

.ext .ext-grid-rows-5 > [class*="_inner-container"] {
  /* no suggestion */
  grid-template-rows: repeat(5, minmax(0, 1fr)) !important;
}

.ext .ext-grid-rows-6 > [class*="_inner-container"] {
  /* no suggestion */
  grid-template-rows: repeat(6, minmax(0, 1fr)) !important;
}

.ext .ext-grid-rows-none > [class*="_inner-container"] {
  /* no suggestion */
  grid-template-rows: none !important;
}

/* Align */

.ext .ext-items-start > [class*="_inner-container"] {
  align-items: flex-start !important;
}

.ext .ext-items-end > [class*="_inner-container"] {
  align-items: flex-end !important;
}

.ext .ext-items-center > [class*="_inner-container"] {
  align-items: center !important;
}

.ext .ext-items-baseline > [class*="_inner-container"] {
  align-items: baseline !important;
}

.ext .ext-items-stretch > [class*="_inner-container"] {
  align-items: stretch !important;
}

.ext.wp-block-group > *:last-child {
  /* no suggestion */
  margin-bottom: 0 !important;
}

/* For <5.9 */

.ext .wp-block-group__inner-container {
  /* no suggestion */
  padding: 0 !important;
}

.ext.has-background {
  /* no suggestion */
  padding-left: var(--wp--style--block-gap, 1.75rem) !important;
  padding-right: var(--wp--style--block-gap, 1.75rem) !important;
}

/* Fallback for classic theme group blocks */

.ext *[class*="inner-container"] > .alignwide *[class*="inner-container"],
.ext
    *[class*="inner-container"]
    > [data-align="wide"]
    *[class*="inner-container"] {
  /* no suggestion */
  max-width: var(--responsive--alignwide-width, 120rem) !important;
}

.ext *[class*="inner-container"] > .alignwide *[class*="inner-container"] > *,
.ext
    *[class*="inner-container"]
    > [data-align="wide"]
    *[class*="inner-container"]
    > * {
  /* no suggestion */
}

.ext *[class*="inner-container"] > .alignwide *[class*="inner-container"] > *, .ext
    *[class*="inner-container"]
    > [data-align="wide"]
    *[class*="inner-container"]
    > * {
  max-width: 100% !important;
}

/* Ensure image block display is standardized */

.ext .wp-block-image {
  /* no suggestion */
  position: relative !important;
  text-align: center !important;
}

.ext .wp-block-image img {
  /* no suggestion */
  display: inline-block !important;
  vertical-align: middle !important;
}

body {
  /* no suggestion */
  /* We need to abstract this out of tailwind.config because clamp doesnt translate with negative margins */
  --extendify--spacing--large: var(
        --wp--custom--spacing--large,
        clamp(2em, 8vw, 8em)
    ) !important;
  /* Add pattern preset font sizes */
  --wp--preset--font-size--ext-small: 1rem !important;
  --wp--preset--font-size--ext-medium: 1.125rem !important;
  --wp--preset--font-size--ext-large: clamp(1.65rem, 3.5vw, 2.15rem) !important;
  --wp--preset--font-size--ext-x-large: clamp(3rem, 6vw, 4.75rem) !important;
  --wp--preset--font-size--ext-xx-large: clamp(3.25rem, 7.5vw, 5.75rem) !important;
  /* Fallbacks for pre 5.9 themes */
  --wp--preset--color--black: #000 !important;
  --wp--preset--color--white: #fff !important;
}

.ext * {
  box-sizing: border-box !important;
}

/* Astra: Remove spacer block visuals in the library */

.block-editor-block-preview__content-iframe
    .ext
    [data-type="core/spacer"]
    .components-resizable-box__container {
  /* no suggestion */
  background: transparent !important;
}

.block-editor-block-preview__content-iframe
    .ext
    [data-type="core/spacer"]
    .block-library-spacer__resize-container::before {
  /* no suggestion */
  display: none !important;
}

/* Twenty Twenty adds a lot of margin automatically to blocks. We only want our own margin added to our patterns. */

.ext .wp-block-group__inner-container figure.wp-block-gallery.alignfull {
  /* no suggestion */
  margin-top: unset !important;
  margin-bottom: unset !important;
}

/* Ensure no funky business is assigned to alignwide */

.ext .alignwide {
  /* no suggestion */
  margin-left: auto !important;
  margin-right: auto !important;
}

/* Negate blockGap being inappropriately assigned in the editor */

.is-root-container.block-editor-block-list__layout
    > [data-align="full"]:not(:first-of-type)
    > .ext-my-0,
.is-root-container.block-editor-block-list__layout
    > [data-align="wide"]
    > .ext-my-0:not([style*="margin"]) {
  /* no suggestion */
  margin-top: calc(-1 * var(--wp--style--block-gap, 28px)) !important;
}

/* Ensure vh content in previews looks taller */

.block-editor-block-preview__content-iframe .preview\:min-h-50 {
  /* no suggestion */
  min-height: 50vw !important;
}

.block-editor-block-preview__content-iframe .preview\:min-h-60 {
  /* no suggestion */
  min-height: 60vw !important;
}

.block-editor-block-preview__content-iframe .preview\:min-h-70 {
  /* no suggestion */
  min-height: 70vw !important;
}

.block-editor-block-preview__content-iframe .preview\:min-h-80 {
  /* no suggestion */
  min-height: 80vw !important;
}

.block-editor-block-preview__content-iframe .preview\:min-h-100 {
  /* no suggestion */
  min-height: 100vw !important;
}

/*  Removes excess margin when applied to the alignfull parent div in Block Themes */

.ext-mr-0.alignfull:not([style*="margin"]):not([style*="margin"]) {
  /* no suggestion */
  margin-right: 0 !important;
}

.ext-ml-0:not([style*="margin"]):not([style*="margin"]) {
  /* no suggestion */
  margin-left: 0 !important;
}

/*  Ensures fullwidth blocks display properly in the editor when margin is zeroed out */

.is-root-container
    .wp-block[data-align="full"]
    > .ext-mx-0:not([style*="margin"]):not([style*="margin"]) {
  /* no suggestion */
  margin-right: calc(1 * var(--wp--custom--spacing--outer, 0)) !important;
  margin-left: calc(1 * var(--wp--custom--spacing--outer, 0)) !important;
  overflow: hidden !important;
  width: unset !important;
}

@media (min-width: 782px) {
  .tablet\:ext-absolute {
    position: absolute !important;
  }

  .tablet\:ext-relative {
    position: relative !important;
  }

  .tablet\:ext-top-base {
    top: var(--wp--style--block-gap, 1.75rem) !important;
  }

  .tablet\:ext-top-lg {
    top: var(--extendify--spacing--large, 3rem) !important;
  }

  .tablet\:ext--top-base {
    top: calc(var(--wp--style--block-gap, 1.75rem) * -1) !important;
  }

  .tablet\:ext--top-lg {
    top: calc(var(--extendify--spacing--large, 3rem) * -1) !important;
  }

  .tablet\:ext-right-base {
    right: var(--wp--style--block-gap, 1.75rem) !important;
  }

  .tablet\:ext-right-lg {
    right: var(--extendify--spacing--large, 3rem) !important;
  }

  .tablet\:ext--right-base {
    right: calc(var(--wp--style--block-gap, 1.75rem) * -1) !important;
  }

  .tablet\:ext--right-lg {
    right: calc(var(--extendify--spacing--large, 3rem) * -1) !important;
  }

  .tablet\:ext-bottom-base {
    bottom: var(--wp--style--block-gap, 1.75rem) !important;
  }

  .tablet\:ext-bottom-lg {
    bottom: var(--extendify--spacing--large, 3rem) !important;
  }

  .tablet\:ext--bottom-base {
    bottom: calc(var(--wp--style--block-gap, 1.75rem) * -1) !important;
  }

  .tablet\:ext--bottom-lg {
    bottom: calc(var(--extendify--spacing--large, 3rem) * -1) !important;
  }

  .tablet\:ext-left-base {
    left: var(--wp--style--block-gap, 1.75rem) !important;
  }

  .tablet\:ext-left-lg {
    left: var(--extendify--spacing--large, 3rem) !important;
  }

  .tablet\:ext--left-base {
    left: calc(var(--wp--style--block-gap, 1.75rem) * -1) !important;
  }

  .tablet\:ext--left-lg {
    left: calc(var(--extendify--spacing--large, 3rem) * -1) !important;
  }

  .tablet\:ext-order-1 {
    order: 1 !important;
  }

  .tablet\:ext-order-2 {
    order: 2 !important;
  }

  .tablet\:ext-m-0:not([style*="margin"]) {
    margin: 0 !important;
  }

  .tablet\:ext-m-auto:not([style*="margin"]) {
    margin: auto !important;
  }

  .tablet\:ext-m-base:not([style*="margin"]) {
    margin: var(--wp--style--block-gap, 1.75rem) !important;
  }

  .tablet\:ext-m-lg:not([style*="margin"]) {
    margin: var(--extendify--spacing--large, 3rem) !important;
  }

  .tablet\:ext--m-base:not([style*="margin"]) {
    margin: calc(var(--wp--style--block-gap, 1.75rem) * -1) !important;
  }

  .tablet\:ext--m-lg:not([style*="margin"]) {
    margin: calc(var(--extendify--spacing--large, 3rem) * -1) !important;
  }

  .tablet\:ext-mx-0:not([style*="margin"]) {
    margin-left: 0 !important;
    margin-right: 0 !important;
  }

  .tablet\:ext-mx-auto:not([style*="margin"]) {
    margin-left: auto !important;
    margin-right: auto !important;
  }

  .tablet\:ext-mx-base:not([style*="margin"]) {
    margin-left: var(--wp--style--block-gap, 1.75rem) !important;
    margin-right: var(--wp--style--block-gap, 1.75rem) !important;
  }

  .tablet\:ext-mx-lg:not([style*="margin"]) {
    margin-left: var(--extendify--spacing--large, 3rem) !important;
    margin-right: var(--extendify--spacing--large, 3rem) !important;
  }

  .tablet\:ext--mx-base:not([style*="margin"]) {
    margin-left: calc(var(--wp--style--block-gap, 1.75rem) * -1) !important;
    margin-right: calc(var(--wp--style--block-gap, 1.75rem) * -1) !important;
  }

  .tablet\:ext--mx-lg:not([style*="margin"]) {
    margin-left: calc(var(--extendify--spacing--large, 3rem) * -1) !important;
    margin-right: calc(var(--extendify--spacing--large, 3rem) * -1) !important;
  }

  .tablet\:ext-my-0:not([style*="margin"]) {
    margin-top: 0 !important;
    margin-bottom: 0 !important;
  }

  .tablet\:ext-my-auto:not([style*="margin"]) {
    margin-top: auto !important;
    margin-bottom: auto !important;
  }

  .tablet\:ext-my-base:not([style*="margin"]) {
    margin-top: var(--wp--style--block-gap, 1.75rem) !important;
    margin-bottom: var(--wp--style--block-gap, 1.75rem) !important;
  }

  .tablet\:ext-my-lg:not([style*="margin"]) {
    margin-top: var(--extendify--spacing--large, 3rem) !important;
    margin-bottom: var(--extendify--spacing--large, 3rem) !important;
  }

  .tablet\:ext--my-base:not([style*="margin"]) {
    margin-top: calc(var(--wp--style--block-gap, 1.75rem) * -1) !important;
    margin-bottom: calc(var(--wp--style--block-gap, 1.75rem) * -1) !important;
  }

  .tablet\:ext--my-lg:not([style*="margin"]) {
    margin-top: calc(var(--extendify--spacing--large, 3rem) * -1) !important;
    margin-bottom: calc(var(--extendify--spacing--large, 3rem) * -1) !important;
  }

  .tablet\:ext-mt-0:not([style*="margin"]) {
    margin-top: 0 !important;
  }

  .tablet\:ext-mt-auto:not([style*="margin"]) {
    margin-top: auto !important;
  }

  .tablet\:ext-mt-base:not([style*="margin"]) {
    margin-top: var(--wp--style--block-gap, 1.75rem) !important;
  }

  .tablet\:ext-mt-lg:not([style*="margin"]) {
    margin-top: var(--extendify--spacing--large, 3rem) !important;
  }

  .tablet\:ext--mt-base:not([style*="margin"]) {
    margin-top: calc(var(--wp--style--block-gap, 1.75rem) * -1) !important;
  }

  .tablet\:ext--mt-lg:not([style*="margin"]) {
    margin-top: calc(var(--extendify--spacing--large, 3rem) * -1) !important;
  }

  .tablet\:ext-mr-0:not([style*="margin"]) {
    margin-right: 0 !important;
  }

  .tablet\:ext-mr-auto:not([style*="margin"]) {
    margin-right: auto !important;
  }

  .tablet\:ext-mr-base:not([style*="margin"]) {
    margin-right: var(--wp--style--block-gap, 1.75rem) !important;
  }

  .tablet\:ext-mr-lg:not([style*="margin"]) {
    margin-right: var(--extendify--spacing--large, 3rem) !important;
  }

  .tablet\:ext--mr-base:not([style*="margin"]) {
    margin-right: calc(var(--wp--style--block-gap, 1.75rem) * -1) !important;
  }

  .tablet\:ext--mr-lg:not([style*="margin"]) {
    margin-right: calc(var(--extendify--spacing--large, 3rem) * -1) !important;
  }

  .tablet\:ext-mb-0:not([style*="margin"]) {
    margin-bottom: 0 !important;
  }

  .tablet\:ext-mb-auto:not([style*="margin"]) {
    margin-bottom: auto !important;
  }

  .tablet\:ext-mb-base:not([style*="margin"]) {
    margin-bottom: var(--wp--style--block-gap, 1.75rem) !important;
  }

  .tablet\:ext-mb-lg:not([style*="margin"]) {
    margin-bottom: var(--extendify--spacing--large, 3rem) !important;
  }

  .tablet\:ext--mb-base:not([style*="margin"]) {
    margin-bottom: calc(var(--wp--style--block-gap, 1.75rem) * -1) !important;
  }

  .tablet\:ext--mb-lg:not([style*="margin"]) {
    margin-bottom: calc(var(--extendify--spacing--large, 3rem) * -1) !important;
  }

  .tablet\:ext-ml-0:not([style*="margin"]) {
    margin-left: 0 !important;
  }

  .tablet\:ext-ml-auto:not([style*="margin"]) {
    margin-left: auto !important;
  }

  .tablet\:ext-ml-base:not([style*="margin"]) {
    margin-left: var(--wp--style--block-gap, 1.75rem) !important;
  }

  .tablet\:ext-ml-lg:not([style*="margin"]) {
    margin-left: var(--extendify--spacing--large, 3rem) !important;
  }

  .tablet\:ext--ml-base:not([style*="margin"]) {
    margin-left: calc(var(--wp--style--block-gap, 1.75rem) * -1) !important;
  }

  .tablet\:ext--ml-lg:not([style*="margin"]) {
    margin-left: calc(var(--extendify--spacing--large, 3rem) * -1) !important;
  }

  .tablet\:ext-block {
    display: block !important;
  }

  .tablet\:ext-inline-block {
    display: inline-block !important;
  }

  .tablet\:ext-inline {
    display: inline !important;
  }

  .tablet\:ext-flex {
    display: flex !important;
  }

  .tablet\:ext-inline-flex {
    display: inline-flex !important;
  }

  .tablet\:ext-grid {
    display: grid !important;
  }

  .tablet\:ext-inline-grid {
    display: inline-grid !important;
  }

  .tablet\:ext-hidden {
    display: none !important;
  }

  .tablet\:ext-w-auto {
    width: auto !important;
  }

  .tablet\:ext-w-full {
    width: 100% !important;
  }

  .tablet\:ext-max-w-full {
    max-width: 100% !important;
  }

  .tablet\:ext-flex-1 {
    flex: 1 1 0% !important;
  }

  .tablet\:ext-flex-auto {
    flex: 1 1 auto !important;
  }

  .tablet\:ext-flex-initial {
    flex: 0 1 auto !important;
  }

  .tablet\:ext-flex-none {
    flex: none !important;
  }

  .tablet\:ext-flex-shrink-0 {
    flex-shrink: 0 !important;
  }

  .tablet\:ext-flex-shrink {
    flex-shrink: 1 !important;
  }

  .tablet\:ext-flex-grow-0 {
    flex-grow: 0 !important;
  }

  .tablet\:ext-flex-grow {
    flex-grow: 1 !important;
  }

  .tablet\:ext-list-none {
    list-style-type: none !important;
  }

  .tablet\:ext-grid-cols-1 {
    grid-template-columns: repeat(1, minmax(0, 1fr)) !important;
  }

  .tablet\:ext-grid-cols-2 {
    grid-template-columns: repeat(2, minmax(0, 1fr)) !important;
  }

  .tablet\:ext-grid-cols-3 {
    grid-template-columns: repeat(3, minmax(0, 1fr)) !important;
  }

  .tablet\:ext-grid-cols-4 {
    grid-template-columns: repeat(4, minmax(0, 1fr)) !important;
  }


  .tablet\:ext-grid-cols-5 {
    grid-template-columns: repeat(5, minmax(0, 1fr)) !important;
  }

  .tablet\:ext-grid-cols-6 {
    grid-template-columns: repeat(6, minmax(0, 1fr)) !important;
  }

  .tablet\:ext-grid-cols-7 {
    grid-template-columns: repeat(7, minmax(0, 1fr)) !important;
  }

  .tablet\:ext-grid-cols-8 {
    grid-template-columns: repeat(8, minmax(0, 1fr)) !important;
  }

  .tablet\:ext-grid-cols-9 {
    grid-template-columns: repeat(9, minmax(0, 1fr)) !important;
  }

  .tablet\:ext-grid-cols-10 {
    grid-template-columns: repeat(10, minmax(0, 1fr)) !important;
  }

  .tablet\:ext-grid-cols-11 {
    grid-template-columns: repeat(11, minmax(0, 1fr)) !important;
  }

  .tablet\:ext-grid-cols-12 {
    grid-template-columns: repeat(12, minmax(0, 1fr)) !important;
  }

  .tablet\:ext-grid-cols-none {
    grid-template-columns: none !important;
  }

  .tablet\:ext-flex-row {
    flex-direction: row !important;
  }

  .tablet\:ext-flex-row-reverse {
    flex-direction: row-reverse !important;
  }

  .tablet\:ext-flex-col {
    flex-direction: column !important;
  }

  .tablet\:ext-flex-col-reverse {
    flex-direction: column-reverse !important;
  }

  .tablet\:ext-flex-wrap {
    flex-wrap: wrap !important;
  }

  .tablet\:ext-flex-wrap-reverse {
    flex-wrap: wrap-reverse !important;
  }

  .tablet\:ext-flex-nowrap {
    flex-wrap: nowrap !important;
  }

  .tablet\:ext-items-start {
    align-items: flex-start !important;
  }

  .tablet\:ext-items-end {
    align-items: flex-end !important;
  }

  .tablet\:ext-items-center {
    align-items: center !important;
  }

  .tablet\:ext-items-baseline {
    align-items: baseline !important;
  }

  .tablet\:ext-items-stretch {
    align-items: stretch !important;
  }

  .tablet\:ext-justify-start {
    justify-content: flex-start !important;
  }

  .tablet\:ext-justify-end {
    justify-content: flex-end !important;
  }

  .tablet\:ext-justify-center {
    justify-content: center !important;
  }

  .tablet\:ext-justify-between {
    justify-content: space-between !important;
  }

  .tablet\:ext-justify-around {
    justify-content: space-around !important;
  }

  .tablet\:ext-justify-evenly {
    justify-content: space-evenly !important;
  }

  .tablet\:ext-justify-items-start {
    justify-items: start !important;
  }

  .tablet\:ext-justify-items-end {
    justify-items: end !important;
  }

  .tablet\:ext-justify-items-center {
    justify-items: center !important;
  }

  .tablet\:ext-justify-items-stretch {
    justify-items: stretch !important;
  }

  .tablet\:ext-justify-self-auto {
    justify-self: auto !important;
  }

  .tablet\:ext-justify-self-start {
    justify-self: start !important;
  }

  .tablet\:ext-justify-self-end {
    justify-self: end !important;
  }

  .tablet\:ext-justify-self-center {
    justify-self: center !important;
  }

  .tablet\:ext-justify-self-stretch {
    justify-self: stretch !important;
  }

  .tablet\:ext-p-0:not([style*="padding"]) {
    padding: 0 !important;
  }

  .tablet\:ext-p-base:not([style*="padding"]) {
    padding: var(--wp--style--block-gap, 1.75rem) !important;
  }

  .tablet\:ext-p-lg:not([style*="padding"]) {
    padding: var(--extendify--spacing--large, 3rem) !important;
  }

  .tablet\:ext-px-0:not([style*="padding"]) {
    padding-left: 0 !important;
    padding-right: 0 !important;
  }

  .tablet\:ext-px-base:not([style*="padding"]) {
    padding-left: var(--wp--style--block-gap, 1.75rem) !important;
    padding-right: var(--wp--style--block-gap, 1.75rem) !important;
  }

  .tablet\:ext-px-lg:not([style*="padding"]) {
    padding-left: var(--extendify--spacing--large, 3rem) !important;
    padding-right: var(--extendify--spacing--large, 3rem) !important;
  }

  .tablet\:ext-py-0:not([style*="padding"]) {
    padding-top: 0 !important;
    padding-bottom: 0 !important;
  }

  .tablet\:ext-py-base:not([style*="padding"]) {
    padding-top: var(--wp--style--block-gap, 1.75rem) !important;
    padding-bottom: var(--wp--style--block-gap, 1.75rem) !important;
  }

  .tablet\:ext-py-lg:not([style*="padding"]) {
    padding-top: var(--extendify--spacing--large, 3rem) !important;
    padding-bottom: var(--extendify--spacing--large, 3rem) !important;
  }

  .tablet\:ext-pt-0:not([style*="padding"]) {
    padding-top: 0 !important;
  }

  .tablet\:ext-pt-base:not([style*="padding"]) {
    padding-top: var(--wp--style--block-gap, 1.75rem) !important;
  }

  .tablet\:ext-pt-lg:not([style*="padding"]) {
    padding-top: var(--extendify--spacing--large, 3rem) !important;
  }

  .tablet\:ext-pr-0:not([style*="padding"]) {
    padding-right: 0 !important;
  }

  .tablet\:ext-pr-base:not([style*="padding"]) {
    padding-right: var(--wp--style--block-gap, 1.75rem) !important;
  }

  .tablet\:ext-pr-lg:not([style*="padding"]) {
    padding-right: var(--extendify--spacing--large, 3rem) !important;
  }

  .tablet\:ext-pb-0:not([style*="padding"]) {
    padding-bottom: 0 !important;
  }

  .tablet\:ext-pb-base:not([style*="padding"]) {
    padding-bottom: var(--wp--style--block-gap, 1.75rem) !important;
  }

  .tablet\:ext-pb-lg:not([style*="padding"]) {
    padding-bottom: var(--extendify--spacing--large, 3rem) !important;
  }

  .tablet\:ext-pl-0:not([style*="padding"]) {
    padding-left: 0 !important;
  }

  .tablet\:ext-pl-base:not([style*="padding"]) {
    padding-left: var(--wp--style--block-gap, 1.75rem) !important;
  }

  .tablet\:ext-pl-lg:not([style*="padding"]) {
    padding-left: var(--extendify--spacing--large, 3rem) !important;
  }

  .tablet\:ext-text-left {
    text-align: left !important;
  }

  .tablet\:ext-text-center {
    text-align: center !important;
  }

  .tablet\:ext-text-right {
    text-align: right !important;
  }
}

@media (min-width: 1080px) {
  .desktop\:ext-absolute {
    position: absolute !important;
  }

  .desktop\:ext-relative {
    position: relative !important;
  }

  .desktop\:ext-top-base {
    top: var(--wp--style--block-gap, 1.75rem) !important;
  }

  .desktop\:ext-top-lg {
    top: var(--extendify--spacing--large, 3rem) !important;
  }

  .desktop\:ext--top-base {
    top: calc(var(--wp--style--block-gap, 1.75rem) * -1) !important;
  }

  .desktop\:ext--top-lg {
    top: calc(var(--extendify--spacing--large, 3rem) * -1) !important;
  }

  .desktop\:ext-right-base {
    right: var(--wp--style--block-gap, 1.75rem) !important;
  }

  .desktop\:ext-right-lg {
    right: var(--extendify--spacing--large, 3rem) !important;
  }

  .desktop\:ext--right-base {
    right: calc(var(--wp--style--block-gap, 1.75rem) * -1) !important;
  }

  .desktop\:ext--right-lg {
    right: calc(var(--extendify--spacing--large, 3rem) * -1) !important;
  }

  .desktop\:ext-bottom-base {
    bottom: var(--wp--style--block-gap, 1.75rem) !important;
  }

  .desktop\:ext-bottom-lg {
    bottom: var(--extendify--spacing--large, 3rem) !important;
  }

  .desktop\:ext--bottom-base {
    bottom: calc(var(--wp--style--block-gap, 1.75rem) * -1) !important;
  }

  .desktop\:ext--bottom-lg {
    bottom: calc(var(--extendify--spacing--large, 3rem) * -1) !important;
  }

  .desktop\:ext-left-base {
    left: var(--wp--style--block-gap, 1.75rem) !important;
  }

  .desktop\:ext-left-lg {
    left: var(--extendify--spacing--large, 3rem) !important;
  }

  .desktop\:ext--left-base {
    left: calc(var(--wp--style--block-gap, 1.75rem) * -1) !important;
  }

  .desktop\:ext--left-lg {
    left: calc(var(--extendify--spacing--large, 3rem) * -1) !important;
  }

  .desktop\:ext-order-1 {
    order: 1 !important;
  }

  .desktop\:ext-order-2 {
    order: 2 !important;
  }

  .desktop\:ext-m-0:not([style*="margin"]) {
    margin: 0 !important;
  }

  .desktop\:ext-m-auto:not([style*="margin"]) {
    margin: auto !important;
  }

  .desktop\:ext-m-base:not([style*="margin"]) {
    margin: var(--wp--style--block-gap, 1.75rem) !important;
  }

  .desktop\:ext-m-lg:not([style*="margin"]) {
    margin: var(--extendify--spacing--large, 3rem) !important;
  }

  .desktop\:ext--m-base:not([style*="margin"]) {
    margin: calc(var(--wp--style--block-gap, 1.75rem) * -1) !important;
  }

  .desktop\:ext--m-lg:not([style*="margin"]) {
    margin: calc(var(--extendify--spacing--large, 3rem) * -1) !important;
  }

  .desktop\:ext-mx-0:not([style*="margin"]) {
    margin-left: 0 !important;
    margin-right: 0 !important;
  }

  .desktop\:ext-mx-auto:not([style*="margin"]) {
    margin-left: auto !important;
    margin-right: auto !important;
  }

  .desktop\:ext-mx-base:not([style*="margin"]) {
    margin-left: var(--wp--style--block-gap, 1.75rem) !important;
    margin-right: var(--wp--style--block-gap, 1.75rem) !important;
  }

  .desktop\:ext-mx-lg:not([style*="margin"]) {
    margin-left: var(--extendify--spacing--large, 3rem) !important;
    margin-right: var(--extendify--spacing--large, 3rem) !important;
  }

  .desktop\:ext--mx-base:not([style*="margin"]) {
    margin-left: calc(var(--wp--style--block-gap, 1.75rem) * -1) !important;
    margin-right: calc(var(--wp--style--block-gap, 1.75rem) * -1) !important;
  }

  .desktop\:ext--mx-lg:not([style*="margin"]) {
    margin-left: calc(var(--extendify--spacing--large, 3rem) * -1) !important;
    margin-right: calc(var(--extendify--spacing--large, 3rem) * -1) !important;
  }

  .desktop\:ext-my-0:not([style*="margin"]) {
    margin-top: 0 !important;
    margin-bottom: 0 !important;
  }

  .desktop\:ext-my-auto:not([style*="margin"]) {
    margin-top: auto !important;
    margin-bottom: auto !important;
  }

  .desktop\:ext-my-base:not([style*="margin"]) {
    margin-top: var(--wp--style--block-gap, 1.75rem) !important;
    margin-bottom: var(--wp--style--block-gap, 1.75rem) !important;
  }

  .desktop\:ext-my-lg:not([style*="margin"]) {
    margin-top: var(--extendify--spacing--large, 3rem) !important;
    margin-bottom: var(--extendify--spacing--large, 3rem) !important;
  }

  .desktop\:ext--my-base:not([style*="margin"]) {
    margin-top: calc(var(--wp--style--block-gap, 1.75rem) * -1) !important;
    margin-bottom: calc(var(--wp--style--block-gap, 1.75rem) * -1) !important;
  }

  .desktop\:ext--my-lg:not([style*="margin"]) {
    margin-top: calc(var(--extendify--spacing--large, 3rem) * -1) !important;
    margin-bottom: calc(var(--extendify--spacing--large, 3rem) * -1) !important;
  }

  .desktop\:ext-mt-0:not([style*="margin"]) {
    margin-top: 0 !important;
  }

  .desktop\:ext-mt-auto:not([style*="margin"]) {
    margin-top: auto !important;
  }

  .desktop\:ext-mt-base:not([style*="margin"]) {
    margin-top: var(--wp--style--block-gap, 1.75rem) !important;
  }

  .desktop\:ext-mt-lg:not([style*="margin"]) {
    margin-top: var(--extendify--spacing--large, 3rem) !important;
  }

  .desktop\:ext--mt-base:not([style*="margin"]) {
    margin-top: calc(var(--wp--style--block-gap, 1.75rem) * -1) !important;
  }

  .desktop\:ext--mt-lg:not([style*="margin"]) {
    margin-top: calc(var(--extendify--spacing--large, 3rem) * -1) !important;
  }

  .desktop\:ext-mr-0:not([style*="margin"]) {
    margin-right: 0 !important;
  }

  .desktop\:ext-mr-auto:not([style*="margin"]) {
    margin-right: auto !important;
  }

  .desktop\:ext-mr-base:not([style*="margin"]) {
    margin-right: var(--wp--style--block-gap, 1.75rem) !important;
  }

  .desktop\:ext-mr-lg:not([style*="margin"]) {
    margin-right: var(--extendify--spacing--large, 3rem) !important;
  }

  .desktop\:ext--mr-base:not([style*="margin"]) {
    margin-right: calc(var(--wp--style--block-gap, 1.75rem) * -1) !important;
  }

  .desktop\:ext--mr-lg:not([style*="margin"]) {
    margin-right: calc(var(--extendify--spacing--large, 3rem) * -1) !important;
  }

  .desktop\:ext-mb-0:not([style*="margin"]) {
    margin-bottom: 0 !important;
  }

  .desktop\:ext-mb-auto:not([style*="margin"]) {
    margin-bottom: auto !important;
  }

  .desktop\:ext-mb-base:not([style*="margin"]) {
    margin-bottom: var(--wp--style--block-gap, 1.75rem) !important;
  }

  .desktop\:ext-mb-lg:not([style*="margin"]) {
    margin-bottom: var(--extendify--spacing--large, 3rem) !important;
  }

  .desktop\:ext--mb-base:not([style*="margin"]) {
    margin-bottom: calc(var(--wp--style--block-gap, 1.75rem) * -1) !important;
  }

  .desktop\:ext--mb-lg:not([style*="margin"]) {
    margin-bottom: calc(var(--extendify--spacing--large, 3rem) * -1) !important;
  }

  .desktop\:ext-ml-0:not([style*="margin"]) {
    margin-left: 0 !important;
  }

  .desktop\:ext-ml-auto:not([style*="margin"]) {
    margin-left: auto !important;
  }

  .desktop\:ext-ml-base:not([style*="margin"]) {
    margin-left: var(--wp--style--block-gap, 1.75rem) !important;
  }

  .desktop\:ext-ml-lg:not([style*="margin"]) {
    margin-left: var(--extendify--spacing--large, 3rem) !important;
  }

  .desktop\:ext--ml-base:not([style*="margin"]) {
    margin-left: calc(var(--wp--style--block-gap, 1.75rem) * -1) !important;
  }

  .desktop\:ext--ml-lg:not([style*="margin"]) {
    margin-left: calc(var(--extendify--spacing--large, 3rem) * -1) !important;
  }

  .desktop\:ext-block {
    display: block !important;
  }

  .desktop\:ext-inline-block {
    display: inline-block !important;
  }

  .desktop\:ext-inline {
    display: inline !important;
  }

  .desktop\:ext-flex {
    display: flex !important;
  }

  .desktop\:ext-inline-flex {
    display: inline-flex !important;
  }

  .desktop\:ext-grid {
    display: grid !important;
  }

  .desktop\:ext-inline-grid {
    display: inline-grid !important;
  }

  .desktop\:ext-hidden {
    display: none !important;
  }

  .desktop\:ext-w-auto {
    width: auto !important;
  }

  .desktop\:ext-w-full {
    width: 100% !important;
  }

  .desktop\:ext-max-w-full {
    max-width: 100% !important;
  }

  .desktop\:ext-flex-1 {
    flex: 1 1 0% !important;
  }

  .desktop\:ext-flex-auto {
    flex: 1 1 auto !important;
  }

  .desktop\:ext-flex-initial {
    flex: 0 1 auto !important;
  }

  .desktop\:ext-flex-none {
    flex: none !important;
  }

  .desktop\:ext-flex-shrink-0 {
    flex-shrink: 0 !important;
  }

  .desktop\:ext-flex-shrink {
    flex-shrink: 1 !important;
  }

  .desktop\:ext-flex-grow-0 {
    flex-grow: 0 !important;
  }

  .desktop\:ext-flex-grow {
    flex-grow: 1 !important;
  }

  .desktop\:ext-list-none {
    list-style-type: none !important;
  }

  .desktop\:ext-grid-cols-1 {
    grid-template-columns: repeat(1, minmax(0, 1fr)) !important;
  }

  .desktop\:ext-grid-cols-2 {
    grid-template-columns: repeat(2, minmax(0, 1fr)) !important;
  }

  .desktop\:ext-grid-cols-3 {
    grid-template-columns: repeat(3, minmax(0, 1fr)) !important;
  }

  .desktop\:ext-grid-cols-4 {
    grid-template-columns: repeat(4, minmax(0, 1fr)) !important;
  }

  .desktop\:ext-grid-cols-5 {
    grid-template-columns: repeat(5, minmax(0, 1fr)) !important;
  }

  .desktop\:ext-grid-cols-6 {
    grid-template-columns: repeat(6, minmax(0, 1fr)) !important;
  }

  .desktop\:ext-grid-cols-7 {
    grid-template-columns: repeat(7, minmax(0, 1fr)) !important;
  }

  .desktop\:ext-grid-cols-8 {
    grid-template-columns: repeat(8, minmax(0, 1fr)) !important;
  }

  .desktop\:ext-grid-cols-9 {
    grid-template-columns: repeat(9, minmax(0, 1fr)) !important;
  }

  .desktop\:ext-grid-cols-10 {
    grid-template-columns: repeat(10, minmax(0, 1fr)) !important;
  }

  .desktop\:ext-grid-cols-11 {
    grid-template-columns: repeat(11, minmax(0, 1fr)) !important;
  }

  .desktop\:ext-grid-cols-12 {
    grid-template-columns: repeat(12, minmax(0, 1fr)) !important;
  }

  .desktop\:ext-grid-cols-none {
    grid-template-columns: none !important;
  }

  .desktop\:ext-flex-row {
    flex-direction: row !important;
  }

  .desktop\:ext-flex-row-reverse {
    flex-direction: row-reverse !important;
  }

  .desktop\:ext-flex-col {
    flex-direction: column !important;
  }

  .desktop\:ext-flex-col-reverse {
    flex-direction: column-reverse !important;
  }

  .desktop\:ext-flex-wrap {
    flex-wrap: wrap !important;
  }

  .desktop\:ext-flex-wrap-reverse {
    flex-wrap: wrap-reverse !important;
  }

  .desktop\:ext-flex-nowrap {
    flex-wrap: nowrap !important;
  }

  .desktop\:ext-items-start {
    align-items: flex-start !important;
  }

  .desktop\:ext-items-end {
    align-items: flex-end !important;
  }

  .desktop\:ext-items-center {
    align-items: center !important;
  }

  .desktop\:ext-items-baseline {
    align-items: baseline !important;
  }

  .desktop\:ext-items-stretch {
    align-items: stretch !important;
  }

  .desktop\:ext-justify-start {
    justify-content: flex-start !important;
  }

  .desktop\:ext-justify-end {
    justify-content: flex-end !important;
  }

  .desktop\:ext-justify-center {
    justify-content: center !important;
  }

  .desktop\:ext-justify-between {
    justify-content: space-between !important;
  }

  .desktop\:ext-justify-around {
    justify-content: space-around !important;
  }

  .desktop\:ext-justify-evenly {
    justify-content: space-evenly !important;
  }

  .desktop\:ext-justify-items-start {
    justify-items: start !important;
  }

  .desktop\:ext-justify-items-end {
    justify-items: end !important;
  }

  .desktop\:ext-justify-items-center {
    justify-items: center !important;
  }

  .desktop\:ext-justify-items-stretch {
    justify-items: stretch !important;
  }

  .desktop\:ext-justify-self-auto {
    justify-self: auto !important;
  }

  .desktop\:ext-justify-self-start {
    justify-self: start !important;
  }

  .desktop\:ext-justify-self-end {
    justify-self: end !important;
  }

  .desktop\:ext-justify-self-center {
    justify-self: center !important;
  }

  .desktop\:ext-justify-self-stretch {
    justify-self: stretch !important;
  }

  .desktop\:ext-p-0:not([style*="padding"]) {
    padding: 0 !important;
  }

  .desktop\:ext-p-base:not([style*="padding"]) {
    padding: var(--wp--style--block-gap, 1.75rem) !important;
  }

  .desktop\:ext-p-lg:not([style*="padding"]) {
    padding: var(--extendify--spacing--large, 3rem) !important;
  }

  .desktop\:ext-px-0:not([style*="padding"]) {
    padding-left: 0 !important;
    padding-right: 0 !important;
  }

  .desktop\:ext-px-base:not([style*="padding"]) {
    padding-left: var(--wp--style--block-gap, 1.75rem) !important;
    padding-right: var(--wp--style--block-gap, 1.75rem) !important;
  }

  .desktop\:ext-px-lg:not([style*="padding"]) {
    padding-left: var(--extendify--spacing--large, 3rem) !important;
    padding-right: var(--extendify--spacing--large, 3rem) !important;
  }

  .desktop\:ext-py-0:not([style*="padding"]) {
    padding-top: 0 !important;
    padding-bottom: 0 !important;
  }

  .desktop\:ext-py-base:not([style*="padding"]) {
    padding-top: var(--wp--style--block-gap, 1.75rem) !important;
    padding-bottom: var(--wp--style--block-gap, 1.75rem) !important;
  }

  .desktop\:ext-py-lg:not([style*="padding"]) {
    padding-top: var(--extendify--spacing--large, 3rem) !important;
    padding-bottom: var(--extendify--spacing--large, 3rem) !important;
  }

  .desktop\:ext-pt-0:not([style*="padding"]) {
    padding-top: 0 !important;
  }

  .desktop\:ext-pt-base:not([style*="padding"]) {
    padding-top: var(--wp--style--block-gap, 1.75rem) !important;
  }

  .desktop\:ext-pt-lg:not([style*="padding"]) {
    padding-top: var(--extendify--spacing--large, 3rem) !important;
  }

  .desktop\:ext-pr-0:not([style*="padding"]) {
    padding-right: 0 !important;
  }

  .desktop\:ext-pr-base:not([style*="padding"]) {
    padding-right: var(--wp--style--block-gap, 1.75rem) !important;
  }

  .desktop\:ext-pr-lg:not([style*="padding"]) {
    padding-right: var(--extendify--spacing--large, 3rem) !important;
  }

  .desktop\:ext-pb-0:not([style*="padding"]) {
    padding-bottom: 0 !important;
  }

  .desktop\:ext-pb-base:not([style*="padding"]) {
    padding-bottom: var(--wp--style--block-gap, 1.75rem) !important;
  }

  .desktop\:ext-pb-lg:not([style*="padding"]) {
    padding-bottom: var(--extendify--spacing--large, 3rem) !important;
  }

  .desktop\:ext-pl-0:not([style*="padding"]) {
    padding-left: 0 !important;
  }

  .desktop\:ext-pl-base:not([style*="padding"]) {
    padding-left: var(--wp--style--block-gap, 1.75rem) !important;
  }

  .desktop\:ext-pl-lg:not([style*="padding"]) {
    padding-left: var(--extendify--spacing--large, 3rem) !important;
  }

  .desktop\:ext-text-left {
    text-align: left !important;
  }

  .desktop\:ext-text-center {
    text-align: center !important;
  }

  .desktop\:ext-text-right {
    text-align: right !important;
  }
}

</style>
<link rel='stylesheet' id='contact-form-7-css' href='css/styles.css?ver=5.7.4' type='text/css' media='all' />
<link rel='stylesheet' id='theme-my-login-css' href='css/theme-my-login.min.css?ver=7.1.5' type='text/css' media='all' />
<link rel='stylesheet' id='woocommerce-layout-css' href='css/woocommerce-layout.css?ver=7.4.1' type='text/css' media='all' />
<link rel='stylesheet' id='woocommerce-smallscreen-css' href='css/woocommerce-smallscreen.css?ver=7.4.1' type='text/css' media='only screen and (max-width: 767px)' />
<link rel='stylesheet' id='woocommerce-general-css' href='css/woocommerce.css?ver=7.4.1' type='text/css' media='all' />
<style id='woocommerce-inline-inline-css' type='text/css'>
.woocommerce form .form-row .required { visibility: visible; }
</style>
<link rel='stylesheet' id='yith-quick-view-css' href='css/yith-quick-view.css?ver=1.24.0' type='text/css' media='all' />
<style id='yith-quick-view-inline-css' type='text/css'>
#yith-quick-view-modal .yith-wcqv-main{background:#ffffff;}
#yith-quick-view-close{color:#cdcdcd;}
#yith-quick-view-close:hover{color:#ff0000;}
</style>
<link rel='stylesheet' id='js_composer_front-css' href='css/js_composer.min.css?ver=6.9.0' type='text/css' media='all' />
<link rel='stylesheet' id='eikra-gfonts-css' href='//fonts.googleapis.com/css?family=Roboto%3A400%2C400i%2C500%2C500i%2C700%2C700i%26subset%3Dlatin%2Clatin-ext&#038;ver=4.4.6' type='text/css' media='all' />
<link rel='stylesheet' id='bootstrap-css' href='css/bootstrap.min.css?ver=4.4.6' type='text/css' media='all' />
<link rel='stylesheet' id='font-awesome-css' href='css/font-awesome.min.css?ver=4.4.6' type='text/css' media='all' />
<style id='font-awesome-inline-css' type='text/css'>
[data-font="FontAwesome"]:before {font-family: 'FontAwesome' !important;content: attr(data-icon) !important;speak: none !important;font-weight: normal !important;font-variant: normal !important;text-transform: none !important;line-height: 1 !important;font-style: normal !important;-webkit-font-smoothing: antialiased !important;-moz-osx-font-smoothing: grayscale !important;}
</style>
<link rel='stylesheet' id='eikra-default-css' href='css/default.css?ver=4.4.6' type='text/css' media='all' />
<link rel='stylesheet' id='eikra-style-css' href='css/style.css?ver=4.4.6' type='text/css' media='all' />
<style id='eikra-style-inline-css' type='text/css'>
    .entry-banner {
	        background: url(images/banner.jpg) no-repeat scroll center center / cover;
	    }
    .content-area {
    padding-top: 0px;
    padding-bottom: 0px;
    }
	        #learn-press-block-content span {
        background-image: url("images/preloader.gif");
        }
		
</style>
<link rel='stylesheet' id='eikra-vc-css' href='css/vc.css?ver=4.4.6' type='text/css' media='all' />
<link rel='stylesheet' id='video-conferencing-with-zoom-api-css' href='css/style.min1.css?ver=4.1.5' type='text/css' media='all' />
<link rel="preload" as="style" href="https://fonts.googleapis.com/css?family=Roboto:500,400&#038;display=swap&#038;ver=1660286202" /><link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto:500,400&#038;display=swap&#038;ver=1660286202" media="print" onload="this.media='all'"><noscript><link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto:500,400&#038;display=swap&#038;ver=1660286202" /></noscript>
<link rel='stylesheet' id='learnpress-widgets-css' href='css/widgets.min.css?ver=4.2.2.1' type='text/css' media='all' />
<link rel='stylesheet' id='learnpress-widgets-css' href='css/skin.css' type='text/css' media='all' />
<link rel='stylesheet' id='eikra-learnpress-css' href='css/learnpress.css?ver=4.4.6' type='text/css' media='all' />
<style id='eikra-learnpress-inline-css' type='text/css'>
@media all and (max-width: 767px) {	html #wpadminbar {position: fixed;} }
</style>
<style id='eikra-dynamic-inline-css' type='text/css'>
 body, gtnbg_root, p { font-family: Roboto, sans-serif;; font-size: 15px; line-height: 26px; font-weight : 400; font-style: normal; } h1 { font-family: Roboto; font-size: 40px; line-height: 44px; font-weight : 500; font-style: normal; } h2 { font-family: Roboto, sans-serif;; font-size: 28px; line-height: 31px; font-weight : 500; font-style: normal; } h3 { font-family: Roboto, sans-serif;; font-size: 20px; line-height: 26px; font-weight : 500; font-style: normal; } h4 { font-family: Roboto, sans-serif;; font-size: 16px; line-height: 18px; font-weight : 500; font-style: normal; } h5 { font-family: Roboto, sans-serif;; font-size: 14px; line-height: 16px; font-weight : 500; font-style: normal; } h6 { font-family: Roboto, sans-serif;; font-size: 12px; line-height: 14px; font-weight : 500; font-style: normal; } a,a:link,a:visited { color: #002147; } a:hover, a:focus, a:active { color: #fdc800; } .wp-block-quote::before { background-color: #002147; } .wp-block-pullquote {   border-color: #002147; } :root{ --rt-primary-color: #002147; --rt-secondary-color: #fdc800; --rt-primary-rgb: 0, 33, 71; --rt-secondary-rgb: 253, 200, 0; } .primary-color { color: #002147; } .secondery-color { color: #fdc800; } .primary-bgcolor { background-color: #002147; } .secondery-bgcolor { background-color: #fdc800; } #tophead { background-color: #002147; } #tophead, #tophead a, #tophead .tophead-social li a, #tophead .tophead-social li a:hover { color: #d0d6dd; } #tophead .tophead-contact i[class^="fa"], #tophead .tophead-address i[class^="fa"] { color: #fdc800; } .trheader #tophead, .trheader #tophead a, .trheader #tophead .tophead-social li a, .trheader #tophead .tophead-social li a:hover { color: #d0d6dd; } .topbar-style-4 #tophead a.topbar-btn { background-color: #fdc800; border-color: #fdc800; color: #002147; } .topbar-style-5 #tophead .widget ul li i { color: #fdc800; }  .site-header .main-navigation ul li a { font-family: Roboto, sans-serif; font-size : 15px; font-weight : 500; line-height : 24px; color: #002147; text-transform : uppercase; font-style: normal; } .site-header .main-navigation ul.menu > li > a:hover, .site-header .main-navigation ul.menu > li.current-menu-item > a, .site-header .main-navigation ul.menu > li.current > a { color: #002147; } .site-header .main-navigation ul li a.active { color: #fdc800 !important; } .trheader #masthead .main-navigation ul.menu > li > a, .trheader #masthead .main-navigation ul.menu > li > a:hover, .trheader #masthead .main-navigation ul.menu > li.current-menu-item > a, .trheader #masthead .main-navigation ul.menu > li.current > a, .trheader #masthead .search-box .search-button i, .trheader #masthead .header-icon-seperator, .trheader #masthead .header-icon-area .cart-icon-area > a, .trheader #masthead .additional-menu-area a.side-menu-trigger { color: #ffffff; } .site-header .main-navigation ul li ul li { background-color: #002147; } .site-header .main-navigation ul li ul li:hover { background-color: #1A3B61; } .site-header .main-navigation ul li ul li a { font-family: Roboto, sans-serif; font-size : 14px; font-weight : 400; line-height : 21px; color: #ffffff; text-transform : uppercase; font-style: normal; } .site-header .main-navigation ul li ul li:hover > a { color: #FDC800; } #sticky-header-wrapper .site-header { border-color: #002147} .site-header .main-navigation ul li.mega-menu > ul.sub-menu { background-color: #002147} .site-header .main-navigation ul li.mega-menu ul.sub-menu li a { color: #ffffff} .site-header .main-navigation ul li.mega-menu ul.sub-menu li a:hover { background-color: #1A3B61; color: #FDC800; } .mean-container a.meanmenu-reveal, .mean-container .mean-nav ul li a.mean-expand { color: #fdc800; } .mean-container a.meanmenu-reveal span { background-color: #fdc800; } .mean-container .mean-bar { border-color: #fdc800; } .mean-container .mean-nav ul li a { font-family: Roboto, sans-serif; font-size : 14px; font-weight : 400; line-height : 21px; color: #002147; text-transform : uppercase; font-style: normal; } .mean-container .mean-nav ul li a:hover, .mean-container .mean-nav > ul > li.current-menu-item > a { color: #fdc800; } body .mean-container .mean-nav ul li.mean-append-area .rtin-append-inner a.header-menu-btn { background-color: #fdc800; border-color: #fdc800; color: #002147; } .header-icon-area .cart-icon-area .cart-icon-num { background-color: #fdc800; } .mean-container .mean-bar .cart-icon-num { background-color: #fdc800; } .site-header .search-box .search-text { border-color: #fdc800; } .header-style-3 .header-social li a:hover, .header-style-3.trheader .header-social li a:hover { color: #fdc800; } .header-style-3.trheader .header-contact li a, .header-style-3.trheader .header-social li a { color: #ffffff; } .header-style-4 .header-social li a:hover { color: #fdc800; } .header-style-4.trheader .header-contact li a, .header-style-4.trheader .header-social li a { color: #ffffff; } .header-style-5 .header-menu-btn { background-color: #002147; } .trheader.header-style-5 .header-menu-btn { color: #ffffff; } .header-style-6 .site-header, .header-style-6 #sticky-header-wrapper .site-header { background-color: #002147; } .header-style-6 .site-header a.header-menu-btn { background-color: #fdc800; border-color: #fdc800; color: #002147; } .header-style-6 .site-header .main-navigation ul.menu > li > a { color: #ffffff; } .header-style-7 .header-social a:hover { color: #002147; } .header-style-7 a.header-menu-btn { background-color: #fdc800; } .header-style-7.trheader .header-social li a:hover { color: #fdc800; } .entry-banner .entry-banner-content h1 { color: #ffffff; } .breadcrumb-area .entry-breadcrumb span a, .breadcrumb-area .entry-breadcrumb span a span { color: #fdc800; } .breadcrumb-area .entry-breadcrumb span a:hover, .breadcrumb-area .entry-breadcrumb span a:hover span { color: #ffffff; } .breadcrumb-area .entry-breadcrumb { color: #ffffff; } .breadcrumb-area .entry-breadcrumb > span { color: #ffffff; } #preloader { background-color: #002147; } .scrollToTop { background-color: #fdc800; } .footer-top-area { background-color: #002147; } .footer-top-area .widget > h3 { color: #ffffff; } .mc4wp-form-fields input[type="email"], .footer-top-area, .footer-top-area .widget { color: #f5f5f5; } .widget.widget_rdtheme_info ul li a, .footer-top-area a:link, .footer-top-area a:visited, .footer-top-area widget_nav_menu ul.menu li:before { color: #f5f5f5; } .footer-top-area .widget a:hover, .footer-top-area .widget a:active { color: #fdc800; } .footer-top-area .search-form input.search-submit { color: #002147; } .footer-top-area .widget_nav_menu ul.menu li:before { color: #fdc800; } .footer-bottom-area { background-color: #001a39; color: #909da4; } .search-form input.search-submit { background-color: #002147; border-color: #002147; } .search-form input.search-submit a:hover { color: #002147; } .widget ul li a:hover { color: #fdc800; } .sidebar-widget-area .widget > h3 { color: #002147; } .sidebar-widget-area .widget > h3:after { background-color: #fdc800; } .sidebar-widget-area .widget_tag_cloud a { color: #002147; } .sidebar-widget-area .widget_tag_cloud a:hover { background-color: #002147; } .widget.widget_rdtheme_about ul li a:hover { background-color: #fdc800; border-color: #fdc800; color: #002147; } .widget.widget_rdtheme_info ul li i { color: #fdc800; } .pagination-area ul li a, .learn-press-pagination ul li a { background-color: #002147 !important; } .pagination-area ul li.active a, .pagination-area ul li a:hover, .pagination-area ul li span.current, .pagination-area ul li .current, .learn-press-pagination ul li.active a, .learn-press-pagination ul li a:hover, .learn-press-pagination ul li span.current, .learn-press-pagination ul li .current { background-color: #fdc800 !important; } .error-page-area { background-color: #FDC800; } .error-page-area .error-page h3 { color: #000000; } .error-page-area .error-page p { color: #634e00; } body .rdtheme-button-1, body .rdtheme-button-1:link { color: #002147; } body .rdtheme-button-1:hover { background-color: #002147; } body a.rdtheme-button-2, body .rdtheme-button-2 { background-color: #002147; } body a.rdtheme-button-2:hover, body .rdtheme-button-2:hover { color: #002147; background-color: #fdc800; } body a.rdtheme-button-3, body .rdtheme-button-3 { background-color: #002147; } body a.rdtheme-button-3:hover, body .rdtheme-button-4:hover { color: #002147; background-color: #fdc800; } .comments-area h3.comment-title { color: #002147; } .comments-area h3.comment-title:after { background-color: #fdc800; } .comments-area .main-comments .comment-meta .comment-author-name, .comments-area .main-comments .comment-meta .comment-author-name a { color: #002147; } .comments-area .main-comments .reply-area a { background-color: #002147; } .comments-area .main-comments .reply-area a:hover { background-color: #fdc800; } #respond .comment-reply-title { color: #002147; } #respond .comment-reply-title:after { background-color: #fdc800; } #respond form .btn-send { background-color: #002147; } #respond form .btn-send:hover { background-color: #fdc800; } .entry-header h2.entry-title a, .entry-header .entry-meta ul li a:hover, .entry-footer .tags a:hover, .event-single .event-meta li, .event-single ul li span i, .event-single .event-info h3, .event-single .event-social h3 { color: #002147; } button, input[type="button"], input[type="reset"], input[type="submit"], .entry-header .entry-thumbnail-area .post-date li:nth-child(odd), .event-single .event-thumbnail-area #event-countdown .event-countdown-each:nth-child(odd), .event-single .event-social ul li a, .instructor-single .rtin-content ul.rtin-social li a:hover { background-color: #002147; } .entry-header h2.entry-title a:hover, .entry-header h2.entry-title a:hover, .entry-header .entry-meta ul li i, .event-single .event-meta li i { color: #fdc800; } .bar1::after, .bar2::after, .hvr-bounce-to-right:before, .hvr-bounce-to-bottom:before, .entry-header .entry-thumbnail-area .post-date li:nth-child(even), .event-single .event-thumbnail-area #event-countdown .event-countdown-each:nth-child(even), .event-single .event-social ul li a:hover { background-color: #fdc800; } .ls-bar-timer { background-color: #fdc800; border-bottom-color: #fdc800; } .instructor-single .rtin-content ul.rtin-social li a:hover { border-color: #002147; } .list-style-1 li { color: #002147; } .list-style-1 li::before { color: #fdc800; } .woocommerce .widget_price_filter .ui-slider .ui-slider-range, .woocommerce .widget_price_filter .ui-slider .ui-slider-handle, .rt-woo-nav .owl-custom-nav-title::after, .rt-woo-nav .owl-custom-nav .owl-prev:hover, .rt-woo-nav .owl-custom-nav .owl-next:hover, .woocommerce ul.products li.product .onsale, .woocommerce span.onsale, .woocommerce a.added_to_cart, .woocommerce div.product form.cart .button, .woocommerce #respond input#submit, .woocommerce a.button, .woocommerce button.button, .woocommerce input.button, p.demo_store, .woocommerce #respond input#submit.disabled:hover, .woocommerce #respond input#submit:disabled:hover, .woocommerce #respond input#submit[disabled]:disabled:hover, .woocommerce a.button.disabled:hover, .woocommerce a.button:disabled:hover, .woocommerce a.button[disabled]:disabled:hover, .woocommerce button.button.disabled:hover, .woocommerce button.button:disabled:hover, .woocommerce button.button[disabled]:disabled:hover, .woocommerce input.button.disabled:hover, .woocommerce input.button:disabled:hover, .woocommerce input.button[disabled]:disabled:hover, .woocommerce #respond input#submit.alt, .woocommerce a.button.alt, .woocommerce button.button.alt, .woocommerce input.button.alt { background-color: #002147; } .product-grid-view .view-mode ul li.grid-view-nav a, .product-list-view .view-mode ul li.list-view-nav a, .woocommerce ul.products li.product h3 a:hover, .woocommerce ul.products li.product .price, .woocommerce div.product p.price, .woocommerce div.product span.price, .woocommerce div.product .product-meta a:hover, .woocommerce div.product .woocommerce-tabs ul.tabs li.active a, .woocommerce a.woocommerce-review-link:hover, .woocommerce-message::before, .woocommerce-info::before { color: #002147; } .woocommerce-message, .woocommerce-info { border-color: #002147; } .woocommerce .product-thumb-area .overlay { background-color: rgba(0, 33, 71, 0.8); } .woocommerce .product-thumb-area .product-info ul li a { border-color: #fdc800; } .woocommerce .product-thumb-area .product-info ul li a:hover { color: #002147; background-color: #fdc800; } .contact-us-form .wpcf7-submit:hover { background-color: #fdc800; } .contact-form-2 h3, .contact-form-2 input[type="submit"]:hover { background-color: #fdc800; } .rt-vc-pagination .pagination-area ul li a, .rt-vc-pagination .pagination-area ul li span {   background-color: #002147; } .rt-vc-pagination .pagination-area ul li.active a, .rt-vc-pagination .pagination-area ul li a:hover, .rt-vc-pagination .pagination-area ul li .current {   background-color: #fdc800; } body .entry-content .rdtheme-button-5, body .rdtheme-button-5 {   border-color: #fdc800; } body .entry-content .rdtheme-button-5:hover, body .rdtheme-button-5:hover{   background-color: #fdc800;   color: #002147; } body .entry-content .rdtheme-button-6, body .rdtheme-button-6 { background-color: #002147; } body .entry-content .rdtheme-button-6:hover, body .rdtheme-button-6:hover {   background-color: #fdc800;   color: #002147; } body .rdtheme-button-7, body a.rdtheme-button-7 {   background-color: #002147; } body .rdtheme-button-7:hover, body a.rdtheme-button-7:hover {   color: #002147;   background-color: #fdc800; } .entry-content .isotop-btn a:hover, .entry-content .isotop-btn .current {   border-color: #002147 !important;   background-color: #002147 !important; } .rt-owl-nav-1 .section-title .owl-custom-nav-title {   color: #002147; } .rt-owl-nav-1 .section-title .owl-custom-nav .owl-prev, .rt-owl-nav-1 .section-title .owl-custom-nav .owl-next {   background-color: #fdc800; } .rt-owl-nav-1 .section-title .owl-custom-nav .owl-prev:hover, .rt-owl-nav-1 .section-title .owl-custom-nav .owl-next:hover {   background-color: #002147; } .rt-vc-title-left {   color: #002147; } .rt-vc-title h2 {   color: #002147; } .rt-info-box .media-heading, .rt-info-box .media-heading a, .rt-info-box.layout2 i, .rt-info-box.layout3 i, .rt-info-box.layout4:hover .rtin-icon i { color: #002147; } .rt-info-box .media-heading a:hover, .rt-info-box.layout2:hover i, .rt-info-box.layout5 .rtin-icon i, .rt-info-box.layout5:hover .media-heading, .rt-info-box.layout6:hover .media-heading a { color: #fdc800; } .rt-info-box.layout4::before, .rt-info-box.layout4:hover { background-color: #002147; } .rt-info-box.layout5 { background-color: rgba( 0, 33, 71, 0.8 ); } .rt-info-box.layout3:hover i, .rt-info-box.layout4 .rtin-icon i {   background-color: #fdc800; } .rt-vc-infobox-6 .rtin-item .rtin-left .rtin-icon i {   color: #fdc800; } .rt-vc-imagetext-2 .rtin-img:before {   background-color: rgba(0, 33, 71, 0.6); } .rt-vc-imagetext-2 .rtin-img a {   border-color: #fdc800; } .rt-vc-imagetext-2 .rtin-title a:hover {   color: #002147; } .rt-vc-text-title .rtin-title { color: #002147; } .rt-vc-text-title.style2 .rtin-title::after { background-color: #fdc800; } .rt-vc-text-title.style3 .rtin-btn a {   background-color: #fdc800; } .rt-vc-text-title.style4 .rtin-btn a {   border-color: #fdc800; } .rt-vc-text-title.style4 .rtin-btn a:hover {   background-color: #fdc800; } .rt-vc-text-button .rtin-btn a {   background-color: #fdc800; } .rt-vc-cta .rtin-right {   background-color: #002147; } .rt-vc-cta .rtin-right .rtin-btn {   background-color: #fdc800;   border-color: #fdc800;   color: #002147; } .rt-vc-cta.style2 .rtin-right {   background-color: #fdc800; } .rt-vc-cta.style2 .rtin-right .rtin-btn {   background-color: #002147;   border-color: #002147; } .rt-vc-cta.style2 .rtin-right .rtin-btn:hover {   color: #002147; } .rt-vc-posts .rtin-item .media-list .rtin-content-area h3 a {   color: #002147; } .rt-vc-posts .rtin-item .media-list .rtin-content-area h3 a:hover {   color: #fdc800; } .rt-vc-posts .rtin-item .media-list .rtin-content-area .rtin-date {   color: #fdc800; } .rt-vc-posts-2 {   background-color: #002147; } .rt-vc-posts-2 .rtin-item .rtin-date {   color: #fdc800; } .rt-vc-posts-2 .rtin-btn:hover {   color: #fdc800; } .rt-vc-posts-2 .rtin-btn i {   color: #fdc800; } .rt-vc-posts-2 .rtin-item .rtin-title a:hover {   color: #fdc800; } .rt-vc-research-1 .rtin-item .rtin-title::after, .rt-vc-research-2 .rtin-item .rtin-title::after, .rt-vc-research-3 .rtin-item .rtin-holder .rtin-title a:hover {   background-color: #fdc800; } .rt-vc-research-1 .rtin-item .rtin-title a, .rt-vc-research-2 .rtin-item .rtin-title a, .rt-vc-research-3 .rtin-item .rtin-holder .rtin-title a:hover, .rt-vc-research-3 .rtin-item .rtin-holder .rtin-title a:hover i {   color: #002147; } .rt-vc-research-1 .rtin-item .rtin-title a:hover, .rt-vc-research-2 .rtin-item .rtin-title a:hover, .rt-vc-research-3 .rtin-item .rtin-holder .rtin-title a i {   color: #fdc800; } .rt-vc-research-3 .rtin-item .rtin-holder .rtin-title a {   background-color: #002147; } .rt-vc-event .rtin-item .rtin-calender-holder .rtin-calender {   background-color:#fdc800; } .rt-vc-event .rtin-item .rtin-calender-holder .rtin-calender:before, .rt-vc-event .rtin-item .rtin-calender-holder .rtin-calender:after, .rt-vc-event .rtin-item .rtin-calender-holder .rtin-calender h3, .rt-vc-event .rtin-item .rtin-calender-holder .rtin-calender h3 p, .rt-vc-event .rtin-item .rtin-calender-holder .rtin-calender h3 span, .rt-vc-event .rtin-item .rtin-right h3 a, .rt-vc-event .rtin-item .rtin-right ul li, .rt-vc-event .rtin-btn a:hover {   color: #002147; } .rt-vc-event .rtin-item .rtin-right h3 a:hover {   color: #fdc800; } .rt-vc-event-box .rtin-item .rtin-meta i {   color: #fdc800; } .rt-vc-event-box .rtin-item .rtin-btn a {   background-color: #fdc800;   border-color: #fdc800; } .rt-vc-counter .rtin-left .rtin-counter {   border-bottom-color: #fdc800; } .rt-vc-counter .rtin-right .rtin-title {   color: #002147; } .rt-vc-testimonial .rt-item .rt-item-content-holder .rt-item-title {   color: #002147; } .rt-vc-testimonial .owl-theme .owl-dots .owl-dot.active span {   background-color: #002147; } .rt-vc-testimonial-2 .rtin-item .rtin-item-designation {   color:#fdc800; } .rt-vc-testimonial-2 .owl-theme .owl-dots .owl-dot:hover span, .rt-vc-testimonial-2 .owl-theme .owl-dots .owl-dot.active span {   background-color: #fdc800;   border-color: #fdc800; } .rt-vc-testimonial-3 .rtin-item .rtin-content-area .rtin-title {   color: #002147; } .rt-countdown .rt-date .rt-countdown-section-2 {   border-color: #fdc800; } .rt-event-countdown .rt-content h2, .rt-event-countdown .rt-content h3, .rt-event-countdown .rt-date .rt-countdown-section .rt-countdown-text .rtin-count, .rt-event-countdown .rt-date .rt-countdown-section .rt-countdown-text .rtin-text { color: #002147; } .rt-event-countdown .rt-date .rt-countdown-section .countdown-colon, .rt-event-countdown.rt-dark .rt-date .rt-countdown-section .rt-countdown-text .rtin-count { color: #fdc800; } .rt-price-table-box1 span {   color: #002147; } .rt-price-table-box1 .rtin-price {   background-color: #002147; } .rt-price-table-box1 .rtin-btn {   background-color: #fdc800;   border-color: #fdc800;   color: #002147; } .rt-price-table-box1:hover {   background-color: #002147; } .rt-price-table-box1:hover .rtin-price {   background-color: #fdc800; } .rt-pricing-box2 .rtin-title, .rt-pricing-box2 ul li {   color: #002147; } .rt-pricing-box2 .rtin-price {   color: #fdc800; } .rt-price-table-box3 .rtin-title, .rt-price-table-box3 .rtin-price {   color: #002147; } .rt-price-table-box3 .rtin-btn {   background-color: #fdc800; } .rt-price-table-box3.rtin-featured, .rt-price-table-box3:hover {   background-color: #002147; } .rt-gallery-1 .rt-gallery-wrapper .rt-gallery-box:before {   background-color: rgba( 253, 200, 0, 0.8 ); } .rt-gallery-1 .rt-gallery-wrapper .rt-gallery-box .rt-gallery-content a {   background-color: #002147; } .rt-vc-video .rtin-item .rtin-btn {   color: #fdc800; } .rt-vc-video .rtin-item .rtin-btn:hover {   border-color: #fdc800; } .rt-vc-video.rt-light .rtin-item .rtin-title {   color: #002147; } .rt-vc-video.rt-light .rtin-item .rtin-btn {   color: #002147;   border-color: #fdc800; } .rt-vc-contact-1 ul.rtin-item > li > i {   color: #002147; } .rt-vc-contact-1 ul.rtin-item > li .contact-social li a {   color: #002147;   border-color: #002147; } .rt-vc-contact-1 ul.rtin-item > li .contact-social li a:hover {   background-color: #002147; } .rt-vc-contact-2 ul.rtin-item > li {   color: #002147; } .rt-vc-contact-2 ul.rtin-item > li > i {   color: #fdc800; } .rt-vc-contact-2 ul.rtin-item > li.rtin-social-wrap .rtin-social li a {   background-color: #fdc800; } .rt-vc-contact-2 ul.rtin-item > li.rtin-social-wrap .rtin-social li a:hover {   background-color: #002147; } .rt-vc-instructor-1 .rtin-item .rtin-content .rtin-title a {   color: #002147; } .rt-vc-instructor-1 .rtin-item .rtin-content .rtin-title a:hover {   color: #fdc800; } .rt-vc-instructor-1 .rtin-item .rtin-content .rtin-social li a {   background-color: #fdc800; } .rt-vc-instructor-1 .rtin-item .rtin-content .rtin-social li a:hover {   background-color: #002147; } .rt-vc-instructor-2 .rtin-item .rtin-content .rtin-title a, .rt-vc-instructor-2 .rtin-item .rtin-content .rtin-social li a {   color: #fdc800; } .rt-vc-instructor-2 .rtin-item .rtin-content .rtin-social li a:hover {   border-color: #fdc800;   background-color: #fdc800; } .rt-vc-instructor-2 .rtin-item:before {   background: linear-gradient(to bottom, rgba(125, 185, 232, 0) 55%, #002147); } .rt-vc-instructor-2 .rtin-item:hover:after {   background-color: rgba( 0, 33, 71 , 0.7 ); } .rt-vc-instructor-3 .rtin-item .rtin-meta span {   color: #fdc800; } .rt-vc-instructor-3 .rtin-btn a {   color: #002147;   border-color: #002147; } .rt-vc-instructor-3 .rtin-btn a:hover {   background-color: #002147; } .rt-vc-instructor-4 .rtin-item .rtin-content:after {   background-color: #fdc800; } .rt-vc-instructor-5 .rtin-item {   background-color: #fdc800; } .rt-vc-instructor-5 .rtin-item .rtin-content .rtin-social li a:hover {   background-color: #002147; } .rt-vc-course-search .form-group .input-group .input-group-addon.rtin-submit-btn-wrap .rtin-submit-btn {   background-color: #fdc800; } .rt-vc-course-slider.style-4.rt-owl-nav-1 .section-title .owl-custom-nav .owl-prev:hover, .rt-vc-course-slider.style-4.rt-owl-nav-1 .section-title .owl-custom-nav .owl-next:hover {   background-color: #fdc800; } .rt-vc-course-featured .rtin-sec-title {   color: #002147; } .rt-vc-course-featured .rt-course-box .rtin-thumbnail::before {   background-color: rgba( 253, 200, 0 , 0.8 ); } .rt-vc-course-featured .rt-course-box .rtin-thumbnail a {   background-color: #002147;   border-color: #002147; } .rt-vc-course-isotope.style-2 .isotop-btn a {   border-color: #002147; } .rt-vc-course-isotope.style-2 .rtin-btn a {   color: #002147;   border-color: #002147; } .rt-vc-course-isotope.style-2 .rtin-btn a:hover {   background-color: #002147; } .wpb_gallery .wpb_flexslider .flex-direction-nav a {   background-color: #fdc800; } .wpb-js-composer .vc_tta.vc_tta-o-no-fill .vc_tta-panels .vc_tta-panel-body {  background-color: #002147 !important; } .wpb-js-composer .vc_tta-style-classic .vc_tta-panel .vc_tta-panel-title > a, .wpb-js-composer .vc_tta-style-classic .vc_tta-panel.vc_active .vc_tta-panel-title > a {  color: #002147 !important; } .wpb-js-composer .vc_tta-style-classic .vc_tta-controls-icon:after, .wpb-js-composer .vc_tta-style-classic .vc_tta-controls-icon:before {  border-color: #002147 !important; } .wpb-js-composer .vc_tta-container .vc_tta-panel span.faq-box-count {   background-color: #fdc800;   color: #002147; } .rt-course-box-3 .rtin-meta .rtin-author span, ul.learn-press-courses .rt-course-box-3 .rtin-meta .rtin-author span, .rt-course-box-4 .rtin-content .rtin-author-area .rtin-author span, ul.learn-press-courses .rt-course-box-4 .rtin-content .rtin-author-area .rtin-author span, .rt-lp-socials li a:hover, .learn-press-message:before, #popup_container #popup_title { background-color: #002147; } .rt-course-box .rtin-thumbnail .rtin-price, ul.learn-press-courses .rt-course-box .rtin-thumbnail .rtin-price, .rt-course-box-2 .rtin-meta .rtin-price ins, ul.learn-press-courses .rt-course-box-2 .rtin-meta .rtin-price ins, .rt-course-box-3 .rtin-thumbnail .rtin-price, ul.learn-press-courses .rt-course-box-3 .rtin-thumbnail .rtin-price, .rt-lp-socials li a, .lp-label.label-enrolled, .lp-label.label-started, .single-lp_course .learn-press-message .learn-press-countdown { background-color: #fdc800; } .rt-course-box .rtin-thumbnail:before, ul.learn-press-courses .rt-course-box .rtin-thumbnail:before, .rt-course-box-3 .rtin-thumbnail:before, ul.learn-press-courses .rt-course-box-3 .rtin-thumbnail:before, .rt-course-box-4 .rtin-thumbnail:before, ul.learn-press-courses .rt-course-box-4 .rtin-thumbnail:before { background-color: rgba(0, 33, 71, 0.6); } .rt-course-box .rtin-content .rtin-author i, ul.learn-press-courses .rt-course-box .rtin-content .rtin-author i, .rt-course-box-4 .rtin-content .rtin-title a:hover, ul.learn-press-courses .rt-course-box-4 .rtin-content .rtin-title a:hover { color: #fdc800; } .course-remaining-time { border-color: #002147; } .rt-course-box .rtin-thumbnail a, ul.learn-press-courses .rt-course-box .rtin-thumbnail a, .rt-course-box-3 .rtin-thumbnail a, ul.learn-press-courses .rt-course-box-3 .rtin-thumbnail a, .rt-course-box-4 .rtin-thumbnail a, ul.learn-press-courses .rt-course-box-4 .rtin-thumbnail a { border-color: #fdc800; } .rt-course-archive-top .rtin-left .rtin-icons a:hover, .rt-course-grid-view .rt-course-archive-top .rtin-left .rtin-icons a.rtin-grid, .rt-course-list-view .rt-course-archive-top .rtin-left .rtin-icons a.rtin-list, .rt-course-archive-top .rtin-left .rtin-text { color: #002147; } .rt-course-archive-top .rtin-search form button[type="submit"] { background-color: #002147; } #learn-press-course-tabs li.course-nav.active label, #learn-press-course-tabs li.course-nav:hover label {   background-color: #002147 !important; } .single-lp_course .content-area .site-main > .lp_course ul.learn-press-nav-tabs li a { color: #002147; } .single-lp_course .content-area .site-main > .lp_course ul.learn-press-nav-tabs li.active, .single-lp_course .content-area .site-main > .lp_course ul.learn-press-nav-tabs li:hover { background-color: #002147; } ul.course-features li:before { color: #fdc800; } #learn-press-course-curriculum .curriculum-sections .section .section-header, #learn-press-course-curriculum .curriculum-sections .section .section-header .meta .collapse, #learn-press-course-curriculum .curriculum-sections .section .section-content li .section-item-link .rtin-center .course-item-meta .course-item-status:before { color: #002147; } #learn-press-course-curriculum .curriculum-sections .section .section-header.active, #learn-press-course-curriculum .curriculum-sections .section .section-header:hover { background-color: #fdc800; } #learn-press-course-curriculum .curriculum-sections .section .section-content li .section-item-link .rtin-left .rtin-left-icon { color: #fdc800; } #learn-press-course-curriculum .curriculum-sections .section .section-content li .section-item-link .rtin-center .course-item-meta span { background-color: #002147; } #popup-course #popup-content .lp-button, body.course-item-popup #popup-course #popup-sidebar .course-curriculum .section .section-header, body.course-item-popup #learn-press-course-curriculum .curriculum-sections .section .section-header, body.course-item-popup #learn-press-course-curriculum .curriculum-sections .section .section-content li:before, body.course-item-popup #learn-press-content-item #content-item-quiz .question-numbers li a:hover, body.course-item-popup #learn-press-content-item #content-item-quiz .question-numbers li.current a, .scrollbar-light > .scroll-element.scroll-y .scroll-bar { background-color: #002147; } #popup-course #popup-header, #popup-course #popup-content .lp-button:hover, body.course-item-popup #course-item-content-header { background-color: #fdc800; } body.course-item-popup #learn-press-content-item #content-item-quiz .quiz-result .result-achieved { color: #002147; } body.course-item-popup #learn-press-content-item #content-item-quiz .question-numbers li a:hover, body.course-item-popup #learn-press-content-item #content-item-quiz .question-numbers li.current a { border-color: #002147; } #popup-course #sidebar-toggle::before {   color: #fdc800; } #course-reviews .course-review-head, #course-reviews .course-reviews-list li .review-text .user-name { color: #002147; } #course-reviews .course-reviews-list li .review-text .review-meta .review-title { background-color: #002147; } #course-reviews .course-review-head::after { background-color: #fdc800; } .learnpress-page .course_enroll_wid .rtin-pricing, .course-rate .average-rating, .course-rate .course-each-rating .star-info { color: #002147; } .learnpress-page .course_enroll_wid a, .learnpress-page .course_enroll_wid .lp-button, .learnpress-page .course_enroll_wid button { color: #002147; background-color: #fdc800; border-color: #fdc800; } .rt-related-courses .owl-custom-nav-title { color: #002147; } .rt-related-courses .owl-custom-nav .owl-prev:hover, .rt-related-courses .owl-custom-nav .owl-next:hover { background-color: #002147; } .rt-related-courses .owl-custom-nav .owl-prev, .rt-related-courses .owl-custom-nav .owl-next { background-color: #fdc800; } #learn-press-profile .wrapper-profile-header, .learn-press-tabs .learn-press-tabs__checker:nth-child(1):checked ~ .learn-press-tabs__nav .learn-press-tabs__tab:nth-child(1)::before, .learn-press-tabs .learn-press-tabs__checker:nth-child(2):checked ~ .learn-press-tabs__nav .learn-press-tabs__tab:nth-child(2)::before, #learn-press-user-profile .rdtheme-lp-profile-header, #learn-press-user-profile #learn-press-profile-content .lp-tab-sections li a:hover, #learn-press-user-profile #learn-press-profile-content .learn-press-subtab-content .lp-sub-menu li.active span, #learn-press-user-profile #learn-press-profile-content .learn-press-subtab-content .lp-sub-menu li a:hover, #learn-press-user-profile #learn-press-profile-nav:hover #profile-mobile-menu { background-color: #002147; } #learn-press-profile #profile-nav .lp-profile-nav-tabs li.active, #learn-press-profile #profile-nav .lp-profile-nav-tabs li:hover, #learn-press-user-profile .rdtheme-lp-profile-header .rtin-item .rtin-right .rtin-social li a, #learn-press-user-profile .rdtheme-lp-profile-header .rtin-logout a, #learn-press-user-profile #learn-press-profile-nav .learn-press-tabs li.active > a, #learn-press-user-profile #learn-press-profile-nav .learn-press-tabs li a:hover { background-color: #fdc800; } #learn-press-user-profile #learn-press-profile-content .lp-tab-sections li span, #learn-press-user-profile #learn-press-profile-content .lp-tab-sections li a { color: #002147; } #learn-press-profile #profile-nav .lp-profile-nav-tabs > li.wishlist > a::before, #learn-press-profile #profile-nav .lp-profile-nav-tabs > li > a > i, #learn-press-profile #profile-nav .lp-profile-nav-tabs > li ul li a:hover, #learn-press-profile #profile-nav .lp-profile-nav-tabs li.active > ul .active > a {   color: #fdc800; } #learn-press-profile .lp-user-profile-socials a:hover {   border-color: #fdc800;   background-color: #fdc800; } .learn-press-checkout .lp-list-table thead tr th { background: #002147; }
</style>
<script type='text/javascript' src='js/jquery.min.js?ver=3.6.1' id='jquery-core-js'></script>
<script type='text/javascript' src='js/jquery-migrate.min.js?ver=3.3.2' id='jquery-migrate-js'></script>
<script type='text/javascript' src='js/jquery.blockUI.min.js?ver=2.7.0-wc.7.4.1' id='jquery-blockui-js'></script>
<script type='text/javascript' src='js/add-to-cart.min.js?ver=7.4.1' id='wc-add-to-cart-js'></script>
<script type='text/javascript' src='js/woocommerce-add-to-cart.js?ver=6.9.0' id='vc_woocommerce-add-to-cart-js-js'></script>
<meta name="generator" content="Powered by LayerSlider 7.2.5 - Multi-Purpose, Responsive, Parallax, Mobile-Friendly Slider Plugin for WordPress." />
<!-- LayerSlider updates and docs at: https://layerslider.com -->
<meta name="generator" content="Redux 4.3.26" />
<style id="learn-press-custom-css">
:root {
--lp-primary-color: #ffb606;
--lp-secondary-color: #442e66;
}
</style>
<noscript><style>.woocommerce-product-gallery{ opacity: 1 !important; }</style></noscript>	
<!-- This Google structured data (Rich Snippet) auto generated by RadiusTheme Review Schema plugin version 2.1.0 -->
<meta name="generator" content="<?php echo $SITE_NAME; ?>"/>
<link rel="icon" href="images/favicon.png" sizes="32x32" />
<link rel="icon" href="images/favicon.png" sizes="192x192" />
<link rel="apple-touch-icon" href="images/favicon.png" />
<meta name="msapplication-TileImage" content="images/favicon.png" />
<style type="text/css" data-type="vc_shortcodes-custom-css">.vc_custom_1515066600956{padding-top: 85px !important;padding-bottom: 100px !important;background-image: url(images/student4.jpg?id=1496) !important;background-position: center !important;background-repeat: no-repeat !important;background-size: cover !important;}.vc_custom_1508848182497{padding-top: 55px !important;padding-bottom: 70px !important;background-color: #f5f5f5 !important;}.vc_custom_1515755231129{background-image: url(https://radiustheme.com/demo/wordpress/eikra/wp-content/uploads/2018/01/bg6.jpg?id=1499) !important;}.vc_custom_1508848784627{padding-top: 55px !important;padding-bottom: 100px !important;}.vc_custom_1508848826323{padding-top: 60px !important;padding-bottom: 100px !important;background-color: #f5f5f5 !important;}.vc_custom_1508848968428{padding-top: 80px !important;padding-bottom: 120px !important;background-image: url(https://radiustheme.com/demo/wordpress/eikra/wp-content/uploads/2017/10/counter.jpg?id=1008) !important;}.vc_custom_1508849149992{padding-top: 55px !important;padding-bottom: 90px !important;}.vc_custom_1508849313217{padding-top: 65px !important;padding-bottom: 100px !important;background-color: #fdc800 !important;}.vc_custom_1508915762011{padding-top: 25px !important;padding-bottom: 65px !important;}.vc_custom_1509108645328{padding-right: 0px !important;padding-left: 0px !important;}.vc_custom_1509110039881{padding-right: 0px !important;padding-left: 0px !important;}.vc_custom_1509110052516{padding-right: 0px !important;padding-left: 0px !important;}.vc_custom_1515057094027{border-left-width: 1px !important;border-left-color: rgba(255,255,255,0.3) !important;border-left-style: solid !important;}.vc_custom_1515053415058{border-left-width: 1px !important;border-left-color: rgba(255,255,255,0.3) !important;border-left-style: solid !important;}.vc_custom_1508849702754{margin-bottom: 50px !important;}</style>
<noscript><style> .wpb_animate_when_almost_visible { opacity: 1; }</style></noscript> 