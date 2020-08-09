
@extends('layouts.header')

@section('content')
<div class="right_col booking" role="main">
  <div class="col-md-12 col-sm-12">
    <div class="row">
      <div id="container" class="card" style="margin: 3px; padding: 3px; -webkit-box-flex: 0; -ms-flex: 0 0 33.333333%; flex: 0 0 49%; max-width: 49%;"></div>
      <div id="container-2" class="card" style="margin: 3px; padding: 3px; -webkit-box-flex: 0; -ms-flex: 0 0 33.333333%; flex: 0 0 49%; max-width: 49%;"></div>
    </div>
    <div class="row">
      <div id="container-3" class="card" style="margin: 3px; padding: 3px; -webkit-box-flex: 0; -ms-flex: 0 0 33.333333%; flex: 0 0 49%; max-width: 49%;"></div>
      <div id="container-4" class="card" style="margin: 3px; padding: 3px; -webkit-box-flex: 0; -ms-flex: 0 0 33.333333%; flex: 0 0 49%; max-width: 49%;"></div>
    </div>
    <div class="row">
      <div id="container-5" class="card" style="margin: 3px; padding: 3px; -webkit-box-flex: 0; -ms-flex: 0 0 33.333333%; flex: 0 0 49%; max-width: 49%;"></div>
      <div id="container-6" class="card" style="margin: 3px; padding: 3px; -webkit-box-flex: 0; -ms-flex: 0 0 33.333333%; flex: 0 0 49%; max-width: 49%;"></div>
    </div>
  </div>
</div>

<input type="hidden" id="serverData" 
  data-bookings="{{json_encode($bookings)}}"
  data-nama_booking="{{json_encode($nama_booking)}}"

  data-departements="{{json_encode($departements)}}"
  data-nama_departemen="{{json_encode($nama_departemen)}}"
  
  data-faculties="{{json_encode($faculties)}}"
  data-nama_fakultas="{{json_encode($nama_fakultas)}}"

  data-units="{{json_encode($units)}}"
  data-nama_unit="{{json_encode($nama_unit)}}"
  
  data-dosen="{{json_encode($dosen)}}"
  data-tendik="{{json_encode($tendik)}}"
  data-mahasiswa="{{json_encode($mahasiswa)}}"
  
>

@endsection

@section('scripts')
<script src="{{ asset('js/highchart/highcharts-bisa.js')}}" defer></script>
<script src="{{ asset('js/highchart/chart.js')}}" defer></script>
@endsection
