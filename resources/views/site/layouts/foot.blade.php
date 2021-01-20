<script src="{{ asset('js/site/lib/js/plugins.js?v='.config('version.version_script')) }}"></script>
<script type="text/javascript" src="{{ asset('js/site/lang/lang_'.$cuRRlocal.'.js?v='.config('version.version_script')) }}"></script>
@if($cuRRlocal=='ar')
<script src="{{ asset('js/site/lib/js/theme.js?v='.config('version.version_script')) }}"></script>
@else
<script src="{{ asset('js/site/lib/js/theme-en.js?v='.config('version.version_script')) }}"></script>
@endif
<script type="text/javascript" src="{{ asset('js/site/public.js?v='.config('version.version_script')) }}"></script>
<script type="text/javascript" src="{{ asset('js/site/ajax.js?v='.config('version.version_script')) }}"></script>
<!-- bootstrap datepicker -->
<script src="{{ asset('js/site/js/bootstrap-datepicker/js/bootstrap-datepicker.js') }}"></script>
<script src="{{ asset('js/site/js/bootstrap-timepicker/js/bootstrap-timepicker.min.js') }}"></script>
<!--  datatable -->