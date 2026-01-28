<!DOCTYPE html>
<html dir="{{ getLangCode() === 'ar' ? 'rtl' : 'ltr' }}" lang="{{ getLangCode() }}">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <style type="text/css">
        body,
        p,
        td,
        div,
        th {
            font-family: "Poppins", sans-serif;
            direction: {{ getLangCode() === 'ar' ? 'rtl' : 'ltr' }};
            text-align: {{ getLangCode() === 'ar' ? 'right' : 'left' }};
        }

        .margin-bottom {
            margin-bottom: 1rem;
        }

        .heading_gatepass {
            align-items: center;
            text-align: center;
        }

        .fw-bold {
            font-weight: 700 !important;
            color: #333;
        }

        .padding-8 {
            padding: 8px;
        }

        table {
            border-collapse: collapse;
            width: 100%;
        }

        th, td {
            padding: 8px;
            border: 1px solid #ddd;
            text-align: {{ getLangCode() === 'ar' ? 'right' : 'left' }};
        }
    </style>
</head>

<body>
    <table border="0" style="margin: 0px 8px; font-family: Poppins;">
        <tr>
            <td align="{{ getLangCode() === 'ar' ? 'right' : 'left' }}">
                <h3 style="font-size: 18px;">{{ $logo->system_name }}</h3>
            </td>
        </tr>
    </table>
    <hr />
    <table border="0">
        <tbody>
            <tr>
                <td width="50%">
                    <img src="{{ base_path() }}/public/general_setting/{{ $logo->logo_image }}" width="230px" height="70px">
                </td>
                <td width="50%">
                    <div class="col-xl-12 col-md-12 col-sm-12 mb-3">
                        <label class="fw-bold"><b>{{ trans('message.Product Code') }}: </b></label>
                        <label> {{ $product->product_no }} </label>
                    </div>
                    <div class="col-xl-12 col-md-12 col-sm-12 mb-3">
                        <label class="fw-bold"><b>{{ trans('message.Manufacturer Name') }}: </b></label>
                        <label> {{ getProductName($product->product_type_id) }} </label>
                    </div>
                    <div class="col-xl-12 col-md-12 col-sm-12">
                        <label class="fw-bold"><b>{{ trans('message.Product Name') }}: </b></label>
                        <label> {{ $product->name }} </label>
                    </div>
                </td>
            </tr>
        </tbody>
    </table>
    <br><br>
    <hr />
    <h3 class="heading_gatepass"><u>{{ trans('message.PURCHASE DETAILS') }}</u></h3>
    <br>
    <table class="margin-bottom table-bordered" border="1">
        <thead>
            <tr>
                <th>{{ trans('message.Purchase Date') }}</th>
                <th>{{ trans('message.Supplier Name') }}</th>
                <th>{{ trans('message.Quantity') }}</th>
            </tr>
        </thead>
        <tbody>
            @if(count($stockdata) !== 0)
                @foreach($stockdata as $stock)
                <tr>
                    <td align="center">{{ date(getDateFormat(), strtotime($stock->date)) }}</td>
                    <td align="center">{{ getSupplierName($stock->supplier_id) }}</td>
                    <td align="center">{{ $stock->qty }}</td>
                </tr>
                @endforeach
            @else
                <tr>
                    <td colspan="3">{{ trans('message.No data available in table.') }}</td>
                </tr>
            @endif
        </tbody>
    </table>
    <table class="margin-bottom table-bordered" border="1">
        <tbody>
            <tr>
                <td colspan="2" class="text-right padding-8">
                    <label class="fw-bold"><b>{{ trans('message.Sales Stock:') }} </b></label>
                    <label>{{ $celltotal }}</label>
                </td>
            </tr>
        </tbody>
    </table>
    <table class="margin-bottom table-bordered" border="1">
        <tbody>
            <tr>
                <td colspan="2" class="text-right padding-8">
                    <label class="fw-bold"><b>{{ trans('message.Service Stock') }}:</b></label>
                    <label>{{ $product_service_stocks_total }}</label>
                </td>
            </tr>
        </tbody>
    </table>
    <table class="margin-bottom table-bordered" border="1">
        <tbody>
            <tr>
                <td colspan="2" class="text-right padding-8">
                    <label class="fw-bold"><b>{{ trans('message.Current Stock:') }} </b></label>
                    <label>{{ getStockCurrent($p_id) }}</label>
                </td>
            </tr>
        </tbody>
    </table>
</body>

</html>
