    <!doctype html>
    <html lang="en">
        @include('sweetalert::alert')
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>S-Kepuharjo</title>
        <link rel="shortcut icon" type="image/png" href="{{ asset('template/images/logo.png') }}" />
        <link rel="stylesheet" href="{{ asset('assets/css/styles.min.css') }}" />
    </head>
    
<body>
        <!--  Body Wrapper -->
    <div class="page-wrapper" id="main-wrapper" data-layout="vertical" data-navbarbg="skin6" data-sidebartype="full"
        data-sidebar-position="fixed" data-header-position="fixed">
    <div
            class="position-relative overflow-hidden radial-gradient min-vh-100 d-flex align-items-center justify-content-center">
        <div class="d-flex align-items-center justify-content-center w-100">
            <div class="row justify-content-center w-100">
                <div class="col-md-8 col-lg-3 col-xxl-3">
                    <div class="card mb-0">
                    <div class="card-body">
                    <a href="" class="text-nowrap logo-img text-center d-block py-3 w-100">
                        <img src="assets/img/logo.png" width="180" alt="">
                    </a>
                    <p class="text-center">Masuk Sekarang</p>
                    <form action="{{ url('login/auth') }}"  method="POST">
                        @csrf
                        <div class="mb-3">
                        <label for="username" class="form-label">Username</label>
                        <input type="text" name="username"
                                    class="form-control @error('username') is-invalid
                                @enderror"
                                    value="{{ old('username') }}" placeholder="Username">
                                @error('username')
                        <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-4">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" name="password"
                                            class="form-control @error('password') is-invalid
    
                                        @enderror"
                                            value="{{ old('password') }}" placeholder="Password">
                                        @error('password')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                        </div>
                        <button type="submit" class="btn btn-success w-100 py-8 fs-4 mb-4 rounded-2">Masuk</button>
                        <div class="d-flex align-items-center justify-content-center">
                        <p class="fs-4 mb-0 fw-bold">S-Kepuharjo@2023</p>
                        </div>
                        </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="{{ asset('frontend/assets/libs/jquery/dist/jquery.min.js') }}"></script>
    <script src="{{ asset('frontend/assets/libs/bootstrap/dist/js/bootstrap.bundle.min.') }}js"></script>
</body>
    
    </html>
    {{-- toast cdn --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"
        integrity="sha512-VEd+nq25CkR676O+pLBnDW09R7VQX9Mdiij052gVCp5yVH3jGtH70Ho/UUv4mJDsEdTvqRCFZg0NKGiojGnUCw=="
    crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    {{-- jquery cdn  --}}
    <script src="https://code.jquery.com/jquery-3.6.4.min.js" 
        integrity="sha256-oP6HI9z1XaZNBrJURtCoUT5SUnxFr8s3BzRl+cbzUq8=" crossorigin="anonymous"></script> 