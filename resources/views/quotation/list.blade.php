@extends('layouts.app')
@section('content')
<style>
    @media screen and (max-width:540px) {
        div#quotation_info {
            margin-top: -177px;
        }

        span.titleup {
            /* margin-left: -10px; */
        }
    }
</style>
<!-- page content -->
<div class="right_col" role="main">
    <div id="myModal" class="modal fade" role="dialog">
        <div class="modal-dialog modal-lg">
            <!-- Modal content-->
            <div class="modal-content modal_data">
            </div>
        </div>
    </div>

    <!-- Modal for Coupon Data -->
    <div class="modal fade" id="coupaon_data" role="dialog">
        <div class="modal-dialog modal-lg">
            <div class="modal-content used_coupn_modal_data">

            </div>
        </div>
    </div>
    <!-- End Modal for Coupon Data -->
    <div class="">
        <div class="page-title">
            <div class="nav_menu">
                <nav>
                    <div class="nav toggle">
                        <a id="menu_toggle"><i class="fa fa-bars sidemenu_toggle"></i></a><span class="titleup">{{ trans('message.Quotation') }}
                            @can('quotation_add')
                            <a href="{!! url('/quotation/add') !!}" id="" class="addbotton">
                                <img src="{{ URL::asset('public/img/icons/plus Button.png') }}" class="">
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
            @if(!empty($service) && count($service) > 0)
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel table_up_div mb-0">
                    <table id="supplier" class="table jambo_table" style="width:100%">
                        <thead>
                            <tr>
                                @can('quotation_delete')
                                <th> </th>
                                @endcan
                                <th>{{ trans('message.Quotation No') }}</th>
                                <th>{{ trans('message.Customer Name') }}</th>
                                <th>{{ trans('message.Date') }}</th>
                                <th>{{ trans('message.Service Category') }}</th>
                                <!-- <th>{{ trans('message.Assign To') }}</th> -->
                                <th>{{ trans('message.Number Plate') }}</th>
                                <th>{{ trans('message.Price') }} ({{ getCurrencySymbols() }})</th>
                                <th>{{ trans('message.Status') }}</th>
                                <th>{{ trans('message.Action') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if (!empty($service))
                            <?php $i = 1; ?>
                            @foreach ($service as $services)
                            <tr data-user-id="{{ $services->id }}">
                                @can('quotation_delete')
                                <td>
                                    <label class="container checkbox">
                                        <input type="checkbox" name="chk">
                                        <span class="checkmark"></span>
                                    </label>
                                </td>
                                @endcan

                                <td><a data-bs-toggle="modal" data-bs-target="#myModal" serviceid="{{ $services->id }}" url="{!! url('/quotation/list/view') !!}" class="save">{{ getQuotationNumber($services->job_no) }}</a></td>
                                <td><a data-bs-toggle="modal" data-bs-target="#myModal" serviceid="{{ $services->id }}" url="{!! url('/quotation/list/view') !!}" class="save">{{ getCustomerName($services->customer_id) }}</a></td>
                                <?php $date_db = date('Y-m-d', strtotime($services->service_date)); ?>
                                @if (!empty($current_month) && strpos($available, $date_db) !== false)
                                <td><span class="label label-danger">{{ date(getDateFormat(), strtotime($date_db)) }}</span>
                                </td>
                                @else
                                <td> {{ date(getDateFormat(), strtotime($date_db)) }}</td>
                                @endif
                                <td>{{ $services->service_category }}</td>
                                <!-- <td>{{ getAssignTo($services->assign_to) }}</td> -->
                                <td>{{ getVehicleNumberPlate($services->vehicle_id) ?? trans('message.Not Added') }}
                                </td>
                                <td>{{ number_format(getTotalPriceOfQuotation($services->id), 2) }}</td>
                                <?php $coupon = getAllCoupon($services->customer_id, $services->vehicle_id);
                                ?>
                                <td>
                                    <?php
                                    if ($services->is_appove === null) {
                                        echo '<span style="color: rgb(255, 165, 0);">' . trans('message.Pending') . '</span>';
                                    } elseif ($services->is_appove == 0) {
                                        echo '<span style="color: rgb(255, 0, 0);">' . trans('message.Rejected') . '</span>';
                                    } elseif ($services->is_appove == 1) {
                                        echo '<span style="color: rgb(0, 128, 0);">' . trans('message.Accepted') . '</span>';
                                    }
                                    ?>
                                </td>
                                <td>
                                    <div class="dropdown_toggle">
                                        <img src="{{ URL::asset('public/img/list/dots.png') }}" class="btn dropdown-toggle border-0" type="button" id="dropdownMenuButtonaction" data-bs-toggle="dropdown" aria-expanded="false">

                                        <ul class="dropdown-menu heder-dropdown-menu action_dropdown shadow py-2" aria-labelledby="dropdownMenuButtonaction">
                                            @can('quotation_view')
                                            <li class="px-2"><button type="button" data-bs-toggle="modal" data-bs-target="#myModal" serviceid="{{ $services->id }}" url="{!! url('/quotation/list/view') !!}" class="btn border-0 save dropdown-item p-2"><img src="{{ URL::asset('public/img/list/Vector.png') }}" class="me-3">{{ trans('message.View') }}</button></li>
                                            @endcan

                                            @can('quotation_edit')
                                            @if ($services->quotation_modify_status == 2)
                                            <li><a class="dropdown-item" href="{!! url('/quotation/list/edit/' . $services->id) !!}"><img src="{{ URL::asset('public/img/list/Edit.png') }}" class="me-3"> {{ trans('message.Edit') }}</a></li>
                                            @else
                                            <li><a class="dropdown-item" href="{!! url('/quotation/list/edit/' . $services->id) !!}"><img src="{{ URL::asset('public/img/list/Edit.png') }}" class="me-3"> {{ trans('message.Edit') }}</a></li>
                                            @endif
                                            <!-- @if ($services->quotation_modify_status == 1)
                                            <li><a href="{{ url('quotation/list/modify/' . $services->id) }}" class="dropdown-item"><img src="{{ URL::asset('public/img/list/process.png') }}" class="me-3">{{ trans('message.Quotation Process') }}</button></a></li>
                                            @elseif($services->quotation_modify_status == 2)
                                            <li><a href="{{ url('quotation/list/modify/' . $services->id) }}" class="dropdown-item"><img src="{{ URL::asset('public/img/list/process.png') }}" class="me-3">{{ trans('message.Quotation Process') }}</button></a></li>
                                            @endif -->
                                            @if($services->is_appove == 1)
                                            @if ($services->quotation_modify_status == 1)
                                            <li><a class="dropdown-item sa-final" url="{{ url('/quotation/list/final/' . $services->id) }}"><img src="{{ URL::asset('public/img/list/process.png') }}" class="me-3">{{ trans('message.Finalize Quotation') }}</button></a></li>
                                            @elseif($services->quotation_modify_status == 2)
                                            <li><a class="dropdown-item sa-final" url="{{ url('/quotation/list/final/' . $services->id) }}"><img src="{{ URL::asset('public/img/list/process.png') }}" class="me-3">{{ trans('message.Finalize Quotation') }}</button></a></li>
                                            @endif
                                            @endif
                                            @endcan

                                            <li><a href="{{ route('quotationStatus', ['id' => $services->id]) }}" class="dropdown-item"><img src="{{ URL::asset('public/img/list/process.png') }}" class="me-3">{{ trans('message.Status') }}</a></li>
                                            <li><a href="{{ route('quotationSend', ['service_id' => $services->id]) }}" target="_blank" class="dropdown-item"><img src="{{ URL::asset('public/img/list/WhatsApp.png') }}" class="me-3">{{ trans('message.Share on WhatsApp') }}</a></li>
                                            @can('quotation_delete')
                                            <div class="dropdown-divider m-0"></div>
                                            <li><a class="dropdown-item deletedatas" url="{!! url('/quotation/list/delete/' . $services->id) !!}" style="color:#FD726A"><img src="{{ URL::asset('public/img/list/Delete.png') }}" class="me-3">{{ trans('message.Delete') }}</a></li>
                                            @endcan
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                            <?php $i++; ?>
                            @endforeach
                            @endif
                        </tbody>
                    </table>
                    @can('quotation_delete')
                    <button id="select-all-btn" class="btn select_all"><input type="checkbox" name="selectAll"> {{ trans('message.Select All') }}</button>
                    <button id="delete-selected-btn" class="btn btn-danger text-white border-0" data-url="{!! url('/quotation/list/delete/') !!}"><i class="fa fa-trash" aria-hidden="true"></i></button>
                    @endcan
                </div>
            </div>
            @else
            <p class="d-flex justify-content-center mt-5 pt-5"><img src="{{ URL::asset('public/img/dashboard/No-Data.png') }}" width="300px"></p>
            @endif
        </div>
    </div>
</div>
<!-- /page content -->


<!-- Scripts starting -->
<script nonce="{{ $cspNonce }}" src="{{ URL::asset('vendors/jquery/dist/jquery.min.js') }}"></script>
<!-- language change in user selected -->
<script nonce="{{ $cspNonce }}">
    $(document).ready(function() {

        var search = "{{ trans('message.Search...') }}";
        var info = "{{ trans('message.Showing page _PAGE_ - _PAGES_') }}";
        var zeroRecords = "{{ trans('message.No Data Found') }}";
        var infoEmpty = "{{ trans('message.No records available') }}";
        var filtermessage = "{{trans('message.filtered from _MAX_ total records')}}";
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
            "language": {
                lengthMenu: "_MENU_ ",
                info: info,
                zeroRecords: zeroRecords,
                infoEmpty: infoEmpty,
                infoFiltered: filtermessage,
                searchPlaceholder: search,
                search: '',
                paginate: {
                    previous: "<",
                    next: ">",
                }
            },
            aoColumnDefs: [{
                bSortable: false,
                aTargets: [-1]
            }],
            order: [
                [1, 'desc']
            ]
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
        /*delete service*/
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

        /*Finalize Quotation*/
        $('body').on('click', '.sa-final', function() {

            var url = $(this).attr('url');
            var msg1 = "{{ trans('message.Are you sure you want to finalize this quotation?') }}";
            var msg2 = "{{ trans('message.On your confirmation  system will generate new Job Card automatically.') }}";
            var msg3 = "{{ trans('message.Cancel') }}";
            var msg4 = "{{ trans('message.Yes, Final!') }}";

            swal({
                title: msg1,
                text: msg2,
                icon: 'warning',
                cancelButtonColor: '#C1C1C1',
                buttons: [msg3, msg4],
                dangerMode: true,
            }).then((willFinal) => {
                if (willFinal) {
                    window.location.href = url;
                }
            });
        });


        $('body').on('click', '.save', function() {
            var servicesid = $(this).attr("serviceid");
            var msg10 = "{{ trans('message.An error occurred :') }}";
            var url = $(this).attr('url');
            var currentPageAction = getParameterByName('page_action');

            // Construct the URL for AJAX request with page_action parameter
            if (currentPageAction) {
                url += '?page_action=' + currentPageAction;
            }

            $.ajax({
                type: 'GET',
                url: url,
                data: {
                    servicesid: servicesid
                },
                dataType: 'json',
                success: function(data) {
                    $('.modal_data').html(data.html);
                },
                error: function(e) {
                    alert(msg10 + " " + e.responseText);
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


        $('body').on('click', '.coupon_btn', function() {
            var coupon_no = $(this).attr('coupon_no');
            var ser_id = $(this).attr('servi_id');
            var url = $(this).attr('url');

            $.ajax({
                url: url,
                type: 'GET',
                data: {
                    coupon_no: coupon_no,
                    ser_id: ser_id
                },
                success: function(response) {
                    $('.used_coupn_modal_data').html(response.html);
                },
                erro: function(e) {
                    console.log(e);
                }
            });
        });
    });
</script>
@endsection
