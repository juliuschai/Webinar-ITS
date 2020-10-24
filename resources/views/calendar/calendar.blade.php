@extends('layouts.header')

@section('content')

<div class="right_col booking" role="main">
    <div class="col-md-12 col-sm-12">
        <h2 class="table-title">Jadwal Webinar</h2>
        <h5>Penjelasan warna webinar/meeting</h5>
        <div><span style="color:red;">merah</span> untuk webinar</div>
        <div><span style="color:blue;">biru</span> untuk meeting</div>
        <br>
        <div class="col-xl-12">
            <!-- Calendar Container -->
            <input id="calendarData" data-eventroute="{{route('calendar.event')}}" type="hidden">
            <div id="calendar"></div>
            {{-- <div id="calendar" class="js-calendar"></div> --}}
        </div>
    </div>
</div>

<link href="{{ asset('assets/js/plugins/fullcalendar/fullcalendar.min.css') }}" rel="stylesheet" >

<!-- Page JS Plugins -->
<script src="{{ asset('assets/js/plugins/moment/moment.min.js') }}" defer></script>
<!-- Page JS Code -->
<script src="{{ asset('assets/js/plugins/fullcalendar/fullcalendar.js') }}" defer></script>

<script src="{{asset('js/fullcalendarSettings.js')}}?2" defer></script>


@endsection
