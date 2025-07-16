<script type="text/javascript">
    jQuery('.datatable-form-filter select').on('change', function(e) {
        window.LaravelDataTables["dataTableBuilder"].draw();
        e.preventDefault();
    });
    jQuery('.datatable-form-filter input').on('keyup', function(e) {
        window.LaravelDataTables["dataTableBuilder"].draw();
        e.preventDefault();
    });
    jQuery('.dataTables_length select').select2({
        minimumResultsForSearch: Infinity,
        width: 'auto'
    });
    jQuery(".reset_filters").on('click', function(e) {
        jQuery(".datatable-form-filter input").val("");
        jQuery(".datatable-form-filter select").val("");
        window.LaravelDataTables["dataTableBuilder"].state.clear();
        var uri = window.location.toString();
        if (uri.indexOf("/?") > 0) {
            var clean_uri = uri.substring(0, uri.indexOf("/?"));
            window.history.replaceState({}, document.title, clean_uri);
        }
        $("#dataTableBuilder").DataTable().ajax.reload(null, false);
    });
</script>
