
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Pizzoteria</title>
  <link rel="shortcut icon" type="image/png" href="{{ asset('dashboard//images/logos/favicon-32x32.png') }}" />
  <link rel="stylesheet" href="{{ asset('dashboard/css/styles.min.css') }}" />
</head>

<body>
  <div class="page-wrapper" id="main-wrapper" data-layout="vertical" data-navbarbg="skin6" data-sidebartype="full"
    data-sidebar-position="fixed" data-header-position="fixed">
    <div
      class="position-relative overflow-hidden radial-gradient min-vh-100 d-flex align-items-center justify-content-center">
      <div class="d-flex align-items-center justify-content-center w-100">
        <div class="row justify-content-center w-100">
         @if (Session::has('success'))
            <div class="col-md-8 mt-4">
              <div class="alert alert-success">
              {{ Session::get('success')}}
              </div>
            </div>
          @endif
          @if (Session::has('error'))
            <div class="col-md-8 mt-4">
              <div class="alert alert-danger">
              {{ Session::get('error')}}
              </div>
            </div>
          @endif
          <div class="col-md-8 col-lg-6 col-xxl-3">
            <div class="card mb-0">
                <a href="./index.html" class="text-nowrap logo-img text-center d-block py-1 w-100">
                  <img src="{{ asset('dashboard/images/logos/logo.png') }}" width="120" style="border-radius: 50%;margin-top:10px;" alt="">
                </a>
                <p class="text-center">Where Every Bite Melts In Pure Flavour 🍕🌿</p>
              <div class="card-body">
                
                <form id="userForm" action="{{ route('user.authentication') }}" method="POST" enctype="multipart/form-data">
                  @csrf
                  <div class="mb-3">
                    <label for="exampleInputEmail1" class="form-label">Email Id</label>
                    <input type="text" name="email" value="{{ old('email') }}" class="form-control @error('email') is-invalid @enderror" id="exampleInputEmail1" aria-describedby="emailHelp">
                    @error('email')
                      <p class="invalid-feedback" style="color: red;">{{ $message }}</p>
                    @enderror                  
                  </div>
                 
                  <div class="mb-4">
                    <label for="exampleInputPassword1" class="form-label">Password</label>
                    <input type="password" value="{{ old('password') }}" name="password" class="form-control @error('password') is-invalid @enderror" id="exampleInputPassword1">
                    @error('password')
                      <p class="invalid-feedback" style="color: red;">{{ $message }}</p>
                    @enderror 
                  </div>
                  
                  <button type="submit" class="btn btn-primary w-100 py-8 fs-4 mb-4 rounded-2">Sign In</button>
                  <div class="d-flex align-items-center justify-content-center">
                  </div><br>
                 
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <script src="{{ asset('dashboard/libs/jquery/dist/jquery.min.js') }}" ></script>
  <script src="{{ asset('dashboard/libs/bootstrap/dist/js/bootstrap.bundle.min.js') }}" ></script>

</body>

</html>