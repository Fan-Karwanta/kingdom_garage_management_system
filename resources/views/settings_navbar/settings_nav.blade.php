<style>
     @media only screen and (max-width: 450px) {
   .csv-import{
    display: none !important;
   }
}
</style>
<ul class="nav nav-tabs">
    @can('generalsetting_view')
    <li class="nav-item">
        <a href="{{ url('setting/general_setting/list') }}" class="nav-link {{ Request::is('setting/general_setting/list') ? 'active' : 'nav-link-not-active' }}">
            <b>{{ trans('message.GENERAL') }}</b>
        </a>
    </li>
    @endcan
    
    @can('timezone_view')
    <li class="nav-item">
        <a href="{{ url('setting/timezone/list') }}" class="nav-link {{ Request::is('setting/timezone/list') ? 'active' : 'nav-link-not-active' }}">
            <b>{{ trans('message.OTHER') }}</b>
        </a>
    </li>
    @endcan
    
    @can('accessrights_view')
    <li class="nav-item">
        <a href="{{ url('setting/accessrights/show') }}" class="nav-link {{ Request::is('setting/accessrights/show') ? 'active' : 'nav-link-not-active' }}">
            <b>{{ trans('message.ACCESS RIGHTS') }}</b>
        </a>
    </li>
    @endcan
    @can('customer_onboarding')
    <li class="nav-item">
        <a href="{{ url('setting/customerOnboarding/list') }}" class="nav-link {{ Request::is('setting/customerOnboarding/list') ? 'active' : 'nav-link-not-active' }}">
            <b>{{ trans('message.Customer Onboarding') }}</b>
        </a>
    </li>
    @endcan

    @can('businesshours_view')
    <li class="nav-item">
        <a href="{{ url('setting/hours/list') }}" class="nav-link {{ Request::is('setting/hours/list') ? 'active' : 'nav-link-not-active' }}">
            <b>{{ trans('message.BUSINESS HOURS') }}</b>
        </a>
    </li>
    @endcan

    @can('stripesetting_view')
    <li class="nav-item">
        <a href="{{ url('setting/stripe/list') }}" class="nav-link {{ Request::is('setting/stripe/list') ? 'active' : 'nav-link-not-active' }}">
            <b>{{ trans('message.STRIPE') }}</b>
        </a>
    </li>
    @endcan

    @can('branchsetting_view')
    <li class="nav-item">
        <a href="{{ url('branch_setting/list') }}" class="nav-link {{ Request::is('branch_setting/list') ? 'active' : 'nav-link-not-active' }}">
            <b>{{ trans('message.BRANCH') }}</b>
        </a>
    </li>
    @endcan

    @can('email_view')
    <li class="nav-item">
        <a href="{{ url('setting/email_setting/list') }}" class="nav-link {{ Request::is('setting/email_setting/list') ? 'active' : 'nav-link-not-active' }}">
            <b>{{ trans('message.EMAIL') }}</b>
        </a>
    </li>
    @endcan
    @can('license_view')
    @if(Auth::check() && isAdmin(Auth::user()->role_id))
    <li class="nav-item">
        <a href="{{ url('setting/license_setting/list') }}" class="nav-link {{ Request::is('setting/license_setting/list') ? 'active' : 'nav-link-not-active' }}">
            <b>{{ trans('message.LICENSE') }}</b>
        </a>
    </li>
    @endif
    @endcan

    @can('quotationsetting_view')
    <li class="nav-item">
        <a href="{{ url('setting/quotation_setting/list') }}" class="nav-link {{ Request::is('setting/quotation_setting/list') ? 'active' : 'nav-link-not-active' }}">
            <b>{{ trans('message.QUOTATION') }}</b>
        </a>
    </li>
    @endcan
    @can('csvimport_view')
    <li class="nav-item csv-import">
        <a href="{!! url('csv_import/list') !!}" class="nav-link {{ Request::is('csv_import/list') ? 'active' : 'nav-link-not-active' }}"><span class="visible-xs"></span><i class="">&nbsp;</i>
          <b>{{ trans('message.CSV IMPORT') }}</b>
        </a>
    </li>
     @endcan
    <!-- @if(Auth::check() && isAdmin(Auth::user()->role_id) && \Illuminate\Support\Facades\Schema::hasTable('payment_gateways'))
    <li class="nav-item">
        <a href="{{ url('setting/payment-gateways/list') }}" class="nav-link {{ Request::is('setting/payment-gateways/list') ? 'active' : 'nav-link-not-active' }}">
            <b>PAYMENT GATEWAY SETTINGS</b>
        </a>
    </li>
    @endif
    @if(Auth::check() && isAdmin(Auth::user()->role_id) && \Illuminate\Support\Facades\Schema::hasTable('sms_settings'))
    <li class="nav-item">
        <a href="{{ url('setting/sms_setting/list') }}" class="nav-link {{ Request::is('setting/sms_setting/list') ? 'active' : 'nav-link-not-active' }}">
            <b>SMS SETTINGS</b>
        </a>
    </li>
    @endif -->
</ul>