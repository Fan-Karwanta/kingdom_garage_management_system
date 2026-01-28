

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Database Connection Error</title>
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
      <h1 style="color:#FF9E19; font-size:150px; font-weight:800">500</h1>
      <h3 class="text-uppercase" style="font-size:35px;">Oops! Something went wrong.</h3>
      <p class="text-muted" style="color:#FF9E19;font-size:22px;">Your database credentials are incorrect. Please check your database settings in the <code>.env</code> file.</p>
      <!-- <a href="{{ URL::previous() }}" class="btn btn-info btn-rounded waves-effect waves-light m-b-40">Please check it</a>  -->   </div>
      <footer class="footer text-center">{{ date('Y') }} © Copyright Garage Management System.</footer>
    </div>
 
</section>
</body>
</html>



