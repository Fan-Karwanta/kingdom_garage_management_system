@extends('layouts.app')
@section('content')
<style>
    .box_color {
        width: 40px;
        height: 10px;
        float: left;
        margin: 0px 5px 3px 0px;
    }

    .table>tbody>tr>td {
        padding: 10px 8px !important;
    }

    .right_side .table_row,
    .table_row {
        border-bottom: 1px solid #dedede;
        float: left;
        width: 100%;
        padding: 1px 0px 4px 2px;

    }

    .member_right {
        border: 1px solid #dedede;
        /* margin-left: 9px; */
    }

    .table_row .table_td {
        padding: 8px 8px !important;

    }

    .report_title {
        float: left;
        font-size: 20px;
        margin-bottom: 10px;
        padding-top: 10px;
        width: 100%;
    }

    .b-detail__head-title {
        border-left: 4px solid #2A3F54;
        padding-left: 15px;
        text-transform: capitalize;

    }

    .b-detail__head-price {
        width: 100%;
        float: right;
        text-align: center;
    }

    .b-detail__head-price-num {
        padding: 4px 34px;
        font: 700 23px 'PT Sans', sans-serif;

    }

    .thumb img {
        border-radius: 0px;
    }


    .item .thumb {
        width: 23%;
        cursor: pointer;
        /* float: left; */
        /* border: 1px solid; */
        margin: 3px;

    }

    .item .thumb img {
        width: 70px;
        height: 70px;
    }

    .item img {
        width: 435px;

    }

    .carousel-inner-1 {
        margin-top: 16px;
    }

    .carousel-inner>.item>a>img,
    .carousel-inner>.item>img,
    .img-responsive,
    .thumbnail a>img,
    .thumbnail>img {
        height: 268px;
        width: 268px;
    }

    .shiptitleright {
        float: right;
    }

    ul.bar_tabs>li.active {
        background: #fff !important;
    }

    img.up_arrow {
        margin-left: 35px;
    }

    img.down_arrow {
        margin-left: 33px;
    }

    div#carousel {
        margin-left: 45px;
        margin-top: -289px;
    }

    @media (max-width: 540px) {
        .view_top1 {
            margin-top: -6rem !important;
            margin-left: 58% !important;
        }

        .col-md-12 {
            /* margin-top: -99px; */
            /* margin-top: 3rem!important; */
        }
    }

    @media (width: 540px) {
        .view_top1 {
            margin-top: 1rem !important;
            margin-left: 5% !important;
        }
    }

    .zoom-container {
        overflow: hidden;
        position: relative;
    }

    .zoom-container img {
        transition: transform 0.25s ease;
    }

    .zoom-container:hover img {
        transform: scale(1.5);
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
        <div class="">
            <div class="page-title">
                <div class="nav_menu">
                    <nav>
                        <div class="nav toggle">
                            <a id="menu_toggle">
                                <i class="fa fa-bars sidemenu_toggle"></i>
                            </a>
                            <span class="titleup">
                                <a href="{!! url('/service/list') !!}">
                                    <img src="{{ URL::asset('public/supplier/Back Arrow.png') }}" class="me-2 back-arrow">
                                    {{ $service->job_no }}</a>
                            </span>
                        </div>
                        @include('dashboard.profile')
                    </nav>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 col-lg-12 col-xl-12 col-xxl-12 col-sm-12 col-xs-12">
                <div class="row view_page_header_bg ms-0">
                    <div class="row">
                        <div class="col-xl-10 col-md-9 col-sm-10">
                            <div class="user_profile_header_left">
                                <img class="user_view_profile_image" src="{{ URL::asset('public/customer/' . $customer->image) }}">
                                <i class="fa-solid fa-wrench margin-right-10px"></i>
                                <div class="row">
                                    <div class="view_top1">
                                        <div class="col-xl-12 col-md-12 col-sm-12">
                                            <label class="nav_text h5 user-name fh5">
                                                {{ $customer->name . ' ' . $customer->lastname }}&nbsp;
                                            </label>
                                            @can('customer_edit')
                                            <div class="view_user_edit_btn d-inline">
                                                <a href="{!! url('/service/list/edit/' . $service->id) !!}">
                                                    <img src="{{ URL::asset('public/img/dashboard/Edit.png') }}">
                                                </a>
                                            </div>
                                            @endcan
                                        </div>
                                        <div class="col-xl-12 col-md-12 col-sm-12 nav_text mt-2">
                                            <div class="d-lg-inline">
                                                <i class=" fa fa-phone"></i>
                                                {{ $customer->mobile_no }}
                                            </div>
                                            <div class="d-lg-inline">
                                                <i class=" fa fa-envelope"></i>
                                                {{ $customer->email }}
                                            </div>
                                        </div>
                                        <div class="col-xl-12 col-md-12 col-sm-12 heading_view mt-3" style="width: 90%;">
                                            <i class="fa-solid fa-location-dot"></i>
                                            <lable class="">
                                                {{ $customer->address }}
                                                <?php echo getCityName($customer->city_id) != null ? getCityName($customer->city_id) . ',' : ''; ?>{{ getStateName($customer->state_id) }}, {{ getCountryName($customer->country_id) }}
                                            </lable>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-xl-12 col-md-12 col-sm-12">
                                        <div class="view_top1">
                                            <div class="row">
                                                <div class="col-md-12 heading_view"></div>
                                            </div>
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
                <div class="panel-body padding_0 pb-0">
                    <div class="row mt-4">
                        <div class="col-xl-12 col-md-12 col-sm-12 pt-3">
                            <ul class="nav nav-tabs" role="tablist">
                                <li class="">
                                    <a href="{!! url('/service/list/view/' . $service->id) !!}" class="tab active fw-bold">
                                        {{ trans('message.GENERAL') }}
                                    </a>
                                </li>
                                <li class="active text-uppercase">
                                    <a href="{!! url('/service/list/view/notes' . $service->id) !!}" class="tab fw-bold">
                                        {{ trans('message.Notes') }}
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            @if($service->notes->isEmpty())
            <p class="d-flex justify-content-center mt-5 pt-5"><img src="{{ URL::asset('public/img/dashboard/No-Data.png') }}" width="300px"></p>
            @else
            <div class="row mt-3">
                <div class="col-xl-12 col-md-12 col-sm-12">
                    <div class="row mx-1">
                        <table id="supplier" class="table responsive jumbo_table" style="width:100%">
                            <ul class="list-unstyled scroll-view mb-0">
                                @foreach ($service->notes as $key => $note)
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
    </div>
</div>
</div>
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

<script nonce="{{ $cspNonce }}">
    function displayImage(imageURL) {
        // Get the 'selected-image' section
        var selectedImageSection = document.getElementById("selected-image");

        // Create an image element
        var image = new Image();
        image.src = imageURL;
        image.alt = "Selected Image";
        image.style.height = "200px";
        // image.style.width = "400px";
        image.id = "zoomed-image";

        // Clear any previous content in the 'selected-image' section
        selectedImageSection.innerHTML = "";

        // Append the selected image to the 'selected-image' section
        selectedImageSection.appendChild(image);
    }
</script>
@endsection