@extends('layouts.app')
@section('content')
<style>
    button.btn.btn-default.buttons-print,
    .btn.btn-default.buttons-pdf.buttons-html5 {
        border: 1px solid black;
        margin-right: 10px;
    }

    button.btn.btn-default.buttons-excel {
        border: 1px solid black;
    }

    @media screen and (max-width:540px) {
        div#report_service_info {
            margin-top: -150px;
        }

        span.titleup {
            margin-left: -10px;
        }
    }
    @media screen and (max-width:800px) {
         .text-end {
            text-align: left !important;
        }
    }
    .select2-container--default .select2-results > .select2-results__options {
    max-height: 300px; 
    overflow-y: auto; 
    scrollbar-width: thin; /* For modern browsers, make scrollbar thinner */
}
   @media (max-width: 912px) {
    span.select2-selection.select2-selection--single {
        width: auto !important;
    }
}

@media (max-width: 400px){
     span.select2-selection.select2-selection--single {
        width: 302px !important;
    }
}
@media (max-width: 370px){
     span.select2-selection.select2-selection--single {
        width: auto !important;
    }
}
  .nav-link.active{
    border-bottom: 3px solid #EA6B00 !important;
  }
</style>
<?php
$options = [
    'title' => trans('message.All Service'),
    'titleTextStyle' => ['color' => '#73879C', 'fontSize' => 14, 'bold' => true, 'italic' => false, 'fontName' => '"Helvetica Neue",Roboto,Arial,"Droid Sans",sans-serif'],
    'legend' => ['position' => 'right', 'textStyle' => ['color' => '#73879C', 'fontSize' => 14, 'bold' => true, 'italic' => false, 'fontName' => '"Helvetica Neue",Roboto,Arial,"Droid Sans",sans-serif']],

    'hAxis' => [
        'title' => trans('message.Year'),
        'titleTextStyle' => ['color' => '#73879C', 'fontSize' => 14, 'bold' => true, 'italic' => false, 'fontName' => '"Helvetica Neue",Roboto,Arial,"Droid Sans",sans-serif'],
        'textStyle' => ['color' => '#73879C', 'fontSize' => 14],
        'maxAlternation' => 2,
    ],
    'vAxis' => [
        'title' => trans('message.Service'),
        'width' => 100,
        'format' => '#',
        'titleTextStyle' => ['color' => '#73879C', 'fontSize' => 16, 'bold' => true, 'italic' => false, 'fontName' => '"Helvetica Neue",Roboto,Arial,"Droid Sans",sans-serif'],
        'textStyle' => ['color' => '#73879C', 'fontSize' => 12],
    ],
    'colors' => ['#26b99a'],
    'bar' => [
        'groupWidth' => '100',
    ],
];

$chartStatus = 'no';
if (!empty($service)) {
    $chartStatus = 'yes';
}
foreach ($service as $data) {
    $datas = $data->counts;
}
$chart_array = [];
$chart_array[] = [trans('message.date'), trans('message.counts'), ['role' => 'style']];
foreach ($service as $services) {
    $chart_array[] = [$services->date, (int) $services->counts, '#26b99a'];
}

?>

<!-- CSS For Chart -->
<link rel="stylesheet" type="text/css" href="{{ URL::asset('public/js/49/css/tooltip.css') }}">
<link rel="stylesheet" type="text/css" href="{{ URL::asset('public/js/49/css/util.css') }}">

<div class="right_col servi" role="main">
    <div class="page-title">
        <div class="nav_menu">
            <nav>
                <div class="nav toggle">
                    <a id="menu_toggle"><i class="fa fa-bars sidemenu_toggle"></i></a><a id=""><i class=""></i><span class="titleup">
                            {{ trans('message.Reports') }}</span></a>
                </div>
                @include('dashboard.profile')
            </nav>
        </div>
    </div>

    <div class="x_content table-responsive">
        <ul class="nav nav-tabs">
            <!-- <li class="nav-item">
                @can('report_view')
                <a href="{!! url('/report/salesreport') !!}" class="nav-link nav-link-not-active"><span class="visible-xs"></span>
                    <i class="">&nbsp;</i><b>{{ trans('message.VEHICLE SALES') }}</b></a>
                <i class="fa fa-tty fa-lg">&nbsp;</i><b>{{ trans('VEHICLE SALES') }}</b></a>

                @endcan
            </li> -->
            <li class="nav-item">
                @can('report_view')
                <a href="{!! url('/report/servicereport') !!}" class="nav-link active"><span class="visible-xs"></span><i class="">&nbsp;</i><b>{{ trans('message.SERVICES') }}</b></a>
                <!-- class="fa-brands fa-slack fa-lg">&nbsp;</i><b>{{ trans('SERVICES') }}</b></a> -->

                @endcan
            </li>
            <li class="nav-item">
                @can('report_view')
                <a href="{!! url('/report/productreport') !!}" class="nav-link nav-link-not-active"><span class="visible-xs"></span><i class="">&nbsp;</i><b>{{ trans('message.PRODUCT STOCK') }}</b></a>
                <!-- class="fa-brands fa-product-hunt fa-lg">&nbsp;</i><b>{{ trans('PRODUCT STOCK') }}</b></a> -->

                @endcan
            </li>
            <li class="nav-item">
                @can('report_view')
                <a href="{!! url('/report/productuses') !!}" class="nav-link nav-link-not-active"><span class="visible-xs"></span><i class="">&nbsp;</i><b>{{ trans('message.PRODUCT USAGE') }}</b></a>
                <!-- class="fa-brands fa-product-hunt fa-lg">&nbsp;</i><b>{{ trans('PRODUCT USAGE') }}</b></a> -->

                @endcan
            </li>
            <li class="nav-item">
                @can('report_view')
                <a href="{!! url('/report/servicebyemployee') !!}" class="nav-link nav-link-not-active"><span class="visible-xs"></span><i class="">&nbsp;</i><b>{{ trans('message.EMP. SERVICES') }}</b></a>
                <!-- class="fa-brands fa-slack fa-lg">&nbsp;</i><b>{{ trans('EMP.SERVICES') }}</b></a> -->

                @endcan
            </li>
            <li class="nav-item">
                @can('report_view')
                <a href="{!! url('/report/upcomingservice') !!}" class="nav-link nav-link-not-active"><span class="visible-xs"></span><i class="">&nbsp;</i><b>{{ strtoupper(trans('message.Upcoming Services')) }}</b></a>
                <!-- class="fa-brands fa-slack fa-lg">&nbsp;</i><b>{{ trans('EMP.SERVICES') }}</b></a> -->

                @endcan
            </li>
            <li class="nav-item">
                @can('report_view')
                <a href="{!! url('/report/email') !!}" class="nav-link nav-link-not-active"><span class="visible-xs"></span><i class="">&nbsp;</i><b>{{ strtoupper(trans('message.Emails')) }}</b></a>
                @endcan
            </li>
        </ul>
    </div>

    <div class="row row-mb-0">
        <div class="col-md-12 col-lg-12 col-xl-12 col-xxl-12 col-sm-12 col-xs-12">
            <div class="x_panel row-mb-0 mb-0">
                <div class="x_content">
                    <form method="post" action="{!! url('/report/record_service') !!}" enctype="multipart/form-data" class="form-horizontal upperform">
                        <div class="row mt-3 row-mb-0">
                            <div class="row col-md-6 col-lg-6 col-xl-6 col-xxl-6 col-sm-6 col-xs-6 {{ $errors->has('start_date') ? ' has-error' : '' }}">
                                <label class="control-label col-md-4 col-lg-4 col-xl-4 col-xxl-4 col-sm-4 col-xs-4 currency" for="Country">{{ trans('message.Start Date') }} <label class="color-danger">*</label>
                                </label>
                                </label>
                                <div class="col-md-8 col-lg-8 col-xl-8 col-xxl-8 col-sm-8 col-xs-8 date">
                                    <input type="text" name="start_date" id="start_date_sales" autocomplete="off" class="form-control start_date datepicker" value="{{ !empty($s_date) ? date(getDateFormat(), strtotime($s_date)) : date(getDateFormat(), strtotime('first day of this month')) }}" placeholder="<?php echo getDatepicker(); ?>" onkeypress="return false;" required />
                                    <span id="common_error_span" class="help-block error-help-block text-danger" style="display: none">{{ trans('message.Please Select Start Date.') }}</span>
                                </div>

                            </div>

                            <div class="row col-md-6 col-lg-6 col-xl-6 col-xxl-6 col-sm-6 col-xs-6 {{ $errors->has('end_date') ? ' has-error' : '' }}">
                                <label class="control-label col-md-4 col-lg-4 col-xl-4 col-xxl-4 col-sm-4 col-xs-4 currency" for="Country">{{ trans('message.End Date') }} <label class="color-danger">*</label>
                                </label>

                                <div class="col-md-8 col-lg-8 col-xl-8 col-xxl-8 col-sm-8 col-xs-8 date">

                                    <input type="text" name="end_date" id="end_date_sales" autocomplete="off" class="form-control end_date datepicker" value="{{ old('p_date', date('Y-m-d')) }}" placeholder="<?php echo getDatepicker(); ?>" onkeypress="return false;" required />
                                    <span id="common_error_span_end" class="help-block error-help-block text-danger" style="display: none">{{ trans('message.Please Select End Date.') }}</span>
                                </div>

                            </div>
                        </div>
                        <div class="row row-mb-0">
                            <div class="row col-md-6 col-lg-6 col-xl-6 col-xxl-6 col-sm-6 col-xs-6">
                                <label class="control-label col-md-4 col-lg-4 col-xl-4 col-xxl-4 col-sm-4 col-xs-4" for="option">{{ trans('message.Select Service') }} </label>
                                <div class="col-md-8 col-lg-8 col-xl-8 col-xxl-8 col-sm-8 col-xs-8">
                                    <select class="form-control form-select" name="service_select">
                                        <option value="all" <?php if ($all_service == 'all') {
                                                                echo 'selected';
                                                            } ?>>{{ trans('message.All') }}</option>

                                        <option value="free" <?php if ($all_service == 'free') {
                                                                    echo 'selected';
                                                                } ?>>{{ trans('message.Free') }}</option>
                                        <option value="paid" <?php if ($all_service == 'paid') {
                                                                    echo 'selected';
                                                                } ?>>{{ trans('message.Paid') }}</option>
                                    </select>
                                </div>
                            </div>

                            <!-- Filter for select customer Start-->
                            <div class="row col-md-6 col-lg-6 col-xl-6 col-xxl-6 col-sm-6 col-xs-6">
                                <label class="control-label col-md-4 col-lg-4 col-xl-4 col-xxl-4 col-sm-4 col-xs-4 currency customer_select_padding" for="option">{{ trans('message.Select Customer') }} </label>
                                <div class="col-md-8 col-lg-8 col-xl-8 col-xxl-8 col-sm-8 col-xs-8">
                                    <select class="form-control customername_selectbox form-select" name="select_customername" id="customerNameOptions" cus_url="{!! url('report/get_cust_name') !!}"  @if(getLangCode() == 'ar') style="direction: ltr !important; text-align: left !important;" @endif>
                                        <option value="">{{ trans('message.Select Customer') }}</option>
                                        @if (!empty($customers))
                                        @foreach ($customers as $customer)
                                        <option value="{{ $customer->id }}" <?php if ($customer->id == $select_customerId) {
                                                                                echo 'selected';
                                                                            } ?>>
                                            {{ getCustomerName($customer->id) }}-{{ getCustomerMobile($customer->id) }}
                                        </option>
                                        @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>
                        </div>
                        <!-- Filter for select customer End-->

                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <div class="row mt-3">
                            <div class="col-md-12 col-lg-12 col-xl-12 col-xxl-12 col-sm-12 col-xs-12 text-lg-end">
                                <button type="submit" class="btn btn-success colorname">{{ trans('message.Go') }}</button>
                                <button type="button" class="btn btn-success" id="chartshow">{{ trans('message.View Chart') }}</button>
                            </div>
                            <div class="text-end mt-3">
                                <p class="btn btn-success">{{ trans('message.Total Amount')}}({{ getCurrencySymbols() }}) : {{ $totalAmount }}</p>
                            </div>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>

    @if (!empty($datas))
    <div class="row">
        <div id="chartdiv" style="visibility:hidden;height:0;float:left;width:100%;">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel tab_bottom">
                    <div id="service_report"></div>
                </div>
            </div>
        </div>
    </div>
    @endif


    @if(!empty($servicereport) && count($servicereport) > 0)
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="x_panel table_up_div">
                <table id="supplier" class="table jambo_table"> 
                    <thead>
                        <tr>
                            <th> </th>
                            <th>{{ trans('message.Job No') }}.</th>
                            <th>{{ trans('message.Customer Name') }}</th>
                            <th>{{ trans('message.Date') }}</th>
                            <th>{{ trans('message.Vehicle Name') }}</th>
                            <th>{{ trans('message.Service Type') }}</th>
                            <th>{{ trans('message.Paid Amount') }} ({{ getCurrencySymbols() }})</th>
                            <th>{{ trans('message.Assign To') }}</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php $i = 1; ?>
                        @if (!empty($servicereport))
                        @foreach ($servicereport as $servicereports)
                        <tr>
                            <td>{{ $i }}</td>
                            <td>{{ $servicereports->job_no }}
                                <!-- <a data-toggle="tooltip" data-placement="bottom" title="Job No." class="text-primary"><i class="fa fa-info-circle" style="color:#D9D9D9"></i></a> -->
                            </td>
                            <td>{{ getCustomerName($servicereports->customer_id) }}
                                <!-- <a data-toggle="tooltip" data-placement="bottom" title="Customer Name" class="text-primary"><i class="fa fa-info-circle" style="color:#D9D9D9"></i></a> -->
                            </td>
                            <td>{{ date(getDateFormat(), strtotime($servicereports->service_date)) }}
                                <!-- <a data-toggle="tooltip" data-placement="bottom" title="Date" class="text-primary"><i class="fa fa-info-circle" style="color:#D9D9D9"></i></a> -->
                            </td>
                            <td>{{ getVehicleName($servicereports->vehicle_id) }}
                                <!-- <a data-toggle="tooltip" data-placement="bottom" title="Vehicle Name" class="text-primary"><i class="fa fa-info-circle" style="color:#D9D9D9"></i></a> -->
                            </td>
                            <td>{{ ucfirst($servicereports->service_type) }}
                                <!-- <a data-toggle="tooltip" data-placement="bottom" title="Service Type" class="text-primary"><i class="fa fa-info-circle" style="color:#D9D9D9"></i></a> -->
                            </td>
                            <td>{{ number_format(getPaidAmount($servicereports->job_no), 2) }}
                                <!-- <a data-toggle="tooltip" data-placement="bottom" title="Paid Amount( <?php echo getCurrencySymbols(); ?> )" class="text-primary"><i class="fa fa-info-circle" style="color:#D9D9D9"></i></a> -->
                            </td>
                            <td>{{ getAssignedName($servicereports->assign_to) }}
                                <!-- <a data-toggle="tooltip" data-placement="bottom" title="Assign To" class="text-primary"><i class="fa fa-info-circle" style="color:#D9D9D9"></i></a> -->
                            </td>
                        </tr>
                        <?php $i++; ?>
                        @endforeach
                        @endif
                    </tbody>
                </table>
            </div>

        </div>

    </div>
    @else
    <p class="d-flex justify-content-center"><img src="{{ URL::asset('public/img/dashboard/No-Data.png') }}" width="300px"></p>
    @endif
</div>
<!-- content page end -->

<!-- <script nonce="{{ $cspNonce }}" src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script> -->
<script nonce="{{ $cspNonce }}" src="{{ URL::asset('vendors/jquery/jquery-3.7.1.min.js') }}"></script>

<script nonce="{{ $cspNonce }}" src="{{ URL::asset('public/js/49/loader.js') }}" defer="defer"></script>

<script nonce="{{ $cspNonce }}">
    $(document).ready(function() {
        // Initialize Select2 on the dropdown
        $('#customerNameOptions').select2({
            placeholder: "{{ trans('message.Select Customer') }}",
            allowClear: true, // Adds a clear button
            width: '100%' // Ensures the dropdown matches the input width
        });
    });
    document.getElementById('chartshow').addEventListener('click', function() {
        myFunction();
    });
</script>
<!-- language change in user selected -->
<script nonce="{{ $cspNonce }}">
    $(document).ready(function() {
        var pdf = "{{ trans('message.PDF') }}";
        var print = "{{ trans('message.print') }}";
        var excel = "{{ trans('message.excel') }}";
        var search = "{{ trans('message.Search...') }}";
        var info = "{{ trans('message.Showing page _PAGE_ - _PAGES_') }}";
        var zeroRecords = "{{ trans('message.No Data Found') }}";
        var infoEmpty = "{{ trans('message.No records available') }}";

        // For get getParameterByName
        function getParameterByName(name, url = window.location.href) {
            name = name.replace(/[\[\]]/g, '\\$&');
            var regex = new RegExp('[?&]' + name + '(=([^&#]*)|&|#|$)'),
                results = regex.exec(url);
            if (!results) return null;
            if (!results[2]) return '';
            return decodeURIComponent(results[2].replace(/\+/g, ' '));
        }
        var currentPageAction = getParameterByName('page_action');
        var buttons = [{
                extend: 'pdf',
                text: pdf,
            },
            {
                extend: 'print',
                text: print
            },
            {
                extend: 'excel',
                text: excel
            },
        ];

        
        
            buttons[0].action = function() {
                // Get DataTable instance
                var table = $('#supplier').DataTable();

                // Get column names
                var columnNames = table.columns().header().toArray().map(function(header) {
                    return $(header).text();
                });
                console.log([columnNames]);
                // Get rows data
                var tableData = table.rows().data().toArray();
               
                // Make AJAX request
                $.ajax({
                    url: "{{ url('/report/generate_pdf') }}",
                    type: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        tableData: tableData,
                        columnNames: columnNames
                    },
                    success: function(response) {
                        // Trigger PDF download
                        const link = document.createElement('a');
                        link.href = response.pdfPath;
                        link.download = 'Garage_Report.pdf'; // Set desired filename
                        document.body.appendChild(link);
                        link.click();
                        document.body.removeChild(link);
                    },
                    error: function(xhr, status, error) {
                        console.error(error);
                    }
                });
            };
        

        $('#supplier').DataTable({
            columnDefs: [{
                width: 2,
                targets: 0
            }],
            fixedColumns: true,
            paging: true,
            scrollCollapse: true,
            scrollX: true,
            // scrollY: 300,

            responsive: true,
            dom: 'Bfrtip',
            buttons: buttons,
            pagingType: 'simple_numbers',
            "language": {
                search: '',
                searchPlaceholder: search,
                lengthMenu: "_MENU_  ",
                info: info,
                zeroRecords: zeroRecords,
                infoEmpty: infoEmpty,
                infoFiltered: '(filtered from _MAX_ total records)',
                paginate: {
                    previous: "<",
                    next: ">",
                }
            },
            aoColumnDefs: [{
                bSortable: false,
                aTargets: [-1]
            }]
        });
    });
</script>


<!-- All Js file for Charts -->

<script nonce="{{ $cspNonce }}" type="text/javascript">
    <?php if (!empty($chart)) {
        echo $chart;
    } ?>
</script>

<script nonce="{{ $cspNonce }}">
    $(document).ready(function() {
        $(".datepicker").datetimepicker({
            format: "<?php echo getDatepicker(); ?>",
            todayBtn: true,
            autoclose: 1,
            minView: 2,
            endDate: new Date(),
            language: "{{ getLangCode() }}",
        });
        $(".colorname").click(function() {
            var start_date = $('#start_date').val();
            var end_date = $('#end_date').val();
            if (start_date === "") {
                $('#common_error_span').css({
                    "display": ""
                });
            } else {
                $('#common_error_span').css({
                    "display": "none"
                });
                return true;
            }
            if (end_date === "") {
                $('#common_error_span_end').css({
                    "display": ""
                });
                return false;
            } else {
                $('#common_error_span_end').css({
                    "display": "none"
                });
                return true;
            }
        });
        $('body').on('change', '#start_date', function() {
            var start_date = $('#start_date').val();
            if (start_date === "") {
                $('#common_error_span').css({
                    "display": ""
                });
            } else {
                $('#common_error_span').css({
                    "display": "none"
                });
            }
        });
        $('body').on('change', '#end_date', function() {
            var end_date = $('#end_date').val();
            if (end_date === "") {
                $('#common_error_span_end').css({
                    "display": ""
                });
            } else {
                $('#common_error_span_end').css({
                    "display": "none"
                });
            }
        });

        $(".start_date,.input-group-addon").click(function() {
            var dateend = $('#end_date').val('');
        });


        $(".start_date,.input-group-addon").click(function() {
            var dateend = $('#end_date').val('');
        });

        $(".start_date").datetimepicker({
                format: "<?php echo getDatepicker(); ?>",
                minView: 2,
                autoclose: 1,
                language: "{{ getLangCode() }}",
            }).on('changeDate', function(selected) {
                var startDate = new Date(selected.date.valueOf());

                $('.end_date').datetimepicker({
                    format: "<?php echo getDatepicker(); ?>",
                    minView: 2,
                    autoclose: 1,
                    language: "{{ getLangCode() }}",
                }).datetimepicker('setStartDate', startDate);
            })
            .on('clearDate', function(selected) {
                $('.end_date').datetimepicker('setStartDate', null);
            })

        $(".start_date").on("dp.change", function(e) {
            $('.end_date').data("DateTimePicker").minDate(e.date);

        });
        $(".end_date").on("dp.change", function(e) {
            $('.start_date').data("DateTimePicker").maxDate(e.date);
        });

        $('.end_date').click(function() {

            var date = $('#start_date').val();
            var msg1 = "{{ trans('message.First Select Start Date') }}";
            var msg35 = "{{ trans('message.OK') }}";
            if (date == '') {
                swal({
                    title: msg1,
                    cancelButtonColor: '#C1C1C1',
                    buttons: {
                        cancel: msg35,
                    },
                    dangerMode: true,
                });
            } else {
                $('.end_date').datetimepicker({
                    format: "<?php echo getDatepicker(); ?>",
                })
            }
        });
        google.charts.load("current", {
            packages: ['corechart']
        });
        google.charts.setOnLoadCallback(drawChart);

        function drawChart() {

            var chart_report = [];
            chart_report = <?php echo json_encode($chart_array); ?>;
            var option = <?php echo json_encode($options); ?>;
            var data = google.visualization.arrayToDataTable(chart_report);

            var view = new google.visualization.DataView(data);
            view.setColumns([0, 1,
                {
                    calc: "stringify",
                    sourceColumn: 1,
                    type: "string",
                    role: "annotation"
                },
                2
            ]);


            var chart = new google.visualization.ColumnChart(document.getElementById("service_report"));
            chart.draw(view, option);
        }

        // Initialize select2
        // $("#customerNameOptions").select2();
    });


    function myFunction() {
        var service = '<?php echo $chartStatus; ?>';
        if (service === 'yes') {
            var x = document.getElementById("chartdiv");
            if (x.style.visibility === "hidden") {
                x.style.visibility = "inherit";
                x.style.height = "auto";
                x.style.float = "left";
                x.style.width = "100%";

            } else {
                x.style.visibility = "hidden";
                x.style.height = "0";
                x.style.float = "left";
                x.style.width = "100%";
            }
        } else {
            var msg10 = "{{ trans('message.Data not available to display on chart.') }}";
            var msg36 = "{{ trans('message.OK') }}";
            swal({
                title: msg10,
                cancelButtonColor: '#C1C1C1',
                buttons: {
                    cancel: msg36,
                },
                dangerMode: true,
            });
        }

    }
</script>
<script nonce="{{ $cspNonce }}" type="text/javascript"></script>
<script nonce="{{ $cspNonce }}" src="{{ URL::asset('public/js/49/loader.js') }}" defer="defer"></script>

@endsection