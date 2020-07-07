<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>

  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <script>window.Laravel = { csrfToken: '{{ csrf_token() }}' }</script>

  <title>{{ config('app.name', 'Online Grading System') }}</title>

  <!-- Custom fonts for this template-->
  <link href="{{ asset('vendor/fontawesome-free/css/all.min.css') }}" rel="stylesheet" type="text/css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

  <!-- Icon Title -->
  <link rel="icon" href="{!! asset('assets/logo.ico') !!}"/>
  <!-- Custom styles for this template-->
  <link href="{{ asset('css/common.css') }}" rel="stylesheet">
  <link href="{{ asset('css/sb-admin-2.min.css') }}" rel="stylesheet">
  <!-- Styles -->
  <link href="{{ asset('css/app.css') }}" rel="stylesheet">
  <link href="https://rawgit.com/lykmapipo/themify-icons/master/css/themify-icons.css" rel="stylesheet">
  <link id="external-css" rel="stylesheet" type="text/css" href="https://unpkg.com/element-ui/lib/theme-chalk/index.css" media="all">
<!--===============================================================================================-->
  <link rel="stylesheet" type="text/css" href="{{ asset('vendor/animate/animate.css') }}">
<!--===============================================================================================-->
  <link rel="stylesheet" type="text/css" href="{{ asset('vendor/animsition/css/animsition.min.css') }}">
<!--===============================================================================================-->
  <link rel="stylesheet" type="text/css" href="{{ asset('css/util.css') }}">
  <link rel="stylesheet" type="text/css" href="{{ asset('css/main.css') }}">
<!--===============================================================================================-->
</head>

<body id="page-top" class="bg-white">

  <!-- App Page -->
  <div id="app">
  <!-- Page Wrapper -->
    <div id="wrapper">
      @auth
          <!-- Sidebar -->
          <ul class="navbar-nav bg-black sidebar sidebar-dark accordion" id="accordionSidebar">

            <!-- Sidebar - Brand -->
            <a class="sidebar-brand d-flex align-items-center justify-content-center">
              <div class="sidebar-brand-image">
                <img id="img_logo" class="img-nav-logo" src="{{ asset('assets/default-logo.png') }}">
              </div>
            </a>
            <!-- Nav Item - Dashboard -->
            @php
              $user_role = Auth::user()->user_role;
            @endphp

            @if($user_role == 1)
              @include('nav.admin.index')
            @elseif($user_role == 2)
              @include('nav.principal.index')
            @elseif($user_role == 4)
              @include('nav.teacher.index')
            @elseif($user_role == 5)
              @include('nav.student.index')
            @endif
            
            <!-- Divider -->
            <hr class="sidebar-divider m-0">
            <li class="nav-item">
                <a class="nav-link" href="#" data-toggle="modal" data-target="#logoutModal">
                  <i class="fas fa-sign-out-alt"></i>
                  <span>{{ __('Logout') }} </span>
                </a>
                <form id="logout-form2" action="{{ url('/logout') }}" method="POST" class="d-none">
                    @csrf
                </form>
            </li>
            <!-- Divider -->
            <hr class="sidebar-divider d-none d-md-block">
            <!-- Sidebar Toggler (Sidebar) -->
            <div class="text-center d-none d-md-inline">
              <button class="rounded-circle border-0" id="sidebarToggle"></button>
            </div>

          </ul>
          <!-- End of Sidebar -->
      @endauth
      <!-- Content Wrapper -->
      <div id="content-wrapper" class="d-flex flex-column">
        <!-- Main Content -->
        <div id="content" class="bg-white">
          @auth
          <!-- Topbar -->
          <nav class="navbar navbar-expand navbar-dark nav-bg topbar mb-4 static-top shadow">
            <!-- Sidebar Toggle (Topbar) -->
            <button id="sidebarToggleTop" class="btn btn-link text-white d-md-none rounded-circle mr-3">
              <i class="fa fa-bars"></i>
            </button>
            <!-- Topbar Navbar -->
            <ul class="navbar-nav ml-auto">
              <div class="topbar-divider d-none d-sm-block"></div>
              <!-- Nav Item - User Information -->
              <li class="nav-item dropdown no-arrow">
                <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                  @if($user_role == 5)
                    <span class="mr-2 d-lg-inline text-white small">Hi {{ ucfirst(trans( Auth::user()->studentInfo->first_name)) }} {{ ucfirst(trans(Auth::user()->studentInfo->last_name)) }}</span>
                    <img class="img-profile rounded-circle" src="{{ Auth::user()->studentInfo->avatar ? Auth::user()->studentInfo->avatar : asset('assets/userid.png') }}">
                  @else
                    <span class="mr-2 d-lg-inline text-white small">Hi {{ ucfirst(trans( Auth::user()->info->first_name)) }} {{ ucfirst(trans(Auth::user()->info->last_name)) }}</span>
                    <img class="img-profile rounded-circle" src="{{ Auth::user()->info->avatar ? Auth::user()->info->avatar : asset('assets/userid.png') }}">
                  @endif
                </a>
                <!-- Dropdown - User Information -->
                <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="userDropdown">
                  <a class="dropdown-item" href="#" data-toggle="modal" data-target="#logoutModal">
                    <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                    Logout
                  </a>
                </div>
              </li>
            </ul>
          </nav>
          @endauth
          <!-- End of Topbar -->
          <!-- Begin Page Content -->
          @yield('content')
          <!-- /.container-fluid -->
        </div>
        <!-- End of Main Content -->

        <!-- Footer -->
        <footer class="sticky-footer bg-white" style="background-color: #e9faff !important" >
          <div class="container my-auto">
            <div class="copyright text-center my-auto">
              <span>Copyright &copy; OnlineGradingSystem 2020</span>
            </div>
          </div>
        </footer>
        <!-- End of Footer -->

      </div>
      <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
      <i class="fas fa-angle-up"></i>
    </a>

    <!-- Logout Modal-->
    <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="logout" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="logout">Ready to Leave?</h5>
            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">×</span>
            </button>
          </div>
          <div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
          <div class="modal-footer">
            <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
            <a class="btn btn-primary" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Logout</a>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">@csrf</form>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- App Page -->
  <!-- Core JS -->
  <script src="{{ asset('js/app.js') }}"></script>
  <!-- Custom scripts for all pages-->
  <script src="{{ asset('js/sb-admin-2.min.js') }}"></script>
  <script type="text/javascript">
      if ("img_logo" in localStorage && typeof sideBar !== 'undefined' && sideBar !== null ) {
        let src = localStorage.getItem("img_logo")
        $("#img_logo").attr("src",src);
      } else {
      $.get("/get-system-logo", (data, status) => {
        let src = data['image'];
        $("#img_logo").attr("src",src);
        localStorage.setItem("img_logo", src);
      });
      }
  </script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/1.5.3/jspdf.debug.js" integrity="sha384-NaWTHo/8YCBYJ59830LTz/P4aQZK1sS0SneOgAvhsIl3zBu8r9RevNg5lHCHAuQ/" crossorigin="anonymous"></script>
</body>

</html>
