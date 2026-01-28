@extends('layouts.app')
@section('content')
<style>
  @media screen and (max-width:540px) {
    div#gatepass_info {
      margin-top: -177px;
    }

    span.titleup {
      /* margin-left: -10px; */
    }
  }

  #column-filter {
    display: inline-block;
    height: 47px;
    background: #f2f5fa !important;
    border: none !important;
  }
</style>
<!-- page content start -->
<div class="right_col" role="main">
  <!--gate pass view modal-->
  <div id="myModal-gateview" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg modal-xs">

      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <h4 id="myLargeModalLabel" class="modal-title">{{ getNameSystem() }}</h4>
          <a href="{!! url('/notes/list') !!}" class="prints"><input type="submit" class="btn-close " data-bs-dismiss="modal" value=""></a>
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
            <a id="menu_toggle"><i class="fa fa-bars sidemenu_toggle"></i></a>
            <span class="titleup">{{ trans('message.Notes') }}</span>
          </div>

          @include('dashboard.profile')
        </nav>
      </div>
    </div>
    @include('success_message.message')
    <div class="row">
      @if(!empty($notes) && count($notes) > 0)
      <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel table_up_div">
          <div class="row">
            <div class="col-8"></div>
            <div class="col-4 text-end">
              <select id="column-filter" class="px-2">
                <option value="">All</option>
                <option value="Supplier">Supplier</option>
                <option value="Product">Product</option>
                <option value="Purchase">Purchase</option>
                <option value="Customer">Customer</option>
                <option value="Vehicle">Vehicle</option>
                <option value="Jobcard">Jobcard</option>
                <option value="Quotation">Quotation</option>
                <option value="Invoice">Invoice</option>
              </select>
            </div>
          </div>
          <table id="supplier" class="table jambo_table" style="width:100%">
            <thead>
              <tr>
                @can('notes_delete')
                <th> </th>
                @endcan
                <th>{{ trans('message.Note For') }}</th>
                <th>{{ trans('message.Added by') }}</th>
                <th>{{ trans('message.Created On') }}</th>
                <th>{{ trans('message.Notes Text') }}</th>
                <th>{{ trans('message.Attachments') }}</th>
                <th>{{ trans('message.Action') }}</th>
              </tr>
            </thead>
            <tbody>
              <?php $i = 1; ?>

              @foreach ($notes as $note)
              <tr data-user-id="{{ $note->id }}">
                @can('notes_delete')
                <td>
                  <label class="container checkbox">
                    <input type="checkbox" name="chk">
                    <span class="checkmark"></span>
                  </label>
                </td>
                @endcan
                <td>
                  <a data-bs-toggle="modal" data-bs-target="#myModal-gateview" class="viewnote" id="{{ $note->id }}">{{ $note->entity_type }}</a>
                </td>
                <td>
                  <a data-bs-toggle="modal" data-bs-target="#myModal-gateview" class="viewnote" id="{{ $note->id }}">{{ getUserFullName($note->create_by) }}</a>
                </td>
                <td>{{ $note->created_at->setTimezone(Auth::User()->timezone) }}</td>
                <td>{{ $note->notes ?? trans('message.Not Added') }}</td>
                <td>
                  @php
                  $attachments = \App\note_attachments::where('note_id','=', $note->id)->get();
                  @endphp
                  {{ count($attachments) }} attachment(s)
                </td>
                <td>
                  <div class="dropdown_toggle">
                    <img src="{{ URL::asset('public/img/list/dots.png') }}" class="btn dropdown-toggle border-0" type="button" id="dropdownMenuButtonaction" data-bs-toggle="dropdown" aria-expanded="false">

                    <ul class="dropdown-menu heder-dropdown-menu action_dropdown shadow py-2" aria-labelledby="dropdownMenuButtonaction">
                      @can('notes_view')
                      <li><a class="dropdown-item"><button type="button" data-bs-toggle="modal" data-bs-target="#myModal-gateview" serviceid="" class="btn viewnote border-0 p-0" id="{{ $note->id }}"><img src="{{ URL::asset('public/img/list/Vector.png') }}" class="me-3">{{ trans('message.View') }}</button></a></li>
                      @endcan

                      @can('notes_delete')
                      <div class="dropdown-divider m-0"></div>
                      <li><a class="dropdown-item deletedatas" url="{!! url('/deleteNote/' . $note->id ) !!}" style="color:#FD726A"><img src="{{ URL::asset('public/img/list/Delete.png') }}" class="me-3">{{ trans('message.Delete') }}</a></li>
                      @endcan
                    </ul>
                  </div>

                </td>
              </tr>
              <?php $i++; ?>
              @endforeach
            </tbody>
          </table>
          @can('notes_delete')
          <button id="select-all-btn" class="btn select_all"><input type="checkbox" name="selectAll"> {{ trans('message.Select All') }}</button>
          <button id="delete-selected-btn" class="btn btn-danger text-white border-0" data-url="{!! url('/notes/list/delete/') !!}"><i class="fa fa-trash" aria-hidden="true"></i></button>
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
        infoFiltered: '(filtered from _MAX_ total records)',
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
        [2, 'desc']
      ]
    });

    var table = $('#supplier').DataTable();
    $('#column-filter').on('change', function() {
      var selectedValue = $(this).val();
      console.log(selectedValue);
      table.column(1) // Adjust the column index as needed
        .search(selectedValue)
        .draw();
    });


    $('body').on('click', '.viewnote', function() {
      var id = $(this).attr('id');
      var url = "<?php echo url('/notes/noteview'); ?>";
      var currentPageAction = getParameterByName('page_action');
      // Construct the URL for AJAX request with page_action parameter
      if (currentPageAction) {
        url += '?page_action=' + currentPageAction;
      }
      $.ajax({
        type: 'GET',
        url: url,
        data: {
          id: id
        },
        dataType: 'json',
        success: function(response) {
          $('.modal-body').html(response.html);
        },
      });
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
    // For get getParameterByName
    function getParameterByName(name, url = window.location.href) {
      name = name.replace(/[\[\]]/g, '\\$&');
      var regex = new RegExp('[?&]' + name + '(=([^&#]*)|&|#|$)'),
        results = regex.exec(url);
      if (!results) return null;
      if (!results[2]) return '';
      return decodeURIComponent(results[2].replace(/\+/g, ' '));
    }


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
@endsection