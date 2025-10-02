<script src="{{ asset('lib/jquery-ui/jquery-ui.js') }}"></script>
<script src="{{ asset('lib/jquery-switchbutton/jquery.switchButton.js') }}"></script>
<script src="{{ asset('lib/peity/jquery.peity.js') }}"></script>
<script src="{{ asset('lib/datatables/jquery.dataTables.js') }}"></script>
<script src="{{ asset('lib/datatables-responsive/dataTables.responsive.js') }}"></script>
<script src="{{ asset('lib/select2/js/select2.min.js') }}"></script>
<script src="{{ asset('lib/highlightjs/highlight.pack.js') }}"></script>
<script src="https://cdn.datatables.net/plug-ins/1.10.16/sorting/numeric-comma.js"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.26.0/moment.min.js"></script>
<script src="https://cdn.datatables.net/plug-ins/1.10.25/sorting/datetime-moment.js"></script>



<script>
    $(function(){
        'use strict';
        // $('.tble_dt').dataTable({
        //
        //     order:true,
        // });
         $.fn.dataTable.moment( 'DD.MM.YYYY' );

        $('#datatable1').DataTable({
            iDisplayLength : 100,
            "ordering": true,
            language: {
                searchPlaceholder: 'Search...',
                sSearch: '',
                lengthMenu: '_MENU_ items/page',
            },
            columnDefs: [
                {
                    "orderSequence": ["desc", "asc"],
                    type: 'numeric-comma', targets: 0
                }
            ],
        });
        $('.datatable1').DataTable({
            iDisplayLength : 100,
            "ordering": true,

            language: {
                searchPlaceholder: 'Search...',
                sSearch: '',
                lengthMenu: '_MENU_ items/page',
            },
            columnDefs: [
                {
                    "orderSequence": ["desc", "asc"],
                    type: 'numeric-comma', targets: 0
                }
            ],
        });
        $('#datatable2').DataTable({
            iDisplayLength : 100,

            language: {
                searchPlaceholder: 'Search...',
                sSearch: '',
                lengthMenu: '_MENU_ items/page',
            },
            columnDefs: [
                {
                    "orderSequence": ["desc", "asc"],
                    type: 'numeric-comma', targets: 0
                }
            ],
        });

        // $('#datatable2').DataTable({
        //     bLengthChange: false,
        //     searching: false,
        //     responsive: true
        // });

        // Select2
        $('.dataTables_length select').select2({ minimumResultsForSearch: Infinity });

    });
</script>
