@extends('layouts.app')
@section('content')
<!-- page content -->
<style>@media print {
    .right_col {
        margin-right: 0px !important;
    }
}
</style>
<div class="right_col" role="main">
    <div id="getpassprint">

        <div class="row mx-4">
            <div class="col-md-6 col-sm-6 col-xs-6 col-xl-6 col-xxl-6 col-lg-6 mt-2">
                <img src="..//public/general_setting/<?php echo $setting->logo_image; ?>" class="system_logo_img">
            </div>
            <div class="col-md-6 col-sm-6 col-xs-6 col-xl-6 col-xxl-6 col-lg-6 mt-1 gate_pass">
                <div class="col-12 d-flex align-items-start m-1 mx-0">
                    <img src="{{ URL::asset('public/img/icons/Vector (14).png') }}">
                    <div class="col mx-2">
                        <?php echo $setting->address; ?>
                    </div>
                </div>
                <div class="mb-1">{{ trans('message.Gate Pass No. :') }}
                    <span class="txt_color fw-bold"><?php echo $getpassdata->gatepass_no; ?></span>
                </div>
            </div>
            <hr />
            <tr>
                <h3 align="center"><u>{{ trans('message.Gate Pass Details') }}</u></h3><br>
            </tr>

            <div class="modal-body">
                <div class="row">
                    <table class="table table-bordered table-responsive gate_pass" width="100%" border="1" style="border-collapse:collapse;">

                        <tbody>

                            <tr>
                                <td class="">{{ trans('message.Name') }}:</td>
                                <td class="txt_color fw-bold"> <?php echo $getpassdata->name . ' ' . $getpassdata->lastname; ?></td>
                            </tr>

                            <tr>
                                <td class="">{{ trans('message.Jobcard Number') }}:</td>
                                <td class="txt_color fw-bold"> <?php echo $getpassdata->jobcard_id; ?></td>
                            </tr>

                            <tr>
                                <td class="">{{ trans('message.Vehicle Name') }}:</td>
                                <td class="txt_color fw-bold"> <?php echo getVehicleName($vehicle->id); ?></td>
                            </tr>

                            <tr>
                                <td class="">{{ trans('message.Vehicle Type') }}:</td>
                                <td class="txt_color fw-bold"> {{ getVehicleType($vehicle->vehicletype_id) }}</td>
                            </tr>

                            <tr>
                                <td class="">{{ trans('message.Number Plate') }}:</td>
                                <td class="txt_color fw-bold"><?php echo $getpassdata->number_plate; ?></td>
                            </tr>

                            <tr>
                                <td class="">{{ trans('message.Chassis No') }}.:</td>
                                <td class="txt_color fw-bold">{{ $getpassdata->chassisno ?? trans('message.Not Added') }}</td>
                            </tr>

                            <tr>
                                <td class="">{{ trans('message.KMs.Run') }}:</td>
                                <td class="txt_color fw-bold">{{ $job->kms_run ?? trans('message.Not Added') }}</td>

                            </tr>
                            <tr>
                                <td class="">{{ trans('message.Service Date') }}:</td>
                                <td class="txt_color fw-bold"> {{ date(getDateFormat() . ' H:i:s', strtotime($getpassdata->service_date)) }}</td>
                            </tr>

                            <tr>
                                <td class="">{{ trans('message.Vehicle Out Date') }}:</td>
                                <td class="txt_color fw-bold">{{ date(getDateFormat() . ' H:i:s', strtotime($getpassdata->service_out_date)) }}</td>
                            </tr>

                            <tr>
                                <td class=""> {{ trans('message.Created On:') }}</td>
                                <td class="txt_color fw-bold">{{ date(getDateFormat() . ' H:i:s', strtotime($getpassdata->created_at)) }}</td>
                            </tr>

                            <tr>
                                <td class="">{{ trans('message.Created By:') }}</td>
                                <td class="txt_color fw-bold"><?php echo getAssignTo($getpassdata->create_by); ?></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- /page content -->
@endsection