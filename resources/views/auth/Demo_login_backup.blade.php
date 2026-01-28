<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <meta name="description" content="">
    <meta name="author" content="">
    <title>Garage Management Login Page</title>
    <link rel="icon" href="{{ URL::asset('fevicol.png') }}" type="image/gif" sizes="16x16">
    <link rel="stylesheet" href="/dasinfoau/php/garage/build/css/garage-login.css">

    <!--<link rel="icon" href="../../favicon.ico">-->
    <!-- Bootstrap core CSS -->
    <!-- <link rel="stylesheet" href="https://school.pushnifty.com/wp-content/themes/twentytwenty-child/css/bootstrap.min.css"  integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous"> -->
    <!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css"> -->

    <link rel="stylesheet" type="text/css" href="/dasinfoau/php/garage/build/css/garage-login.css" />

    <link rel="stylesheet" type="text/css" href="https://school.pushnifty.com/wp-content/themes/twentytwenty-child/roboto.css" />
    <link rel="stylesheet" type="text/css" href="https://school.pushnifty.com/wp-content/themes/twentytwenty-child/style.css" />

    <link rel="stylesheet" type="text/css" href="https://school.pushnifty.com/wp-content/plugins/school-management/lib/validationEngine/css/validationEngine.jquery.css" />
    <link rel="stylesheet" type="text/css" href="https://school.pushnifty.com/wp-content/plugins/school-management/assets/accordian/jquery-ui.css" />
    <link rel="stylesheet" type="text/css" href="https://school.pushnifty.com/wp-content/plugins/school-management/assets/css/Bootstrap/bootstrap5.min.css" />

    <script type="text/javascript" src="https://school.pushnifty.com/wp-content/themes/twentytwenty-child/js/mdb.min.js"></script>
    <script type="text/javascript" src="https://school.pushnifty.com/wp-content/themes/twentytwenty-child/js/popup.js"></script>
    <script type="text/javascript" src="https://school.pushnifty.com/wp-content/plugins/school-management/lib/validationEngine/js/jquery.validationEngine.js"></script>
    <script type="text/javascript" src="https://school.pushnifty.com/wp-content/plugins/school-management/lib/validationEngine/js/languages/jquery.validationEngine-en.js"></script>
    <script type="text/javascript" src="https://school.pushnifty.com/wp-content/plugins/school-management/lib/validationEngine/js/jquery-1.8.2.min.js"></script>
    <script type="text/javascript" src="https://school.pushnifty.com/wp-content/plugins/school-management/assets/accordian/jquery-ui.js"></script>
    <script type="text/javascript" src="https://school.pushnifty.com/wp-content/plugins/school-management/assets/js/Bootstrap/bootstrap5.min.js"></script>
    <script type="text/javascript" src="https://school.pushnifty.com/wp-content/plugins/school-management/assets/js/Jquery/jquery-3.6.0.min.js"></script>


    <style>
        /* a.link-button-app-garage.link-button-app-school {
			/* } */
        @media only screen and (max-width: 425px) {
            .school-app-button-main-div.col-md-6 {
                margin-left: 75px;
                margin-top: 5px;
            }

            a.link-button-app-garage.link-button-app-school {
                margin-left: 2px;
            }
        }

        @media only screen and (width: 540px) {
            .school-app-button-main-div.col-md-6 {
                margin-left: 53px;
                margin-top: 5px;
            }

            a.link-button-app-garage.link-button-app-school {
                margin-left: -48px;
            }
        }

        @media only screen and (min-width: 768px) {
            a.link-button-app-garage.link-button-app-school {
                margin-left: -100px;
            }
        }

        @media only screen and (width: 280px) {
            a.link-button-app-school-document.link-button-app-school {
                margin-left: -49px;
            }

            a.link-button-app-garage.link-button-app-school {
                margin-left: -49px;
            }
        }

        @media only screen and (max-width: 540px) {
            a.forgot_pwd_scl {
                margin-left: -163px;
            }
        }

        @media only screen and (width: 1024px) {
            a.forgot_pwd_scl {
                margin-left: 79px;
            }
        }

        @media only screen and (width: 1024px) {
            p.login-remember {
                margin-left: 46px;
            }
        }

        @media only screen and (max-width: 412px) {
            .or_login_as_text_simple {
                margin-left: 123px;
            }
        }

        @media only screen and (width: 540px) {
            .or_login_as_text_simple {
                margin-left: 198px;
            }
        }

        @media only screen and (width: 540px) {
            .same-cls-sprite-bck-img {
                background-size: 89%;
            }
        }

        @media only screen and (max-width: 540px) {
            img.img-first-bck-contn-sch-round {
                visibility: collapse;
            }
        }

        .bookService {
            background: #EA6B00;
            color: #FFFFFF !important;
            padding: 2% 20%;
            border-radius: 0px 30px 30px 0px;
            border: 0px !important;
        }

        .frontend {
            text-align: left;
            font-size: initial;
            margin-left: -12%;
        }
    </style>

</head>
<script type="text/javascript" id="ZC_Forms_Popup" src="https://campaigns.zoho.com/js/optin.min.js"></script>
<!-- <script type="text/javascript">

		function open_popup_document()
		{
			//window.open("https://school.pushnifty.com/documentation/", '_blank')
			window.open("https://school.pushnifty.com/documentation/", '_blank')
		}

		function open_popup()
		{
			window.open("https://school.pushnifty.com/super-admin/", '_blank')
		}
	</script> -->
<script>
    $(document).ready(function() {
        // alert('111');
        $(".first-content-customer").click(function() {
            // alert('hyy');
            $('#user_login').val("Samuel@gmail.com");
            $('#user_pass').val("Samuel123");
            $('.login-demo label').addClass("active");
            $('.login-password label').addClass("active");
        });
    });
    $(document).ready(function() {
        // alert('111');
        $(".input").click(function() {
            // alert('hyy');
            //$('#user_login').val("");
            //$('#user_pass').val("");
            $('.login-demo label').addClass("active");
            $('.login-password label').addClass("active");
        });
    });

    $(document).ready(function() {
        $(".second-content-employee").click(function() {
            $('#user_login').val("William@gmail.com");
            $('#user_pass').val("William123");
            $('.login-demo label').addClass("active");
            $('.login-password label').addClass("active");
        });
    });
    $(document).ready(function() {
        $(".third-content-support_staff ").click(function() {
            $('#user_login').val("George@gmail.com");
            $('#user_pass').val("George123");
            $('.login-demo label').addClass("active");
            $('.login-password label').addClass("active");
        });
    });
    $(document).ready(function() {
        $(".four-content-accountant ").click(function() {
            $('#user_login').val("James@gmail.com");
            $('#user_pass').val("James123");
            $('.login-demo label').addClass("active");
            $('.login-password label').addClass("active");
        });
    });
</script>
<script>
    $('#user_login').on('keyup', function()
        //$(document).ready(function()
        {
            //alert('test');
            if ($("#user_login").val() != "") {
                $(".login-demo label").addClass("active");
                $(".login-password label").addClass("active");

            } else {
                $(".login-demo label").removeClass("active");
                $(".login-password label").removeClass("active");
            }
        });
</script>
<script>
    $(document).ready(function() {
        jQuery.fn.center = function(parent) {
            if (parent) {
                parent = this.parent();
            } else {
                parent = window;
            }
            this.css({
                "position": "absolute",
                "top": ((($(parent).height() - this.outerHeight()) / 2) + $(parent).scrollTop() + "px"),
                "left": ((($(parent).width() - this.outerWidth()) / 2) + $(parent).scrollLeft() + "px"),
                "right": ((($(parent).width() - this.outerWidth()) / 2) + $(parent).scrollLeft() + "px")
            });
            return this;
        }
        $("div.background-main-div-plugin-login:nth-child(1)").center(true);
    });
</script>
<script>
    $(document).ready(function() {
        $("#user_login").attr("autocomplete", "off");
        $("#user_pass").attr("autocomplete", "new-password");
    });
</script>

<body class="school-login-page school-page">
    <!-- @if(session('status'))
	<div class="alert alert-danger">
	{{ session('status') }}
	</div>
	@endif -->

    <div class="background-main-div-plugin-login">
        <div class="container">
            <div class="main-div-school-container">
                <div class="header-bnner-login-page-mc">
                    <div class="heade-content-login-page">
                        <h1 class="header-title-trusted-plugin"><span class="double_shadow_to_text_plugin_trusted">Most Trusted Garage Software</span></h1>
                        <h3 class="selling-codecanyon-plugin">Best in segment on codecanyon</h3>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-5 col-md-5 sidebar">
                        <!--<ul class="nav nav-sidebar">
						<li class="active"><a href="#">Overview <span class="sr-only">(current)</span></a></li>
						<li><a href="#">Reports</a></li>
						<li><a href="#">Analytics</a></li>
						<li><a href="#">Export</a></li>
					  </ul>-->
                        <nav class="navbar navbar-inverse nav-school-menu-logo">
                            <div class="navbar-header">
                                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                                    <span class="sr-only">Toggle navigation</span>
                                    <span class="icon-bar"></span>
                                    <span class="icon-bar"></span>
                                    <span class="icon-bar"></span>
                                </button>
                                <!--<a class="navbar-brand" href="#">Project name</a>-->
                            </div>

                            <div class="logo-title-img-school-plugin">
                                <a href="{{ url('/garage') }}" class="custom-logo-link" rel="home">
                                    <img src="/dasinfoau/php/garage/public/middle_login_page/Garage-Logo- 1.png">
                                </a>
                                <!-- <img width="80" height="80" src="https://school.pushnifty.com/wp-content/uploads/2022/05/finel-logo6.png" class="custom-logo" alt="WP School" decoding="async" />
									 <p class="site-title"><a href="https://school.pushnifty.com/" rel="home">WP School</a></p>
						             <p class="site-description">School Management System </p> -->
                                <?php if (getFrontendBooking() === 1) : ?>
                                    <div class="w-auto frontend my-5">
                                        <a class="bookService rounded-right" href="{{ url('/service/frontendBook') }}">Book an appoinment</a>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <div id="navbar" class="navbar-collapse collapse main-menu-desktop-div" aria-expanded="false" style="height: 1px;">
                                <!--<ul class="nav nav-sidebar">
								<li class="active"><a href="#">Overview <span class="sr-only">(current)</span></a></li>
								<li><a href="#">Reports</a></li>
								<li><a href="#">Analytics</a></li>
								<li><a href="#">Export</a></li>
							  </ul>-->
                                <!-- <div class="menu-main-menu-container"><ul id="menu-main-menu" class="ul-menu-tag-school nav  navbar-nav navbar-left-menu int-collapse-menu"><li id="menu-item-39" class="menu-item menu-item-type-post_type menu-item-object-page current-menu-item page_item page-item-29 current_page_item menu-item-39"><a href="https://school.pushnifty.com/school-management-login-page/" aria-current="page">School Management Login Page</a></li>
                                        <li id="menu-item-38" class="menu-item menu-item-type-post_type menu-item-object-page menu-item-38"><a href="https://school.pushnifty.com/student-registration/">Student Registration</a></li>
                                        <li id="menu-item-37" class="menu-item menu-item-type-post_type menu-item-object-page menu-item-37"><a href="https://school.pushnifty.com/student-admission/">Student Admission</a></li>
                                        </ul></div> -->
                            </div>
                        </nav>
                    </div>
                    <div class="col-sm-7 col-sm-offset-3 col-md-7 col-md-offset-2 main content-start" style="margin: auto;">
                        <div class="content-form-login-page-school-plugin md-form">
                            <form class="form-horizontal" method="POST" action="{{ url('/login') }}">
                                {{ csrf_field() }}
                                <p class="login-demo">
                                    <label for="user_login"> Email </label>

                                    <input type="text" name="email" id="user_login" autocomplete="username" class="input" value="" size="20" />
                                    <!-- @if ($errors->has('email'))
              <div class="mb-1">
                <span class="text-start text-danger"
                  style="margin-top: -15px;">
                  <strong>{{ $errors->first('email') }}</strong>
                </span>
              </div>
              @endif -->
                                    <!-- @error('email')
							<div class="alert alert-danger mt-1 mb-1">{{ $message }}</div>
						@enderror -->
                                    <!-- <span class="text-start text-danger"
                  style="margin-top: -15px;">
                  <strong>These credentials do not match our records.</strong>
                </span> -->

                                </p>
                                <p class="login-password">
                                    <label for="user_pass">Password</label>
                                    <input type="password" name="password" id="user_pass" autocomplete="current-password" class="input" value="" size="20" />
                                    <!-- @error('password')
							<div class="alert alert-danger mt-1 mb-1">{{ $message }}</div>
						@enderror -->
                                </p>
                                <p class="login-remember"><label><input name="rememberme" type="checkbox" id="rememberme" value="forever" /> Remember me</label>
                                </p>
                                <a class="forgot_pwd_scl" href="https://pushnifty.com/dasinfoau/php/garage/password/reset" title="Lost Password">Forgot Password?</a>

                                <!-- <p class="forgot_pwd_scl"><label><a href="https://school.pushnifty.com/lostpassword/?redirect_to=" title="Lost Password" > Forgot password? </a></label>
					</p> -->
                                <p class="login-submit">
                                    <input type="submit" name="wp-submit" id="wp-submit" class="button button-primary" value="Log In" />
                                    <input type="hidden" name="redirect_to" value=" " />
                                    <!-- <a href="{!! url('/') !!}"> </a> -->
                                </p>
                            </form>
                        </div>

                        <div class="or_login_as_text_simple">
                            <p>Or Login As <span class="icon_after_or_login_as_sch_demo"></span> </p>
                        </div>
                        <div class="new-total-four-content-logo-plugin-scl col-sm-12">
                            <div class="first-content-customer col-sm-2 same-img-div-main-class-schl">
                                <!--<a href="">-->
                                <div class="first-img-set-access same-cls-sprite-bck-img">
                                </div>
                                <div class="title-student-new title-school-all-stps">
                                    <p>Customer</p>
                                </div>
                                <!--</a>-->
                            </div>
                            <div class="second-content-employee col-sm-2 same-img-div-main-class-schl">
                                <!--<a href="">-->
                                <div class="second-img-set-access same-cls-sprite-bck-img">
                                </div>
                                <div class="title-student-new title-school-all-stps">
                                    <p>Employee</p>
                                </div>
                                <!--</a>-->
                            </div>
                            <div class="third-content-support_staff col-sm-2 same-img-div-main-class-schl">
                                <!--<a href="">-->
                                <div class="third-img-set-access same-cls-sprite-bck-img">
                                </div>
                                <div class="title-student-new title-school-all-stps">
                                    <p>support staff</p>
                                </div>
                                <!--</a>-->
                            </div>
                            <div class="four-content-accountant col-sm-2 same-img-div-main-class-schl">
                                <!--<a href="">-->
                                <div class="four-img-set-access same-cls-sprite-bck-img">
                                </div>
                                <div class="title-student-new title-school-all-stps">
                                    <p>Accountant</p>
                                </div>
                                <!--</a>-->
                            </div>
                            <!-- 							
							
							<div> </div>
							 <div onclick="open_popup()" class="col-sm-2 same-img-div-main-class-schl super_admin_popup" >
								<a href="">-->
                            <!-- <div class="five-img-set-access same-cls-sprite-bck-img">
									</div>
									<div class="title-student-new title-school-all-stps">
										<p class="super_admin">Super Admin</p>
									</div> -->
                            <!--</a>-->
                            <!-- </div> -->
                        </div>

                        <!-- <div class"row"> -->
                        <!--- Div start school-app-button --->
                        <div class="school-app-button-main-div col-md-6" onclick="open_popup_document()">
                            <a class="link-button-app-school-document link-button-app-school" href="https://pushnifty.com/dasinfoau/php/garage/zohopopup" target="target_black"><img src="/dasinfoau/php/garage/public/middle_login_page/Icons_sprite (3) 1.png"><i class="fa fa-file" aria-hidden="true"></i>Super Admin</a>
                        </div>
                        <div class="school-app-button-main-div col-md-6">
                            <a class="link-button-app-garage link-button-app-school" href="https://mojoomlasoftware.github.io/laravel-garage-document/" target="target_black"><img src="/dasinfoau/php/garage/public/middle_login_page/Help Document-.png"><i class="fa fa-file" aria-hidden="true"></i>Help Document</a>
                        </div>
                        <div class="new-total-four-content-logo-plugin-scl col-sm-12">
                            <div class="col-6"></div>
                            <div class="col-6">
                                <p class="logo text-end mb-0">
                                    <img src="/dasinfoau/php/garage/public/middle_login_page/Mojoomla_Logo.png" alt="Your Image Description" height="50%" class="w-auto shadow rounded-circle" style="margin: -20% 68%;">
                                </p>
                            </div>
                        </div>

                        <!-- <div class="school-app-button-main-div col-md-6">
							<a class="link-button-app-garage" href="https://codecanyon.net/item/school-master-mobile-app-for-android/20806118" target="target_black"><i class="fa fa-file" aria-hidden="true"></i>Help Decument</a>
						</div> -->
                        <!-- Ends -->
                        <!-- </div> -->

                    </div>
                </div>
            </div>
            <!--<div class="bottom-corner-img-right-circle-scl-plg">
				  </div>-->
        </div>
        <div class="img-all-background-box-bck-main-cont">
            <div class="img-one-right-side-min-sch">
                <img class="img-first-bck-contn-sch" alt="garage Right Image" src="/dasinfoau/php/garage/public/middle_login_page/Group 18368.png">
            </div>

            <div class="img-second-right-side-min-sch">
                <img class="img-second-bck-contn-sch" alt="garage Left Image" src="/dasinfoau/php/garage/public/middle_login_page/Group 18367 (1).png ">
            </div>
            <div class="img-one-right-side-min-sch">
                <img class="img-first-bck-contn-sch-round" alt="garage bottom Image" src="/dasinfoau/php/garage/public/middle_login_page/Group 18369.png">
            </div>
            <!-- <div class="img-third-right-side-min-sch">
							<img class="img-third-bck-contn-sch" alt="garage bottom Image" src="/dasinfoau/php/garage/public/middle_login_page/Group 18369.png" >
						</div> -->
        </div>
    </div>
    <!-- Bootstrap core JavaScript
		================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <!--<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></scrip>-->
    <!--<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></>-->




</body>

</html>