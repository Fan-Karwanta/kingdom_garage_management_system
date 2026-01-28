<script nonce="{{ $cspNonce }}">
  function printdiv(el) {
    if ("{{ $page_action }}" === "mobile_app") {
      window.location.href = "{{ url('/stoke/list/stockPdf/' . $stockid) }}?page_action={{ $page_action }}";
    } else {
      var modalContentUrl = "{{ url('/stoke/list/stockprint') }}?stockid={{ $stockid }}";
      // Open modal content URL in a new tab
      var newTab = window.open(modalContentUrl, '_blank');
      newTab.onload = function() {                
                    var spinner = newTab.document.querySelector('.loading-spinner'); // Replace with your actual class or ID
                    if (spinner) spinner.style.display = 'none';

                    // Delay printing to ensure the spinner is removed
                    setTimeout(() => {
                        newTab.print();
                    }, 300);
                };
    }
  }
</script>

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
<div class=" col-md-6 col-lg-6 col-xl-6 col-xxl-6 col-sm-6 col-xs-6 modal-footer ps-0" @if(getLangCode() == 'ar') style="width: 22% !important;" @endif>

  <img src="{{ URL::asset('public/product/Print.png') }} " type="button" class="stock_print_img mx-0" id="printBtn">
  <a href="{{ url('/stoke/list/stockPdf/' . $stockid) }}?page_action={{ $page_action }}" class="prints tagAforPdfBtn"><button type="button" class="btn btn-outline-secondary pdfButton btn-sm mx-0"><img src="{{ URL('public/img/icons/PDF.png') }}" class="pdfButton"></button></a>
  <a href="{!! url('/stoke/list') !!}" class="prints ms-0"><input type="submit" class="btn btn-secondary stoke" data-bs-dismiss="modal" value={{ trans('message.Close') }}></a>

  {{-- <button type="button"
      class="btn btn-secondary "
      data-bs-dismiss="modal">{{ trans('message.Close') }}</button> --}}
</div>
<script nonce="{{ $cspNonce }}">
  const printBtn = document.getElementById('printBtn');
  if (printBtn) {
      printBtn.addEventListener('click', function () {
        printdiv('stockprint');
      });
  }
</script>