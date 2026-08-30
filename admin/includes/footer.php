</div> <!-- End Container Fluid -->
</div> <!-- End Main Content -->

<!-- Bootstrap Bundle -->
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