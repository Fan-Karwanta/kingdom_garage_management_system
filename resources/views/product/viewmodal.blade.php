<style>
  button.btn.btn-outline-secondary {
    margin-left: 0px !important;
  }
  @media screen and (max-width:540px) {
    .printbtn {
      display: none !important;
    }
    
  }
</style>
<div id="print_data">
  <div class="modal-header">
    <h5 class="modal-title fw-bold" id="exampleModalLabel">{{ getNameSystem() }}</h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
  </div>
  <div class="row ms-2">
    <div class="col-md-2 col-sm-2 col-xs-2 col-xl-2 col-xxl-2 col-lg-2 mt-2">
      <img src="{{ URL::asset('public/product/' . $product->product_image) }}" width="100px" class="">
    </div>
    <div class="col-md-10 col-sm-10 col-xs-10 col-xl-10 col-xxl-10 col-lg-10 mt-4 pt-2">
      <div class="mb-1">{{ trans('message.Product Number') }}:
        <span class="txt_color fw-bold">{{ $product->product_no; }}</span>
      </div>
      <div class="mb-1">{{ trans('message.Product Name') }}:
        <span class="txt_color fw-bold">{{ $product->name; }}</span>
      </div>
    </div>
  </div>
  <hr />
  <tr>
    <h3 align="center"><u>{{ trans('message.Product Details') }}</u></h3><br>
  </tr>

  <div class="modal-body">
    <div class="row p-2">
      <table class="table table-bordered table-responsive gate_pass" width="100%" border="1" style="border-collapse:collapse;">

        <tbody>

          <tr>
            <td class="">{{ trans('message.Purchase Date') }}:</td>
            <td class="txt_color fw-bold">{{ $product->product_date ?? 'Not Added' }}</td>
          </tr>

          <tr>
            <td class="">{{ trans('message.Manufacturer Name') }}:</td>
            <td class="txt_color fw-bold">{{ getProductName($product->product_type_id) ?? 'Not Added' }}</td>
          </tr>

          <tr>
            <td class="">{{ trans('message.Price') }} (<?php echo getCurrencySymbols(); ?>):</td>
            <td class="txt_color fw-bold">{{ $product->price ?? 'Not Added' }}</td>
          </tr>

          <tr>
            <td class="">{{ trans('message.Selling Price') }} (<?php echo getCurrencySymbols(); ?>):</td>
            <td class="txt_color fw-bold">{{ $product->selling_price ?? 'Not Added' }}</td>
          </tr>

          <tr>
            <td class="">{{ trans('message.Unit Of Measurement') }}:</td>
            <td class="txt_color fw-bold">{{ getUnitName($product->unit) ?? 'Not Added' }}</td>
          </tr>

          <tr>
            <td class="">{{ trans('message.Suppliers') }}:</td>
            <td class="txt_color fw-bold">{{ getSupplierFullName($product->supplier_id) ?? 'Not Added' }}</td>
          </tr>

          <tr>
            <td class="">{{ trans('message.Company Name') }}:</td>
            <td class="txt_color fw-bold">{{ getCompanyNames($product->supplier_id) ?? 'Not Added' }}</td>
          </tr>

          <tr>
            <td class="">{{ trans('message.Warranty') }}:</td>
            <td class="txt_color fw-bold">{{ $product->warranty ?? 'Not Added' }}</td>
          </tr>

          <tr>
            <td class="">{{ trans('message.Color') }}:</td>
            <td class="txt_color fw-bold">
              <div class="{{ $product->color_id ? 'color_code' : '' }}" style="background-color:{{ $product->color_id ? getColor($product->color_id) : 'transparent' }};">
                {{ $product->color_id ?? 'Not Added' }}
              </div>
            </td>
          </tr>
        </tbody>
      </table>

      @if($product->notes->isEmpty())
      @else
      <div class="px-3">
        <div class="col-xl-12 col-md-12 col-sm-12">
          <p class="fw-bold overflow-visible h5">{{ trans('message.Notes') }}</p>
          <div class="row">
            <ul class="list-unstyled scroll-view mb-0">
              @foreach ($product->notes as $key => $note)
              <li class="row media event d-flex align-items-center guardian_div my-3 left-border viewNote">
                <div class="media-body col-xl-6 col-md-6 col-sm-6">
                  <p><strong>Notes By {{ getUserFullName($note->create_by) }} On {{ $note->created_at->setTimezone(Auth::User()->timezone) }}</strong></p>
                  <p>{{ $note->notes }}</p>
                </div>
                <div class="media-body col-xl-6 col-md-6 col-sm-6">
                  @php
                  $attachments = \App\note_attachments::where('note_id','=', $note->id)->get();
                  @endphp
                  @if($attachments->isEmpty())
                  <br><br><br><br>
                  @else
                  <strong>
                    <p>{{ trans('message.Attachments') }} :</p>
                  </strong>
                  @foreach ($attachments as $attachment)
                  @php
                  $extension = pathinfo($attachment->attachment, PATHINFO_EXTENSION);
                  $attachmentUrl = URL::asset('public/notes/' . basename($attachment->attachment));
                  @endphp
                  @if (in_array($extension, ['jpg', 'jpeg', 'png', 'gif']))
                  <a href="{{ $attachmentUrl }}" target="_blank" data-toggle="tooltip" data-placement="bottom" title="{{ basename($attachment->attachment) }}" class="text-primary">
                    <img src="{{ $attachmentUrl }}" width="55px" class="rounded me-2">
                  </a>
                  @elseif ($extension === 'pdf')
                  <a href="{{ $attachmentUrl }}" target="_blank" data-toggle="tooltip" data-placement="bottom" title="{{ basename($attachment->attachment) }}" class="text-primary">
                    <img src="{{ asset('public/img/icons/pdf_download.png') }}" width="55px" class="rounded me-2">
                  </a>
                  @else
                  <a href="{{ $attachmentUrl }}" target="_blank" data-toggle="tooltip" data-placement="bottom" title="{{ basename($attachment->attachment) }}" class="text-primary">
                    <img src="{{ asset('public/img/icons/video.png') }}" width="55px" class="rounded me-2">
                  </a>
                  @endif
                  @endforeach
                  @endif
                </div>
              </li>
              @endforeach

            </ul>
          </div>
        </div>
      </div>
      @endif

    </div>
  </div>
</div>
</div>

<div class="modal-footer">
  <button type="button" class="btn btn-outline-secondary printbtn btn-sm ms-0 me-0" id="printBtn"><img src="{{ URL('public/img/icons/Print (1).png') }}" class="pdfButton"></button>
  <a href="{{ url('product/modalpdf/' . $product_id) }}?page_action=default" id="pdfDownloadBtn" class="prints tagAforPdfBtn"><button type="button" class="btn btn-outline-secondary pdfButton btn-sm mx-0"><img src="{{ URL('public/img/icons/PDF.png') }}" class="pdfButton"></button></a>
  <button type="button" class="btn btn-outline-secondary btn-sm mx-0" data-bs-dismiss="modal">{{ trans('message.Close') }}</button>
</div>
<script nonce="{{ $cspNonce }}">
  const printBtn = document.getElementById('printBtn');
  if (printBtn) {
      printBtn.addEventListener('click', function () {
        PrintElem('print_data');
      });
  }

    const isMobile = window.innerWidth < 500;
    console.log(isMobile);
    const btn = document.getElementById('pdfDownloadBtn');
    if (isMobile && btn) {
      const currentHref = btn.getAttribute('href');
      const updatedHref = currentHref.replace(/page_action=default/, 'page_action=mobile_app');
      btn.setAttribute('href', updatedHref);
    }
</script>
<script nonce="{{ $cspNonce }}" language="javascript">
  
  function PrintElem(el) {
    if ("{{ $page_action }}" === "mobile_app") {
      window.location.href = "{{ url('product/modalpdf/' . $product_id) }}?page_action={{ $page_action }}";
    } else {
      var modalContentUrl = "{{ url('product/modalprint') }}?product_id={{ $product_id }}";
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