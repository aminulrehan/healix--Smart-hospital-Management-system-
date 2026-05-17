<!-- ============================================================
     PAGE CONTENT ENDS HERE
============================================================ -->
</div><!-- .page-wrapper -->

<!-- ============================================================
     FOOTER
============================================================ -->
<footer style="background:var(--primary);color:rgba(255,255,255,0.6);text-align:center;padding:18px;font-size:0.83rem;margin-top:auto;">
    &copy; <?= date('Y') ?> Healix Hospital Management System &mdash; University Project
</footer>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<!-- Auto-dismiss alerts after 4 seconds -->
<script>
    setTimeout(() => {
        document.querySelectorAll('.healix-alert').forEach(el => {
            el.style.transition = 'opacity 0.5s';
            el.style.opacity = '0';
            setTimeout(() => el.remove(), 500);
        });
    }, 4000);
</script>

</body>
</html>
