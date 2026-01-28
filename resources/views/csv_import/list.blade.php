@extends('layouts.app')
@section('content')

<!-- page content -->
<style>.table-responsive::-webkit-scrollbar {
    height: 8px; /* Height of the horizontal scrollbar */
}

.table-responsive::-webkit-scrollbar-track {
    background:rgb(196, 194, 193); /* Background of the scrollbar track */
}

.table-responsive::-webkit-scrollbar-thumb {
    background:rgb(214, 214, 213); /* Scrollbar thumb color */
    border-radius: 4px; /* Rounded edges for thumb */
}

.table-responsive::-webkit-scrollbar-thumb:hover {
    background: grey; /* Change color on hover for better visibility */
}</style>
<div class="right_col" role="main">
  <div class="">
    <div class="page-title">
      <div class="nav_menu">
        <nav>
          <div class="nav toggle">
          <a id="menu_toggle"><i class="fa fa-bars sidemenu_toggle"></i></a><a href="{{ URL::previous() }}" id=""><i class=""><img src="{{ URL::asset('public/supplier/Back Arrow.png') }}" class="back-arrow"></i><span class="titleup">
                {{ trans('message.CSV IMPORT') }}</span></a>
          </div>
          @include('dashboard.profile')
        </nav>
      </div>
    </div>
    @include('success_message.message')
    @if(session('error'))
      <!-- <div class="alert alert-danger">{{ session('error') }}</div> -->
      <div class="row massage">
        <div class="col-md-12 col-sm-12 col-xs-12">
          <div class="checkbox checkbox-success checkbox-circle mb-2 alert alert-danger alert-dismissible fade show">
            <label for="checkbox-10 colo_success" style="margin-left: 20px;font-weight: 600;">{!! session('error') !!}</label>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close" style="padding: 1rem 0.75rem;"></button>
          </div>
        </div>
      </div>
    @endif 
    <div class="x_content table-responsive">
      @include('settings_navbar.settings_nav')
    </div>
    <div class="clearfix"></div>
    <div class="row">
      <div class="col-md-12 col-lg-12 col-xl-12 col-xxl-12 col-sm-12 col-xs-12">
        <div class="x_panel">
          <div class="x_content">
              @can('csvimport_view')
              <div class="col-md-12 col-lg-12 col-xl-12 col-xxl-12 col-sm-12 col-xs-12 space">
                <h4><b>{{ trans('message.CSV IMPORT') }}</b></h4>
                <p class="col-md-12 col-lg-12 col-xl-12 col-xxl-12 col-sm-12 col-xs-12 ln_solid"></p>
              </div>

              <div class="row mt-3">
                <label class="control-label col-md-2 col-lg-2 col-xl-2 col-xxl-2 col-sm-2 col-xs-2 checkpointtext text-end" for="Country">{{ trans('message.Select Module For Bulk Upload') }} <label class="color-danger">*</label>
                </label>
                <div class="col-md-4 col-lg-4 col-xl-4 col-xxl-4 col-sm-4 col-xs-4">
                  <select class="form-control branchsetting form-select" name="select_module" required>
                    <option value="Users">{{trans('message.Users')}}</option>
                    <option value="Vehicles">{{trans('message.Vehicles')}}</option>
                    <option value="Products">{{trans('message.Products')}}</option>
                
                  </select>
                </div>
                <div class="col-md-3 col-lg-3 col-xl-3 col-xxl-3 col-sm-3 col-xs-3"></div>
              </div>
              @endcan

      

              <div class="row mt-3">
                <!-- <div class="row col-md-6 col-lg-6 col-xl-6 col-xxl-6 col-sm-6 col-xs-6 form-group">
                  <a class="btn branchsettingCancel" href="{{ URL::previous() }}">{{ trans('message.CANCEL') }}</a>
                </div> -->
                <div class="row col-md-6 col-lg-6 col-xl-6 col-xxl-6 col-sm-6 col-xs-6 form-group">
                  <button type="submit" class="btn btn_success_margin" id="importbtn">{{ trans('message.SELECT') }}</button>
                </div>
              </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<!-- CSV User Import Modal -->
<div class="modal fade-in" id="importCsvModal" tabindex="-1" role="dialog" aria-labelledby="importCsvLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="importCsvLabel">{{ trans('message.Import Users from CSV') }}</h5>
        <button type="button" class="close" id="userclose" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <a href="{{ url('/download_sample_csv') }}" class="btn btn-info mb-3">{{ trans('message.Download Sample CSV') }}</a>
        <form id="csvUploadFormUsers" action="{{ url('/users_upload') }}" method="POST" enctype="multipart/form-data">
          @csrf
          <div class="form-group">
            <label for="csv_file">{{ trans('message.Upload CSV File') }}</label>
            <input type="file" class="form-control" name="csv_file_users" id="csv_file_users" required>
          </div>
          <button type="submit" class="btn btn-success mt-2">{{ trans('message.Submit') }}</button>
        </form>
        <div class="alert  mb-2">
          <strong style="margin-left:-15px; text-align:justify">{{trans('message.Note')}} :</strong>{{trans('message.In the Role column, You can add Number for following roles')}}.
          <ul class="mb-0 mt-1">
            <li>{{trans('message.Customer')}} = 1</li>
            <li>{{trans('message.Employee')}} = 2</li>
            <li>{{trans('message.Support Staff')}} = 3</li>
            <li>{{trans('message.Accountant')}} = 4</li>
          </ul>
        </div>
      </div>
    </div>
  </div>
</div>
<!-- end csv modal -->
 <!-- CSV Products Import Modal -->
<div class="modal fade-in" id="productsModal" tabindex="-1" role="dialog" aria-labelledby="importCsvLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="importCsvLabel">{{ trans('message.Import Products from CSV') }}</h5>
        <button type="button" class="close" id="productclose" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <a href="{{ url('/download_products_sample_csv') }}" class="btn btn-info mb-3">{{ trans('message.Download Sample CSV') }}</a>
        <form id="csvUploadFormProducts" action="{{ url('/products_upload') }}" method="POST" enctype="multipart/form-data">
          @csrf
          <div class="form-group">
            <label for="csv_file">{{ trans('message.Upload CSV File') }}</label>
            <input type="file" class="form-control" name="csv_file_products" id="csv_file_products" required>
          </div>
          <button type="submit" class="btn btn-success mt-2">{{ trans('message.Submit') }}</button>
        </form>
        <div class="alert  mb-2">
          <strong style="margin-left:-15px; text-align:justify">{{trans('message.Note')}} :</strong>Please add supplier company name in suppliers column.Make sure this added company name is exist in suppliers.If not exist then add it in suppliers first.
        </div>
      </div>
    </div>
  </div>
</div>
<!-- end csv modal -->
 <!-- CSV Vehicle import Modal -->
<div class="modal fade-in" id="vehiclesModal" tabindex="-1" role="dialog" aria-labelledby="importCsvLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="importCsvLabel">{{ trans('message.Import Vehicles from CSV') }}</h5>
        <button type="button" class="close" id="vehicleclose" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <a href="{{ url('/download_vehicle_sample_csv') }}" class="btn btn-info mb-3">{{ trans('message.Download Sample CSV') }}</a>
        <form id="csvUploadFormVehicle" action="{{ url('/vehicle_upload') }}" method="POST" enctype="multipart/form-data">
          @csrf
          <div class="form-group">
            <label for="csv_file">{{ trans('message.Upload CSV File') }}</label>
            <input type="file" class="form-control" name="csv_file_vehicles" id="csv_file_vehicles" required>
          </div>
          <button type="submit" class="btn btn-success mt-2">{{ trans('message.Submit') }}</button>
        </form>
        <!-- <div class="alert  mb-2">
          <strong style="margin-left:-15px; text-align:justify">{{trans('message.Note')}} :</strong>{{trans('message.In the Role column, You can add Number for following roles')}}.
          <ul class="mb-0 mt-1">
            <li>{{trans('message.Customer')}} = 1</li>
            <li>{{trans('message.Employee')}} = 2</li>
            <li>{{trans('message.Support Staff')}} = 3</li>
            <li>{{trans('message.Accountant')}} = 4</li>
          </ul>
        </div> -->
      </div>
    </div>
  </div>
</div>
<!-- end csv modal -->
<!-- page content end -->
<script nonce="{{ $cspNonce }}">
  $(document).ready(function () {
    $('#userclose').click(function () {
      $('#importCsvModal').modal('hide');
    });
    $('#productclose').click(function () {
      $('#productsModal').modal('hide');
    });
    $('#vehicleclose').click(function () {
      $('#vehiclesModal').modal('hide');
    });  
    $('#importbtn').click(function (e) {
        e.preventDefault(); // Prevent form submission

        var selectedModule = $('select[name="select_module"]').val();
        console.log(selectedModule);
        // Open the corresponding modal
        if (selectedModule === 'Users') {
            $('#importCsvModal').modal('show');
        } else if (selectedModule === 'Vehicles') {
            $('#vehiclesModal').modal('show');
        } else if (selectedModule === 'Products') {
            $('#productsModal').modal('show');
        }
    });
});
</script>

@endsection