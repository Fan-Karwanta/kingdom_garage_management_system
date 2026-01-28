@extends('layouts.app')
@section('content')
<!-- page content -->
<div class="right_col" role="main">
    <div id="print_data">
        <div class="modal-header">
            <!-- <h5 class="modal-title fw-bold" id="exampleModalLabel">{{ getNameSystem() }}</h5> -->
            <h5 class="modal-title fw-bold" id="exampleModalLabel">{{ trans('message.MOT Test Details')}} </h5>

            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        @if ($mot_test_status_yes_or_no == 1)
        <div class="row pt-4">
            <div class="col-6 printimg position-relative mx-0 mt-4">
                <img src="{{ asset('public/general_setting/' . $logo->logo_image) }}" style="width: -webkit-fill-available;">
            </div>
            <div class="col-6">
                <p><b>{{ trans('message.MOT Test Status') }} : </b><span style="text-transform: uppercase;">{{ $get_vehicle_mot_test_reports_data->test_status }}</span></p>
                <p><b>{{ trans('message.MOT Test Number') }} : </b>{{ $get_vehicle_mot_test_reports_data->mot_test_number }}</p>
                <p><b>{{ trans('message.Date') }} : </b>{{ $get_vehicle_mot_test_reports_data->date }}</p>
                <p><b>{{ trans('message.Service Id') }}</b> : {{ $get_vehicle_mot_test_reports_data->service_id }}</p>
            </div>
        </div>
        <div class="col-md-12">
            <table class="table responsive table-bordered">
                <thead>
                    <tr>
                        <td><b>{{ trans('message.Code') }}</b>
                        </td>
                        <td><b>{{ trans('message.Point') }}</b>
                        </td>
                        <td><b>{{ trans('message.Answer') }}</b>
                        </td>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($answers_question_id_array as $key => $ques_answer_id)
                    @if ($answers_question_id_array[$key] == 'x' || $answers_question_id_array[$key] == 'r')

                    <tr>
                        <td> {{ $key }}
                        </td>

                        @foreach ($get_inspection_points_library_data as $insp_point_linrary)
                        @if ($insp_point_linrary->id == $key)
                        <td>{{ $insp_point_linrary->point }}
                        </td>
                        @endif
                        @endforeach

                        <td style="text-transform: uppercase;">
                            {{ $ques_answer_id }}
                        </td>
                    </tr>

                    @endif
                    @endforeach
                    <tr class="text-end">
                        <td class="boldFont" colspan="3"> {{ trans('message.R = Repair Required') }}<br>{{ trans('message.X = Safety Item Defact') }}</td>
                    </tr>
                </tbody>

            </table>
        </div>
        @else
        <h6 class="text-center my-5"><b>{{ trans('message.MOT Test Details are not Available for This Vehicle') }}</b></h6>
        @endif
    </div>
</div>
<!-- /page content -->
@endsection