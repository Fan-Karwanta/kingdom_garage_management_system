<div class="dropdown_profile ulprofile">
    <?php $userid = Auth::User()->id; ?>
    @if (getAccessStatusUser('Settings', $userid) == 'yes')
    @if (getActiveAdmin($userid) == 'yes')
    <a href="{!! url('setting/general_setting/list') !!}"><img src="{{ URL::asset('public/img/dashboard/Settings.png') }}" alt="admin" width="40px" height="40px" class="border-0 m-1  display-left"></a>
    @else
    <a href="{!! url('setting/timezone/list') !!}"><img src="{{ URL::asset('public/img/dashboard/Settings.png') }}" alt="admin" width="40px" height="40px" class="border-0 m-1  display-left"></a>
    @endif
    @endif
    <div class="vr display-none"></div>
    <a href="javascript:;" class=" dropdown_profile pt-2 pb-2 authpic" data-bs-toggle="dropdown" aria-expanded="false">
      @if (!empty(Auth::user()->id))
      @if (Auth::user()->role == 'admin')
      <img src="{{ URL::asset('public/admin/' . Auth::user()->image) }}" alt="admin" width="40px" height="40px" class="rounded  display-right">
      @endif

      @if (Auth::user()->role == 'Customer')
      <img src="{{ URL::asset('public/customer/' . Auth::user()->image) }}" alt="customer" width="40px" height="40px" class="rounded  display-right">
      @endif

      @if (Auth::user()->role == 'Supplier')
      <img src="{{ URL::asset('public/supplier/' . Auth::user()->image) }}" alt="supplier" width="40px" height="40px" class="rounded  display-right">
      @endif

      @if (Auth::user()->role == 'employee')
      <img src="{{ URL::asset('public/employee/' . Auth::user()->image) }}" alt="employee" width="40px" height="40px" class="rounded  display-right">
      @endif

      @if (Auth::user()->role == 'supportstaff')
      <img src="{{ URL::asset('public/supportstaff/' . Auth::user()->image) }}" alt="supportstaff" width="40px" height="40px" class="rounded  display-right">
      @endif

      @if (Auth::user()->role == 'accountant')
      <img src="{{ URL::asset('public/accountant/' . Auth::user()->image) }}" alt="accountant" width="40px" height="40px" class="rounded  display-right">
      @endif

      @if (Auth::user()->role == 'branch_admin')
      <img src="{{ URL::asset('public/branch_admin/' . Auth::user()->image) }}" alt="accountant" width="40px" height="40px" class="rounded  display-right">
      @endif

      @if (Auth::user()->role == '')
      <img src="{{ URL::asset('public/customer/' . Auth::user()->image) }}" alt="customer" width="40px" height="40px" class="rounded  display-right">
      @endif
      @endif


      <!-- @if (!empty(Auth::user()->id))
        {{ Auth::user()->name }}
      @endif -->
      {{-- <span class=" fa fa-angle-down"></span> --}}
    </a>
    <ul class="dropdown-menu dropdown-usermenu float-end" style="width: 1px;">
      <li><a class="dropdown-item profile" href="{!! url('setting/profile') !!}"><i class="fa fa-user me-2" aria-hidden="true"></i>{{ trans('message.Profile') }}</a></li>
      <li>
        <a class="logoutConfirm dropdown-item"><i class="fa fa-power-off" aria-hidden="true"></i> {{ trans('message.Logout') }}</a>
        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
          @csrf
        </form>
        <!-- <a title="{{trans('message.Logout')}}" href="#" onclick="event.preventDefault();document.getElementById('logout-form').submit();">
          <i class="fa fa-power-off" aria-hidden="true"></i> {{trans('message.Logout')}}
          <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
            @csrf
          </form>
        </a> -->
      </li>
    </ul>
  </div>
  @canany(['supplier_add', 'product_add', 'purchase_add', 'customer_add','employee_add','supportstaff_add','accountant_add','branchAdmin_add','vehicle_add','vehicletype_add','vehiclebrand_add','colors_add','service_add','quotation_add','invoice_add','jobcard_add','gatepass_add','taxrate_add','paymentmethod_add','income_add','expense_add','salespart_add','rto_add','customfield_add','observationlibrary_add','branch_add'])
  <div class="dropdown_toggle ulprofile global_plus">
    <img src="{{ URL::asset('public/img/icons/Add.png') }}" alt="Add" width="40px" height="40px" class="m-1 dropdown-toggle border-0" type="button" id="dropdownMenuButtonAction" data-bs-toggle="dropdown" aria-expanded="false">
    <ul class="dropdown-menu heder-dropdown-menu action_dropdown shadow py-2 overflow-auto max-height-245" aria-labelledby="dropdownMenuButtonAction">
      @can('jobcard_add')
      <li><a class="dropdown-item" href="{!! url('/service/add') !!}"><i class="fa fa-plus" aria-hidden="true"></i> {{ trans('message.JobCard') }}</a></li>
      @endcan  
      @can('supplier_add')
      <li><a class="dropdown-item" href="{!! url('/supplier/add') !!}"><i class="fa fa-plus" aria-hidden="true"></i> {{ trans('message.Suppliers') }}</a></li>
      @endcan
      @can('product_add')
      <li><a class="dropdown-item" href="{!! url('/product/add') !!}"><i class="fa fa-plus" aria-hidden="true"></i> {{ trans('message.Product') }}</a></li>
      @endcan
      @can('purchase_add')
      <li><a class="dropdown-item" href="{!! url('/purchase/add') !!}"><i class="fa fa-plus" aria-hidden="true"></i> {{ trans('message.Purchase') }}</a></li>
      <li><a class="dropdown-item" href="{!! url('/purchase/add') !!}"><i class="fa fa-plus" aria-hidden="true"></i> {{ trans('message.Stock') }}</a></li>
      @endcan
      @can('customer_add')
      <li><a class="dropdown-item" href="{!! url('/customer/add') !!}"><i class="fa fa-plus" aria-hidden="true"></i> {{ trans('message.Customers') }}</a></li>
      @endcan
      @can('employee_add')
      <li><a class="dropdown-item" href="{!! url('/employee/add') !!}"><i class="fa fa-plus" aria-hidden="true"></i> {{ trans('message.Employees') }}</a></li>
      @endcan
      @can('supportstaff_add')
      <li><a class="dropdown-item" href="{!! url('/supportstaff/add') !!}"><i class="fa fa-plus" aria-hidden="true"></i> {{ trans('message.Support Staff') }}</a></li>
      @endcan
      @can('accountant_add')
      <li><a class="dropdown-item" href="{!! url('/accountant/add') !!}"><i class="fa fa-plus" aria-hidden="true"></i> {{ trans('message.Accountant') }}</a></li>
      @endcan
      @can('branchAdmin_add')
      <li><a class="dropdown-item" href="{!! url('/branchadmin/add') !!}"><i class="fa fa-plus" aria-hidden="true"></i> {{ trans('message.Branch Admin') }}</a></li>
      @endcan
      @can('vehicle_add')
      <li><a class="dropdown-item" href="{!! url('/vehicle/add') !!}"><i class="fa fa-plus" aria-hidden="true"></i> {{ trans('message.Vehicles') }}</a></li>
      @endcan
      @can('vehicletype_add')
      <li><a class="dropdown-item" href="{!! url('/vehicletype/vehicletypeadd') !!}"><i class="fa fa-plus" aria-hidden="true"></i> {{ trans('message.Vehicle Type') }}</a></li>
      @endcan
      @can('vehiclebrand_add')
      <li><a class="dropdown-item" href="{!! url('/vehiclebrand/add') !!}"><i class="fa fa-plus" aria-hidden="true"></i> {{ trans('message.Vehicle Brand') }}</a></li>
      @endcan
      @can('vehiclemodel_add')
      <li><a class="dropdown-item" href="{!! url('/vehicalmodel/add') !!}"><i class="fa fa-plus" aria-hidden="true"></i> {{ trans('message.Vehicle Model') }}</a></li>
      @endcan
      @can('colors_add')
      <li><a class="dropdown-item" href="{!! url('/color/add') !!}"><i class="fa fa-plus" aria-hidden="true"></i> {{ trans('message.Colors') }}</a></li>
      @endcan
      @can('service_add')
      <li><a class="dropdown-item" href="{!! url('/service/add') !!}"><i class="fa fa-plus" aria-hidden="true"></i> {{ trans('message.Services') }}</a></li>
      @endcan
      @can('quotation_add')
      <li><a class="dropdown-item" href="{!! url('/quotation/add') !!}"><i class="fa fa-plus" aria-hidden="true"></i> {{ trans('message.Quotation') }}</a></li>
      @endcan
      @can('invoice_add')
      <li><a class="dropdown-item" href="{!! url('/invoice/add') !!}"><i class="fa fa-plus" aria-hidden="true"></i> {{ trans('message.Invoices') }}</a></li>
      @endcan
      @can('gatepass_add')
      <li><a class="dropdown-item" href="{!! url('/gatepass/add') !!}"><i class="fa fa-plus" aria-hidden="true"></i> {{ trans('message.Gate Pass') }}</a></li>
      @endcan
      @can('taxrate_add')
      <li><a class="dropdown-item" href="{!! url('/taxrates/add') !!}"><i class="fa fa-plus" aria-hidden="true"></i> {{ trans('message.Tax Rates') }}</a></li>
      @endcan
      @can('paymentmethod_add')
      <li><a class="dropdown-item" href="{!! url('/payment/add') !!}"><i class="fa fa-plus" aria-hidden="true"></i> {{ trans('message.Payment Method') }}</a></li>
      @endcan
      @can('income_add')
      <li><a class="dropdown-item" href="{!! url('/income/add') !!}"><i class="fa fa-plus" aria-hidden="true"></i> {{ trans('message.Income') }}</a></li>
      @endcan
      @can('expense_add')
      <li><a class="dropdown-item" href="{!! url('/expense/add') !!}"><i class="fa fa-plus" aria-hidden="true"></i> {{ trans('message.Expenses') }}</a></li>
      @endcan
      @can('salespart_add')
      <li><a class="dropdown-item" href="{!! url('/sales_part/add') !!}"><i class="fa fa-plus" aria-hidden="true"></i> {{ trans('message.Part Sells') }}</a></li>
      @endcan
      @can('rto_add')
      <li><a class="dropdown-item" href="{!! url('/rto/add') !!}"><i class="fa fa-plus" aria-hidden="true"></i> {{ trans('message.Compliances') }}</a></li>
      @endcan
      @can('customfield_add')
      <li><a class="dropdown-item" href="{!! url('setting/custom/add') !!}"><i class="fa fa-plus" aria-hidden="true"></i> {{ trans('message.Custom Fields') }}</a></li>
      @endcan
      @can('observationlibrary_add')
      <li><a class="dropdown-item" href="{!! url('/observation/add') !!}"><i class="fa fa-plus" aria-hidden="true"></i> {{ trans('message.Observation Library') }}</a></li>
      @endcan
      @can('branch_add')
      <li><a class="dropdown-item" href="{!! url('/branch/add') !!}"><i class="fa fa-plus" aria-hidden="true"></i> {{ trans('message.Branch') }}</a></li>
      @endcan
    </ul>
  </div>
  @endcanany