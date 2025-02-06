 <!-- jQuery -->
 <script src="{{ asset('assetsNew/plugins/jquery/jquery.min.js') }}"></script>
 <!-- jQuery UI 1.11.4 -->
 <script src="{{ asset('assetsNew/plugins/jquery-ui/jquery-ui.min.js') }}"></script>
 <!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
 <script>
     $.widget.bridge('uibutton', $.ui.button)
 </script>
 <!-- bootstrap 5 -->
 <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
 <!-- Bootstrap 4 -->
 <script src="{{ asset('assetsNew/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
 <!-- ChartJS -->
 <script src="{{ asset('assetsNew/plugins/chart.js/Chart.min.js') }}"></script>
 <!-- Sparkline -->
 <script src="{{ asset('assetsNew/plugins/sparklines/sparkline.js') }}"></script>
 <!-- JQVMap -->
 <script src="{{ asset('assetsNew/plugins/jqvmap/jquery.vmap.min.js') }}"></script>
 <script src="{{ asset('assetsNew/plugins/jqvmap/maps/jquery.vmap.usa.js') }}"></script>
 <!-- jQuery Knob Chart -->
 <script src="{{ asset('assetsNew/plugins/jquery-knob/jquery.knob.min.js') }}"></script>
 <!-- daterangepicker -->
 <script src="{{ asset('assetsNew/plugins/moment/moment.min.js') }}"></script>
 <script src="{{ asset('assetsNew/plugins/daterangepicker/daterangepicker.js') }}"></script>
 <!-- Tempusdominus Bootstrap 4 -->
 <script src="{{ asset('assetsNew/plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js') }}"></script>
 <!-- Summernote -->
 <script src="{{ asset('assetsNew/plugins/summernote/summernote-bs4.min.js') }}"></script>
 <!-- overlayScrollbars -->
 <script src="{{ asset('assetsNew/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js') }}"></script>
 <!-- AdminLTE App -->
 <script src="{{ asset('assetsNew/dist/js/adminlte.js') }}"></script>
 <!-- AdminLTE dashboard demo (This is only for demo purposes) -->
 <script src="{{ asset('assetsNew/dist/js/pages/dashboard.js') }}"></script>
 {{-- <script src="{{asset('assetsNew/dist/js/auto-complete.js')}}"></script> --}}
 <!-- data table -->
 <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
 <script src="{{ asset('assetsNew/dist/js/demo.js') }}"></script>
 <script type="text/javascript" src="//cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js"></script>

 <script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/js/bootstrap-select.min.js"></script>
 <script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/js/i18n/defaults-*.min.js"></script>

 <script>
     $(document).ready(function() {
         $(function() {
             $('.select2').selectpicker();
         });
         $(function() {
             $('.selectpicker').selectpicker();
         });
     });
 </script>


 <script>
     $(document).ready(function() {
         $('#site_list01').DataTable({
             "columnDefs": [{
                     "targets": [0],
                     "orderable": false
                 }, // Disable sorting for the first column (e.g., #SL)
             ]
         });

         $('#example1').DataTable({
             "columnDefs": [{
                 "targets": [0],
                 "orderable": false
             }, ]
         });
     });
 </script>
 <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
 <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>