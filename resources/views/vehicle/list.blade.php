@extends('layouts.app')
@section('content')
<style>
  @media screen and (max-width:540px) {
    div#vehicle_info {
      margin-top: -169px;
    }
     div.dataTables_processing{
      display: none !important;
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
        color:#EA6B00;
        margin-left: -100px;
        margin-top: -26px;
        text-align: center;
        padding: 3px 0;
        border: none;
    }
</style>
<!-- page content -->
<div class="right_col" role="main">
  <div class="">
    <div class="page-title">
      <div class="nav_menu">
        <nav>
          <div class="nav toggle">
            <a id="menu_toggle"><i class="fa fa-bars sidemenu_toggle"></i></a><span class="titleup">{{ trans('message.Vehicles') }}</a>
              @can('vehicle_add')
              <a href="{!! url('/vehicle/add') !!}" id="" class="addbotton">
                <img src="{{ URL::asset('public/img/icons/plus Button.png') }}">
              </a>
            </span>
            @endcan
          </div>

          @include('dashboard.profile')
        </nav>
      </div>
    </div>
    @include('success_message.message')
    <div class="row">
    @if(!empty($vehicals) && count($vehicals) > 0)
      <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel mb-0">
          <table id="supplier" class="table jambo_table" style="width:100%">
            <thead>
              <tr>
                @can('vehicle_delete')
                <th> </th>
                @endcan
                <th>{{ trans('message.Image') }}</th>
                <th>{{ trans('message.Type') }}</th>
                <th>{{ trans('message.Model Name') }}</th>
                <th>{{ trans('message.Number Plate') }}</th>
                <th>{{ trans('message.Customer Name') }}</th>
                <!-- <th>{{ trans('message.Price') }} (<?php echo getCurrencySymbols(); ?>)</th> -->
                <th>{{ trans('message.Date Of Manufacturing') }}</th>
                <th>{{ trans('message.Last Service Date') }}</th>
                <th>{{ trans('message.Upcoming Service Date') }}</th>
                <th>{{ trans('message.Engine No') }}</th>
                <th>{{ trans('message.Action') }}</th>
              </tr>
            </thead>
            <tbody>
              <!-- server side datatable- controller vehicle list method -->
            <tbody>
            </tbody>
          </table>
          @can('vehicle_delete')
          <button id="select-all-btn" class="btn select_all"><input type="checkbox" name="selectAll"> {{ trans('message.Select All') }}</button>
          <button id="delete-selected-btn" class="btn btn-danger text-white border-0" data-url="{!! url('/vehicle/list/delete/') !!}"><i class="fa fa-trash" aria-hidden="true"></i></button>
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
    // $('#supplier').DataTable({

    //   columnDefs: [{
    //     width: 2,
    //     targets: 0
    //   }],
    //   fixedColumns: true,
    //   paging: true,
    //   scrollCollapse: true,
    //   scrollX: true,
    //   // scrollY: 300,

    //   responsive: true,
    //   "language": {
    //     lengthMenu: "_MENU_ ",
    //     info: info,
    //     zeroRecords: zeroRecords,
    //     infoEmpty: infoEmpty,
    //     infoFiltered: '(filtered from _MAX_ total records)',
    //     searchPlaceholder: search,
    //     search: '',
    //     paginate: {
    //       previous: "<",
    //       next: ">",
    //     }
    //   },
    //   aoColumnDefs: [{
    //     bSortable: false,
    //     aTargets: [-1]
    //   }],
    // });
    $('#supplier').DataTable({
    processing: true,
    serverSide: true,
    ajax: {
        url: '{{ url("/vehicle/list") }}',
        type: 'GET',
    },
    columns: [
        @can('vehicle_delete')
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
            data: 'image',
            name: 'image',
            orderable: false,
            searchable: false
        },
        {
            data: 'vehicle_type',
            name: 'vehicle_type',
        },
        {
            data: 'modelname',
            name: 'modelname',
        },
        {
            data: 'number_plate',
            name: 'number_plate',
        },
        {
            data: 'customer_name',
            name: 'customer_name',
        },
        {
            data: 'dom',
            name: 'dom',
        },
        {
            data: 'lastServiceDate',
            name: 'lastServiceDate',
           
        },
        {
            data: 'upcomingServiceDate',
            name: 'upcomingServiceDate',
          
        },
        {
            data: 'engineno',
            name: 'engineno',
        },
        {
            data: 'action',
            name: 'action',
            orderable: false,
            searchable: false,
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
            next: ">",
        },
        processing: '<div class="loading-indicator"><i class="fa fa-spinner fa-spin fa-3x fa-fw"></i><span class="sr-only">Loading...</span></div>'
    },
    order: [[2, 'asc']],
    responsive: true,
    fixedColumns: true,
    paging: true,
    scrollCollapse: true,
    scrollX: true,
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

    /*delete vehical*/
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
@endsection