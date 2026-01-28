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
      {{ trans('message.Help Document') }}</span></a>
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
          link: "https://www.youtube.com/watch?v=Dw6TnXqz8y8&list=PLn8hzwYWx-JlBcDzj0m2ho065CQSPBhun",
          image: "public/img/how_to_videos/garage_master_video_thumbnail.png",
          title: "{{ 'Please checkout this garage master youtube playlist | Mojoomla' }}"
        },
        {
          no: 5,
          link: "https://mojoomlasoftware.github.io/laravel-garage-document/",
          image: "public/img/how_to_videos/final_1.jpg",
          title: "{{ 'Please checkout this Garagemaster Documentation | Mojoomla' }}"
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