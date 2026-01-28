@extends('layouts.app')
@section('content')
<!-- page content -->
 <style>
  @media (max-width: 768px) {
    .table-responsive {
      overflow-x: auto !important;
      -webkit-overflow-scrolling: touch !important;
      margin-bottom: 15px !important;
    }
    
    .adddatatable {
      min-width: 800px !important; /* Force minimum width to make it scrollable */
      width: 100% !important;
    }
    
    .adddatatable th,
    .adddatatable td {
      white-space: nowrap !important;
      min-width: 120px !important;
      padding: 8px 5px !important;
      font-size: 12px !important;
    }
    
    /* Specific column widths */
    .adddatatable th:nth-child(1), /* Manufacturer Name */
    .adddatatable td:nth-child(1) {
      min-width: 150px !important;
    }
    
    .adddatatable th:nth-child(2), /* Product Name */
    .adddatatable td:nth-child(2) {
      min-width: 180px !important;
    }
    
    .adddatatable th:nth-child(3), /* Quantity */
    .adddatatable td:nth-child(3) {
      min-width: 100px !important;
    }
    
    .adddatatable th:nth-child(4), /* Price */
    .adddatatable td:nth-child(4) {
      min-width: 120px !important;
    }
    
    .adddatatable th:nth-child(5), /* Amount */
    .adddatatable td:nth-child(5) {
      min-width: 120px !important;
    }
    
    .adddatatable th:nth-child(6), /* Action */
    .adddatatable td:nth-child(6) {
      min-width: 80px !important;
    }
    
    /* Select dropdown styling in mobile */
    .adddatatable select {
      width: 100% !important;
      min-width: 140px !important;
      font-size: 14px !important;
    }
    
    .adddatatable input {
      width: 100% !important;
      min-width: 80px !important;
      font-size: 11px !important;
    }
    
    /* Select2 dropdown adjustments for mobile */
    .select2-container {
      width: 100% !important;
      min-width: 140px !important;
    }
    
    .select2-selection--single {
      height: 32px !important;
      font-size: 14px !important;
    }
    
    .select2-selection__rendered {
      line-height: 30px !important;
      font-size: 14px !important;
    }
  }
  
  @media (max-width: 540px) {
    .adddatatable {
      min-width: 700px !important;
    }
    
    .adddatatable th,
    .adddatatable td {
      font-size: 14px !important;
      padding: 6px 3px !important;
    }
    
    .adddatatable select,
    .adddatatable input {
      font-size: 14px !important;
      padding: 4px !important;
    }
  }
  
  @media (max-width: 375px) {
    .adddatatable {
      min-width: 650px !important;
    }
    
    .adddatatable th,
    .adddatatable td {
      font-size: 14px !important;
      padding: 5px 2px !important;
    }
  }
  @media (min-width: 300px) and (max-width: 550px) {
    span.select2-selection.select2-selection--single {
        width: 100% !important;
    }

    .select2-container--default .select2-selection--single .select2-selection__arrow {
        right: 0 !important;
    }
}
</style>
<div class="right_col" role="main">
  <div class="">
    <div class="page-title">
      <div class="nav_menu">
        <nav>
          <div class="nav toggle">
          <a id="menu_toggle"><i class="fa fa-bars sidemenu_toggle"></i></a><a href="{!! url('/sales_part/list') !!}" id=""><i class=""><img src="{{ URL::asset('public/supplier/Back Arrow.png') }}" class="back-arrow"></i><span class="titleup">
                {{ trans('message.Edit Part Sells') }}</span></a>
          </div>
          @include('dashboard.profile')
        </nav>
      </div>
      @if (session('message'))
      <div class="row massage">
        <div class="col-md-12 col-sm-12 col-xs-12">
          <div class="checkbox checkbox-success checkbox-circle mb-2 alert alert-success alert-dismissible fade show">
            <input id="checkbox-10" type="checkbox" checked="">
            <label for="checkbox-10 colo_success" style="margin-left: 20px;font-weight: 600;"> {{ session('message') }} </label>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close" style="padding: 1rem 0.75rem;"></button>
          </div>
        </div>
      </div>
      @endif
    </div>
    <div class="row">
      <div class="col-md-12 col-lg-12 col-xl-12 col-xxl-12 col-sm-12 col-xs-12">
        <div class="x_panel">
          <div class="x_content">
            <form id="salespartEditForm" method="post" action="update/{{ $sales->id }}" enctype="multipart/form-data" class="form-horizontal upperform">
              <div class="row row-mb-0">
                <div class="row col-md-6 col-lg-6 col-xl-6 col-xxl-6 col-sm-6 col-xs-6 div_bill_no_error">
                  <label class="control-label col-md-4 col-lg-4 col-xl-4 col-xxl-4 col-sm-4 col-xs-4" for="last-name">{{ trans('message.Bill No') }} <label class="color-danger">*</label></label>
                  <div class="col-md-8 col-lg-8 col-xl-8 col-xxl-8 col-sm-8 col-xs-8">
                    <input type="text" id="bill_no" name="bill_no" class="form-control" value="{{ $sales->bill_no }}" readonly>

                    <span id="bill_no-error" class="help-block error-help-block color-danger" style="display: none">{{ trans('message.Bill number is required.') }}</span>
                  </div>
                </div>

                <div class="row col-md-6 col-lg-6 col-xl-6 col-xxl-6 col-sm-6 col-xs-6 div_date_error">
                  <label class="control-label col-md-4 col-lg-4 col-xl-4 col-xxl-4 col-sm-4 col-xs-4" for="last-name">{{ trans('message.Sales Date') }} <label class="color-danger">*</label></label>
                  <div class="col-md-8 col-lg-8 col-xl-8 col-xxl-8 col-sm-8 col-xs-8 date ">
                    <?php
                    if ($sales->date == '0000-00-00') {
                      $salesdate = getDatepicker();
                    } else {
                      $salesdate = date(getDateFormat(), strtotime($sales->date));
                    }
                    ?>
                    {{-- <span class="input-group-addon"><i class="glyphicon glyphicon-calendar fa fa-calendar"></i></span> --}}
                    <input type="text" id="salesDate" name="date" class="form-control dateValue datepicker" autocomplete="off" placeholder="<?php echo getDateFormat(); ?>" value="{{ $salesdate }}" onkeypress="return false;" required>
                  </div>
                  <span id="salesDate-error" class="help-block error-help-block color-danger" style="display: none">{{ trans('message.Date is required.') }}</span>
                </div>
              </div>

              <div class="row row-mb-0">
                <div class="row col-md-6 col-lg-6 col-xl-6 col-xxl-6 col-sm-6 col-xs-6 div_customer_select_error">
                  <label class="control-label col-md-4 col-lg-4 col-xl-4 col-xxl-4 col-sm-4 col-xs-4" for="first-name">{{ trans('message.Customer Name') }} <label class="color-danger">*</label></label>
                  <div class="col-md-8 col-lg-8 col-xl-8 col-xxl-8 col-sm-8 col-xs-8">
                    <select class="form-control customer_select form-select" id="customer_select_box1" name="cus_name" required>
                      <option value="">{{ trans('message.Select Customer') }}</option>
                      @if (!empty($customer))
                      @foreach ($customer as $customers)
                      <option value="{{ $customers->id }}" <?php if ($customers->id == $sales->customer_id) {
                                                              echo 'selected';
                                                            } ?>>{{ $customers->name . ' ' . $customers->lastname }}</option>
                      @endforeach 
                      @endif
                    </select>

                    <span id="customer_select_box-error" class="help-block error-help-block color-danger" style="display: none">{{ trans('message.Customer name is required.') }}</span>
                  </div>
                </div>

                <div class="row col-md-6 col-lg-6 col-xl-6 col-xxl-6 col-sm-6 col-xs-6 div_salesmanname_error">
                  <label class="control-label col-md-4 col-lg-4 col-xl-4 col-xxl-4 col-sm-4 col-xs-4" for="first-name">{{ trans('message.Salesman') }} <label class="color-danger">*</label></label>
                  <div class="col-md-8 col-lg-8 col-xl-8 col-xxl-8 col-sm-8 col-xs-8">
                    <select class="form-control salesmanname form-select" name="salesmanname" id="salesmanname" required>
                      <option value="">{{ trans('message.Select Name') }}</option>
                      @if (!empty($employee))
                      @foreach ($employee as $employees)
                      <option value="{{ $employees->id }}" <?php if ($employees->id == $sales->salesmanname) {
                                                              echo 'selected';
                                                            } ?>>{{ $employees->name . ' ' . $employees->lastname }}</option>
                      @endforeach
                      @endif
                    </select>

                    <span id="salesmanname-error" class="help-block error-help-block color-danger" style="display: none">{{ trans('message.Salesman name is required.') }}</span>
                  </div>
                </div>
              </div>

              <div class="row row-mb-0">
                <div class="row col-md-6 col-lg-6 col-xl-6 col-xxl-6 col-sm-6 col-xs-6">
                  <label class="control-label col-md-4 col-lg-4 col-xl-4 col-xxl-4 col-sm-4 col-xs-4" for="branch">{{ trans('message.Branch') }} <label class="color-danger">*</label></label>

                  <div class="col-md-8 col-lg-8 col-xl-8 col-xxl-8 col-sm-8 col-xs-8">
                    <select class="form-control select_branch form-select" name="branch">
                      @foreach ($branchDatas as $branchData)
                      <option value="{{ $branchData->id }}" <?php if ($sales->branch_id == $branchData->id) {
                                                              echo 'selected';
                                                            } ?>>{{ $branchData->branch_name }}</option>
                      @endforeach
                    </select>
                  </div>
                </div>
              </div>
              <div class="row mt-3">
                <div class="col-md-10 col-lg-10 col-xl-10 col-xxl-10 col-sm-10 col-xs-10 header">
                  <h4><b>{{ trans('message.SALE PART') }}</b>
                  <button type="button" id="add_new_product" class="btn btn-outline-secondary " url="{!! url('sales_part/add/getproductname') !!}" style="margin:5px 0px;">{{ trans('+') }} </button>
                                                            
                </h4>
                </div>
                <div class="col-md-2 col-lg-2 col-xl-2 col-xxl-2 col-sm-2 col-xs-2">
                </div>
              </div>
              <div class="col-md-12 col-xs-12 col-sm-12 form-group table-responsive">
                <table class="table table-bordered adddatatable" id="tab_taxes_detail" align="center" style="font-size:14px;" width="100%">
                  <thead>
                    <tr>
                      <th class="actionre">{{ trans('message.Manufacturer Name') }}</th>
                      <th class="actionre">{{ trans('message.Product Name') }}</th>
                      <th class="actionre">{{ trans('message.Quantity') }}</th>
                      <th class="actionre" style="width:10%;">{{ trans('message.Price') }} (<?php echo getCurrencySymbols(); ?>)</th>
                      <th class="actionre" style="width:13%;">{{ trans('message.Amount') }} (<?php echo getCurrencySymbols(); ?>)</th>
                      <th class="actionre">{{ trans('message.Action') }}</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php $row_id = 0; ?>
                    @foreach ($stock as $stocks)
                    <tr id="row_id_<?php echo $row_id; ?>">
                      <input type="hidden" class="row_number" value="<?php echo $row_id; ?>">
                      <td class="tbl_td_selectManufac_error_<?php echo $row_id; ?>">
                        <select class="form-control select_producttype select_producttype_<?php echo $row_id; ?> form-select" name="product[Manufacturer_id][]" m_url="{!! url('/purchase/producttype/names') !!}" row_did="<?php echo $row_id; ?>" data-id="<?php echo $row_id; ?>" style="width:100%;" required="true">
                          <option value="">-{{ trans('message.Select Manufacturing Name') }}-</option>
                          @if (!empty($manufacture_name))
                          @foreach ($manufacture_name as $manufacture_nm)
                          <option value="{{ $manufacture_nm->id }}" <?php if ($manufacture_nm->id == $stocks->product_type_id) {
                                                                      echo 'selected';
                                                                    } ?>>{{ $manufacture_nm->type }}</option>
                          @endforeach
                          @endif
                        </select>

                        <span id="select_producttype_error_<?php echo $row_id; ?>" class="help-block error-help-block color-danger" style="display: none">{{ trans('message.Manufacturer name is required.') }}</span>
                      </td>

                      <td class="tbl_td_selectProductname_error_<?php echo $row_id; ?>">
                        <input type="hidden" name="product[tr_id][]" value="<?php echo $stocks->id; ?>" class="form-control" data-id="<?php echo $row_id; ?>" id="<?php echo $row_id; ?>">
                        <select name="product[product_id][]" class="form-control form-select productid select_productname_<?php echo $row_id; ?>" url="{!! url('purchase/add/getproduct') !!}" row_did="<?php echo $row_id; ?>" data-id="<?php echo $row_id; ?>" style="width:100%;" required="true">
                          <option value="">{{ trans('message.--Select Product--') }}</option>
                          @if (!empty($brand))
                          @foreach ($brand as $brands)
                          <option value="{{ $brands->id }}" <?php if ($brands->id == $stocks->product_id) {
                                                              echo 'selected';
                                                            } ?>>{{ $brands->name }}</option>
                          @endforeach
                          @endif
                        </select>

                        <span id="select_productname_error_<?php echo $row_id; ?>" class="help-block error-help-block color-danger" style="display: none">{{ trans('message.Product name is required.') }}</span>
                      </td>
                      <td class="tbl_td_quantity_error_<?php echo $row_id; ?>">
                        <input type="number" name="product[qty][]" url="{!! url('purchase/add/getqty') !!}" class="quantity form-control qty qty_<?php echo $row_id; ?>" prd_url="{{ url('/sale_part/get_available_product') }}" id="qty_<?php echo $row_id; ?>" autocomplete="off" row_id="<?php echo $row_id; ?>" value="{{ $stocks->quantity }}" maxlength="8" required="true">
                        <!-- <span class="qty_<?php echo $row_id; ?>">{{ getProductcode($stocks->product_id) }}</span> -->

                        <span id="quantity_error_<?php echo $row_id; ?>" class="help-block error-help-block color-danger" style="display: none">{{ trans('message.Quantity is required.') }}</span>
                      </td>
                      <td class="tbl_td_price_error_<?php echo $row_id; ?>">
                        <input type="text" name="product[price][]" class="product form-control prices price_<?php echo $row_id; ?>" value="{{ $stocks->price }}" autocomplete="off" id="price_<?php echo $row_id; ?>" row_id="<?php echo $row_id; ?>" style="width:100%;" required="true">

                        <span id="price_error_<?php echo $row_id; ?>" class="help-block error-help-block color-danger" style="display: none">{{ trans('message.Price is required.') }}</span>
                      </td>
                      <td class="tbl_td_totaPrice_error_<?php echo $row_id; ?>">
                        <input type="text" name="product[total_price][]" class="product form-control total_price total_price_<?php echo $row_id; ?>" value="{{ $stocks->total_price }}" id="total_price_<?php echo $row_id; ?>" style="width:100%;" readonly="true" required="true">

                        <span id="total_price_error_<?php echo $row_id; ?>" class="help-block error-help-block color-danger" style="display: none">{{ trans('message.Total price is required.') }}</span>
                      </td>
                      <td align="center">
                        <span class="product_delete" data-id="<?php echo $row_id; ?>" pid="<?php echo $stocks->id; ?>" url="{!! url('/sale_part/deleteproduct') !!}"><i class="fa fa-trash fa-2x" aria-hidden="true"></i></span>
                      </td>
                    </tr>
                    <?php
                    $row_id++; ?>
                    @endforeach
                  </tbody>
                </table>
              </div>

              <!-- Custom Filed data value -->
              @if (!empty($tbl_custom_fields))
              <div class="col-md-12 col-xs-12 col-sm-12 space">
                <h4><b>{{ trans('message.Custom Fields') }}</b></h4>
                <p class="col-md-12 col-xs-12 col-sm-12 ln_solid"></p>
              </div>
              <?php
              $subDivCount = 0;
              ?>
              @foreach ($tbl_custom_fields as $myCounts => $tbl_custom_field)
              <?php
              if ($tbl_custom_field->required == 'yes') {
                $required = 'required';
                $red = '*';
              } else {
                $required = '';
                $red = '';
              }
              $tbl_custom = $tbl_custom_field->id;
              $userid = $sales->id;
              $datavalue = getCustomDataSalepart($tbl_custom, $userid);

              $subDivCount++;
              ?>

              @if ($myCounts % 2 == 0)
              <div class="row col-md-12 col-sm-6 col-xs-12 row-mb-0">
                @endif

                <div class="form-group row col-md-6  col-sm-6 col-xs-12 error_customfield_main_div_{{ $myCounts }}">
                  <label class="control-label col-md-4 col-sm-4 col-xs-12" for="account-no">{{ $tbl_custom_field->label }} <label class="color-danger">{{ $red }}</label></label>
                  <div class="col-md-8 col-sm-8 col-xs-12">
                    @if ($tbl_custom_field->type == 'textarea')
                    <textarea name="custom[{{ $tbl_custom_field->id }}]" class="form-control textarea_{{ $tbl_custom_field->id }} textarea_simple_class common_simple_class common_value_is_{{ $myCounts }}" placeholder="{{ trans('message.Enter') }} {{ $tbl_custom_field->label }}" maxlength="100" isRequire="{{ $required }}" type="textarea" fieldNameIs="{{ $tbl_custom_field->label }}" rows_id="{{ $myCounts }}" {{ $required }}>{{ $datavalue }}</textarea>

                    <span id="common_error_span_{{ $myCounts }}" class="help-block error-help-block color-danger" style="display: none"></span>
                    @elseif($tbl_custom_field->type == 'radio')
                    <?php
                    $radioLabelArrayList = getRadiolabelsList($tbl_custom_field->id);
                    ?>
                    @if (!empty($radioLabelArrayList))
                    <div style="margin-top: 5px;">
                      @foreach ($radioLabelArrayList as $k => $val)
                      <input type="{{ $tbl_custom_field->type }}" name="custom[{{ $tbl_custom_field->id }}]" value="{{ $k }}" <?php
                                                                                                                              //$formName = "product";
                                                                                                                              $getRadioValue = getRadioLabelValueForUpdateForAllModules($tbl_custom_field->form_name, $sales->id, $tbl_custom_field->id);

                                                                                                                              if ($k == $getRadioValue) {
                                                                                                                                echo 'checked';
                                                                                                                              } ?>> {{ $val }} &nbsp;
                      @endforeach
                    </div>
                    @endif
                    @elseif($tbl_custom_field->type == 'checkbox')
                    <?php
                    $checkboxLabelArrayList = getCheckboxLabelsList($tbl_custom_field->id);
                    ?>
                    @if (!empty($checkboxLabelArrayList))
                    <?php
                    $getCheckboxValue = getCheckboxLabelValueForUpdateForAllModules($tbl_custom_field->form_name, $sales->id, $tbl_custom_field->id);
                    ?>
                    <div class="required_checkbox_parent_div_{{ $tbl_custom_field->id }}" style="margin-top: 5px;">
                      @foreach ($checkboxLabelArrayList as $k => $val)
                      <input type="{{ $tbl_custom_field->type }}" name="custom[{{ $tbl_custom_field->id }}][]" value="{{ $val }}" isRequire="{{ $required }}" fieldNameIs="{{ $tbl_custom_field->label }}" custm_isd="{{ $tbl_custom_field->id }}" class="checkbox_{{ $tbl_custom_field->id }} required_checkbox_{{ $tbl_custom_field->id }} checkbox_simple_class common_value_is_{{ $myCounts }} common_simple_class" rows_id="{{ $myCounts }}" <?php
                                                                                                                                                                                                                                                                                                                                                                                                                                                  if ($val == getCheckboxValForAllModule($tbl_custom_field->form_name, $sales->id, $tbl_custom_field->id, $val)) {
                                                                                                                                                                                                                                                                                                                                                                                                                                                    echo 'checked';
                                                                                                                                                                                                                                                                                                                                                                                                                                                  }
                                                                                                                                                                                                                                                                                                                                                                                                                                                  ?>> {{ $val }} &nbsp;
                      @endforeach
                      <span id="common_error_span_{{ $myCounts }}" class="help-block error-help-block color-danger" style="display: none"></span>
                    </div>
                    @endif
                    @elseif($tbl_custom_field->type == 'textbox')
                    <input type="{{ $tbl_custom_field->type }}" name="custom[{{ $tbl_custom_field->id }}]" placeholder="{{ trans('message.Enter') }} {{ $tbl_custom_field->label }}" value="{{ $datavalue }}" maxlength="30" class="form-control textDate_{{ $tbl_custom_field->id }} textdate_simple_class common_value_is_{{ $myCounts }} common_simple_class" isRequire="{{ $required }}" fieldNameIs="{{ $tbl_custom_field->label }}" rows_id="{{ $myCounts }}" {{ $required }}>

                    <span id="common_error_span_{{ $myCounts }}" class="help-block error-help-block color-danger" style="display:none"></span>
                    @elseif($tbl_custom_field->type == 'date')
                    <input type="{{ $tbl_custom_field->type }}" name="custom[{{ $tbl_custom_field->id }}]" placeholder="{{ trans('message.Enter') }} {{ $tbl_custom_field->label }}" value="{{ $datavalue }}" maxlength="30" class="form-control textDate_{{ $tbl_custom_field->id }} date_simple_class common_value_is_{{ $myCounts }} common_simple_class" isRequire="{{ $required }}" fieldNameIs="{{ $tbl_custom_field->label }}" rows_id="{{ $myCounts }}" onkeydown="return false" {{ $required }}>

                    <span id="common_error_span_{{ $myCounts }}" class="help-block error-help-block color-danger" style="display:none"></span>
                    @endif
                  </div>
                </div>
                @if ($myCounts % 2 != 0)
              </div>
              @endif
              @endforeach
              <?php
              if ($subDivCount % 2 != 0) {
                echo '</div>';
              }
              ?>
              @endif
              <!-- Custom Filed data value End-->

              <div class="row">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <!-- <div class="row col-md-6 col-lg-6 col-xl-6 col-xxl-6 col-sm-6 col-xs-6 my-1 mx-0">
                  <a class="btn btn-primary" href="{{ URL::previous() }}">{{ trans('message.CANCEL') }}</a>
                </div> -->
                <div class="row col-md-6 col-lg-6 col-xl-6 col-xxl-6 col-sm-6 col-xs-6 my-1 mx-0">
                  <button type="submit" class="btn btn-success salesPartEditSubmitButton">{{ trans('message.UPDATE') }}</button>
                </div>
              </div>
            </form>
          </div>

        </div>
      </div>
    </div>
  </div>
</div>
<!-- /page content -->


<!-- Scripts starting -->
<!-- <script nonce="{{ $cspNonce }}" src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script> -->
<script nonce="{{ $cspNonce }}" src="{{ URL::asset('vendors/jquery/jquery-3.7.1.min.js') }}"></script>
<script nonce="{{ $cspNonce }}">
    var msg10 = "{{ trans('message.OK') }}"
    $(document).ready(function() {

        /*datetimepicker*/
        $('.datepicker').datetimepicker({
            format: "<?php echo getDatepicker(); ?>",
            todayBtn: true,
            autoclose: 1,
            minView: 2,
            endDate: new Date(),
            language: "{{ getLangCode() }}",
        });
    });


    $("#add_new_product").click(function() {
        var url = $(this).attr('url');

        // Get the highest row number and add 1
        var row_len = jQuery(".row_number").length;
        var row_id;
        
        if (row_len > 0) {
            var highest_num = 0;
            jQuery(".row_number").each(function() {
                var current_num = parseInt($(this).val());
                if (current_num > highest_num) {
                    highest_num = current_num;
                }
            });
            row_id = highest_num + 1;
        } else {
            row_id = 0;
        }

        var msg16 = "{{ trans('app.An error occurred :') }}";
        $.ajax({
            type: 'GET'
            , url: url
            , data: {
                row_id: row_id
            }
            , beforeSend: function() {
                // $("#add_new_product").prop('disabled', true);
            }
            , success: function(response) {
                console.log(response);
                $("#tab_taxes_detail > tbody").append(response.html);
                $('.adddatatable').table().row.add($(response.html)).draw();
                $("#add_new_product").prop('disabled', false);
                return false;
            }
            , error: function(e) {
                alert(msg16 + " " + e.responseText);
                console.log(e);
            }
        });
    });



    $('body').on('click', '.product_delete', function() {
        var row_id = $(this).attr('data-id');


        $('table#tab_taxes_detail tr#row_id_' + row_id).fadeOut();

        $('table#tab_taxes_detail tr.child').fadeOut();
        $('table#tab_taxes_detail tr#row_id_' + row_id).html(
            '<option value="">Select product</option>');
        $('table#tab_taxes_detail tr#row_id_' + row_id).html(
            '<input type="text" name="" class="form-control qty" value="" id="tax_1" readonly="true">'
        );
        $('table#tab_taxes_detail tr#row_id_' + row_id).html(
            '<input type="text" name="" class="form-control price" value="" id="tax_1" readonly="true">'
        );
        $('table#tab_taxes_detail tr#row_id_' + row_id).html(
            '<input type="text" name="" class="form-control total_price" value="" id="tax_1" readonly="true">'
        );
        $('table#tab_taxes_detail tr#row_id_' + row_id).html(
            '<span class="product_delete" style="cursor: pointer;" data-id="0"><i class="fa fa-trash"></i> Delete</span>'
        );
        return false;
    });



    $('body').on('change', '.productid', '.qty', function() {
        var row_id = $(this).attr('row_did');
        var p_id = $(this).val();

        var qty = $('.qty_' + row_id).val();
        var price = $('.price_' + row_id).val();
        var url = $(this).attr('url');

        var msg17 = "{{ trans('message.An error occurred :') }}";
        $.ajax({
            type: 'GET',
            url: url,
            data: {
                p_id: p_id
            },
            success: function(response) {
                var json_obj = jQuery.parseJSON(response);
                // var price = json_obj['price'];
                var price = json_obj['selling_price'] ? json_obj['selling_price'] : json_obj['price'];
                var total_price = price * qty;
                $('.price_' + row_id).val(price);
                $('.total_price_' + row_id).val(price);
                // $('.total_price_' + row_id).val(total_price);
                $('.qty_' + row_id).val(1);
                var product_no = json_obj['product_no'];
                // $('.qty_' + row_id).html(product_no);
            },
            error: function(e) {
                alert(msg17 + " " + e.responseText);
                console.log(e);
            }
        });
    });



    $('body').on('keyup', '.qty', function() {
        var row_id = $(this).attr('row_id');
        var p_id = $('.select_productname_' + row_id).val();
        var qtyVal = $('.qty_' + row_id).val();

        var msg18 = "{{ trans('message.An error occurred :') }}";
        var msg20 = "{{ trans('message.First select product name') }}";

        if (p_id == '') {
            alert(msg20);
            $('.qty_' + row_id).val('');
        } else {
            if (/\D/g.test(this.value)) {
                $('.qty_' + row_id).val('');

                $('#quantity_error_' + row_id).css({
                    "display": ""
                });
                $('.tbl_td_quantity_error_' + row_id).addClass('has-error');
            } else if (this.value <= 0) {
                $('.qty_' + row_id).val('');
                $('#quantity_error_' + row_id).css({
                    "display": ""
                });
                $('.tbl_td_quantity_error_' + row_id).addClass('has-error');
            } else {
                var qty = $('.qty_' + row_id).val();
                var price = $('.price_' + row_id).val();
                var url = $(this).attr('url');

                $('#quantity_error_' + row_id).css({
                    "display": "none"
                });
                $('.tbl_td_quantity_error_' + row_id).removeClass('has-error');

                $.ajax({
                    type: 'GET',
                    url: url,
                    data: {
                        qty: qty,
                        price: price
                    },
                    success: function(response) {
                        total_price = price * qty;
                        $('.total_price_' + row_id).val(total_price);
                    },
                    beforeSend: function() {},
                    error: function(e) {
                        alert(msg18 + " " + e.responseText);
                        console.log(e);
                    }
                });
            }
        }
    });
    $('body').on('change', '.qty', function() {
        var row_id = $(this).attr('row_id');
        var p_id = $('.select_productname_' + row_id).val();
        var qtyVal = $('.qty_' + row_id).val();

        var msg18 = "{{ trans('message.An error occurred :') }}";
        var msg20 = "{{ trans('message.First select product name') }}";

        if (p_id == '') {
            alert(msg20);
            $('.qty_' + row_id).val('');
        } else {
            if (/\D/g.test(this.value)) {
                $('.qty_' + row_id).val('');

                $('#quantity_error_' + row_id).css({
                    "display": ""
                });
                $('.tbl_td_quantity_error_' + row_id).addClass('has-error');
            } else if (this.value <= 0) {
                $('.qty_' + row_id).val('');
                $('#quantity_error_' + row_id).css({
                    "display": ""
                });
                $('.tbl_td_quantity_error_' + row_id).addClass('has-error');
            } else {
                var qty = $('.qty_' + row_id).val();
                var price = $('.price_' + row_id).val();
                var url = $(this).attr('url');

                $('#quantity_error_' + row_id).css({
                    "display": "none"
                });
                $('.tbl_td_quantity_error_' + row_id).removeClass('has-error');

                $.ajax({
                    type: 'GET',
                    url: url,
                    data: {
                        qty: qty,
                        price: price
                    },
                    success: function(response) {
                        total_price = price * qty;
                        $('.total_price_' + row_id).val(total_price);
                    },
                    beforeSend: function() {},
                    error: function(e) {
                        alert(msg18 + " " + e.responseText);
                        console.log(e);
                    }
                });
            }
        }
    });



    $(function() {
        $('#supplier_select').change(function() {
            var supplier_id = $(this).val();
            var url = $(this).attr('url');

            var msg19 = "{{ trans('message.An error occurred :') }}";
            $.ajax({
                type: 'GET',
                url: url,
                data: {
                    supplier_id: supplier_id
                },
                success: function(response) {
                    var res_supplier = jQuery.parseJSON(response);
                    $('#mobile').attr('value', res_supplier.mobile_no);
                    $('#email').attr('value', res_supplier.email);
                    $('#address').text(res_supplier.address);
                },
                beforeSend: function() {
                    $('#mobile').attr('value', 'Loading..');
                    $('#email').attr('value', 'Loading..');
                    $('#address').attr('value', 'Loading..');
                },
                error: function(e) {
                    alert(msg19 + " " + e.responseText);
                    console.log(e);
                }
            });
        });
    });
    /*Get product name when changing manufacturer name*/
    $('body').on('change', '.select_producttype', function() {

        var row_id = $(this).attr('row_did');
        var m_id = $(this).val();
        var url = $(this).attr('m_url');
        //alert(url);
        $.ajax({
            type: 'GET',
            url: url,
            data: {
                m_id: m_id
            },
            success: function(response) {
                $('.select_productname_' + row_id).html(response);
            }
        });
    });

  

    $('body').on('click', '.productid', function() {
        var rowId = $(this).attr('row_did');
        var url = $(this).attr('url');
        var manufacture_selected = $('.select_producttype_' + rowId).val();

        var msg25 = "{{ trans('message.First Select Manufacturer') }}";

        if (manufacture_selected == "") {
            swal({
                title: msg35,
                cancelButtonColor: '#C1C1C1',
                buttons: {
                    cancel: msg10,
                },
                dangerMode: true,
            });

        }
    });


    /*Price field should editable and editable price should change the Total-Amount (on-time editable price )*/
    $('body').on('change', '.prices', function() {

        var row_id = $(this).attr('row_id');
        var qty = $('.qty_' + row_id).val();
        var price = $('.price_' + row_id).val();
        var total_price = price * qty;

        var regex = /^\d*(.\d{2})?$/;

        if (!regex.test(price)) {
            $('.price_' + row_id).val("");
            $('.price_' + row_id).attr('required', true);

            $('#price_error_' + row_id).css({
                "display": ""
            });
            $('.tbl_td_price_error_' + row_id).addClass('has-error');
        } else if (price == 0 || price == null) {
            $('.price_' + row_id).val("");
            $('.price_' + row_id).attr('required', true);

            $('#price_error_' + row_id).css({
                "display": ""
            });
            $('.tbl_td_price_error_' + row_id).addClass('has-error');
        } else {
            $('.price_' + row_id).val(price);
            $('.total_price_' + row_id).val(total_price);

            $('#price_error_' + row_id).css({
                "display": "none"
            });
            $('.tbl_td_price_error_' + row_id).removeClass('has-error');
        }
    });


    /*Form submit time specific field value changes time make validation using Jquery*/
    var msg1 = "{{ trans('message.field is required') }}";
    var msg2 = "{{ trans('message.Only blank space not allowed') }}";
    var msg3 = "{{ trans('message.Special symbols are not allowed.') }}";
    var msg4 = "{{ trans('message.At first position only alphabets are allowed.') }}";

    $('.salesPartAddSubmitButton').click(function(e) {

        var bill_no = $('#bill_no').val();
        var date = $('.dateValue').val();
        var selectCustomer = $('#customer_select_box1').val();
        var salesmanname = $('#salesmanname').val();

        var count_row = $("#tab_taxes_detail > tbody > tr").length;

        if (bill_no == "") {
            $('#bill_no-error').css({
                "display": ""
            });
            $('.div_bill_no_error').addClass('has-error');
        } else {
            $('#bill_no-error').css({
                "display": "none"
            });
            $('.div_bill_no_error').removeClass('has-error');
        }

        if (date == "") {
            $('#salesDate-error').css({
                "display": ""
            });
            $('.div_date_error').addClass('has-error');
        } else {
            $('#salesDate-error').css({
                "display": "none"
            });
            $('.div_date_error').removeClass('has-error');
        }

        if (selectCustomer == "") {
            $('#customer_select_box1-error').css({
                "display": ""
            });
            $('.div_customer_select_error').addClass('has-error');
            $('.select2-selection--single').addClass('has-error');
        } else {
            $('#customer_select_box1-error').css({
                "display": "none"
            });
            $('.div_customer_select_error').removeClass('has-error');
        }

        if (salesmanname == "") {
            $('#salesmanname-error').css({
                "display": ""
            });
            $('.div_salesmanname_error').addClass('has-error');
        } else {
            $('#salesmanname-error').css({
                "display": "none"
            });
            $('.div_salesmanname_error').removeClass('has-error');
        }


        /*Table data validation*/
        for (var i = 1; i <= count_row; i++) {

            var selectPrd = $('.select_producttype_' + i).val();
            var selectPrdQty = $('.qty_' + i).val();
            var selectPrdId = $('.select_productname_' + i).val();

            if (selectPrd == "") {
                $('#select_producttype_error_' + i).css({
                    "display": ""
                });
                $('.tbl_td_selectManufac_error_' + i).addClass('has-error');
            } else {
                $('#select_producttype_error_' + i).css({
                    "display": "none"
                });
                $('.tbl_td_selectManufac_error_' + i).removeClass('has-error');
            }

            if (selectPrdQty == "") {
                $('#quantity_error_' + i).css({
                    "display": ""
                });
                $('.tbl_td_quantity_error_' + i).addClass('has-error');
                return false;
            } else {
                $('#quantity_error_' + i).css({
                    "display": "none"
                });
                $('.tbl_td_quantity_error_' + i).removeClass('has-error');
                return true;
            }

            if (selectPrdId == "") {
                $('#select_productname_error_' + i).css({
                    "display": ""
                });
                $('.tbl_td_selectProductname_error_' + i).addClass('has-error');
            } else {
                $('#select_productname_error_' + i).css({
                    "display": "none"
                });
                $('.tbl_td_selectProductname_error_' + i).removeClass('has-error');
            }
        }


        $('#salespartAddForm input, #salespartAddForm select, #salespartAddForm textarea').each(

            function(index) {
                var input = $(this);

                if (input.attr('isRequire') == 'required') {
                    var rowid = (input.attr('rows_id'));
                    var labelName = (input.attr('fieldnameis'));

                    if (input.attr('type') == 'textbox' || input.attr('type') == 'textarea') {
                        if (input.val() == '' || input.val() == null) {
                            $('.common_value_is_' + rowid).val("");
                            $('#common_error_span_' + rowid).text(labelName + " : " + msg1);
                            $('#common_error_span_' + rowid).css({
                                "display": ""
                            });
                            $('.error_customfield_main_div_' + rowid).addClass('has-error');
                            e.preventDefault();
                            return false;
                        } else if (!input.val().replace(/\s/g, '').length) {
                            $('.common_value_is_' + rowid).val("");
                            $('#common_error_span_' + rowid).text(labelName + " : " + msg2);
                            $('#common_error_span_' + rowid).css({
                                "display": ""
                            });
                            $('.error_customfield_main_div_' + rowid).addClass('has-error');
                            e.preventDefault();
                            return false;
                        } else if (!input.val().match(/^[(a-zA-Z0-9\s)\p{L}]+$/u)) {
                            $('.common_value_is_' + rowid).val("");
                            $('#common_error_span_' + rowid).text(labelName + " : " + msg3);
                            $('#common_error_span_' + rowid).css({
                                "display": ""
                            });
                            $('.error_customfield_main_div_' + rowid).addClass('has-error');
                            e.preventDefault();
                            return false;
                        }
                    } else if (input.attr('type') == 'checkbox') {
                        var ids = input.attr('custm_isd');
                        if ($(".required_checkbox_" + ids).is(':checked')) {
                            $('#common_error_span_' + rowid).css({
                                "display": "none"
                            });
                            $('.error_customfield_main_div_' + rowid).removeClass('has-error');
                            $('.required_checkbox_parent_div_' + ids).css({
                                "color": ""
                            });
                            $('.error_customfield_main_div_' + ids).removeClass('has-error');
                        } else {
                            $('#common_error_span_' + rowid).text(labelName + " : " + msg1);
                            $('#common_error_span_' + rowid).css({
                                "display": ""
                            });
                            $('.error_customfield_main_div_' + rowid).addClass('has-error');
                            $('.required_checkbox_' + ids).css({
                                "outline": "2px solid #a94442"
                            });
                            $('.required_checkbox_parent_div_' + ids).css({
                                "color": "#a94442"
                            });
                            e.preventDefault();
                            return false;
                        }
                    } else if (input.attr('type') == 'date') {
                        if (input.val() == '' || input.val() == null) {
                            $('.common_value_is_' + rowid).val("");
                            $('#common_error_span_' + rowid).text(labelName + " : " + msg1);
                            $('#common_error_span_' + rowid).css({
                                "display": ""
                            });
                            $('.error_customfield_main_div_' + rowid).addClass('has-error');
                            e.preventDefault();
                            return false;
                        } else {
                            $('#common_error_span_' + rowid).css({
                                "display": "none"
                            });
                            $('.error_customfield_main_div_' + rowid).removeClass('has-error');
                        }
                    }
                }


            }
        );
    });

    $('body').on('change', '.dateValue', function() {

        var dateValue = $(this).val();

        if (dateValue == "") {
            $('#salesDate-error').css({
                "display": ""
            });
            $('.div_date_error').addClass('has-error');
        } else {
            $('#salesDate-error').css({
                "display": "none"
            });
            $('.div_date_error').removeClass('has-error');
        }
    });


    $('.customer_select').on('change', function() {

        var customerValue = $('select[name=cus_name]').val();

        if (customerValue == "") {
            $('#customer_select_box1-error').css({
                "display": ""
            });
            $('.div_customer_select_error').addClass('has-error');
        } else {
            $('#customer_select_box1-error').css({
                "display": "none"
            });
            $('.div_customer_select_error').removeClass('has-error');
        }

    });

    $('.salesmanname').on('change', function() {

        var salesmanValue = $('select[name=salesmanname]').val();

        if (salesmanValue == "") {
            $('#salesmanname-error').css({
                "display": ""
            });
            $('.div_salesmanname_error').addClass('has-error');
        } else {
            $('#salesmanname-error').css({
                "display": "none"
            });
            $('.div_salesmanname_error').removeClass('has-error');
        }
    });


    /*for table field validation*/
    $('body').on('change', '.select_producttype', function() {

        var row_id = $(this).attr("row_did");
        var manufactureValue = $('.select_producttype_' + row_id).val();

        if (manufactureValue == "") {
            $('#select_producttype_error_' + row_id).css({
                "display": ""
            });
            $('.tbl_td_selectManufac_error_' + row_id).addClass('has-error');
        } else {
            $('#select_producttype_error_' + row_id).css({
                "display": "none"
            });
            $('.tbl_td_selectManufac_error_' + row_id).removeClass('has-error');
        }
    });



    $('body').on('change', '.productid', function() {

        var row_id = $(this).attr("row_did");
        var prdValue = $('.select_productname_' + row_id).val();

        if (prdValue == "") {
            $('#select_productname_error_' + row_id).css({
                "display": ""
            });
            $('.tbl_td_selectProductname_error_' + row_id).addClass('has-error');
        } else {
            $('#select_productname_error_' + row_id).css({
                "display": "none"
            });
            $('.tbl_td_selectProductname_error_' + row_id).removeClass('has-error');
        }
    });

    /*Anykind of input time check for validation for Textbox, Date and Textarea*/
    $('body').on('keyup', '.common_simple_class', function() {

        var rowid = $(this).attr('rows_id');
        var valueIs = $('.common_value_is_' + rowid).val();
        var requireOrNot = $('.common_value_is_' + rowid).attr('isrequire');
        var labelName = $('.common_value_is_' + rowid).attr('fieldnameis');
        var inputTypes = $('.common_value_is_' + rowid).attr('type');

        if (requireOrNot != "") {
            if (inputTypes != 'radio' && inputTypes != 'checkbox' && inputTypes != 'date') {
                if (valueIs == "") {
                    $('.common_value_is_' + rowid).val("");
                    $('#common_error_span_' + rowid).text(labelName + " : " + msg1);
                    $('#common_error_span_' + rowid).css({
                        "display": ""
                    });
                    $('.error_customfield_main_div_' + rowid).addClass('has-error');
                } else if (valueIs.match(/^\s+/)) {
                    $('.common_value_is_' + rowid).val("");
                    $('#common_error_span_' + rowid).text(labelName + " : " + msg4);
                    $('#common_error_span_' + rowid).css({
                        "display": ""
                    });
                    $('.error_customfield_main_div_' + rowid).addClass('has-error');
                } else if (!valueIs.match(/^[(a-zA-Z0-9\s)\p{L}]+$/u)) {
                    $('.common_value_is_' + rowid).val("");
                    $('#common_error_span_' + rowid).text(labelName + " : " + msg3);
                    $('#common_error_span_' + rowid).css({
                        "display": ""
                    });
                    $('.error_customfield_main_div_' + rowid).addClass('has-error');
                } else {
                    $('#common_error_span_' + rowid).css({
                        "display": "none"
                    });
                    $('.error_customfield_main_div_' + rowid).removeClass('has-error');
                }
            } else if (inputTypes == 'date') {
                if (valueIs != "") {
                    $('#common_error_span_' + rowid).css({
                        "display": "none"
                    });
                    $('.error_customfield_main_div_' + rowid).removeClass('has-error');
                } else {
                    $('.common_value_is_' + rowid).val("");
                    $('#common_error_span_' + rowid).text(labelName + " : " + msg1);
                    $('#common_error_span_' + rowid).css({
                        "display": ""
                    });
                    $('.error_customfield_main_div_' + rowid).addClass('has-error');
                }
            } else {
                //alert("Yes i am radio and checkbox");
            }
        } else {
            if (inputTypes != 'radio' && inputTypes != 'checkbox' && inputTypes != 'date') {
                if (valueIs != "") {
                    if (valueIs.match(/^\s+/)) {
                        $('.common_value_is_' + rowid).val("");
                        $('#common_error_span_' + rowid).text(labelName + " : " + msg4);
                        $('#common_error_span_' + rowid).css({
                            "display": ""
                        });
                        $('.error_customfield_main_div_' + rowid).addClass('has-error');
                    } else if (!valueIs.match(/^[(a-zA-Z0-9\s)\p{L}]+$/u)) {
                        $('.common_value_is_' + rowid).val("");
                        $('#common_error_span_' + rowid).text(labelName + " : " + msg3);
                        $('#common_error_span_' + rowid).css({
                            "display": ""
                        });
                        $('.error_customfield_main_div_' + rowid).addClass('has-error');
                    } else {
                        $('#common_error_span_' + rowid).css({
                            "display": "none"
                        });
                        $('.error_customfield_main_div_' + rowid).removeClass('has-error');
                    }
                } else {
                    $('#common_error_span_' + rowid).css({
                        "display": "none"
                    });
                    $('.error_customfield_main_div_' + rowid).removeClass('has-error');
                }
            }
        }
    });


    /*For required checkbox checked or not*/
    $('body').on('click', '.checkbox_simple_class', function() {

        var rowid = $(this).attr('rows_id');
        var requireOrNot = $('.common_value_is_' + rowid).attr('isrequire');
        var labelName = $('.common_value_is_' + rowid).attr('fieldnameis');
        var inputTypes = $('.common_value_is_' + rowid).attr('type');
        var custId = $('.common_value_is_' + rowid).attr('custm_isd');

        if (requireOrNot != "") {
            if ($(".required_checkbox_" + custId).is(':checked')) {
                $('.required_checkbox_' + custId).css({
                    "outline": ""
                });
                $('.required_checkbox_' + custId).css({
                    "color": ""
                });
                $('#common_error_span_' + rowid).css({
                    "display": "none"
                });
                $('.required_checkbox_parent_div_' + custId).css({
                    "color": ""
                });
                $('.error_customfield_main_div_' + rowid).removeClass('has-error');
            } else {
                $('#common_error_span_' + rowid).text(labelName + " : " + msg1);
                $('.required_checkbox_' + custId).css({
                    "outline": "2px solid #a94442"
                });
                $('.required_checkbox_' + custId).css({
                    "color": "#a94442"
                });
                $('#common_error_span_' + rowid).css({
                    "display": ""
                });
                $('.required_checkbox_parent_div_' + custId).css({
                    "color": "#a94442"
                });
                $('.error_customfield_main_div_' + rowid).addClass('has-error');
            }
        }
    });


    $('body').on('change', '.date_simple_class', function() {

        var rowid = $(this).attr('rows_id');
        var valueIs = $('.common_value_is_' + rowid).val();
        var requireOrNot = $('.common_value_is_' + rowid).attr('isrequire');
        var labelName = $('.common_value_is_' + rowid).attr('fieldnameis');
        var inputTypes = $('.common_value_is_' + rowid).attr('type');
        var custId = $('.common_value_is_' + rowid).attr('custm_isd');

        if (requireOrNot != "") {
            if (valueIs != "") {
                $('#common_error_span_' + rowid).css({
                    "display": "none"
                });
                $('.error_customfield_main_div_' + rowid).removeClass('has-error');
            } else {
                $('#common_error_span_' + rowid).text(labelName + " : " + msg1);
                $('#common_error_span_' + rowid).css({
                    "display": ""
                });
                $('.error_customfield_main_div_' + rowid).addClass('has-error');
            }
        }
    });
</script>
@endsection