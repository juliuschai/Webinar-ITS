@extends('layouts.app')

@section('content')

    <!-- Bootstrap -->
    <link href="{{ asset('assets/vendors/bootstrap/dist/css/bootstrap.min.css') }}" rel="stylesheet">
    <!-- <!-- NProgress -->
    <link href="{{ asset('assets/vendors/nprogress/nprogress.css') }}" rel="stylesheet">
    <!-- iCheck -->
    <link href="{{ asset('assets/vendors/iCheck/skins/flat/green.css') }}" rel="stylesheet">
	
    <!-- bootstrap-progressbar -->
    <link href="{{ asset('assets/vendors/bootstrap-progressbar/css/bootstrap-progressbar-3.3.4.min.css') }}" rel="stylesheet">
    <!-- JQVMap -->
    <link href="{{ asset('assets/vendors/jqvmap/dist/jqvmap.min.css') }}" rel="stylesheet"/>
    <!-- bootstrap-daterangepicker -->
    <link href="{{ asset('assets/vendors/bootstrap-daterangepicker/daterangepicker.css') }}" rel="stylesheet">

  </head>

  <body class="nav-md admin">
    <div class="container body admin">
      <div class="main_container">
        <div id="dashboard" class="col-md-3 left_col">
          <div class="left_col scroll-view">
            <div class="navbar nav_title" style="border: 0;">
              <a href="index.html" class="site_title">
                  <span>Dashboard</span></a>
            </div>

            <div class="clearfix"></div>

            <br />

            <!-- sidebar menu -->
            <div id="sidebar-menu" class="main_menu_side hidden-print main_menu">
              <div class="menu_section">
                <!-- Home, List, Calendar, Report -->
                <ul class="nav side-menu">
                  <li><a><i class="fa fa-home"></i> Home <span class="fa fa-chevron-down"></span></a>
                    <ul class="nav child_menu">
                      <li><a href="index.html">Dashboard</a></li>
                    </ul>
                  </li>
                  <li><a><i class="fa fa-edit"></i> Booking <span class="fa fa-chevron-down"></span></a>
                    <ul class="nav child_menu">
                      <li><a href="{{ url('/booking/new') }}">Booking Webinar</a></li>
                      <li><a href="form_advanced.html">Edit Webinar</a></li>
                      <li><a href="form_validation.html">Form Validation</a></li>
                    </ul>
                  </li>
                  <li><a><i class="fa fa-desktop"></i> Calendar <span class="fa fa-chevron-down"></span></a>
                    <ul class="nav child_menu">
                      <li><a href="calendar.html">Calendar</a></li>
                    </ul>
                  </li>
                  <li><a><i class="fa fa-table"></i> Report <span class="fa fa-chevron-down"></span></a>
                    <ul class="nav child_menu">
                      <li><a href="tables.html">Tables</a></li>
                    </ul>
                  </li>
                </ul>
              </div>

            </div>
            <!-- /sidebar menu -->

          </div>
        </div>

        <!-- top navigation -->
        <div class="top_nav">
          <div class="nav_menu">
              <div class="nav toggle">
                <a id="menu_toggle"><i class="fa fa-bars"></i></a>
              </div>
            <nav class="nav navbar-nav">
              <ul class=" navbar-right">
                <li class="nav-item dropdown open" style="padding-left: 15px;">
                  <a style="color: white" href="javascript:;" class="user-profile dropdown-toggle" aria-haspopup="true" id="navbarDropdown" data-toggle="dropdown" aria-expanded="false">
                    <i class="fa fa-user admin"></i>User
                  </a>
                  <div class="dropdown-menu dropdown-usermenu pull-right" aria-labelledby="navbarDropdown">
                    <a class="dropdown-item"  href="login.html"><i class="fa fa-sign-out pull-right"></i> Log Out</a>
                  </div>
                </li>

              </ul>
            </nav>
          </div>
        </div>
        <!-- /top navigation -->

            <!-- page content -->
        <!-- <div class="container"> -->
            <!-- <div class="row justify-content-center"> -->
            <div class="right_col booking" role="main">
                <div class="col-md-12 col-sm-12 ">
                    <div class="card">
                        <div class="card-header">{{ __('Booking Form') }}</div>

                        <div class="card-body">
                            @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                            @endif
                            <form method="POST" action="{{ route('booking.new') }}">
                                @csrf

                                <div class="form-group row">
                                    <label for="namaAcara" class="col-md-4 col-form-label text-md-left">{{ __('Nama Acara') }}</label>
                                    <i class="fa fa-sticky-note-o booking"></i>
                                    <div class="col-md-6">
                                        <input id="namaAcara" type="text" class="form-control" name="namaAcara" value="{{ old('namaAcara') }}" required autofocus>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="unitDepartemen" class="col-md-4 col-form-label text-md-left">{{ __('Unit/Departemen') }}</label>
                                    <i class="fa fa-building booking"></i>
                                    <div class="col-md-6">
                                        <input id="unitDepartemen" type="text" class="form-control" name="unitDepartemen" value="{{ old('unitDepartemen') }}" required>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="namaAnda" class="col-md-4 col-form-label text-md-left">{{ __('Nama Anda') }}</label>
                                    <i style="padding-left: 1px" class="fa fa-user booking"></i>
                                    <!-- style="margin-left: 1px" -->
                                    <div class="col-md-6">
                                        <input id="namaAnda" type="text" class="form-control" name="namaAnda" value="{{ old('namaAnda') }}" required>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="emailITS" class="col-md-4 col-form-label text-md-left">{{ __('Email ITS') }}</label>
                                    <i class="fa fa-envelope-o booking"></i>
                                    <div class="col-md-6">
                                        <input id="emailITS" type="email" class="form-control" name="emailITS" value="{{ old('emailITS') }}" required autocomplete="email">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="userIntegra" class="col-md-4 col-form-label text-md-left">{{ __('User Integra') }}</label>
                                    <i class="fa fa-address-card booking"></i>
                                    <div class="col-md-6">
                                        <input id="userIntegra" type="text" class="form-control" name="userIntegra" value="{{ old('userIntegra') }}" required>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="waktuMulai" class="col-md-4 col-form-label text-md-left">{{ __('Waktu Mulai Webinar') }}</label>
                                    <i class="fa fa-calendar-o booking"></i>
                                    <div class="col-md-6">
                                        <input id="waktuMulai" type="datetime-local" class="form-control" name="waktuMulai" value="{{ old('waktuMulai') }}" required>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="durasi" class="col-md-4 col-form-label text-md-left">{{ __('Durasi Webinar') }}</label>
                                    <i class="fa fa-clock-o booking"></i>
                                    <div class="col-md-6">
                                        <input id="durasi" type="text" class="form-control" value="" required>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="waktuSelesai" class="col-md-4 col-form-label text-md-left">{{ __('Waktu Selesai Webinar') }}</label>
                                    <i class="fa fa-calendar booking"></i>
                                    <div class="col-md-6">
                                        <input id="waktuSelesai" type="datetime-local" class="form-control" name="waktuSelesai" value="{{ old('waktuSelesai') }}" required>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="civitasAkademik" class="col-md-4 col-form-label text-md-left">{{ __('Civitas Akademik') }}</label>
                                    <i class="fa fa-users booking"></i>
                                    <div class="col-md-6">
                                        <select name="civitasAkademik" id="civitasAkademik" class="form-control">
                                            @foreach ($civitas as $item)
                                            <option value="{{$item['nama']}}">{{$item['nama']}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="relayITSTV" class="col-md-4 col-form-label text-md-left">{{ __('Relay ke ITS TV') }}</label>
                                    <div class="col-md-6">
                                        <input id="relayITSTV" type="checkbox" class="" name="relayITSTV" value="relayITSTVBoolean">
                                        <!-- <label for="iya">Iya</label>
                                        <label for="tidak">Tidak</label> -->
                                    </div>
                                    <div class="col-md-6">
                                        <sub class="">Webinar akan direlay sesuai dengan persetujuan ......</sub>
                                    </div>	
                                </div>

                                <div class="form-group row">
                                    <label for="pesertaBanyak" class="col-md-4 col-form-label text-md-left">{{ __('Peserta sebanyak 500 atau lebih') }}</label>
                                    <div class="col-md-6">
                                        <input id="pesertaBanyak" type="checkbox" class="" name="pesertaBanyak" value="pesertaBanyakBoolean">
                                    </div>
                                    {{-- <sub>Jawaban iya mengurangi kemungkinan di approve karena kurangnya sumber daya</sub> --}}
                                </div>


                                <div class="form-group row mb-0">
                                    <div class="col-md-8 offset-md-4">
                                        <button type="submit" class="btn btn-submit">
                                            {{ __('Submit Booking') }}
                                        </button>

                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        <!-- </div> -->
        <script defer>
            countDuration();
            function countDuration() {
                document.getElementById('durasi').value = 0;
            }

            /* Date time DOM Value
            document.getElementById('waktuMulai').value = '1990-12-31T23:59:59';
            1990-12-31T23:59:60Z
            1996-12-19T16:39:57-08:00 */
        </script>
    
        <!-- /page content -->

      </div>
    </div>

    <!-- jQuery -->
    <script src="{{ asset('assets/vendors/jquery/dist/jquery.min.js') }}"></script>
    <!-- Bootstrap -->
    <script src="{{ asset('assets/vendors/bootstrap/dist/js/bootstrap.bundle.min.js') }}"></script>

@endsection

