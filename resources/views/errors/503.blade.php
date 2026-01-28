<!-- <!DOCTYPE html>
<html>

<head>
  <title>Be right back.</title>

  <link href="https://fonts.googleapis.com/css?family=Lato:100"
    rel="stylesheet"
    type="text/css">

  <style>
    html,
    body {
      height: 100%;
    }

    body {
      margin: 0;
      padding: 0;
      width: 100%;
      color: #B0BEC5;
      display: table;
      font-weight: 100;
      font-family: 'Lato';
    }

    .container {
      text-align: center;
      display: table-cell;
      vertical-align: middle;
    }

    .content {
      text-align: center;
      display: inline-block;
    }

    .title {
      font-size: 72px;
      margin-bottom: 40px;
    }

  </style>
</head>

<body>
  <div class="container">
    <div class="content">
      <div class="title">Be right back.</div>
    </div>
  </div>
</body>

</html> -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Missing Files Error</title>
    <link href="{{ URL::asset('build/css/404error.css') }}" type="text/css" rel="stylesheet" media="all">
<link href="{{ URL::asset('/build/css/bootstrap.min.css') }}" type="text/css" rel="stylesheet" media="all">
</head>
<body>
<div class="preloader">
  <div class="cssload-speeding-wheel"></div>
</div>
<section id="wrapper" class="error-page">
  <div class="error-box">
    <div class="text-center">
      <h1 style="color:#FF9E19; font-size:150px; font-weight:800">503</h1>
      <h3 class="text-uppercase" style="font-size:35px; text-wrap:nowrap">Configuration Error..!!!</h3>
      <p class="text-muted" style="color:#FF9E19;font-size:22px;">The <code>.env</code> and/or <code>.htaccess</code> file is missing. Please ensure these files are present into the root directory.</p>
      <!-- <a href="{{ URL::previous() }}" class="btn btn-info btn-rounded waves-effect waves-light m-b-40">Please check it</a>  -->   </div>
      <footer class="footer text-center">{{ date('Y') }} © Copyright Garage Management System.</footer>
    </div>
 
</section>
</body>
</html>
