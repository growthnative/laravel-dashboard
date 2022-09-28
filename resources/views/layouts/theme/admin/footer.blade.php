<footer class="py-4 bg-light mt-auto">
    <div class="container-fluid px-4">
        <div class="d-flex align-items-center justify-content-between small">
            <div class="text-muted">Copyright &copy; {{config('app.name')}} <?php echo date("Y")?></div>
            <!-- <div>
                <a href="#">Privacy Policy</a>
                &middot;
                <a href="#">Terms &amp; Conditions</a>
            </div> -->
        </div>
    </div>
</footer>

</div>
</div>

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
<script src="{{ asset('js/bootstrap.bundle.min.js') }}" crossorigin="anonymous"></script>
<script src="{{ asset('js/jquery.min.js') }}" crossorigin="anonymous"></script>
<script src="{{ asset('js/jquery.validate.min.js') }}" crossorigin="anonymous"></script>
<script src="{{ asset('js/simple-datatables.js') }}" crossorigin="anonymous"></script>
<script src="{{ asset('js/scripts.js') }}" crossorigin="anonymous"></script>
<script src="{{ asset('js/datatables.js') }}" crossorigin="anonymous"></script>
<script src="{{ asset('js/validation.js') }}" crossorigin="anonymous"></script>
<script src="{{ asset('js/style.js') }}" crossorigin="anonymous"></script>
<script src="{{ asset('js/custom.js') }}" crossorigin="anonymous"></script>
<script src="{{ asset('js/bootstrap.min.js') }}" crossorigin="anonymous"></script>


<!-- fadout alert after 3 seconds-->
<script crossorigin="anonymous">
$(document).ready(function() {
    $(".alert-disable").delay(3000).fadeOut(500);
});
</script>
