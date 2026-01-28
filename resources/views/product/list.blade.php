@extends('layouts.app')
@section('content')
<style>
  @media screen and (max-width:540px) {
    div#product_info {
      margin-top: -177px;
    }

    span.titleup {
      margin-left: -10px;
    }
  }
</style>
<!-- page content -->
<div class="right_col" role="main">
  <!--invoice modal-->
  <div id="myModal" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg">
      <!-- Modal content-->
      <div class="modal-content modal-body-data">

      </div>
    </div>
  </div>
  <div class="">
    <div class="page-title">
      <div class="nav_menu">
        <nav>
          <div class="nav toggle">
            <a id="menu_toggle"><i class="fa fa-bars sidemenu_toggle"></i></a><span class="titleup">&nbsp;{{ trans('message.Product') }}
              @can('product_add')
              <a href="{!! url('/product/add') !!}" id="" class="addbotton">
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
      @if(!empty($product) && count($product) > 0)
      <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel table_up_div">
          <table id="supplier" class="table jambo_table" style="width:100%">
            <thead>
              <tr>
                @can('product_delete')
                <th> </th>
                @endcan
                <th>{{ trans('message.Image') }}</th>
                <th>{{ trans('message.Product Number') }}</th>
                <th>{{ trans('message.Manufacturer Name') }}</th>
                <th>{{ trans('message.Product Name') }}</th>
                <th>{{ trans('message.Price') }} (<?php echo getCurrencySymbols(); ?>)</th>
                <th>{{ trans('message.Selling Price') }} (<?php echo getCurrencySymbols(); ?>)</th>
                <th>{{ trans('message.Supplier Name') }}</th>
                <th>{{ trans('message.Company Name') }}</th>
                <th>{{ trans('message.Color') }}</th>

                <!-- Custom Field Data Label Name-->
                @if (!empty($tbl_custom_fields))
                @foreach ($tbl_custom_fields as $tbl_custom_field)
                <th>{{ $tbl_custom_field->label }}</th>
                @endforeach
                @endif
                <!-- Custom Field Data End -->

                @canany(['product_view', 'product_edit', 'product_delete'])
                <th>{{ trans('message.Action') }}</th>
                @endcanany

              </tr>
            </thead>
            <tbody>
              <?php $i = 1; ?>
              @foreach ($product as $products)
              <tr data-user-id="{{ $products->id }}">
                <!-- <td>{{ $i }}</td> -->
                @can('product_delete')
                <td>
                  <label class="container checkbox">
                    <input type="checkbox" name="chk">
                    <span class="checkmark"></span>
                  </label>
                </td>
                @endcan
                <td><a data-bs-toggle="modal" data-bs-target="#myModal" product_id="{{ $products->id }}" url="{!! url('/product/modalview') !!}" class="save"><img src="{{ URL::asset('public/product/' . $products->product_image) }}" width="52px" height="52px" class="datatable_img"></a></td>

                <td><a data-bs-toggle="modal" data-bs-target="#myModal" product_id="{{ $products->id }}" url="{!! url('/product/modalview') !!}" class="save">{{ $products->product_no }}&nbsp;
                    <!-- <a data-toggle="tooltip" data-placement="bottom" title="Product Number" class="text-primary">
                    <i class="fa fa-info-circle" style="color:#D9D9D9"></i>
                  </a>  -->
                  </a>
                </td>
                </td>
                <td>{{ getProductName($products->product_type_id) }}&nbsp;
                  <!-- <a data-toggle="tooltip" data-placement="bottom" title="Manufacturer Name" class="text-primary">
                    <i class="fa fa-info-circle" style="color:#D9D9D9"></i>
                  </a> -->
                </td>
                <td>{{ $products->name }}&nbsp;
                  <!-- <a data-toggle="tooltip" data-placement="bottom" title="Product Name" class="text-primary">
                    <i class="fa fa-info-circle" style="color:#D9D9D9"></i>
                  </a> -->
                </td>
                <td>{{ $products->price }}&nbsp;
                  <!-- <a data-toggle="tooltip" data-placement="bottom" title="Price ( <?php echo getCurrencySymbols(); ?> )" class="text-primary">
                    <i class="fa fa-info-circle" style="color:#D9D9D9"></i>
                  </a> -->
                </td>
                 <td>{{ $products->selling_price ?? 'Not Added' }}&nbsp;
                  <!-- <a data-toggle="tooltip" data-placement="bottom" title="Price ( <?php echo getCurrencySymbols(); ?> )" class="text-primary">
                    <i class="fa fa-info-circle" style="color:#D9D9D9"></i>
                  </a> -->
                </td>
                <td>{{ getSupplierFullName($products->supplier_id) }}&nbsp;
                  <!-- <a data-toggle="tooltip" data-placement="bottom" title="Supplier Name" class="text-primary">
                    <i class="fa fa-info-circle" style="color:#D9D9D9"></i>
                  </a> -->
                </td>
                <td>{{ getCompanyNames($products->supplier_id) }}&nbsp;
                  <!-- <a data-toggle="tooltip" data-placement="bottom" title="Company Name" class="text-primary">
                    <i class="fa fa-info-circle" style="color:#D9D9D9"></i>
                  </a> -->
                </td>
                <td>
                  <div class="{{ $products->color_id ? 'color_code' : '' }}" style="background-color:{{ $products->color_id ? getColor($products->color_id) : 'transparent' }};">
                    {{ $products->color_id ?? 'Not Added' }}
                  </div>
                </td>

                <!-- Custom Field Data Value-->
                @if (!empty($tbl_custom_fields))
                @foreach ($tbl_custom_fields as $tbl_custom_field)
                <?php
                $tbl_custom = $tbl_custom_field->id;
                $userid = $products->id;

                $datavalue = getCustomDataProduct($tbl_custom, $userid);
                ?>

                @if ($tbl_custom_field->type == 'radio')
                @if ($datavalue != '')
                <?php
                $radio_selected_value = getRadioSelectedValue($tbl_custom_field->id, $datavalue);
                ?>
                <td>{{ $radio_selected_value }}</td>
                @else
                <td>{{ trans('message.Data not available') }}</td>
                @endif
                @else
                @if ($datavalue != null)
                <td>{{ $datavalue }}</td>
                @else
                <td>{{ trans('message.Data not available') }}</td>
                @endif
                @endif
                @endforeach
                @endif
                <!-- Custom Field Data End -->
                @canany(['product_view', 'product_edit', 'product_delete'])
                <td>
                  <div class="dropdown_toggle">
                    <img src="{{ URL::asset('public/img/list/dots.png') }}" class="btn dropdown-toggle border-0" type="button" id="dropdownMenuButtonAction" data-bs-toggle="dropdown" aria-expanded="false">

                    <ul class="dropdown-menu heder-dropdown-menu action_dropdown shadow py-2" aria-labelledby="dropdownMenuButtonAction">
                      @can('product_view')
                      <li class="px-0"><button type="button" data-bs-toggle="modal" data-bs-target="#myModal" product_id="{{ $products->id }}" url="{!! url('/product/modalview') !!}" class="btn border-0 save dropdown-item"><img src="{{ URL::asset('public/img/list/Vector.png') }}" class="me-3">{{ trans('message.View') }}</button></li>
                      @endcan

                      @can('product_edit')
                      <li><a class="dropdown-item" href="{!! url('/product/list/edit/' . $products->id) !!}"><img src="{{ URL::asset('public/img/list/Edit.png') }}" class="me-3"> {{ trans('message.Edit') }}</a></li>
                      @endcan

                      @can('product_delete')
                      <div class="dropdown-divider"></div>
                      <li><a class="dropdown-item deletedatas" url="{!! url('/product/list/delete/' . $products->id) !!}" style="color:#FD726A"><img src="{{ URL::asset('public/img/list/Delete.png') }}" class="me-3">{{ trans('message.Delete') }}</a></li>
                      @endcan
                    </ul>
                  </div>
                </td>
                @endcanany
              </tr>
              <?php $i++; ?>
              @endforeach
            </tbody>
          </table>
          @can('product_delete')
          <button id="select-all-btn" class="btn select_all"><input type="checkbox" name="selectAll"> {{ trans('message.Select All') }} </button>
          <button id="delete-selected-btn" class="btn btn-danger text-white border-0" data-url="{!! url('/product/list/delete/') !!}"><i class="fa fa-trash" aria-hidden="true"></i></button>
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
<input type="hidden" name="page_action" id="page_action" value="">
<!-- Scripts starting -->
<script nonce="{{ $cspNonce }}" src="{{ URL::asset('vendors/jquery/dist/jquery.min.js') }}"></script>
<script nonce="{{ $cspNonce }}" src="{{ URL::asset('vendors/jquery/jquery-3.7.1.min.js') }}"></script>

<script nonce="{{ $cspNonce }}">
  document.addEventListener('DOMContentLoaded', function () {
    if (window.innerWidth < 1200) {
        document.getElementById('page_action').value = 'mobile_app';
    }
});
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
    //view invoice 
    $('body').on('click', '.save', function() {
      $('.modal-body-data').html("");
      var product_id = $(this).attr("product_id");
      var url = $(this).attr('url');
      var currentPageAction = getParameterByName('page_action') ?? document.getElementById('page_action').value;
      // Construct the URL for AJAX request with page_action parameter
      if (currentPageAction) {
        url += '?page_action=' + currentPageAction;
      }

      $.ajax({
        type: 'GET',
        url: url,
        data: {
          product_id: product_id,
        },
        dataType: 'json',
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
</script>
@endsection