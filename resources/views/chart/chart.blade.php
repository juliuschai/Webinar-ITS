
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
@endsection

@section('scripts')

<script type="text/javascript">

var users =  <?php echo json_encode($bookings) ?>;
var nama_booking =  <?php echo json_encode($nama_booking) ?>;

var departements =  <?php echo json_encode($departements) ?>;
var nama_departemen =  <?php echo json_encode($nama_departemen) ?>;

var faculties =  <?php echo json_encode($faculties) ?>;
var nama_fakultas =  <?php echo json_encode($nama_fakultas) ?>;

var units =  <?php echo json_encode($units) ?>;
var nama_unit =  <?php echo json_encode($nama_unit) ?>;

var dosen =  <?php echo json_encode($dosen) ?>;
var tendik =  <?php echo json_encode($tendik) ?>;
var mahasiswa =  <?php echo json_encode($mahasiswa) ?>;

var test =  <?php echo json_encode($test) ?>;
var nama_test =  <?php echo json_encode($nama_test) ?>;

   
Highcharts.chart('container', {
credits: {
enabled: false
  },
  title: {
      text: 'User Webinar'
  },
    xAxis: {
      categories: ['Juli', 'Augustus', 'September', 'Oktober', 'November', 'Desember', 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni']
  },
  yAxis: {
      title: {
          text: ''
      }
  },
  legend: {
      layout: 'vertical',
      align: 'right',
      verticalAlign: 'middle'
  },
  plotOptions: {
      series: {
          allowPointSelect: true
      }
  },
  series: [{
      name: '',
      data: users
  }],
  responsive: {
      rules: [{
          condition: {
              maxWidth: 500
          },
          chartOptions: {
              legend: {
                  layout: 'horizontal',
                  align: 'center',
                  verticalAlign: 'bottom'
              }
          }
      }]
  }
});

Highcharts.chart('container-2', {
    credits: {
      enabled: false
    },
    chart: {
        type: 'column'
    },
    title: {
        text: 'Sivitas Akademik'
    },
    xAxis: {
        categories: ['Total'] 
    },
    yAxis: {
        min: 0,
        title: {
            text: ''
        }
    },
    tooltip: {
        pointFormat: '<span style="color:{series.color}">{series.name}</span>: <b>{point.y}</b> ({point.percentage:.0f}%)<br/>',
        shared: true
    },
    plotOptions: {
        column: {
            stacking: 'percent'
        }
    },
    series: [{
        name: 'Dosen',
        data: dosen
    }, {
        name: 'Tendik',
        data: tendik
    }, {
        name: 'Mahasiswa',
        data: mahasiswa
    }]
});

Highcharts.chart('container-3', {
  credits: {
      enabled: false
    },
  chart: {
    type: 'column'
  },
  title: {
    text: 'Departemen'
  },
  accessibility: {
    announceNewData: {
      enabled: true
    }
  },
  xAxis: {
    categories: nama_departemen
  },
  yAxis: {
    title: {
      text: ''
    }

  },
  legend: {
    enabled: false
  },
  plotOptions: {
    series: {
      borderWidth: 0,
      dataLabels: {
        enabled: true,
        format: '{point.y:.f}%'
      }
    }
  },

  tooltip: {
    headerFormat: '<span style="font-size:11px"></span><br>',
    pointFormat: '<b>{point.y:.f}%</b> dari Total Departemen<br/>'
  },

  series: [
    {
      name: nama_departemen,
      colorByPoint: true,
      data: departements
    }
  ]
});

Highcharts.chart('container-4', {
  credits: {
      enabled: false
  },
  chart: {
    type: 'column'
  },
  title: {
    text: 'Fakultas'
  },
  accessibility: {
    announceNewData: {
      enabled: true
    }
  },
  xAxis: {
    categories: nama_fakultas
  },
  yAxis: {
    title: {
      text: ''
    }

  },
  legend: {
    enabled: false
  },
  plotOptions: {
    series: {
      borderWidth: 0,
      dataLabels: {
        enabled: true,
        format: '{point.y:.f}%'
      }
    }
  },

  tooltip: {
    headerFormat: '<span style="font-size:11px"></span><br>',
    pointFormat: '<b>{point.y:.f}%</b> dari Total Fakultas<br/>'
  },

  series: [
    {
      name: nama_fakultas,
      colorByPoint: true,
      data: faculties
    }
  ]
});

Highcharts.chart('container-5', {
  credits: {
      enabled: false
    },
  chart: {
    type: 'column'
  },
  title: {
    text: 'Unit'
  },
  accessibility: {
    announceNewData: {
      enabled: true
    }
  },
  xAxis: {
    categories: nama_unit
  },
  yAxis: {
    title: {
      text: ''
    }

  },
  legend: {
    enabled: false
  },
  plotOptions: {
    series: {
      colorByPoint: true,
      borderWidth: 0,
      dataLabels: {
        enabled: true,
        format: '{point.y:.f}%'
      }
    }
  },

  tooltip: {
    headerFormat: '<span style="font-size:11px"></span><br>',
    pointFormat: '<b>{point.y:.f}%</b> dari Total Unit<br/>'
  },

  series: [
    {
      name: nama_unit,
      colorByPoint: true,
      data: units
    }
  ]
});

Highcharts.chart('container-6', {
    credits: {
      enabled: false
    },
    chart: {
        type: 'column'
    },
    title: {
        text: 'Waktu Webinar'
    },
    xAxis: {
        categories: nama_test,
    },
    yAxis: {
        min: 0,
        title: {
            text: ''
        }
    },
    legend: {
        enabled: false
    },
    tooltip: {
        pointFormat: '<b>{point.y:.f} menit</b>'
    },
    series: [{
        name: 'Population',
        colorByPoint: true,
        data: test,
        dataLabels: {
            enabled: true,
            rotation: -90,
            color: 'black',
            align: 'right',
            format: '{point.y:.f}', // one decimal
            y: 10, // 10 pixels down from the top
            style: {
                // fontSize: '13px'
                fontFamily: 'Verdana, sans-serif'
            }
        }
    }]
});

</script>
@endsection
