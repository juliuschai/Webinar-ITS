let $data = $('#serverData')

var users = $data.data('bookings');
var nama_booking = $data.data('nama_booking');

var departements = $data.data('departements');
var nama_departemen = $data.data('nama_departemen');

var faculties = $data.data('faculties');
var nama_fakultas = $data.data('nama_fakultas');

var units = $data.data('units');
var nama_unit = $data.data('nama_unit');

var dosen = $data.data('dosen');
var tendik = $data.data('tendik');
var mahasiswa = $data.data('mahasiswa');

var test = $data.data('test');
var nama_test = $data.data('nama_test');

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
      plotBackgroundColor: null,
      plotBorderWidth: null,
      plotShadow: false,
      type: 'pie'
  },
  title: {
      text: 'Sivitas Akademika'
  },
  tooltip: {
      pointFormat: '<b>{point.percentage:.0f}%</b>'
  },
  accessibility: {
      point: {
          valueSuffix: '%'
      }
  },
  plotOptions: {
      pie: {
          allowPointSelect: true,
          cursor: 'pointer',
          dataLabels: {
              enabled: false
          },
          showInLegend: true
      }
  },
  series: [{
      colorByPoint: true,
      data: [{
          name: 'Dosen',
          data: dosen,
          y: 61.41,
          sliced: true,
          selected: true
      }, {
          name: 'Tendik',
          data: tendik,
          y: 11.84
      }, {
          name: 'Mahasiswa',
          data: mahasiswa,
          y: 10.85
      }]
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
        format: '{point.y:.f}'
      }
    }
  },

  tooltip: {
    headerFormat: '<span style="font-size:11px"></span><br>',
    pointFormat: '<b>{point.y:.f}</b> dari Total Departemen<br/>'
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
      // connectNulls: true,
      dataLabels: {
        enabled: true,
        format: '{point.y:.f}'
      }
    }
  },

  tooltip: {
    headerFormat: '<span style="font-size:11px"></span><br>',
    pointFormat: '<b>{point.y:.f}</b> dari Total Fakultas<br/>'
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
        format: '{point.y:.f}'
      }
    }
  },

  tooltip: {
    headerFormat: '<span style="font-size:11px"></span><br>',
    pointFormat: '<b>{point.y:.f}</b> dari Total Unit<br/>'
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
