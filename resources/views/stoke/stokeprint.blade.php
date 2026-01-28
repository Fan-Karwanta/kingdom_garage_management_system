@extends('layouts.app')
@section('content')
<!-- page content -->
<style>@media print {
    .right_col {
        margin-right: 0px !important;
    }
}
</style>
<div class="right_col" role="main">
    <div id="stockprint">
        <!-- <table width="100%" border="0">
        <tbody>
          <tr>
            <td align="right">
              <div class="col-xl-12 col-md-12 col-sm-12">
                <label class="fw-bold"><?php $nowdate = date('Y-m-d'); ?>{{ trans('message.Date') }} : </label>
                <label class=""> <?php echo date(getDateFormat(), strtotime($nowdate)); ?> </label>
              </div>
            </td>
          </tr>
        </tbody>
      </table> -->
        <h4> {{ getNameSystem() }} </h4>
        <hr>
        <table border="0">
            <tbody>
                <tr>
                    <td width="50%">
                        <!-- <h4 class="text-center">{{ $logo->system_name }}</h4> -->
                        <img src="{{ url('public/general_setting/' . $logo->logo_image) }}" class="system_logo_img">
                    </td>
                    <td width="20%">
                        <div class="col-xl-12 col-md-12 col-sm-12 mb-3">
                            <label class="fw-bold">{{ trans('message.Product Code') }} : </label>
                            <label class=""> <?php echo $product->product_no; ?> </label>
                        </div>
                        <div class="col-xl-12 col-md-12 col-sm-12 mb-3">
                            <label class="fw-bold">{{ trans('message.Manufacturer Name') }} : </label>
                            <label class=""> <?php echo getProductName($product->product_type_id); ?> </label>
                        </div>
                        <div class="col-xl-12 col-md-12 col-sm-12">
                            <label class="fw-bold">{{ trans('message.Product Name') }} : </label>
                            <label class=""> <?php echo $product->name; ?> </label>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
        <br />
        </hr>
        <table width="100%" border="0">
            <tbody>
                <tr>
                    <td align="left">
                        <h4 class="text-center mb-3">{{ trans('message.PURCHASE DETAILS') }}</h4>
                    </td>
                </tr>
            </tbody>
        </table>

        <div class="table-responsive">
            <table class="table table-bordered table-responsive" border="1" style="border-collapse:collapse;">
                <thead>
                    <tr>
                        <th class="text-center fw-bold">{{ trans('message.Purchase Date') }}</th>
                        <th class="text-center fw-bold">{{ trans('message.Supplier Name') }}</th>
                        <th class="text-center fw-bold">{{ trans('message.Quantity') }}</th>
                    </tr>
                </thead>
                <tbody>

                    <?php $total = 0;
                    // if(!empty($stockdata))
                    if (count($stockdata) !== 0) {

                        foreach ($stockdata as $stockdatas) { ?>
                            <tr>
                                <td class="text-center"><?php echo date(getDateFormat(), strtotime($stockdatas->date)); ?></td>
                                <td class="text-center"><?php echo getSupplierName($stockdatas->supplier_id); ?></td>
                                <td class="text-center"><?php echo $stockdatas->qty; ?></td>
                                <?php $total += $stockdatas->qty; ?>
                            </tr>
                        <?php }
                    } else {
                        ?>
                        <tr>
                            <td class="text-center" colspan="7">{{ trans('message.No data available in table.') }}</td>
                        </tr>
                    <?php
                    }
                    ?>
                </tbody>
            </table>
        </div>
        <!-- <table class="table" style="border:1px solid #ddd" width="100%">
        <tbody>
          <tr>
            <td colspan="2" class="text-right" align="right">
              <div class="col-xl-6 col-md-6 col-sm-12 me-50">
                <label class="fw-bold">{{ trans('message.Total Stock:') }}&nbsp;&nbsp;&nbsp; </label>
                <label class=""> <?php echo $total; ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </label>
              </div>
    
            </td>
          </tr>
        </tbody>
      </table> -->
        <table class="table" style="border:1px solid #ddd" width="100%">
            <tbody>
                <tr>
                    <td colspan="2" class="text-right" align="right">
                        <div class="col-xl-6 col-md-6 col-sm-12 me-50">
                            <label class="fw-bold"> {{ trans('message.Sales Stock:') }}&nbsp;&nbsp;&nbsp; </label>
                            <label class=""> <?php echo $celltotal; ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>
                        </div>
                        <!-- {{ trans('message.Sales Stock:') }} &nbsp; &nbsp; <?php echo $celltotal; ?></td> -->
                </tr>
            </tbody>
        </table>
        <table class="table" style="border:1px solid #ddd" width="100%">
            <tbody>
                <tr>
                    <td colspan="2" class="text-right" align="right">
                        <div class="col-xl-6 col-md-6 col-sm-12 me-50">
                            <label class="fw-bold"> {{ trans('message.Service Stock') }}:&nbsp;&nbsp;&nbsp; </label>
                            <label class=""> <?php echo $product_service_stocks_total; ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>
                        </div>
                        <!-- {{ trans('message.Service Stock') }}: &nbsp; &nbsp; <?php echo $product_service_stocks_total; ?></td> -->
                </tr>
            </tbody>
        </table>
        <table class="table" style="border:1px solid #ddd" width="100%">
            <tbody>
                <tr> <?php $Currentstock = $total - $sale_service_stock; ?>
                    <td colspan="2" class="text-right" align="right">
                        <div class="col-xl-6 col-md-6 col-sm-12 me-50">
                            <label class="fw-bold"> {{ trans('message.Current Stock:') }}&nbsp;&nbsp;&nbsp; </label>
                            <label class="">{{ getStockCurrent($p_id) }}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </label>
                        </div>
                        <!-- {{ trans('message.Current Stock:') }} &nbsp; &nbsp; <?php echo $Currentstock; ?></td> -->
                </tr>
            </tbody>
        </table>
    </div>
</div>
<!-- /page content -->
@endsection