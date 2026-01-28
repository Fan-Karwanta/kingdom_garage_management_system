@extends('layouts.app')
@section('content')

<style>
  .right_side .table_row,
  .member_right .table_row {
    border-bottom: 1px solid #dedede;
    float: left;
    width: 100%;
    padding: 1px 0px 4px 2px;
  }

  .table_row .table_td {
    padding: 8px 8px !important;
  }

  .report_title {
    float: left;
    font-size: 20px;
    width: 100%;
  }

  .dataTables_scrollHead {
    display: none;
  }

  .table>:not(caption)>*>* {
    border-bottom-width: 0px !important;
  }

  .row.dt-row {
    padding: 0px 5px;
  }
   @media (max-width: 460px) {
        .view_page_header_bg {
            height: auto !important;
        }
        .heading_view{
            color: white;
        }
    }
</style>
<!-- page content -->
<div class="right_col" role="main">
  <div class="">
    <div class="page-title">
      <div class="nav_menu">
        <nav>
          <div class="nav toggle">
            <a id="menu_toggle"><i class="fa fa-bars sidemenu_toggle"></i></a><span class="titleup me-0"><a href="{!! url('/supplier/list') !!}"><i class=""><img src="{{ URL::asset('public/supplier/Back Arrow.png') }}" class="back-arrow mb-0"></i><span class="titleup"> {{ $user->name . ' ' . $user->lastname }}</span></a>
          </div>
          @include('dashboard.profile')
        </nav>
      </div>
    </div>
  </div>
  <div class="row view_page_header_bg ms-0">
    <div class="row">
      <div class="col-xl-10 col-md-9 col-sm-10">
        <div class="user_profile_header_left">
          <img class="user_view_profile_image" src="{{ URL::asset('public/supplier/' . $user->image) }}">
          <div class="row">
            <div class="view_top1">
              <div class="col-xl-12 col-md-12 col-sm-12">
                <label class="nav_text h5 user-name">
                  {{ $user->name . ' ' . $user->lastname }}&nbsp;
                </label>
                @can('supplier_edit')
                <div class="view_user_edit_btn d-inline">
                  <a href="{!! url('/supplier/list/edit/' . $user->id) !!}">
                    <img src="{{ URL::asset('public/img/dashboard/Edit.png') }}">
                  </a>
                </div>
                @endcan
              </div>
              <div class="col-xl-12 col-md-12 col-sm-12 nav_text mt-2">
                <div class="d-lg-inline">
                  <i class=" fa fa-phone"></i> {{ $user->mobile_no }}
                </div>
                <div class="d-lg-inline">
                  <i class=" fa fa-envelope"></i> {{ $user->email }}
                </div>
              </div>
              <div class="col-xl-12 col-md-12 col-sm-12 heading_view mt-3" style="width: 90%;">
                <i class="fa-solid fa-location-dot"></i>
                <lable class="">
                  {{ $user->address }}
                  <!-- , <?php echo getCityName($user->city_id) != null ? getCityName($user->city_id) . ',' : ''; ?>{{ getStateName($user->state_id) }}, {{ getCountryName($user->country_id) }}. -->
                </lable>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-xl-2 col-lg-3 col-md-3 col-sm-2">
        <div class="group_thumbs">
          <img src="{{ URL::asset('public/img/dashboard/Design.png') }}" height="93px" width="134px">
        </div>
      </div>
    </div>
  </div>

  <section id="" class="">

    <div class="panel-body padding_0 pb-0">
      <div class="row mt-4">
        <div class="col-xl-12 col-md-12 col-sm-12 table-responsive">
          <ul class="nav nav-tabs" role="tablist">
            <li class="text-uppercase">
              <a href="{!! url('supplier/list/'. $user->id) !!}" class="tab fw-bold">
                {{ trans('message.general') }}</a>
            </li>
            <li class="nav-item active text-uppercase">
              <a href="{!! url('supplier/list/notes/'. $user->id) !!}" class="nav-link nav-link-not-active fw-bold">
                {{ trans('message.Notes') }}</a>
            </li>
          </ul>

        </div>
      </div>

      @if($user->notes->isEmpty())
      <p class="d-flex justify-content-center mt-5 pt-5"><img src="{{ URL::asset('public/img/dashboard/No-Data.png') }}" width="300px"></p>
      @else
      <div class="row mt-3">
        <div class="col-xl-12 col-md-12 col-sm-12">
          <div class="row mx-1">
            <table id="supplier" class="table responsive jumbo_table" style="width:100%">
              <ul class="list-unstyled scroll-view mb-0">
                @foreach ($user->notes as $key => $note)
                <tr>
                  <td>
                    <li class="row media event d-flex align-items-center guardian_div my-3 left-border mb-0 mt-0">
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
                  </td>
                </tr>
                @endforeach

              </ul>
            </table>
          </div>
        </div>
      </div>
      @endif

    </div>
    <!-- END PANEL BODY DIV-->

  </section>
</div>

<!-- /page content -->

<!-- Scripts starting -->
<script nonce="{{ $cspNonce }}" src="{{ URL::asset('vendors/jquery/dist/jquery.min.js') }}"></script>

<script nonce="{{ $cspNonce }}">
  $(document).ready(function() {

    var search = "{{ trans('message.Search...') }}";
    var info = "{{ trans('message.Showing page _PAGE_ - _PAGES_') }}";
    var zeroRecords = "{{ trans('message.No Data Found') }}";
    var infoEmpty = "{{ trans('message.No records available') }}";

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
        infoFiltered: '(filtered from _MAX_ total records)',
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
  });
</script>

@endsection