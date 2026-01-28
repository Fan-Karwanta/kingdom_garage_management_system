<html>

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
        .system_addr {
            line-height: 25px;
        }

        .itemtable {
            font-size: 14px;
            line-height: 25px;
        }
    </style>
</head>

<body>
    <div class="row" id="invoice_print">
        <table width="100%" border="0" style="margin:0px 8px 0px 8px; font-family:Poppins;">
            <tr>
                <td align="left">
                    <h3 style="font-size:18px;">{{ trans('message.MOT Test Details')}}</h3>
                </td>
            </tr>
        </table>
        <hr />
        @if ($mot_test_status_yes_or_no == 1)
        <table width="100%" border="0">
            <tbody>
                <tr>
                    <td style="vertical-align:top; float:left; width:15%;" align="left">
                        <br>
                        <span style="float:left; width:100%; ">
                            <img src="{{ base_path() }}/public/general_setting/<?php echo $logo->logo_image; ?>" width="230px" height="70px">
                        </span>
                    </td>
                    <td style="width: 45%; vertical-align:top;">
                        <span style="float:right; font-size: 14px;" class="system_addr">
                            <b>{{ trans('message.MOT Test Status') }} : </b><span style="text-transform: uppercase;">{{ $get_vehicle_mot_test_reports_data->test_status }}</span><br><br>
                            <b>{{ trans('message.MOT Test Number') }} : </b>{{ $get_vehicle_mot_test_reports_data->mot_test_number }}<br><br>
                            <b>{{ trans('message.Date') }} : </b>{{ $get_vehicle_mot_test_reports_data->date }}<br><br>
                            <b>{{ trans('message.Service Id') }}</b> : {{ $get_vehicle_mot_test_reports_data->service_id }}<br><br>
                        </span>
                    </td>
                </tr>
            </tbody>
        </table>
        <br>
        <table class="table table-bordered itemtable" width="100%" border="1" style="border-collapse:collapse;">
            <thead>
                <tr>
                    <td style="padding:8px;"><b>{{ trans('message.Code') }}</b>
                    </td>
                    <td style="padding:8px;"><b>{{ trans('message.Point') }}</b>
                    </td>
                    <td style="padding:8px;"><b>{{ trans('message.Answer') }}</b>
                    </td>
                </tr>
            </thead>
            <tbody class="itemtable">
                @foreach ($answers_question_id_array as $key => $ques_answer_id)
                @if ($answers_question_id_array[$key] == 'x' || $answers_question_id_array[$key] == 'r')

                <tr>
                    <td style="padding:8px;"> {{ $key }}
                    </td>

                    @foreach ($get_inspection_points_library_data as $insp_point_linrary)
                    @if ($insp_point_linrary->id == $key)
                    <td style="padding:8px;">{{ $insp_point_linrary->point }}
                    </td>
                    @endif
                    @endforeach

                    <td style="text-transform: uppercase; padding: 8px;" class="ps-3">
                        {{ $ques_answer_id }}
                    </td>
                </tr>

                @endif
                @endforeach
                <tr>
                    <td class="boldFont" colspan="3" align="right" style="padding:8px;"> <b>{{ trans('message.R = Repair Required') }}<br>{{ trans('message.X = Safety Item Defact') }}</b></td>
                </tr>
            </tbody>

        </table>
        @else
        <h6 class="text-center my-5"><b>{{ trans('message.MOT Test Details are not Available for This Vehicle') }}</b></h6>
        @endif
    </div>
</body>

</html>