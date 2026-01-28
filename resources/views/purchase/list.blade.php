@extends('layouts.app')
@section('content')
<style>
  @media screen and (max-width:540px) {
    /* div#purchase_info {
      margin-top: -177px;
    } */

    span.titleup {
      margin-left: -10px;
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
    @media only screen and (max-width: 600px) {
      div.dataTables_processing {
        position: fixed;
        width: 150px;
        top:30%;
        left:60%;
        color:#EA6B00;
        background: none;
        text-align: center;
        padding: 3px 0;
        border: none;
      }
    }
</style>
<!-- page content -->
<div class="right_col" role="main">
  <div id="purchaseview" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg">
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title"> {{ getNameSystem() }} </h4>
          <!-- <h4 id="myLargeModalLabel" class="modal-title">{{ trans('message.Purchase') }}</h4> -->
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">

        </div>
      </div>
    </div>
  </div>
  <div class="">
    <div class="page-title">
      <div class="nav_menu">
        <nav>
          <div class="nav toggle">
            <a id="menu_toggle"><i class="fa fa-bars sidemenu_toggle"></i></a><span class="titleup">&nbsp;{{ trans('message.Purchase') }}
              @can('purchase_add')
              <a href="{!! url('/purchase/add') !!}" id="" class="addbotton">
                <img src="{{ URL::asset('public/img/icons/plus Button.png') }}" class="">
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
  <div class="row">
    @if(!empty($purchase) && count($purchase) > 0)
    <div class="col-md-12 col-sm-12 col-xs-12" style="float: none;">
      <div class="x_panel bgr">
        <table id="supplier" class="example table responsive-utilities jambo_table" style="width:100%">
          <thead>
            <tr>
              @can('purchase_delete')
              <th> </th>
              @endcan
              <th>{{ trans('message.Purchase Code') }}</th>
              <th>{{ trans('message.Supplier Name') }}</th>
              <th>{{ trans('message.Email') }}</th>
              <th>{{ trans('message.Mobile') }}</th>
              <th>{{ trans('message.Date') }}</th>
              <th>{{ trans('message.Products') }}</th>
              <th>{{ trans('message.Action') }}</th>
            </tr>
          </thead>
          <tbody>
            
          </tbody>
        </table>
        @can('purchase_delete')
        <button id="select-all-btn" class="btn select_all"><input type="checkbox" name="selectAll"> {{ trans('message.Select All') }}</button>
        <button id="delete-selected-btn" class="btn btn-danger text-white border-0" data-url="{!! url('/purchase/list/delete/') !!}"><i class="fa fa-trash" aria-hidden="true"></i></button>
        @endcan

      </div>
    </div>
    @else
    <p class="d-flex justify-content-center mt-5 pt-5"><img src="{{ URL::asset('public/img/dashboard/No-Data.png') }}" width="300px"></p>
    @endif
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
    //   "search": {
    //     "search": "",
    //   },
    //   pagingType: 'simple_numbers',
    //   "language": {
    //     search: '',
    //     searchPlaceholder: search,
    //     lengthMenu: "_MENU_  ",
    //     info: info,
    //     zeroRecords: zeroRecords,
    //     infoEmpty: infoEmpty,
    //     infoFiltered: '(filtered from _MAX_ total records)',
    //     paginate: {
    //       previous: "<",
    //       next: ">",
    //     }
    //   },
    //   aoColumnDefs: [{
    //     bSortable: false,
    //     aTargets: [-1]
    //   }],
    //   order: [
    //     [5, 'desc']
    //   ]
    // });
    $('#supplier').DataTable({
    processing: true,
    serverSide: true,
    ajax: {
        url: '{{ url("/purchase/list") }}',
        type: 'GET',
    },
    columns: [
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
        {
            data: 'purchase_no',
            name: 'purchase_no'
        },
        {
            data: 'supplier_name',
            name: 'supplier_id'
        },
        {
            data: 'email',
            name: 'email'
        },
        {
            data: 'mobile',
            name: 'mobile'
        },
        {
            data: 'date',
            name: 'date'
        },
        {
            data: 'products',
            name: 'products'
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
        searchPlaceholder: search,
        search: '',
        paginate: {
            previous: "<",
            next: ">",
        },
         processing: '<div class="loading-indicator"><i class="fa fa-spinner fa-spin fa-3x fa-fw"></i><span class="sr-only">Loading...</span></div>'
    },
    order: [[1, 'asc']],
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


    $('body').on('click', '.purchasesave', function() {

      $('.modal-body').html("");
      var purchaseid = $(this).attr("purchaseid");
      var url = $(this).attr('url');

      $.ajax({
        type: 'GET',
        url: url,
        data: {
          purchaseid: purchaseid
        },
        success: function(data) {
          $('.modal-body').html(data.html);
        },
        beforeSend: function() {
          $(".modal-body").html("<center><h2 class=text-muted><b>Loading...</b></h2></center>");
        },
        error: function(e) {}
      });
    });
  });
  $(document).on('click', '.purchasesave', function() {
    let purchaseId = $(this).attr('purchaseid');
    let url = $(this).attr('url');
    
    // Ajax call to load modal content
    $.ajax({
        url: url,
        type: 'POST',
        data: {
            purchase_id: purchaseId,
            _token: $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            $('#purchaseview .modal-content').html(response);
            $('#purchaseview').modal('show');
        }
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