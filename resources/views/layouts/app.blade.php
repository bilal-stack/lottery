<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Lottery Feed') }}</title>

    <!-- Scripts -->
    <script src="{{ asset('assets/js/jquery-3.3.1.min.js') }}"></script>
    <!-- bootstrap js file -->
    <script src="{{ asset('assets/js/bootstrap.min.js') }}"></script>
    <!-- flipclock js file -->
    <script src="{{ asset('assets/js/flipclock.min.js') }}"></script>
    <!-- countdown js file -->
    <script src="{{ asset('assets/js/jquery.countdown.min.js') }}"></script>
    <!-- slick js file -->
    <script src="{{ asset('assets/js/slick.min.js') }}"></script>
    <!-- swiper js file -->
    <script src="{{ asset('assets/js/swiper.min.js') }}"></script>
    <!-- lightcase js file -->
    <script src="{{ asset('assets/js/lightcase.js') }}"></script>
    <!-- wow js file -->
    <script src="{{ asset('assets/js/wow.min.js') }}"></script>
    <!-- vamp js files -->
    <script src="{{ asset('assets/js/jquery.vmap.min.js') }}"></script>
    <script src="{{ asset('assets/js/jquery.vmap.world.js') }}"></script>
    <!-- main script js file -->
    <script src="{{ asset('assets/js/main.js') }}"></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <link rel="shortcut icon" type="image/png" href="{{ asset('assets/images/favicon.jpg') }}">
    <!-- fontawesome css link -->
    <link rel="stylesheet" href="{{ asset('assets/css/fontawesome.min.css') }}">
    <!-- bootstrap css link -->
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.min.css') }}">
    <!-- animate css link -->
    <link rel="stylesheet" href="{{ asset('assets/css/animate.css') }}">
    <!-- lightcase css link -->
    <link rel="stylesheet" href="{{ asset('assets/css/lightcase.css') }}">
    <!-- slick css link -->
    <link rel="stylesheet" href="{{ asset('assets/css/slick.css') }}">
    <!-- swiper css link -->
    <link rel="stylesheet" href="{{ asset('assets/css/swiper.min.css') }}">
    <!-- flipclock css link -->
    <link rel="stylesheet" href="{{ asset('assets/css/flipclock.css') }}">
    <!-- jqvmap css link -->
    <link rel="stylesheet" href="{{ asset('assets/css/jqvmap.min.css') }}">
    <!-- main style css link -->
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
    <!-- dark version css -->
    <link rel="stylesheet" href="{{ asset('assets/css/dark-version.css') }}">
    <!-- responsive css link -->
    <link rel="stylesheet" href="{{ asset('assets/css/responsive.css') }}">

</head>
<body>

<div class="main-light-version">
    <!--  header-section start  -->
    <header class="header-section">
      <div class="header-bottom">
        <div class="container">
          <nav class="navbar navbar-expand-xl">
            <a class="site-logo site-title" href="{{ route('home') }}">
                Lottery Feed
            </a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
              <span class="menu-toggle"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
              <ul class="navbar-nav main-menu ml-auto">
                <li><a href="{{ route('home') }}">Home</a></li>
                <li><a href="{{ route('lottery.index') }}">Lotteries</a>
                <!-- <li><a href="#">Jackpot</a></li> -->
                @guest
                    @if (Route::has('login'))
                        <li class="">
                            <a class="" href="{{ route('login') }}">{{ __('Login') }}</a>
                        </li>
                    @endif

                    @if (Route::has('register'))
                        <li class="">
                            <a class="" href="{{ route('register') }}">{{ __('Register') }}</a>
                        </li>
                    @endif
                @else

                  @hasanyrole('admin')
                      <li class="">
                          <a class="" href="{{ route('scraper') }}">{{ __('Scraper') }}</a>
                      </li>
                  @endhasanyrole

                <li class=" dropdown">
                    <a id="navbarDropdown" class=" dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                        {{ Auth::user()->name }}
                    </a>

                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                        <a class="dropdown-item" href="{{ route('logout') }}"
                           onclick="event.preventDefault();
                                         document.getElementById('logout-form').submit();">
                            {{ __('Logout') }}
                        </a>

                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                            @csrf
                        </form>
                    </div>
                </li>
                @endguest
              </ul>
            </div>
          </nav>
        </div>
      </div><!-- header-bottom end -->
    </header>
    <!--  header-section end  -->

    <div class="row justify-content-center">
        <div class="col-md-12">
            @if(Session::has('success'))
                <div class="alert alert-success" role="alert">
                    {{ Session::get('success') }}
                </div>
            @elseif(Session::has('error'))
                <div class="alert alert-danger" role="alert">
                    {!! Session::get('error') !!}
                </div>
            @endif
        </div>
    </div>

    @yield('content')


    <!-- footer-section start -->
    <footer class="footer-section">
        <div class="footer-bottom">
            <div class="container">
            <div class="row justify-content-between align-items-center">
                <div class="col-lg-6 col-sm-7">
                <div class="copy-right-text">
                    <p>© {{ date('Y') }} <a href="#">Lottery Feed</a> - All Rights Reserved.</p>
                </div>
                </div>
                <div class="col-lg-6 col-sm-5">
                <ul class="footer-social-links d-flex justify-content-end">
                    <li><a href="#0"><i class="fa fa-facebook"></i></a></li>
                    <li><a href="#0"><i class="fa fa-twitter"></i></a></li>
                    <li><a href="#0"><i class="fa fa-google-plus"></i></a></li>
                    <li><a href="#0"><i class="fa fa-instagram"></i></a></li>
                </ul>
                </div>
            </div>
            </div>
        </div>
    </footer>
    <!-- footer-section end -->

  </div>

    @yield('js')
</body>
</html>
