</div>

</div>

</div>
</div>
</div>
</div>
</div>

<script src="<?= APP_PATH; ?>libraries/js/jquery/jquery.js"></script>
<script src="<?= APP_PATH; ?>libraries/js/popper/popper.js"></script>
<script src="<?= APP_PATH; ?>assets/js/bundle.base.js"></script>

<?php if (!isset($isLogin)):
   include __DIR__ . '/autologout.php';
   if ($title !== 'Inicio'): ?>
      <script src="<?= APP_PATH; ?>libraries/js/select2/select2.min.js"></script>
      <script src="<?= APP_PATH; ?>libraries/js/dataTables/datatables.js"></script>
      <script src="<?= APP_PATH; ?>libraries/js/dataTables/datatables.datetime.min.js"></script>
      <script src="<?= APP_PATH; ?>libraries/js/dataTables/dataTables.responsive.min.js"></script>
      <script src="<?= APP_PATH; ?>libraries/js/dataTables/responsive.bootstrap5.min.js"></script>

      <script src="<?= APP_PATH; ?>libraries/js/dataTables/dataTables.buttons.min.js"></script>
      <script src="<?= APP_PATH; ?>libraries/js/dataTables/dataTables.select.min.js"></script>
      <script src="<?= APP_PATH; ?>libraries/js/dataTables/buttons.bootstrap4.min.js"></script>
      <script src="<?= APP_PATH; ?>libraries/js/dataTables/jszip.min.js"></script>
      <script src="<?= APP_PATH; ?>libraries/js/dataTables/pdfmake.min.js"></script>
      <script src="<?= APP_PATH; ?>libraries/js/dataTables/vfs_fonts.js"></script>
      <script src="<?= APP_PATH; ?>libraries/js/dataTables/buttons.html5.min.js"></script>
      <script src="<?= APP_PATH; ?>libraries/js/dataTables/buttons.print.min.js"></script>

      <script src="<?= APP_PATH; ?>libraries/js/jspdf/jspdf.min.js"></script>
   <?php else: ?>
      <script src="<?= APP_PATH; ?>libraries/js/chartJs/chart.min.js"></script>
   <?php endif; ?>

   <script src="<?= APP_PATH; ?>libraries/js/momentjs/moment.min.js"></script>
   <script src="<?= APP_PATH; ?>assets/js/off-canvas.js"></script>
   <script src="<?= APP_PATH; ?>assets/js/hoverable-collapse.js"></script>
   <script src="<?= APP_PATH; ?>assets/js/misc.js"></script>
   <script src="<?= APP_PATH; ?>assets/js/settings.js"></script>

   <?php if ($title == 'Inventario'): ?>
      <script src="<?= APP_PATH; ?>libraries/js/barcode/barcode.all.min.js"></script>
   <?php endif; ?>
<?php endif; ?>
<script src="<?= APP_PATH; ?>libraries/js/sweetalert2/sweetalert2.all.min.js"></script>
<script type="module" src="./index.js?r=<?= substr(rand(), 0, 4); ?>"></script>

</body>

</html>