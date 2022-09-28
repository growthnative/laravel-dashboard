<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <title>404 Error</title>

        <!-- Styles -->
       <link href="{{ asset('css/styles.css') }}" rel="stylesheet" />

       <!-- Scripts -->
       <script src="{{ asset('js/all.js') }}" crossorigin="anonymous"></script>

    </head>
    <body>
        <div id="layoutError">
            <div id="layoutError_content">                
                <main>
                    <div class="container">
                        <div class="row justify-content-center">
                            <div class="col-lg-6">
                                <div class="text-center mt-4">
                                    <img class="mb-4 img-error" src="/images/error-404-monochrome.svg" />
                                    <p class="lead">This requested URL was not found on this server.</p>
                                    <a href="{{ URL::previous() }}">
                                        <i class="fas fa-arrow-left me-1"></i>
                                        Return to Back
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </main>
            </div>
        </div>
        <script src="{{ asset('js/bootstrap.bundle.min.js') }}" crossorigin="anonymous"></script>
       <script src="{{ asset('js/scripts.js') }}" crossorigin="anonymous"></script>
    </body>
</html>
