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
                            <a id="menu_toggle"><i class="fa fa-bars sidemenu_toggle"></i></a><span class="titleup"><a href="{!! url('/vehicle/list') !!}"><img src="{{ URL::asset('public/supplier/Back Arrow.png') }}" class="me-2 back-arrow"> {{ getVehicleBrand($vehical->vehiclebrand_id) }} {{ $vehical->modelname }} {{ $vehical->modelyear }}</span></a>
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
                                <?php $vehicleimage = getVehicleImage($vehical->id); ?>
                                <img class="user_view_profile_image" src="{{ URL::asset('public/vehicle/' . $vehicleimage) }}">
                                <div class="row">
                                    <div class="view_top1">
                                        <div class="col-xl-12 col-md-12 col-sm-12">
                                            <label class="nav_text h5">
                                                {{ getVehicleBrand($vehical->vehiclebrand_id) }} {{ $vehical->modelname }} {{ $vehical->modelyear }}
                                            </label>
                                            @can('vehicle_edit')
                                            <div class="view_user_edit_btn d-inline">
                                                <a href="{!! url('/vehicle/list/edit/' . $view_id) !!}">
                                                    <img src="{{ URL::asset('public/img/dashboard/Edit.png') }}">
                                                </a>
                                            </div>
                                            @endcan
                                        </div>
                                        <div class="col-xl-12 col-md-12 col-sm-12 nav_text mt-2">
                                            <img src="{{ URL::asset('public/vehicle/4344326 1.png') }}"></img>&nbsp;{{ getVehicleType($vehical->vehicletype_id) }}&nbsp;&nbsp;&nbsp;
                                            @if (!empty($vehical->dom))
                                            <img src="{{ URL::asset('public/vehicle/Vector (11).png') }}" class="small_img"></img>&nbsp;
                                            <span class="txt_color">
                                               
                                                {{ date(getDateFormat(), strtotime($vehical->dom)) }}
                                               
                                            </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-xl-12 col-md-12 col-sm-12">
                                        <div class="view_top1">
                                            <div class="row">
                                                <div class="col-md-12 heading_view">
                                                @if(!empty($vehical->odometerreading))
                                                    <img src="{{ URL::asset('public/vehicle/Vector (12).png') }}" class="small_img"></img>&nbsp;
                                                    <lable class="">
                                                       {{ $vehical->odometerreading }} km 
                                                    </lable>
                                                    @endif
                                                </div>
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

                <!--page conten -->
                <div class="row">

                    <section id="" class="">

                        <div class="panel-body padding_0 pb-0">
                            <div class="row mt-4">
                                <div class="col-xl-11 col-md-11 col-sm-11 pt-3 table-responsive">
                                    <ul class="nav nav-tabs">
                                        @can('vehicle_view')
                                        <li class="nav-item">
                                            <a href="{!! url('vehicle/list/view/' . $vehical->id) !!}" class="nav-link active fw-bold">
                                                {{ trans('message.BASIC DETAILS') }}</a>
                                        </li>
                                        @endcan
                                        @can('vehicle_view')
                                        <li class="nav-item">
                                            <a href="{!! url('vehicle/list/view/description/' . $vehical->id) !!}" class="nav-link nav-link-not-active fw-bold">
                                                {{ trans('message.DESCRIPTION') }}</a>
                                        </li>
                                        @endcan
                                        @can('service_view')
                                        <li class="nav-item">
                                            <a href="{!! url('vehicle/list/view/maintainance/' . $vehical->id) !!}" class="nav-link nav-link-not-active fw-bold">
                                                {{ trans('message.MAINTAINANCE HISTORY') }}</a>
                                        </li>
                                        @endcan
                                        <li class="nav-item text-uppercase">
                                            <a href="{!! url('vehicle/list/view/notes/' . $vehical->id) !!}" class="nav-link nav-link-not-active fw-bold">
                                                {{ trans('message.Notes') }}</a>
                                        </li>
                                    </ul>

                                </div>
                                @canany(['service_add'])
                                <div class="ms-lg-auto col-xl-1 col-md-1 col-sm-1 text-end">
                                    <div class="dropdown_toggle">
                                        <img src="{{ URL::asset('public/img/icons/plus Button.png') }}" class="btn dropdown-toggle border-0" type="button" id="dropdownMenuButtonAction" data-bs-toggle="dropdown" aria-expanded="false">
                                        <ul class="dropdown-menu heder-dropdown-menu action_dropdown shadow py-2" aria-labelledby="dropdownMenuButtonAction">
                                            @can('service_add')
                                            <li><a class="dropdown-item" href="{!! url('/service/add') !!}?c_id={{ $vehical->customer_id }}&v_id={{ $vehical->id }}" style="padding-left: 10px;"><i class="fa fa-plus" aria-hidden="true"></i> {{ trans('message.MAINTAINANCE HISTORY') }}</a></li>
                                            @endcan
                                            @can('vehicle_edit')
                                            <li><a class="dropdown-item" href="{!! url('/vehicle/list/edit/' . $view_id) !!}" style="padding-left: 10px;"><i class="fa fa-plus" aria-hidden="true"></i> {{ trans('message.DESCRIPTION') }}</a></li>
                                            @endcan
                                            @can('vehicle_edit')
                                            <li><a class="dropdown-item text-uppercase" href="{!! url('/vehicle/list/edit/' . $view_id) !!}" style="padding-left: 10px;"><i class="fa fa-plus" aria-hidden="true"></i> {{ trans('message.Notes') }}</a></li>
                                            @endcan
                                        </ul>
                                    </div>
                                </div>
                                @endcanany
                            </div>
                            <div class="row margin_top_15px mx-1">
                                <div class="col-xl-3 col-md-3 col-sm-6">
                                    <label class="">{{ trans('message.Number Plate') }} </label><br>
                                    <label class="fw-bold">{{ $vehical->number_plate ?? trans('message.Not Added') }}<br></label>
                                </div>
                                <div class="col-xl-3 col-md-3 col-sm-6">
                                    <label class="">{{ trans('message.Vehicle Name') }}</label><br>
                                    <label class="fw-bold">{{ getVehicleBrand($vehical->vehiclebrand_id) }} {{ $vehical->modelname }} {{ $vehical->modelyear }}</label>
                                </div>
                                <div class="col-xl-3 col-md-3 col-sm-6">
                                    <label class="">{{ trans('message.Date of Manufacturing') }} </label><br>
                                    <label class="fw-bold">
                                        @if (!empty($vehical->dom))
                                        {{ date(getDateFormat(), strtotime($vehical->dom)) }}
                                        @else
                                        {{ trans('message.Not Added') }}
                                        @endif
                                    </label>
                                </div>
                                <div class="col-xl-3 col-md-3 col-sm-6">
                                    <label class="">{{ trans('message.Vehicle Type') }}</label><br>
                                    <label class="fw-bold">
                                        {{ getVehicleType($vehical->vehicletype_id) }}<br>
                                    </label>
                                </div>
                            </div>
                    </section>
                </div>
            </div>
                <!-- end  slider -->
                <div class="row">
                    <div class="col-md-12 col-lg-12 col-xl-12 col-xxl-12 col-sm-12 col-xs-12">
                        <div class="x_content">
                            <div class="row">
                                <div class="col-md-12 col-lg-12 col-xl-12 col-xxl-12 col-sm-12 col-xs-12">
                                    <div class="x_panel p-0 mb-0">
                                        <div class="tab-content">
                                            <div class="tab-pane fade show active" {{-- style="margin-top: 60px;" --}} id="tab_content1">
                                                <div class="col-md-12 col-lg-12 col-xl-12 col-xxl-12 col-sm-12 col-xs-12">
                                                    <div class="row mt-3">

                                                        <div class="col-xl-6 col-md-6 col-sm-6">
                                                            <div class="guardian_div pb-0">
                                                                <div class="center-container mb-3">
                                                                    <section id="selected-image" class="zoom-container">
                                                                        <img src="{{ $available1[0] }}" alt="Selected Image" height="200px" class="center-image" id="zoomed-image">
                                                                    </section>
                                                                </div>
                                                                <div class="scroll-image">
                                                                    @foreach($available1 as $key => $imageURL)
                                                                    <a href="javascript:void(0);" onclick="displayImage('{{ $imageURL }}')">
                                                                        <img src="{{ $imageURL }}" alt="Image {{ $key + 1 }}" height="50px" class="vehicleImg">
                                                                    </a>
                                                                    @endforeach
                                                                </div>
                                                            </div>
                                                        </div>


                                                        <div class="col-xl-6 col-md-6 col-sm-6">
                                                            <div class="guardian_div mb-3">
                                                                <h2><label class="text-dark fw-bold"> {{ trans('message.More Info.') }} </label></h2>
                                                                <div class="row">

                                                                    <!-- <div class="col-xl-6 col-md-6 col-sm-12 mt-2">
                                                                    <label class=""> {{ trans('message.Date of Manufacturing') }} : </label>
                                                                    <label class="fw-bold">@if (!empty($vehical->dom))
                                                                        {{ date(getDateFormat(), strtotime($vehical->dom)) }}
                                                                        @else
                                                                        {{ trans('message.Not Added') }}
                                                                        @endif</label>
                                                                </div> -->

                                                                    <div class="col-xl-6 col-md-6 col-sm-12 mt-2">
                                                                        <label class=""> {{ trans('message.Vehicle Brand') }} : </label>
                                                                        <label class="fw-bold"> {{ getVehicleBrand($vehical->vehiclebrand_id) }} </label>
                                                                    </div>

                                                                    <div class="col-xl-6 col-md-6 col-sm-12 mt-2">
                                                                        <label class=""> {{ trans('message.Model Name') }} : </label>
                                                                        <label class="fw-bold"> {{ $vehical->modelname }} </label>
                                                                    </div>

                                                                    <div class="col-xl-6 col-md-6 col-sm-12 mt-2">
                                                                        <label class=""> {{ trans('message.Model Years') }} :</label>
                                                                        <label class="fw-bold"> {{ $vehical->modelyear ?? trans('message.Not Added') }} </label>
                                                                    </div>

                                                                    <div class="col-xl-6 col-md-6 col-sm-12 mt-2">
                                                                        <label class=""> {{ trans('message.Gear Box') }} :</label>
                                                                        <label class="fw-bold"> {{ $vehical->gearbox ?? trans('message.Not Added') }} </label>
                                                                    </div>

                                                                    <div class="col-xl-6 col-md-6 col-sm-12 mt-2">
                                                                        <label class=""> {{ trans('message.Chassis No') }} : </label>
                                                                        <label class="fw-bold"> {{ $vehical->chassisno ?? trans('message.Not Added') }} </label>
                                                                    </div>

                                                                    <div class="col-xl-6 col-md-6 col-sm-12 mt-2">
                                                                        <label class=""> {{ trans('message.Gear Box No :') }} </label>
                                                                        <label class="fw-bold"> {{ $vehical->gearboxno ?? trans('message.Not Added') }} </label>
                                                                    </div>

                                                                    <div class="col-xl-6 col-md-6 col-sm-12 mt-2">
                                                                        <label class=""> {{ trans('message.Number Plate') }} : </label>
                                                                        <label class="fw-bold"> {{ $vehical->number_plate ?? trans('message.Not Added') }} </label>
                                                                    </div>

                                                                    <div class="col-xl-6 col-md-6 col-sm-12 mt-2">
                                                                        <label class="">{{ trans('message.Engine No:') }} </label>
                                                                        <label class="fw-bold"> {{ $vehical->engineno ?? trans('message.Not Added') }} </label>
                                                                    </div>

                                                                    <div class="col-xl-6 col-md-6 col-sm-12 mt-2">
                                                                        <label class=""> {{ trans('message.Fuel type :') }} </label>
                                                                        <label class="fw-bold"> {{ getFuelType($vehical->fuel_id) ?? trans('message.Not Added') }} </label>
                                                                    </div>

                                                                    <div class="col-xl-6 col-md-6 col-sm-12 mt-2">
                                                                        <label class="">{{ trans('message.Engine Size:') }} </label>
                                                                        <label class="fw-bold"> {{ $vehical->enginesize ?? trans('message.Not Added') }} </label>
                                                                    </div>

                                                                    <div class="col-xl-6 col-md-6 col-sm-12 mt-2">
                                                                        <label class=""> {{ trans('message.Total Gears') }}: </label>
                                                                        <label class="fw-bold"> {{ $vehical->nogears ?? trans('message.Not Added') }} </label>
                                                                    </div>

                                                                    <div class="col-xl-6 col-md-6 col-sm-12 mt-2">
                                                                        <label class="">{{ trans('message.Key No :') }} </label>
                                                                        <label class="fw-bold"> {{ $vehical->keyno ?? trans('message.Not Added') }} </label>
                                                                    </div>

                                                                    <div class="col-xl-6 col-md-6 col-sm-12 mt-2">
                                                                        <label class=""> {{ trans('message.Odometer Reading :') }} </label>
                                                                        <label class="fw-bold"> {{ $vehical->odometerreading ?? trans('message.Not Added') }} </label>
                                                                    </div>

                                                                    <div class="col-xl-6 col-md-6 col-sm-12 mt-2">
                                                                        <label class=""> {{ trans('message.Color') }} : </label>
                                                                        <label class="fw-bold"> @if (!empty($col))
                                                                            @foreach ($col as $color_id)
                                                                            <span class="box_color txt_color" style=" background-color:{{ getColor($color_id->color) }}">
                                                                            </span>
                                                                            @endforeach
                                                                            @else
                                                                            {{ trans('message.Not Added') }}
                                                                            @endif
                                                                        </label>
                                                                    </div>
                                                                    @if (!empty($tbl_custom_fields))
                                                                    @foreach ($tbl_custom_fields as $tbl_custom_field)
                                                                    <?php
                                                                    $tbl_custom = $tbl_custom_field->id;
                                                                    $userid = $vehical->id;

                                                                    $datavalue = getCustomDataVehicle($tbl_custom, $userid);
                                                                    ?>

                                                                    @if ($tbl_custom_field->type == 'radio')
                                                                    @if ($datavalue != '')
                                                                    <?php
                                                                    $radio_selected_value = getRadioSelectedValue($tbl_custom_field->id, $datavalue);
                                                                    ?>

                                                                    <div class="col-xl-6 col-md-6 col-sm-12 mt-2">
                                                                        <label class=""> {{ $tbl_custom_field->label }}: </label>
                                                                        <label class="fw-bold"> {{ $radio_selected_value }} </label>
                                                                    </div>
                                                                </div>
                                                                @else
                                                                <div class="col-xl-6 col-md-6 col-sm-12 mt-2">
                                                                    <label class=""> {{ $tbl_custom_field->label }}: </label>
                                                                    <label class="fw-bold"> {{ trans('message.Not Added') }} </label>
                                                                </div>
                                                                @endif
                                                                @else
                                                                @if ($datavalue != null)
                                                                <div class="col-xl-6 col-md-6 col-sm-12 mt-2">
                                                                    <label class=""> {{ $tbl_custom_field->label }}: </label>
                                                                    <label class="fw-bold"> {{ $datavalue }} </label>
                                                                </div>
                                                                @else
                                                                <div class="col-xl-6 col-md-6 col-sm-12 mt-2">
                                                                    <label class=""> {{ $tbl_custom_field->label }}: </label>
                                                                    <label class="fw-bold"> {{ trans('message.Not Added') }} </label>
                                                                </div>
                                                                @endif
                                                                @endif
                                                                @endforeach
                                                            </div>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
      <!-- @if($vehical->notes->isEmpty())
      @else
      <div class="row mx-1">
        <div class="col-xl-12 col-md-12 col-sm-12">
          <p class="fw-bold overflow-visible h5">{{ trans('message.Notes') }}</p>
          <div class="row">
            <ul class="list-unstyled scroll-view mb-0">
              @foreach ($vehical->notes as $key => $note)
              <li class="row media event d-flex align-items-center guardian_div my-3 left-border">
                <div class="media-body col-xl-6 col-md-6 col-sm-6">
                  <p><strong>Notes By {{ getUserFullName($note->create_by) }} On {{ $note->created_at->setTimezone(Auth::User()->timezone) }}</strong></p>
                  <p>{{ $note->notes }}</p>
                </div>
                <div class="text-end col-xl-6 col-md-6 col-sm-6">
                  <strong>
                    <p class="text-center">{{ trans('message.Attachments') }} :</p>
                  </strong>
                  @php
                  $attachments = \App\note_attachments::where('note_id','=', $note->id)->get();
                  @endphp
                  @if($attachments->isEmpty())
                  <p class="text-center text-danger">{{ trans('message.Not Added') }} :</p>
                  @else
                  @foreach ($attachments as $attachment)
                  @php
                  $extension = pathinfo($attachment->attachment, PATHINFO_EXTENSION);
                  $attachmentUrl = URL::asset('public/notes/' . basename($attachment->attachment));
                  @endphp
                  @if (in_array($extension, ['jpg', 'jpeg', 'png', 'gif']))
                  <a href="{{ $attachmentUrl }}" target="_blank">
                    <img src="{{ $attachmentUrl }}" width="55px" class="rounded me-2">
                  </a>
                  @elseif ($extension === 'pdf')
                  <a href="{{ $attachmentUrl }}" target="_blank">
                    <img src="{{ asset('public/img/icons/pdf_download.png') }}" width="55px" class="rounded me-2">
                  </a>
                  @else
                  <a href="{{ $attachmentUrl }}" target="_blank">
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
      @endif -->
</div>
<!-- /page content -->


<!-- Scripts starting -->
<script nonce="{{ $cspNonce }}" type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>

<script nonce="{{ $cspNonce }}">
    $(document).ready(function() {
        var search = "{{ trans('message.Search...') }}";
        var info = "{{ trans('message.Showing page _PAGE_ - _PAGES_') }}";
        var zeroRecords = "{{ trans('message.Nothing found - sorry') }}";
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
    $(document).ready(function() {
        var m = <?php echo $available; ?>;

        for (var i = 0; i < m.length; i++) {
            $('<div class="item"><img src="' + m[i] + '"><div class="carousel-caption"></div>   </div>')
                .appendTo(
                    '.carousel-inner');

            $('<div class="item"> <div data-target="#carousel" data-slide-to="' + i +
                '" class="thumb"><img src="' + m[
                    i] + '"></div></div>').appendTo('.carousel-inner-1');

            $('<li data-target="#carousel-example-generic" data-slide-to="' + i + '"></li>').appendTo(
                '.carousel-indicators')
        }

        $('#thumbcarousel .item').first().addClass('active');
        $('.item').first().addClass('active');
        $('.carousel-indicators > li').first().addClass('active');
        $('#carousel-example-generic').carousel();
    });

    function myFunction(imgs) {
        var expandImg = document.getElementById("expandedImg");
        var imgText = document.getElementById("imgtext");
        expandImg.src = imgs.src;
        imgText.innerHTML = imgs.alt;
        expandImg.parentElement.style.display = "block";
    }
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