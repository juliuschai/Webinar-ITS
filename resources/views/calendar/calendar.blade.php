@extends('layouts.user')

@section('content')

<div class="right_col booking" role="main">
    <div class="col-md-12 col-sm-12">
    <h2 class="table-title">Jadwal Webinar</h2>
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
{{-- <script src="{{ asset('assets/js/pages/be_comp_calendar.min.js') }}" defer></script> --}}
<script src="{{ asset('assets/js/plugins/fullcalendar/fullcalendar.js') }}" defer></script>

<script src="{{asset('js/fullcalendarSettings.js')}}" defer></script>


@endsection