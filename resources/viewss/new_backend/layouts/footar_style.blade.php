<a href="#top" id="back-to-top"><i class="fa fa-long-arrow-up"></i></a>

<!-- JQUERY JS -->
<script src="{{asset('new_admin')}}/assets/plugins/jquery/jquery.min.js"></script>

<!-- BOOTSTRAP JS -->
<script src="{{asset('new_admin')}}/assets/plugins/bootstrap/js/popper.min.js"></script>
<script src="{{asset('new_admin')}}/assets/plugins/bootstrap/js/bootstrap.min.js"></script>

<!-- SIDE-MENU JS -->
<script src="{{asset('new_admin')}}/assets/plugins/sidemenu/sidemenu.js"></script>

<!-- Perfect SCROLLBAR JS-->
<script src="{{asset('new_admin')}}/assets/plugins/p-scroll/perfect-scrollbar.js"></script>
<script src="{{asset('new_admin')}}/assets/plugins/p-scroll/pscroll.js"></script>

<!-- STICKY JS -->
<script src="{{asset('new_admin')}}/assets/js/sticky.js"></script>
<script src="{{asset('new_admin')}}/assets/js/jquery.qrcode.min.js"></script>
<script src="{{asset('new_admin')}}/assets/js/JsBarcode.all.js"></script>
{{--<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.qrcode/1.0/jquery.qrcode.min.js" integrity="sha512-NFUcDlm4V+a2sjPX7gREIXgCSFja9cHtKPOL1zj6QhnE0vcY695MODehqkaGYTLyL2wxe/wtr4Z49SvqXq12UQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>--}}


<!-- APEXCHART JS -->
<script src="{{asset('new_admin')}}/assets/js/apexcharts.js"></script>
<script src="{{asset('new_admin')}}/assets/js/jquery.maphilight.js"></script>

<!-- INTERNAL SELECT2 JS -->
<script src="{{asset('new_admin')}}/assets/plugins/select2/select2.full.min.js"></script>

<!-- CHART-CIRCLE JS-->
<script src="{{asset('new_admin')}}/assets/plugins/circle-progress/circle-progress.min.js"></script>

<!-- INTERNAL DATA-TABLES JS-->
<script src="{{asset('new_admin')}}/assets/plugins/datatable/js/jquery.dataTables.min.js"></script>
<script src="{{asset('new_admin')}}/assets/plugins/datatable/js/dataTables.bootstrap5.js"></script>
<script src="{{asset('new_admin')}}/assets/plugins/datatable/dataTables.responsive.min.js"></script>

<!-- INDEX JS -->
<script src="{{asset('new_admin')}}/assets/js/index1.js"></script>
<script src="{{asset('new_admin')}}/assets/js/index.js"></script>

<!-- Reply JS-->
<script src="{{asset('new_admin')}}/assets/js/reply.js"></script>


<!-- COLOR THEME JS -->
<script src="{{asset('new_admin')}}/assets/js/themeColors.js"></script>

<!-- CUSTOM JS -->
<script src="{{asset('new_admin')}}/assets/js/custom.js"></script>

<!-- SWITCHER JS -->
<script src="{{asset('new_admin')}}/assets/switcher/js/switcher.js"></script>


<!-- FORMVALIDATION JS -->
<script src="{{asset('new_admin')}}/assets/js/form-validation.js"></script>

<!-- SWITCHER JS -->

<script src="{{asset('new_admin')}}/assets/js/tagify.min.js"></script>
<script src="{{asset('new_admin')}}/assets/js/toastr.min.js" ></script>
<script src="{{asset('new_admin')}}/assets/js/resumable.min.js"></script>
<script src="{{asset('new_admin')}}/assets/plugins/sweet-alert/sweetalert.min.js"></script>
<script src="{{asset('new_admin')}}/assets/plugins/sweet-alert/jquery.sweet-alert.js"></script>

<script src="{{asset('new_admin')}}/assets/plugins/datatable/js/dataTables.buttons.min.js"></script>
<script src="{{asset('new_admin')}}/assets/plugins/datatable/js/buttons.bootstrap5.min.js"></script>
<script src="{{asset('new_admin')}}/assets/plugins/datatable/js/jszip.min.js"></script>
<script src="{{asset('new_admin')}}/assets/plugins/datatable/pdfmake/pdfmake.min.js"></script>
<script src="{{asset('new_admin')}}/assets/plugins/datatable/pdfmake/vfs_fonts.js"></script>
<script src="{{asset('new_admin')}}/assets/plugins/datatable/js/buttons.html5.min.js"></script>
<script src="{{asset('new_admin')}}/assets/plugins/datatable/js/buttons.print.min.js"></script>
<script src="{{asset('new_admin')}}/assets/plugins/datatable/js/buttons.colVis.min.js"></script>
<script src="{{asset('new_admin')}}/assets/plugins/moment/moment.js"></script>
<script src="{{asset('new_admin')}}/assets/plugins/fullcalendar/main.js"></script>

{{--<script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.5/index.global.min.js'></script>--}}

{{--<script src="{{asset('new_admin')}}/assets/plugins/fullcalendar/fullcalendar.min.js"></script>--}}
{{--<script src="{{asset('new_admin')}}/assets/js/fullcalendar.js"></script>--}}




<script src="{{asset('new_admin')}}/assets/plugins/datatable/js/dataTables.fixedHeader.min.js"></script>

<script src="{{asset('new_admin')}}/assets/plugins/datatable/responsive.bootstrap5.min.js"></script>
<script src="{{asset('new_admin')}}/assets/plugins/chart/Chart.bundle.js"></script>
<script src="{{asset('new_admin')}}/assets/plugins/chart/utils.js"></script>
<script type="text/javascript" src="https://maps.google.com/maps/api/js?key=AIzaSyA9UBKQHciVMSJZEoM640mtwKkTXavjrD4&libraries=places"></script>

<script data-name="basic">
    (function(){
// The DOM element you wish to replace with Tagify
        var input = document.querySelector('.basic_tag');

// initialize Tagify on the above input node reference
        new Tagify(input)
    })()
</script>

{{--@include('new_backend.layouts.ckeditor')--}}





<script>

    function upload_image(element){
        $(element).siblings('input').click();
    }
    function upload_video(element){
        if (element.files && element.files[0]) {

            var reader = new FileReader();

            reader.onload = function (e) {


                $(element).siblings('.pernt_image').find('source').attr('src', e.target.result);
            }

            reader.readAsDataURL(element.files[0]);
        }

    }
    @if(session('error'))

        toastr["error"]('{{session('error')}}', "Error")
    @endif
    $(document).ready(function() {
        "use strict"
        toastr.options = {
            "closeButton": false,
            "debug": false,
            "newestOnTop": false,
            "progressBar": false,
            "positionClass": "toast-bottom-right",
            "preventDuplicates": false,
            "onclick": null,
            "showDuration": "300",
            "hideDuration": "1000",
            "timeOut": "5000",
            "extendedTimeOut": "1000",
            "showEasing": "swing",
            "hideEasing": "linear",
            "showMethod": "fadeIn",
            "hideMethod": "fadeOut"
        }
        // $('.selectpicker').selectpicker()
        $('.select2').select2()

        var readURL = function(input) {

            if (input.files && input.files[0]) {

                var reader = new FileReader();

                reader.onload = function (e) {


                    $(input).siblings('.pernt_image').find('.show_image').attr('src', e.target.result);
                }

                reader.readAsDataURL(input.files[0]);
            }
        }
        var mulable = function(input) {

            var total_file=$(input).get(0).files.length;

            for(var i=0;i<total_file;i++)
            {
                $(input).siblings('.pernt_image').append("<img style='margin: 5px' width='70px' src='"+URL.createObjectURL(event.target.files[i])+"'>");
            }
        }


        $(".input_upload").on('change', function(){

            readURL(this);
        });

        $('.input_uploads').on('change',function (){
            $(this).siblings('.pernt_image').html('');

            mulable($(this))
        });



    });
    function input_file_change(element){



        const [file] = $(element).files[0]

        if (file) {


            element.siblings('.pernt_image').find('.show_image').attr('src',URL.createObjectURL(file));
        }
    }
    function upload_tet(input){
        if (input.files && input.files[0]) {

            var reader = new FileReader();

            reader.onload = function (e) {


                $(input).siblings('.pernt_image').find('.show_image').attr('src', e.target.result);
            }

            reader.readAsDataURL(input.files[0]);
        }
    }
    function view_image(element){
    }

    function add_debart (e,a) {

        a.preventDefault();
        var remove="";
        $('.department').find('select').select2("destroy")
        remove="<button class='btn btn-danger'  onclick='remove(this,event)'> <i class='fa fa-trash'> </i></button> ";
        //debart=e.parents('.row').find('.form_debart')
        x= e.parents(".department").find('.form_debart:first').clone();

        x.find('input').val('')
        y= x.find('.remove_ro').html(remove);


        e.parents(".department").append(x)
        $('.department').find("select").select2();


    }
    function add_debart_plan (e,a) {

        a.preventDefault();
        var remove = "";

        var len= $('.depart_plan').find('.form_debart').length

        x_imag=$('#x_image').val()
        y_imag=$('#y_image').val()
        w_imag=$('#w_image').val()
        h_imag=$('#h_image').val()
        last_plan=$('.plan_name:last').val()
        if(x_imag>0 ) {

            $('#x_image').val(" ")

            if ($('.depart_plan').hasClass("d-none")) {

                $('.depart_plan').removeClass("d-none")
                $('.depart_plan').find('.x_image_plan:last').val(x_imag)
                $('.depart_plan').find('.y_image_plan:last').val(y_imag)
                $('.depart_plan').find('.h_image_plan:last').val(h_imag)
                $('.depart_plan').find('.w_image_plan:last').val(w_imag)

            } else if (last_plan!='') {
                remove = "<button class='btn btn-danger'  onclick='remove(this,event)'> <i class='fa fa-trash'> </i></button> ";
                //debart=e.parents('.row').find('.form_debart')
                x = $(".depart_plan").find('.form_debart:first').clone();

                x.find('input').val('')
                x.find('.x_image_plan:last').val(x_imag)
                x.find('.y_image_plan:last').val(y_imag)
                x.find('.h_image_plan:last').val(h_imag)
                x.find('.w_image_plan:last').val(w_imag)
                y = x.find('.remove_ro').html(remove);



                $(".depart_plan").append(x)

            }
        }else {
            alert("Select Part")
        }


    }
    function remove(e,a) {
        a.preventDefault();
        $(e).parents('.form_debart').remove();
    }


    function save_form(element,event) {


        event.preventDefault()
        var t=$(element).parents('form');
        // Array.prototype.filter.call(t,
        //     (function(t){t.addEventListener("submit",(function(e){!1===t.checkValidity()&&(e.preventDefault(),
        //         e.stopPropagation()),t.classList.add("was-validated")}),!1)}))



        var post=$(element).parents('form').attr('method');

        $('.textarea').each(function () {
            var ed = tinyMCE.get($(this).attr('id'));

            var text = ed.getContent();
            $(this).text(text)

        })

        var form=$(element).parents('form')[0];



        var data = new FormData(form);

        // var data=data.escape(text);
        // $(this).serialize();
        var url=$(element).parents('form').attr('action');
        $(element).prop('disabled', true);

        $.ajax({
            type: post,
            url: url,
            data: data,
            dataty: "json",
            contentType: false,
            processData: false,
            success: function (data) {
                $(element).prop('disabled', false);


                if(data!= ''&&typeof data.route !=='undefined'){
                    toastr["success"]("تم الحفظ بنجاح  ", "تم")

                    setInterval(function(){
                        location.href=data.route
                    },2000);

                }
                else if(data!= ''&&typeof data.type !=='undefined'){
                    $('textarea').val("")
                    $('#images_vue').html("")

                }  else if(data!= ''&&typeof data.error !=='undefined'){
                    toastr["error"](" " + data.error, "خطاء")
                    $(element).prop('disabled', false);

                }
                else{
                    toastr["success"]("تم الحفظ بنجاح  ", "تم")
                    $('input').val('');
                    setInterval(function(){
                        location.reload();
                    },2000);
                }


            },
            error: function (error) {
                $(element).prop('disabled', false);
                if (error.status === 422) {

                    const errors = error.responseJSON.errors
                    const first_item = Object.keys(errors)[0]
                    const first_dom = document.getElementById(first_item)
                    const first_message = errors[first_item][0]

                    toastr["error"](" " + first_message, "Error")

                    var headerOffset = 60;
                    var elementPosition = first_dom.getBoundingClientRect().top;
                    var offsetPosition = elementPosition + window.pageYOffset - headerOffset;

                    window.scrollTo({
                        top: offsetPosition,
                        behavior: "smooth"
                    });


                    // first_dom.scrollIntoView({behavior: "smooth"})
                    const error_message = document.querySelectorAll('.text-danger')
                    error_message.forEach((element) => element.textContent = '')
                    first_dom.insertAdjacentHTML('afterend', '<div class="text-danger">' + first_message + '</div>')
                    const dom_error = document.querySelectorAll('.form-control')
                    dom_error.forEach((element) => element.classList.remove('border', 'border-danger'))
                    first_dom.classList.add('border', 'border-danger')


                } else {
                    toastr["error"](" Error", "Error")
                }


            }
        });


    }

</script>
<script>



    $('.datatable').DataTable( {

        responsive:true,
        autoWidth:false,
        dom: 'Bfrtip',
        buttons: [
            {

                extend: 'print',
                text:'Print',
                exportOptions: {
                    columns: ':visible',

                }
            },
            {

                extend: 'colvis',

            },{
                extend: 'excel',
                text:'Excel',
                exportOptions: {
                    columns: ':visible'
                }
            },


        ],

        columnDefs: [
            {
                "orderSequence": ["desc", "asc"],
                type: 'numeric-comma', targets: 0
            }
        ],
        "language": {
            "sSearch": "Search ...:   ",
            "sEmptyTable": "Empty Table",
            "oPaginate": {
                "sNext": "Next",
                "sPrevious": "Previous",
                "info": "Show page _PAGE_ From _PAGES_",
            }
        }
    } );
    function delete_confirm(element,event) {
        var form = $(element).closest("form");
        $('body').removeClass('timer-alert');
        event.preventDefault();

        swal({
                title: `هل أنت متأكد من حذف هذا العنصر؟`,
                text: " سيتم حذف بيانات هذا العنصر بالكامل ",
                icon: "warning",
                buttons: true,
                dangerMode: true,

                type: "warning",
                showCancelButton: true,
                confirmButtonClass: "btn-danger",
                confirmButtonText: "نعم حذف !",
                cancelButtonText: "الغاء !",
                closeOnConfirm: false,
                closeOnCancel: false

            },
            function (isConfirm) {
                if (isConfirm) {
                    swal("حذف!", "سيتم الحذف بنجاح.", "success");
                    form.submit();
                }
            });

        // .then((willDelete) => {
        //     if (willDelete) {
        //         form.submit();
        //     }
        // });
    }





</script>
