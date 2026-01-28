@extends('layouts.app')
@section('content')
    <style>
        .table>thead>tr>th {
            padding: 12px 2px 12px 4px;
        }

        .col-sm-12 {
            float: none;
        }

        @media screen and (max-width:540px) {
            div#invoice_info {
                margin-top: -187px;
            }

            span.titleup {
                /* margin-left: -10px; */
            }
        }

        div.dataTables_processing {
            position: fixed;
            top: 50%;
            left: 50%;
            width: 150px;
            color: #EA6B00;
            margin-left: -100px;
            margin-top: -26px;
            text-align: center;
            padding: 3px 0;
            border: none;
        }

        .payment-gateway-pay-btn {
            background-color: #EA6B00 !important;
            color: #FFFFFF;
            font-size: 16px;
            border: none;
            border-radius: 5px;
            padding: 10px 20px;
            cursor: pointer;
            transition: background-color 0.3s ease, transform 0.1s ease;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            text-transform: uppercase;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .nested-dropdown {
            display: none;
        }

        .pay-btn {
            font-size: 15px !important;
            font-weight: 500 !important;
        }

        .pay-btn:hover {
            color: #EA6B00 !important;
            font-size: 15px !important;
            font-weight: 500 !important;
        }
        .submit2:hover {
            color: #EA6B00 !important;
        }
    </style>


    <!-- page content -->
    <div class="right_col" role="main">

            @if (!empty($activePaymentGateways))
                @foreach ($activePaymentGateways as $gateway)

                    @if (View::exists('easypay::' . strtolower($gateway->gateway_name) . '.' . strtolower($gateway->gateway_name) . '_payment'))
                        @include('easypay::' . strtolower($gateway->gateway_name) . '.' . strtolower($gateway->gateway_name) . '_payment')
                    @endif

                @endforeach  
            @endif 


        <!--invoice modal-->
        <div id="myModal-job" class="modal fade" role="dialog">
            <div class="modal-dialog modal-lg">
                <!-- Modal content-->
                <div class="modal-content modal-body-data">
                </div>
            </div>
        </div>
        <!--Payment modal-->
        <div id="myModal-payment" class="modal fade" role="dialog">
            <div class="modal-dialog modal-lg">
                <!-- Modal content-->
                <div class="modal-content modal-data">

                </div>
            </div>
        </div>
        <div class="">
            <div class="page-title">
                <div class="nav_menu">
                    <nav>
                        <div class="nav toggle">
                            <a id="menu_toggle"><i class="fa fa-bars sidemenu_toggle"></i></a>
                            <span class="titleup">{{ trans('message.Invoices') }}
                                @can('invoice_add')
                                <a href="{!! url('/invoice/add') !!}" id="" class="addbotton">
                                    <img src="{{ URL::asset('public/img/icons/plus Button.png') }}">
                                </a>
                                @endcan
                            </span>
                        </div>

                        @include('dashboard.profile')
                    </nav>
                </div>
            </div>
            @include('success_message.message')

            <div class="row">
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="x_content">
                        <ul class="nav nav-tabs">
                            <li class="nav-item">
                                @can('invoice_view')
                                <a href="{!! url('/invoice/list') !!}" class="nav-link active"><span class="visible-xs"></span>
                                    <i class=""></i><b>{{ trans('message.INVOICE LIST') }}</b></a>
                                @endcan
                            </li>
                            <li class="nav-item">
                                @can('invoice_view')
                                @can('salespart_view')
                                <a href="{!! url('/invoice/sale_part') !!}" class="nav-link nav-link-not-active"><span class="visible-xs"></span><i class="">&nbsp;</i><b>{{ trans('message.SOLD PART INVOICE LIST') }}</b></a>
                                @endcan
                                @endcan
                            </li>
                        </ul>
                    </div>
                    <div class="x_panel mb-0">
                        @if(!empty($invoice) && count($invoice) > 0)
                        <table id="supplier" class="table table-sm" style="width:100%">
                            <thead>
                                <tr>
                                    @can('invoice_delete')
                                    <th> </th>
                                    @endcan
                                    <th>{{ trans('message.Invoice Number') }}</th>
                                    <th>{{ trans('message.Customer Name') }}</th>
                                    <th>{{ trans('message.Invoice For') }}</th>
                                    <th>{{ trans('message.Number Plate') }}</th>
                                    <th>{{ trans('message.Total Amount') }} ({{ getCurrencySymbols() }})</th>
                                    <th>{{ trans('message.Paid Amount') }} ({{ getCurrencySymbols() }})</th>
                                    <th>{{ trans('message.Date') }}</th>
                                    <th>{{ trans('message.Status') }}</th>
                                    <th>{{ trans('message.Action') }}</th>
                                </tr>
                            </thead>
                            <tbody>

                            </tbody>
                        </table>
                        @can('invoice_delete')
                        <button id="select-all-btn" class="btn select_all"><input type="checkbox" name="selectAll"> {{ trans('message.Select All') }}</button>
                        <button id="delete-selected-btn" class="btn btn-danger text-white border-0" data-url="{!! url('/invoice/list/delete/') !!}"><i class="fa fa-trash" aria-hidden="true"></i></button>
                        @endcan
                        @else
                        <p class="d-flex justify-content-center mt-5 pt-5"><img src="{{ URL::asset('public/img/dashboard/No-Data.png') }}" width="300px"></p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- /page content -->


    <!-- Scripts starting -->
    <script nonce="{{ $cspNonce }}" src="{{ URL::asset('vendors/jquery/dist/jquery.min.js') }}"></script>
    <script nonce="{{ $cspNonce }}" src="https://js.stripe.com/v3/"></script>
    <script nonce="{{ $cspNonce }}">
        $(document).ready(function() {

        var search = "{{ trans('message.Search...') }}";
        var info = "{{ trans('message.Showing page _PAGE_ - _PAGES_') }}";
        var zeroRecords = "{{ trans('message.No Data Found') }}";
        var infoEmpty = "{{ trans('message.No records available') }}";
        var filtermessage = "{{trans('message.filtered from _MAX_ total records')}}";
        // $('#supplier').DataTable({
        //     columnDefs: [{
        //         width: 2,
        //         targets: 0
        //     }],
        //     fixedColumns: true,
        //     paging: true,
        //     scrollCollapse: true,
        //     scrollX: true,
        //     // scrollY: 300,

        //     responsive: true,
        //     "language": {
        //         lengthMenu: "_MENU_ ",
        //         info: info,
        //         zeroRecords: zeroRecords,
        //         infoEmpty: infoEmpty,
        //         infoFiltered: '(filtered from _MAX_ total records)',
        //         searchPlaceholder: search,
        //         search: '',
        //         paginate: {
        //             previous: "<",
        //             next: ">",
        //         }
        //     },
        //     aoColumnDefs: [{
        //         bSortable: false,
        //         aTargets: [-1]
        //     }],
        //     order: [
        //         [1, 'desc']
        //     ]
        // });     
        $('#supplier').DataTable({
    processing: true,
    serverSide: true,
    ajax: {
        url: '{{ url("/invoice/list") }}',
        type: 'GET'
    },
    columns: [
        @can('invoice_delete')
        {
            data: 'id',
            name: 'id',
            orderable: false,
            searchable: false,
            render: function(data) {
                return `<label class="container checkbox">
                            <input type="checkbox" name="chk" value="${data}">
                            <span class="checkmark"></span>
                        </label>`;
            }
        },
        @endcan
        {
            data: 'invoice_number',
            name: 'invoice_number'
        },
        {
            data: 'customer_name',
            name: 'customer_id'
        },
        {
            data: 'invoice_for',
            name: 'type'
        },
        {
            data: 'number_plate',
            name: 'number_plate'
        },
        {
            data: 'total_amount',
            name: 'grand_total'
        },
        {
            data: 'paid_amount',
            name: 'paid_amount'
        },
        {
            data: 'date',
            name: 'date'
        },
        {
            data: 'status',
            name: 'payment_status'
        },
        {
            data: 'action',
            name: 'action',
            orderable: false,
            searchable: false
        }
    ],
    language: {
        lengthMenu: "_MENU_ ",
        info: info,
        zeroRecords: zeroRecords,
        infoEmpty: infoEmpty,
        infoFiltered: filtermessage,
        searchPlaceholder: search,
        search: '',
        paginate: {
            previous: "<",
            next: ">"
        },
        processing: '<div class="loading-indicator"><i class="fa fa-spinner fa-spin fa-3x fa-fw"></i><span class="sr-only">Loading...</span></div>'
    },
    order: [[1, 'asc']],
    responsive: true,
    paging: true,
    scrollX: true,
    scrollCollapse: true
});
    
    $(document).on('click', '#select-all-btn', function () {
        let $checkbox = $(this).find('input[type="checkbox"]');
        let isChecked = !$checkbox.prop('checked'); // toggle the value
        $checkbox.prop('checked', isChecked); // update checkbox
        $('input[name="chk"]').prop('checked', isChecked); // select/deselect all rows
    });
    
    $(document).on('change', 'input[name="chk"]', function () {
    console.log($('input[name="chk"]:checked').length);
    let total = $('input[name="chk"]').length;
    let checked = $('input[name="chk"]:checked').length;
    
    // if all checkboxes are checked manually, check the main checkbox
    $('input[name="selectAll"]').prop('checked', total === checked);
    });
    // When the user directly clicks the checkbox (optional, same logic to keep consistent)
    $(document).on('click', '#select-all-btn input[type="checkbox"]', function (e) {
        e.stopPropagation(); // prevent triggering the button click again
        $('input[name="chk"]').prop('checked', $(this).prop('checked'));
    });

            /*Delete invoice*/
            $('body').on('click', '.deletedatas', function() {

                var url = $(this).attr('url');
                var msg1 = "{{ trans('message.Are You Sure?') }}";
                var msg2 = "{{ trans('message.You will not be able to recover this data afterwards!') }}";
                var msg3 = "{{ trans('message.Cancel') }}";
                var msg4 = "{{ trans('message.Yes, delete!') }}";
                swal({
                    title: msg1,
                    text: msg2,
                    icon: 'warning',
                    cancelButtonColor: '#C1C1C1',
                    buttons: [msg3, msg4],
                    dangerMode: true,
                }).then((willDelete) => {
                    if (willDelete) {
                        window.location.href = url;
                    }
                });
            });


            $('body').on('click', '.payWarning', function() {

                var msg5 = "{{ trans('message.Stripe Payment Failed') }}";
                var msg6 =
                    "{{ trans('message.You can not pay more than 999999.99 in a single transaction using card!') }}";
                var msg7 = "{{ trans('message.OK') }}";
                swal({
                    title: msg5,
                    text: msg6,
                    cancelButtonColor: '#C1C1C1',
                    buttons: {
                        cancel: msg7,
                    },
                    dangerMode: true,
                });


            });


            //view invoice 
            $('body').on('click', '.save', function() {

                $('.modal-body-data').html("");
                var type_id = $(this).attr("type_id");
                var serviceid = $(this).attr("serviceid");
                var auto_id = $(this).attr("auto_id");
                var job_no = $(this).attr("job_no")
                if (type_id == 0) {
                    var url = $(this).attr('url');
                } else {
                    var url = $(this).attr('sale_url');
                }

                var currentPageAction = getParameterByName('page_action');

                // Construct the URL for AJAX request with page_action parameter
                if (currentPageAction) {
                    url += '?page_action=' + currentPageAction;
                }

                $.ajax({
                    type: 'GET',
                    url: url,
                    data: {
                        serviceid: serviceid,
                        auto_id: auto_id,
                        job_no: job_no

                    },
                    dataType:'json',
                    success: function(data) {
                        $('.modal-body-data').html(data.html);
                    },
                    beforeSend: function() {
                        $(".modal-body-data").html(
                            "<center><h2 class=text-muted><b>Loading...</b></h2></center>");
                    },
                    error: function(e) {
                        alert("An error occurred: " + e.responseText);
                        console.log(e);
                    }
                });
            });

            // For get getParameterByName
            function getParameterByName(name, url = window.location.href) {
                name = name.replace(/[\[\]]/g, '\\$&');
                var regex = new RegExp('[?&]' + name + '(=([^&#]*)|&|#|$)'),
                    results = regex.exec(url);
                if (!results) return null;
                if (!results[2]) return '';
                return decodeURIComponent(results[2].replace(/\+/g, ' '));
            }



            //view Payment 
            $('body').on('click', '.Payment', function() {

                $('.modal-data').html("");
                var invoice_id = $(this).attr("invoice_id");
                var url = $(this).attr('url');

                $.ajax({
                    type: 'GET',
                    url: url,
                    data: {
                        invoice_id: invoice_id
                    },
                    dataType:'json',
                    success: function(data) {
                        $('.modal-data').html(data.html);
                    },
                    beforeSend: function() {
                        $(".modal-data").html(
                            "<center><h2 class=text-muted><b>Loading...</b></h2></center>");
                    },
                    error: function(e) {
                        alert("An error occurred: " + e.responseText);
                        console.log(e);
                    }
                });
            });
        });
    </script>
    <script nonce="{{ $cspNonce }}">
      $(document).on('click', '#delete-selected-btn', function () {
        var selectedIds = $('input[name="chk"]:checked').map(function () {
            return $(this).val(); // Get checked values
        }).get();

        console.log("Selected IDs:", selectedIds); // Debugging: Check if IDs are collected

    if (selectedIds.length === 0) {
        swal({
          title: "{{ trans('message.Please select atleast one record') }}",
          text:'',
          icon: "warning",
          buttons:  "{{ trans('message.OK') }}",
          dangerMode: true,
        });
        return;
    }

        var deleteUrl = $(this).data('url');

        swal({
            title: "{{ trans('message.Are You Sure?') }}",
            text: "{{ trans('message.You will not be able to recover this data afterwards!') }}",
            icon: "warning",
            buttons: ["{{ trans('message.Cancel') }}", "{{ trans('message.Yes, delete!') }}"],
            dangerMode: true,
        }).then((willDelete) => {
            if (willDelete) {
                $.ajax({
                    url: deleteUrl,
                    type: 'POST',
                    data: { ids: selectedIds, _token: '{{ csrf_token() }}' },
                    success: function (response) {
                        swal("{{ trans('message.Deleted!') }}", response.message, "success");
                        $('#supplier').DataTable().ajax.reload(); // Refresh DataTable
                    },
                    error: function (xhr) {
                        swal("{{ trans('message.Something went wrong. Please try again.') }}", { icon: "error" });
                    }
                });
            }
        });
    });


    </script>

    {{-- payment method dropdown --}}
    <script nonce="{{ $cspNonce }}">

        $(document).on("click", "#dropdownMenuButton1", function () {
            // Select elements inside the opened dropdown
            const payButton = $(this).closest(".dropdown_toggle").find(".pay-btn");
            const nestedDropdown = $(this).closest(".dropdown_toggle").find(".nested-dropdown");
            const mainDropdown = $(this).closest(".dropdown-menu");
            const closeItems = mainDropdown.find(".close-dropdowns");

            // Unbind previous events to prevent multiple bindings
            payButton.off("click").on("click", function (event) {
                event.stopPropagation(); // Prevent closing the main dropdown
                nestedDropdown.css({
                    'margin': '0',
                    'position': 'absolute',
                    'top': '-50px',
                    'left': '0%'
                });
                nestedDropdown.toggle();
                // Toggle nested dropdown visibility
            });

            // Close both dropdowns when clicking a nested item
            closeItems.off("click").on("click", function () {
                mainDropdown.removeClass("show"); // Close main dropdown
                nestedDropdown.hide(); // Close nested dropdown
            });

            // Close nested dropdown if clicking outside
            $(document).off("click.nested").on("click.nested", function (event) {
                if (!payButton.is(event.target) && !nestedDropdown.has(event.target).length) {
                    nestedDropdown.hide();
                }
            });
        });


    </script>
@endsection