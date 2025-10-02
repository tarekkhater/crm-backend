@if (session('success'))
    <script>
        "use strict";
        $(document).ready(function () {
            swal("Success!", "{{ session('success') }}", "success");
        });
    </script>
@endif

@if (session('alert'))
    <script>
        "use strict";
        $(document).ready(function () {
            swal("Opps!", "{{ session('alert') }}", "error");
        });
    </script>
@endif
@if (session('failure'))
    <script>
        "use strict";
        $(document).ready(function () {
            swal("Opps!", "{{ session('failure') }}", "error");
        });
    </script>
@endif
<script>
    @if (session('error'))
    toastr.error("{{ session('error') }}");
    @endif
    @if (session('success'))
    toastr.success("{{ session('success') }}");
    @endif
</script>
