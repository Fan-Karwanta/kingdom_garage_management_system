@extends('layouts.app')
@section('content')
<?php if (
  getLangCode() == 'de' ||
  getLangCode() == 'gr' ||
  getLangCode() == 'ger' ||
  getLangCode() == 'pt' ||
  getLangCode() == 'fr' ||
  getLangCode() == 'cs'
) {
?>
  <style>
    @media (max-width: 320px) {
      ul.bar_tabs>li {
        margin-top: 1px !important;
        margin-left: 0px !important;
      }

      .x_panel {
        margin-top: 40px !important;
      }
    }

    @media (max-width: 360px) {
      ul.bar_tabs>li {
        margin-top: 1px !important;
        margin-left: 0px !important;
      }

      .x_panel {
        margin-top: 40px !important;
      }
    }
  </style>
<?php
} ?>
<?php if (
  getLangCode() == 'id' ||
  getLangCode() == 'ru' ||
  getLangCode() == 'vi' ||
  getlangCode() == 'ta'
) {
?>
  <style>
    @supports (-webkit-touch-callout: none) {
      ul.bar_tabs>li {
        margin-top: 1px !important;
        margin-left: 0px !important;
      }

      .x_panel {
        margin-top: 40px !important;
      }
    }

    @media (max-width: 320px) {
      ul.bar_tabs>li {
        margin-top: 1px !important;
        margin-left: 0px !important;
      }

      .x_panel {
        margin-top: 40px !important;
      }
    }

    @media (max-width: 360px) {
      ul.bar_tabs>li {
        margin-top: 1px !important;
        margin-left: 0px !important;
      }

      .x_panel {
        margin-top: 40px !important;
      }
    }
  </style>
<?php
}
?>
<style>
  .col-sm-12 {
    float: none;
  }

  @media screen and (max-width:540px) {
    div#customer_info {
      margin-top: -277px;
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
              &nbsp;<a id="menu_toggle"><i class="fa fa-bars sidemenu_toggle"></i></a><span class="titleup me-2">{{ trans('message.Customers') }}</span>
              @can('customer_add')
              <a href="{!! url('/customer/add') !!}" id="" class="addbotton">
                <img src="{{ URL::asset('public/img/icons/plus Button.png') }}">
              </a>
              @endcan
            </span>
          </div>
          @include('dashboard.profile')
        </nav>
      </div>
    </div>
  </div>
  @include('success_message.message')
  {{-- @if (session('message'))
      <div class="row massage">
        <div class="col-md-12 col-sm-12 col-xs-12">
          <div class="checkbox checkbox-success checkbox-circle mb-2">
            @if (session('message') == 'Successfully Submitted')
              <label for="checkbox-10 colo_success"> {{ trans('message.Successfully Submitted') }} </label>
  @elseif(session('message') == 'Successfully Updated')
  <label for="checkbox-10 colo_success"> {{ trans('message.Successfully Updated') }} </label>
  @elseif(session('message') == 'Successfully Deleted')
  <label for="checkbox-10 colo_success"> {{ trans('message.Successfully Deleted') }} </label>
  @endif
</div>
</div>
</div>
@endif --}}
<div class="row">
  <div class="col-md-12 col-sm-12 col-xs-12">
    {{-- <div class="x_content">
          <ul class="nav nav-tabs bar_tabs"
            role="tablist">
            @can('customer_view')
              <li role="presentation"
                class="active"><a href="{!! url('/customer/list') !!}"><span class="visible-xs"></span><i
                    class="fa fa-list fa-lg">&nbsp;</i><b>{{ trans('message.Customer List') }}</b></a></li>
    @endcan

    @can('customer_add')
    <li role="presentation" class=""><a href="{!! url('/customer/add') !!}"><span class="visible-xs"></span><i class="fa fa-plus-circle fa-lg">&nbsp;</i> {{ trans('message.Add Customer') }}</a></li>
    @endcan

    </ul>
  </div> --}}
  @if(!empty($customer) && count($customer) > 0)
  <div class="x_panel bgr">
    <table id="supplier" class="table responsive jumbo_table" style="width:100%">
      <thead>
        <tr>
          @can('customer_delete')
          <th> </th>
          @endcan
          <th>{{ trans('message.Image') }}</th>
          <th>{{ trans('message.First Name') }}</th>
          <th>{{ trans('message.Last Name') }}</th>
          <th>{{ trans('message.Email') }}</th>
          <th>{{ trans('message.Mobile Number') }}</th>
          <th>{{ trans('message.Vehicle List') }}</th>
          <th>{{ trans('message.Action') }}</th>
        </tr>
      </thead>
      <tbody>
       
      </tbody>
    </table>
    @can('customer_delete')
    <button id="select-all-btn" class="btn select_all"><input type="checkbox" name="selectAll"> {{ trans('message.Select All') }}</button>
    <button id="delete-selected-btn" class="btn btn-danger text-white border-0" data-url="{!! url('/customer/list/delete/') !!}"><i class="fa fa-trash" aria-hidden="true"></i></button>
    @endcan
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
        url: '{{ url("/customer/list") }}',
        type: 'GET',
    },
    columns: [
        @can('customer_delete')
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
            data: 'name', // Assuming 'first_name' is the column name in the database
            name: 'name'
        },
        {
            data: 'lastname', // Assuming 'last_name' is the column name in the database
            name: 'lastname'
        },
        {
            data: 'email',
            name: 'email'
        },
        {
            data: 'mobile_no',
            name: 'mobile_no'
        },
        {
            data: 'vehicle_list',
            name: 'vehicle_list',
            searchable: false

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
            next: ">",
        },
        processing: '<div class="loading-indicator"><i class="fa fa-spinner fa-spin fa-3x fa-fw"></i><span class="sr-only">Loading...</span></div>'
    },
    order: [[2, 'asc']],  // Ordering by first name
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
    
    $('body').on('click', '.deletecustomers', function() {
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
                  swal({
                          title: "{{ trans('message.Deleted!') }}",
                          text: response.message,
                          icon: "success"
                      });
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