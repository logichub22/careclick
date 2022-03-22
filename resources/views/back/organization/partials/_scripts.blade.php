<script src="{{ asset('js/main/jquery.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('plugins/tether/js/tether.min.js') }}"></script>
<script src="{{ asset('js/back/theme.js') }}"></script>
<script type="text/javascript" src="{{ asset('plugins/detectmobilebrowser/detectmobilebrowser.js') }}"></script>
<script type="text/javascript" src="{{ asset('plugins/jscrollpane/jquery.mousewheel.js') }}"></script>
<script type="text/javascript" src="{{ asset('plugins/jscrollpane/mwheelIntent.js') }}"></script>
<script type="text/javascript" src="{{ asset('plugins/jscrollpane/jquery.jscrollpane.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('plugins/jquery-fullscreen-plugin/jquery.fullscreen-min.js') }}"></script>
<script type="text/javascript" src="{{ asset('plugins/waves/waves.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('plugins/chartist/chartist.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('plugins/switchery/dist/switchery.min.js') }}"></script>
<!-- This will be moved for faster page load -->
<script type="text/javascript" src="{{ asset('plugins/DataTables/js/jquery.dataTables.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('plugins/DataTables/js/dataTables.bootstrap4.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('plugins/DataTables/Responsive/js/dataTables.responsive.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('plugins/DataTables/Responsive/js/responsive.bootstrap4.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('plugins/DataTables/Buttons/js/dataTables.buttons.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('plugins/DataTables/Buttons/js/buttons.bootstrap4.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('plugins/DataTables/JSZip/jszip.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('plugins/DataTables/pdfmake/build/pdfmake.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('plugins/DataTables/pdfmake/build/vfs_fonts.js') }}"></script>
<script type="text/javascript" src="{{ asset('plugins/DataTables/Buttons/js/buttons.html5.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('plugins/DataTables/Buttons/js/buttons.print.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('plugins/DataTables/Buttons/js/buttons.colVis.min.js') }}"></script>
<!-- // End Scripts to be moved -->
<script type="text/javascript" src="{{ asset('plugins/flot/jquery.flot.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('plugins/flot/jquery.flot.pie.js') }}"></script>
<script type="text/javascript" src="{{ asset('plugins/flot/jquery.flot.stack.js') }}"></script>
<script type="text/javascript" src="{{ asset('plugins/flot/jquery.flot.resize.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('plugins/flot.tooltip/js/jquery.flot.tooltip.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('plugins/CurvedLines/curvedLines.js') }}"></script>
<script type="text/javascript" src="{{ asset('plugins/TinyColor/tinycolor.js') }}"></script>
<script type="text/javascript" src="{{ asset('plugins/sparkline/jquery.sparkline.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('plugins/raphael/raphael.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('plugins/morris/morris.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('plugins/jvectormap/jquery-jvectormap-2.0.3.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('plugins/jvectormap/jquery-jvectormap-world-mill.js') }}"></script>

@stack('date-scripts')

<!-- Neptune JS -->
<script type="text/javascript" src="{{ asset('js/back/neptune.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/back/demo.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/back/index2.js') }}"></script>

<!-- Theme JS -->
<script src="{{ asset('js/back/back.js') }}"></script>
<script src="{{ asset('js/main/jamborow.js') }}"></script>
<script src="{{ asset('plugins/icheck/icheck.min.js') }}"></script>
<script>
    $(document).ready(function(){
        $('input').iCheck({
        checkboxClass: 'icheckbox_square-blue',
        radioClass: 'iradio_square-blue',
        increaseArea: '20%' // optional
        });
    });
</script>

@stack('scripts')