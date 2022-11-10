<!-- Footer-->
<footer class="content-footer footer bg-footer-theme">
  <div class="{{ (!empty($containerNav) ? $containerNav : 'container-fluid') }} d-flex flex-wrap justify-content-between py-2 flex-md-row flex-column">
    <div class="mb-2 mb-md-0">
      © <script>
        document.write(new Date().getFullYear())
      </script>
      , made with ❤️ by <a href="{{ (!empty(config('variables.creatorUrl')) ? config('variables.creatorUrl') : '') }}" target="_blank" class="footer-link fw-bolder">{{ (!empty(config('variables.creatorName')) ? config('variables.creatorName') : '') }}</a>
    </div>
    <div>
      <!-- <a href="{{ config('variables.licenseUrl') ? config('variables.licenseUrl') : '#' }}" class="footer-link me-4" target="_blank">License</a> -->
      <a href="/" target="_blank" class="footer-link me-4">Help</a>
      <a href="/" target="_blank" class="footer-link me-4">Contact</a>
      <a href="/" target="_blank" class="footer-link me-4">Terms &amp; Conditions</a>
    </div>
  </div>
</footer>
<!--/ Footer-->