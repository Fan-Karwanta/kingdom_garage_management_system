<body>
    <div id="getpassprint">

        <div class="row mx-4">
            <div class="col-md-6 col-sm-6 col-xs-6 col-xl-6 col-xxl-6 col-lg-6 mt-2">
                <img src="..//public/general_setting/<?php echo $setting->logo_image; ?>" class="system_logo_img">
            </div>
            <div class="col-md-6 col-sm-6 col-xs-6 col-xl-6 col-xxl-6 col-lg-6 mt-1 gate_pass">
                <div class="col-12 d-flex align-items-start m-1 mx-0">
                    <div class="col mx-2">
                        <p class="mb-0">{{ trans('message.Note For') }}: {{ trans('message.' .$noteFor) }}</p>
                        <p>{{ trans('message.' . $entityLabel) }}: {{ $entityDetails }}</p>
                    </div>
                </div>
            </div>
            <hr />
            <tr>
                <h3 align="center"><u>{{ trans('message.Note Details') }}</u></h3><br>
            </tr>

            <div class="modal-body">
                <ul class="list-unstyled scroll-view mb-0">
                    <li class="row media event d-flex align-items-center guardian_div left-border position-relative">
                        <div class="media-body col-xl-6 col-md-6 col-sm-6">
                            <p><strong>{{ trans('message.Notes By')}} {{ getUserFullName($note->create_by) }} On {{ $note->created_at->setTimezone(Auth::User()->timezone) }}</strong></p>
                            <p>{{ $note->notes }}</p>
                        </div>
                        <div class="text-end col-xl-5 col-md-5 col-sm-5">

                            @php
                            $attachments = \App\note_attachments::where('note_id','=', $note->id)->get();
                            @endphp
                            @if($attachments->isEmpty())
                            <br><br><br><br>
                            @else
                            <strong>
                                <p class="text-center">{{ trans('message.Attachments') }} :</p>
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
                </ul>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12 col-lg-12 col-xl-12 col-xxl-12 col-sm-12 col-xs-12 modal-footer mx-2 gatepass-footer-button" @if(getLangCode() == 'ar') style="width: 97%" @endif>
            <a href="" class="prints tagAforCloseBtn"><button type="button" data-bs-dismiss="modal" class="btn btn-outline-secondary closeButton btn-sm m-0">{{ trans('message.Close') }}</button></a>
            <a href="{{ $goModuleUrl }}" class="" target="_blank"><button type="button" class="btn btn-outline-secondary btn-sm m-0">{{ trans('message.' .$goModuleText) }}</button></a>
        </div>
    </div>
</body>