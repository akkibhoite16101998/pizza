
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Pizzoteria</title>
  <link rel="shortcut icon" type="image/png" href="{{ asset('dashboard/images/logos/favicon-32x32.png') }}"/>
  <link href="{{ asset('dashboard/css/styles.min.css') }}" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" integrity="sha384-HmLO8g0JWBXaNseBhITelYRhZcZ1cUsKdPn/0sA9Z5jphvIgF0JOhWgJzW8B5J8T" crossorigin="anonymous">
  <!-- Font Awesome 6 CDN -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
  <!-- Select2 CSS -->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet">


</head>
<body>
  <!--  Body Wrapper -->
  <div class="page-wrapper" id="main-wrapper" data-layout="vertical" data-navbarbg="skin6" data-sidebartype="full"
    data-sidebar-position="fixed" data-header-position="fixed">
    <!-- Sidebar Start -->
    <aside class="left-sidebar">
      <!-- Sidebar scroll-->
      <div>
        <div class="brand-logo d-flex align-items-center justify-content-between">
         <a href="{{-- {{ route('account.dashboard') }} --}}" class="text-nowrap logo-img"> 
            <img src="{{ asset('dashboard/images/logos/logo.png') }}"  width="80" alt="" style="margin-top:10px;width: 100px;height: 100px;border-radius: 50%;object-fit: cover; overflow: hidden;" />
          </a>
          <div class="close-btn d-xl-none d-block sidebartoggler cursor-pointer" id="sidebarCollapse">
            <i class="ti ti-x fs-8"></i>
          </div>
        </div>
        <!-- Sidebar navigation-->
        <nav class="sidebar-nav scroll-sidebar" data-simplebar="">
           @php
              $user_role = Auth::user()->role;

              //echo $user_role;
            @endphp
            <ul id="sidebarnav">
    <li class="nav-small-cap">
        <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
        <span class="hide-menu">Home</span>
    </li>

    <li class="sidebar-item {{ request()->routeIs('account.dashboard') ? 'active' : '' }}">
        <a class="sidebar-link" href="{{-- {{ route('account.dashboard') }} --}}" aria-expanded="false">
            <span><i class="ti ti-layout-dashboard"></i></span>
            <span class="hide-menu">Dashboard</span>
        </a>
    </li>

    <li class="nav-small-cap">
        <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
        <span class="hide-menu">UI COMPONENTS</span>
    </li>

    @if(Auth::user()->id === 1)
        <li class="sidebar-item {{ request()->routeIs('add_fund_view') ? 'active' : '' }}">
            <a class="sidebar-link" href="{{ route('add_fund_view') }}" aria-expanded="false">
                <span><i class="fa-solid fa-inr"></i></span>
                <span class="hide-menu">Credit Fund</span>
            </a>
        </li>

        <li class="sidebar-item {{ request()->routeIs('expens_fund_view') ? 'active' : '' }}">
            <a class="sidebar-link" href="{{ route('expens_fund_view') }}" aria-expanded="false">
                <span><i class="fa-solid fa-inr"></i></span>
                <span class="hide-menu">Debit Fund</span>
            </a>
        </li>
    @endif

    @if(Auth::user()->role === 'admin')
        <li class="sidebar-item {{ request()->routeIs('menulist') ? 'active' : '' }}">
            <a class="sidebar-link" href="{{ route('menulist') }}" aria-expanded="false">
                <span><i class="fa-solid fa-utensils"></i></span>
                <span class="hide-menu">Menu List</span>
            </a>
        </li>
    @endif

    <li class="sidebar-item {{ request()->routeIs('order') ? 'active' : '' }}">
        <a class="sidebar-link" href="{{ route('order') }}" aria-expanded="false">
            <span><i class="fa-solid fa-pizza-slice"></i></span>
            <span class="hide-menu">Order</span>
        </a>
    </li>

    <li class="sidebar-item {{ request()->routeIs('orderlist') ? 'active' : '' }}">
        <a class="sidebar-link" href="{{ route('orderlist') }}" aria-expanded="false">
            <span><i class="fa fa-list-ul"></i></span>
            <span class="hide-menu">Order List</span>
        </a>
    </li>
    <!--
    <li class="sidebar-item {{ request()->routeIs('getCustomerWithDetails') ? 'active' : '' }}">
        <a class="sidebar-link" href="{{-- {{ route('bills.getCustomerWithDetails') }} --}}" aria-expanded="false">
            <span><i class="fa-solid fa-file-invoice"></i></span>
            <span class="hide-menu">Bills List</span>
        </a>
    </li>-->

    <li class="sidebar-item">
        <a class="sidebar-link" href="{{ route('logout') }}" aria-expanded="false">
            <span><i class="fa fa-power-off"></i></span>
            <span class="hide-menu">Logout</span>
        </a>
    </li>
</ul>

          
        </nav>
        <!-- End Sidebar navigation -->
      </div>
      <!-- End Sidebar scroll-->
    </aside>
    <!--  Sidebar End --> 

    <!--  Main wrapper -->
    <div class="body-wrapper">
      <!--  Header Start -- >
      <header class="app-header">
        <nav class="navbar navbar-expand-lg navbar-light">
          <ul class="navbar-nav">
            <li class="nav-item d-block d-xl-none">
              <a class="nav-link sidebartoggler nav-icon-hover" id="headerCollapse" href="javascript:void(0)">
                <i class="ti ti-menu-2"></i>
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link nav-icon-hover" href="javascript:void(0)"> 
                <i class="ti ti-bell-ringing"></i>
                <div class="notification bg-primary rounded-circle"></div>
              </a>
            </li>
          </ul>
          <div class="navbar-collapse justify-content-end px-0" id="navbarNav">
            <ul class="navbar-nav flex-row ms-auto align-items-center justify-content-end">
              <li class="nav-item dropdown">
                <a class="nav-link nav-icon-hover" href="javascript:void(0)" id="drop2" data-bs-toggle="dropdown"
                  aria-expanded="false">
                  <img src="{{ asset('/dashboard/images/profile/user-1.jpg') }}" alt="" width="35" height="35" class="rounded-circle">
                </a>
                <div class="dropdown-menu dropdown-menu-end dropdown-menu-animate-up" aria-labelledby="drop2">
                  <div class="message-body">
                    <a href="javascript:void(0)" class="d-flex align-items-center gap-2 dropdown-item">
                      <i class="ti ti-user fs-6"></i>
                      <p class="mb-0 fs-3">My Profile</p>
                    </a>
                    <a href="javascript:void(0)" class="d-flex align-items-center gap-2 dropdown-item">
                      <i class="ti ti-mail fs-6"></i>
                      <p class="mb-0 fs-3">{{ Auth::user()->email }}</p>
                    </a>
                    <a href="" class="d-flex align-items-center gap-2 dropdown-item">
                      <i class="ti ti-list-check fs-6"></i>
                      <p class="mb-0 fs-3">My Task</p>
                    </a>
                    <a href=" {{ route('logout') }} " class="btn btn-outline-primary mx-3 mt-2 d-block">Logout </a> 
                  </div>
                </div>
              </li>
            </ul>
          </div>
        </nav>
      </header>
      <! --  Header End -->
      @if (Session::has('success'))
        <div class="col-md-12 mt-4">
            <div class="alert alert-success alert-dismissible fade show" id="success-alert">
                {{ Session::get('success') }}
            </div>
        </div>
      @endif

      @if (Session::has('error'))
        <div class="col-md-12 mt-4">
            <div class="alert alert-danger alert-dismissible fade show" id="error-alert">
                {{ Session::get('error') }}
            </div>
        </div>
      @endif
    
    @yield('main')
  </div>
  <script src="{{ asset('dashboard/libs/jquery/dist/jquery.min.js') }}"  ></script>
  <script src="{{ asset('dashboard/libs/bootstrap/dist/js/bootstrap.bundle.min.js') }}"  ></script>
  <script src="{{ asset('dashboard/js/sidebarmenu.js') }}"  ></script>
  <script src="{{ asset('dashboard/js/app.min.js') }}" ></script>
  <script src="{{ asset('dashboard/libs/apexcharts/dist/apexcharts.min.js') }}"  ></script>
  <script src="{{ asset('dashboard/libs/simplebar/dist/simplebar.js') }}" ></script>
  <script src="{{ asset('dashboard/js/dashboard.js') }}" ></script>
  <!-- Select2 JS -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
  
  <script>
    // Hide alerts after 5 seconds (5000ms)
    setTimeout(function () {
        document.getElementById('success-alert')?.remove();
        document.getElementById('error-alert')?.remove();
    }, 10000);
</script>
<script>
    $(document).ready(function() {
        $('.select2-multiple').select2({
            placeholder: "Select",
            allowClear: true
        });
    });
</script>
</body>
</html>
