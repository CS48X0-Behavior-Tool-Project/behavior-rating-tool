jQuery(function(){
    // Set CSRF token in header
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $(document).ready(function() {
          $('#dataTable').DataTable();
    });


    $('#dataTable').DataTable({
      "pageLength": 15,
      "paging": true,
      "lengthChange": true,
      "searching": true,
      "ordering": true,
      "info": true,
      "autoWidth": true,
      "order": [[0, 'asc']]
      });

});
