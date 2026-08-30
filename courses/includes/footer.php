<footer class="lms-footer mt-auto">
    <div class="container py-5">
        <div class="row g-4">
            <div class="col-lg-4">
                <img src="<?php echo $SITE_URL; ?>/images/logo.png" alt="Logo" class="lms-logo mb-4" style="height: 50px;">
                <p class="text-muted">Transforming computer education with modern technology and expert-led training since inception.</p>
                <div class="social-links mt-4">
                    <a href="#"><i class="fab fa-facebook-f"></i></a>
                    <a href="#"><i class="fab fa-twitter"></i></a>
                    <a href="#"><i class="fab fa-instagram"></i></a>
                </div>
            </div>
            
            <div class="col-lg-2 offset-lg-1">
                <h5 class="fw-bold mb-4">Academy</h5>
                <ul class="list-unstyled footer-links">
                    <li><a href="index.php">All Courses</a></li>
                    <li><a href="#">About Academy</a></li>
                    <li><a href="#">Instructor</a></li>
                </ul>
            </div>
            
            <div class="col-lg-2">
                <h5 class="fw-bold mb-4">Support</h5>
                <ul class="list-unstyled footer-links">
                    <li><a href="#">Help Center</a></li>
                    <li><a href="#">Terms of Service</a></li>
                    <li><a href="#">Privacy Policy</a></li>
                </ul>
            </div>
            
            <div class="col-lg-3">
                <h5 class="fw-bold mb-4">Contact</h5>
                <p class="text-muted mb-2"><i class="fa fa-envelope me-2"></i> info@lkvm.org</p>
                <p class="text-muted"><i class="fa fa-phone me-2"></i> +91 9801664500</p>
            </div>
        </div>
        
        <div class="border-top mt-5 pt-4 text-center">
            <p class="text-muted mb-0">&copy; <?php echo date('Y'); ?> LKVM Academy. All rights reserved.</p>
        </div>
    </div>
</footer>

<!-- Bootstrap Bundle with Popper -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
document.addEventListener("DOMContentLoaded", function() {
    var token = "<?php echo function_exists('csrf_token') ? csrf_token() : ''; ?>";
    if (token) {
        var forms = document.getElementsByTagName("form");
        for (var i = 0; i < forms.length; i++) {
            if (forms[i].method && forms[i].method.toLowerCase() === "post") {
                if (!forms[i].querySelector('input[name="csrf_token"]')) {
                    var input = document.createElement("input");
                    input.type = "hidden";
                    input.name = "csrf_token";
                    input.value = token;
                    forms[i].appendChild(input);
                }
            }
        }
    }
});
</script>
</body>
</html>
