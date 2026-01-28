<html {{ getLangCode() === 'ar' ? 'ar' : 'en' }}" dir="{{ getLangCode() === 'ar' ? 'rtl' : 'ltr' }}">

<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

  <style type="text/css">
    @if (getLangCode()==='hi')body,
    p,
    td,
    div,
    th {
      font-family: Poppins;
    }
    @elseif (getLangCode() === 'ar')
    body,
    p,
    td,
    div,
    th {
      font-family: dejavusans;
      direction: rtl;
      text-align: right;
    }
    @elseif (getLangCode()==='gu') body,
    p,
    td,
    div,
    th {
      font-family: Poppins;
    }

    @elseif (getLangCode()==='ja') body,
    p,
    td,
    div,
    th {
      font-family: Poppins;
    }

    @elseif (getLangCode()==='zhcn') body,
    p,
    td,
    div,
    th {
      font-family: Poppins; // not working
    }

    @elseif (getLangCode()==='th') body,
    p,
    td,
    div,
    th,
    strong {
      font-family: Poppins;
    }

    @elseif (getLangCode()==='mr') body,
    p,
    td,
    div,
    th {
      font-family: Poppins;
    }

    @elseif (getLangCode()==='ta') body,
    p,
    td,
    div,
    th,
    strong {
      font-family: Poppins;
    }

    @else body,
    p,
    td,
    div,
    th,
    strong {
      font-family: Poppins;
    }

    @endif
    /* * {
      font-family: Poppins;
    }
    body, p, td, div, th { font-family: Poppins; } */

    @font-face {
      font-family: "Poppins" !important;
      font-weight: normal;
      font-style: italic;
    }

    body {
      font-family: "Poppins";
    }
  </style>
  <style>
    .invoice_print {
      font-size: 14px;
    }

    .mail_img {
      width: 9px;
      margin-top: 8px;
    }

    .system_addr {
      padding-top: 5%;
      line-height: 25px;
    }

    .heading_gatepass {
      align-items: center;
      margin-left: 40%;
    }

    .itemtable {
      font-size: 14px;
      line-height: 25px;
    }

    .padding-8 {
      padding: 8px;
    }
  </style>
</head>

<body>
  <div class="row" id="invoice_print">
    <table width="100%" border="0" style="margin:0px 8px 0px 8px; font-family:Poppins;">
      <tr>
        <td align="left">
          <h3 style="font-size:18px;"><?php echo $logo->system_name; ?></h3>
        </td>
      </tr>
    </table>
    <hr />
    <table width="100%" border="0">
      <tbody>
        <tr>
          <td style="vertical-align:top; float:left; width:10%;" align="left">
            <span style="float:left; width:100%; margin:10px ">
              <img src="{{ base_path() }}/public/product/<?php echo $product->product_image; ?>" width="100px">
            </span>
          </td>
          <td style="width: 45%; vertical-align:top;">
            <span style="float:right; font-size: 14px;" class="system_addr">
              {{ trans('message.Product Number') }}:<b>{{ $product->product_no; }}</b><br>
              {{ trans('message.Product Name') }}:<b>{{ $product->name; }}</b>
            </span>

          </td>
        </tr>
      </tbody>
    </table>
    <br><br>
    <h3 class="heading_gatepass"><u>{{ trans('message.Product Details') }}</u></h3>
    <br>
    <table class="table table-bordered itemtable" width="100%" border="1" style="border-collapse:collapse;">
      <tbody class="itemtable">

        <tr>
          <td class="padding-8">{{ trans('message.Product Number') }}:</td>
          <td class="padding-8"><b>{{ $product->product_no ?? 'Not Added' }}</b></td>
        </tr>

        <tr>
          <td class="padding-8">{{ trans('message.Purchase Date') }}:</td>
          <td class="padding-8"><b>{{ $product->product_date ?? 'Not Added' }}</b></td>
        </tr>

        <tr>
          <td class="padding-8">{{ trans('message.Product Name') }}:</td>
          <td class="padding-8"><b>{{ $product->name ?? 'Not Added' }}</b></td>
        </tr>

        <tr>
          <td class="padding-8">{{ trans('message.Manufacturer Name') }}:</td>
          <td class="padding-8"><b>{{ getProductName($product->product_type_id) ?? 'Not Added' }}</b></td>
        </tr>

        <tr>
          <td class="padding-8">{{ trans('message.Price') }} (<?php echo getCurrencySymbols(); ?>):</td>
          <td class="padding-8"><b>{{ $product->price ?? 'Not Added' }}</b></td>
        </tr>

        <tr>
          <td class="padding-8">{{ trans('message.Unit Of Measurement') }}:</td>
          <td class="padding-8"><b>{{ getUnitName($product->unit) ?? 'Not Added' }}</b></td>
        </tr>

        <tr>
          <td class="padding-8">{{ trans('message.Suppliers') }}:</td>
          <td class="padding-8"><b>{{ getSupplierFullName($product->supplier_id) ?? 'Not Added' }}</b></td>
        </tr>

        <tr>
          <td class="padding-8">{{ trans('message.Company Name') }}:</td>
          <td class="padding-8"><b>{{ getCompanyNames($product->supplier_id) ?? 'Not Added' }}</b></td>
        </tr>

        <tr>
          <td class="padding-8">{{ trans('message.Warranty') }}:</td>
          <td class="padding-8"><b>{{ $product->warranty ?? 'Not Added' }}</b></td>
        </tr>

        <tr>
          <td class="padding-8">{{ trans('message.Color') }}:</td>
          <td class="padding-8">
        @if($product->color_id)
          <div style="display: inline-block;width: 40px; height: 20px; background-color: {{ getColor($product->color_id) }}; border: 1px solid #333;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
          &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</div>
        @else
          <b>{{ trans('message.Not Added') }}</b>
        @endif
      </td>

        </tr>
      </tbody>
    </table>

    <br />
    <?php
    if ($product->notes->isEmpty()) {
    } else {
    ?>
      <table class="table table-bordered itemtable" width="100%" border="1" style="border-collapse:collapse;">
        <tbody>
          <tr class="printimg" style=" color:#333;">
            <th align="left" style="padding:8px; font-size:14px;" colspan="2">{{ trans('message.Notes') }}</th>
          </tr>
          <?php
          foreach ($product->notes as $key => $note) {
          ?>
            <tr>
              <td align="left" style="padding:8px;">
                <p><strong>Notes By {{ getUserFullName($note->create_by) }} On {{ $note->created_at->setTimezone(Auth::User()->timezone) }}</strong></p>
                <p>{{ $note->notes }}</p>
              </td>
              <td align="left" style="padding:8px;">
                <strong>
                  <p class="text-start mb-0">{{ trans('message.Attachments') }} :</p>
                </strong>
                <?php
                $attachments = \App\note_attachments::where('note_id', '=', $note->id)->get();
                if ($attachments->isEmpty()) {
                ?>
                  <p class="text-start text-danger">{{ trans('message.Not Added') }} :</p>
                <?php
                } else {
                ?>
                  <p class="text-start text-danger">{{ count($attachments) }} attachment(s)</p>
                <?php } ?>
              </td>
            </tr>
          <?php
          }
          ?>
        </tbody>
      </table>
    <?php
    }
    ?>
    <br />
  </div>
</body>

</html>