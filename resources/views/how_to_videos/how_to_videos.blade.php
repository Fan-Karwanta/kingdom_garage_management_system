@extends('layouts.app')
@section('content')
    <!-- page content -->
    <style>
    .video-card {
    display: flex;
    align-items: center;
    background: #fff;
    border-radius: 10px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    padding: 15px;
    margin: 10px 0;
    }
    .video-card img {
      width: 138px;
    height: 78px;
    border-radius: 5px;
    margin-right: 15px;
    }
    .video-card a {
      font-weight: 600;
      align-self: flex-start;
    }
    </style>
    <div class="right_col" role="main">
    <div class="">
    <div class="page-title">
      <div class="nav_menu">
      <nav>
      <div class="nav toggle">
      <a id="menu_toggle"><i class="fa fa-bars sidemenu_toggle"></i></a><a href="{{ URL::previous() }}" id=""><i class=""><img src="{{ URL::asset('public/supplier/Back Arrow.png') }}" class="back-arrow"></i><span class="titleup">
      {{ trans('message.How To Videos') }}</span></a>
      </div>
      @include('dashboard.profile')
      </nav>
      </div>
    </div>
    @include('success_message.message')

    <div class="container mt-4">
    
      <div class="row" id="videoContainer"></div>


    </div>

    </div>
    </div>

      <script nonce="{{ $cspNonce }}">
        const videos = [
        {
          no: 1,
          link: "https://www.youtube.com/watch?v=Dw6TnXqz8y8&list=PLn8hzwYWx-JlBcDzj0m2ho065CQSPBhun&index=1",
          image: "public/img/how_to_videos/garage_master_video_thumbnail.png",
          title: "{{ trans('message.No.1 Garage Management System in 2025 | Mojoomla') }}"
        },
        {
          no: 5,
          link: "https://www.youtube.com/watch?v=TVE_I-6TPWI&list=PLn8hzwYWx-JlBcDzj0m2ho065CQSPBhun&index=5",
          image: "public/img/how_to_videos/car_maintainance_video_thumbnail.jpg",
          title: "{{ trans('message.Garage Master Customer Journey - Hassle-Free Car Maintenance Experience') }}"
        },
        {
          no: 7,
          link: "https://www.youtube.com/watch?v=zu5N9F507HE&list=PLn8hzwYWx-JlBcDzj0m2ho065CQSPBhun&index=7",
          image: "public/img/how_to_videos/free_demo_video_thumbnail.jpg",
          title: "{{ trans('message.FREE Garage Management System Demo - Mojoomla') }}"
        },
        {
          no: 8,
          link: "https://www.youtube.com/watch?v=KP3uT0O-fQw&list=PLn8hzwYWx-JlBcDzj0m2ho065CQSPBhun&index=8",
          image: "public/img/how_to_videos/whatsapp_share_video_thumbnail.jpg",
          title: "{{ trans('message.Expert Tips for Garage Invoice Sharing on WhatsApp - Mojoomla') }}"
        },
        {
          no: 12,
          link: "https://www.youtube.com/watch?v=xYsmZB6vIaY&list=PLn8hzwYWx-JlBcDzj0m2ho065CQSPBhun&index=12",
          image: "public/img/how_to_videos/fitness_test_video_thumbnail.jpg",
          title: "{{ trans('message.Vehicle Fitness Certificate in Garage Management System') }}"
        },
        ];

        const videoContainer = document.getElementById("videoContainer");

      videos.forEach(video => {
        const col = document.createElement("div");
        col.className = "col-md-6";
        col.innerHTML = `
          <div class="video-card">
            <a href="${video.link}" target="_blank">
              <img  src="{{ URL::asset('') }}${video.image}" alt="Video Thumbnail">
            </a>
            <a href="${video.link}" target="_blank">${video.title}</a>
          </div>
        `;
        videoContainer.appendChild(col);
      });
    </script>

    <!-- page content end -->

    <script nonce="{{ $cspNonce }}" src="{{ URL::asset('vendors/jquery/dist/jquery.min.js') }}"></script>

@endsection