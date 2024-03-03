
<!DOCTYPE html>
<html lang="en" class="h-100">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="keywords" content="admin, dashboard">
    <meta name="author" content="DexignZone">
    <meta name="robots" content="index, follow">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Portfolio Admin">
    <meta property="og:title" content="Portfolio Admin">
    <meta property="og:description" content="Portfolio Admin">
    <meta property="og:image" content="https://dompet.dexignlab.com/xhtml/social-image.png">
    <meta name="format-detection" content="telephone=no">
    <meta name="csrf-token" content="{{ csrf_token() }}" />

    <!-- PAGE TITLE HERE -->
    <title>Login</title>

    <!-- FAVICONS ICON -->
    <link rel="shortcut icon" type="image/png" href="{{ '/plugins/images/favicon.png' }}">
    <link href="{{ 'plugins/css/style.css' }}" rel="stylesheet">
    <link href="{{ '/plugins/parsleyjs/parsley_style.css' }}" rel="stylesheet">

    <!--- JQuery min js --->
    {{-- <script src="{{ '/plugins/vendor/global/global.min.js') }}"></script>
    <script src="{{'/plugins/parsleyjs/parsley.min.js')}}"></script> --}}

    <script src="{{ '/plugins/js/jquery.min.js' }}"></script>
    <script src="{{ '/plugins/vendor/global/global.min.js' }}"></script>
    <script src="{{ '/plugins/vendor/jquery-nice-select/js/jquery.nice-select.min.js' }}"></script>
    <script src="{{'/plugins/parsleyjs/parsley.min.js'}}"></script>
</head>

<body class="vh-100">
<div class="authincation h-100">
    <div class="container h-100">
        <div class="row justify-content-center h-100 align-items-center">
            <div class="col-md-6">
                <div class="authincation-content">
                    <div class="row no-gutters">
                        <div class="col-xl-12">
                            <div class="auth-form">
                                <div class="text-center mb-3">
                                    <img src="{{ '/assets/portfolio.png' }}" alt="" width="90%" height="170">
                                </div>
                                <h4 class="text-center mb-4">Sign in your account</h4>
                                <form id="login_form" data-parsley-validate="parsley">
                                    <div class="mb-3">
                                        <label class="mb-1"><strong>Username</strong></label>
                                        <input type="text" id="username" name="username" class="form-control" value="" data-parsley-trigger="keyup" required>
                                    </div>
                                    <div class="mb-3">
                                        <label class="mb-1"><strong>Password</strong></label>
                                        <input type="password" id="password" name="password" class="form-control" value="" data-parsley-trigger="keyup" required>
                                    </div>
                                    <div class="row d-flex justify-content-between mt-4 mb-2">
                                        <div class="mb-3">
                                            <a href="javascript:void(0)">Forgot Password?</a>
                                        </div>
                                    </div>
                                    <div class="text-center">
                                        <button type="submit" class="btn btn-primary btn-block">Sign Me In</button>
                                    </div>
                                </form>
                                <div class="new-account mt-3">
                                    <p>Don't have an account? <a class="text-primary" href="javascript:void(0)">Sign up</a></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<!--**********************************
    Scripts
***********************************-->
<!-- Required vendors -->
<script src="{{ '/plugins/js/custom.min.js' }}"></script>
<script>
    $(document).ready(function () {

        $('#login_form').on('submit', function(e) {
            e.preventDefault();

            let form = $('#login_form');
            let button = form.find('button[type="submit"]');

            if ($('#login_form').parsley().validate()) {
                let data = form.serialize();
                $(".remove_custome_div").remove();

                $.ajax({
                    url: window.origin + '/user-login',
                    type: "post",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: data,
                    beforeSend: function() {
                        button.attr('disabled', true).text('Please wait...')
                    },
                    complete: function() {
                        button.attr('disabled', false).text('Login');
                    },
                    success: function(response) {

                        if(response.success) {
                            let redirectRoute = '/dashboard';
                            window.location.href = redirectRoute;
                        }else{
                            // Toast.fire({
                            //     icon: 'error',
                            //     title: response.message
                            // })
                        }
                    },
                    error: function(xhr, status, error) {
                        var e = JSON.parse(xhr.responseText);

                        var errorCode = e.errors_fields;

                        for (x in errorCode) {
                            $("#login_form").find("input#" + x).after(
                                "<span  class='error remove_custome_div text-danger'  id=" + x +"-error'>" + errorCode[x] + "</span>");
                        }
                    },

                });
            }
        });

    });
</script>
</body>
</html>
