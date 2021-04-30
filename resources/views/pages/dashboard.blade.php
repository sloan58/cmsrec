@extends('layouts.app', [
    'class' => '',
    'elementActive' => 'dashboard'
])

@section('content')
    <div class="content">
        <div class="row justify-content-left">
            <h5 class="font-weight-bolder text-info ml-3"><u>Actual Data</u></h5>
        </div>
        <div class="row">
            <div class="col-lg-3 col-md-6 col-sm-6">
                <div class="card card-stats">
                    <div class="card-body ">
                        <div class="row">
                            <div class="col-5 col-md-4">
                                <div class="icon-big text-center icon-warning">
                                    <i class="fa fa-hdd-o text-warning"></i>
                                </div>
                            </div>
                            <div class="col-7 col-md-8">
                                <div class="numbers">
                                    <p class="card-category">NFS Space Used</p>
                                    <p class="card-title">{{ $diskUsage }}
                                        <p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer ">
                        <hr>
                        <div class="stats">
                            <i class="nc-icon nc-globe"></i> out of {{ $diskSize }}
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-6">
                <div class="card card-stats">
                    <div class="card-body ">
                        <div class="row">
                            <div class="col-5 col-md-4">
                                <div class="icon-big text-center icon-warning">
                                    <i class="fa fa-video-camera text-success"></i>
                                </div>
                            </div>
                            <div class="col-7 col-md-8">
                                <div class="numbers">
                                    <p class="card-category">Recordings</p>
                                    <p class="card-title">{{ $recordingCount }}
                                        <p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer ">
                        <hr>
                        <div class="stats">
                            <i class="fa fa-calendar-o"></i> Last recording received {{ $lastRecordingIn }}
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-6">
                <div class="card card-stats">
                    <div class="card-body ">
                        <div class="row">
                            <div class="col-5 col-md-4">
                                <div class="icon-big text-center icon-warning">
                                    <i class="nc-icon nc-vector text-danger"></i>
                                </div>
                            </div>
                            <div class="col-7 col-md-8">
                                <div class="numbers">
                                    <p class="card-category">Average Recording Size</p>
                                    <p class="card-title">{{ $averageRecordingSize }}
                                        <p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer ">
                        <hr>
                        <div class="stats">
                            <i class="fa fa-clock-o"></i> Largest: {{ $largestRecordingSize }} | Smallest: {{ $smallestRecordingSize }}
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-6">
                <div class="card card-stats">
                    <div class="card-body ">
                        <div class="row">
                            <div class="col-5 col-md-4">
                                <div class="icon-big text-center icon-warning">
                                    <i class="nc-icon nc-favourite-28 text-primary"></i>
                                </div>
                            </div>
                            <div class="col-7 col-md-8">
                                <div class="numbers">
                                    <p class="card-category">Shared Recordings</p>
                                    <p class="card-title">{{ $sharedRecordings }}
                                        <p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer ">
                        <hr>
                        <div class="stats">
                            <i class="fa fa-television"></i> {{ $views }} total viewers
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row justify-content-left">
            <h5 class="font-weight-bolder text-info ml-3"><u>Demo Charts</u></h5>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="card ">
                    <div class="card-header ">
                        <h5 class="card-title">CMS Space Participants</h5>
                        <p class="card-category">24 Hours performance</p>
                    </div>
                    <div class="card-body ">
                        <canvas id=chartHours width="400" height="100"></canvas>
                    </div>
                    <div class="card-footer ">
                        <hr>
                        <div class="stats">
                            <i class="fa fa-history"></i> Updated 3 minutes ago
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-4">
                <div class="card ">
                    <div class="card-header ">
                        <h5 class="card-title">Shared Recordings</h5>
                        <p class="card-category">Viewership Statistics</p>
                    </div>
                    <div class="card-body ">
                        <canvas id="chartEmail"></canvas>
                    </div>
                    <div class="card-footer ">
                        <div class="legend">
                            <i class="fa fa-circle text-primary"></i> Watched
                            <i class="fa fa-circle text-warning"></i> Favorited
                            <i class="fa fa-circle text-danger"></i> Commented
                            <i class="fa fa-circle text-gray"></i> Unwatched
                        </div>
                        <hr>
                        <div class="stats">
                            <i class="fa fa-calendar"></i> Updated every 3hrs
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-8">
                <div class="card card-chart">
                    <div class="card-header">
                        <h5 class="card-title">Disk Space Utilization</h5>
                        <p class="card-category">NFS Storage usage over time</p>
                    </div>
                    <div class="card-body">
                        <canvas id="speedChart" width="400" height="100"></canvas>
                    </div>
                    <div class="card-footer">
                        <div class="chart-legend">
                            <i class="fa fa-circle text-info"></i> Total Space Available
                            <i class="fa fa-circle text-warning"></i> Space Used
                        </div>
                        <hr />
                        <div class="card-stats">
                            <i class="fa fa-check"></i> Data information certified
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            function initDocChart() {
                chartColor = "#FFFFFF";

                ctx = document.getElementById('chartHours').getContext("2d");

                myChart = new Chart(ctx, {
                    type: 'line',

                    data: {
                        labels: ["00:00", "01:00", "02:00", "03:00", "04:00", "05:00", "06:00", "07:00", "08:00", "09:00", "10:00", "11:00", "12:00", "13:00", "14:00", "15:00", "16:00", "17:00", "18:00", "19:00", "20:00", "21:00", "22:00", "23:00"],
                        datasets: [{
                            borderColor: "#6bd098",
                            backgroundColor: "#6bd098",
                            pointRadius: 0,
                            pointHoverRadius: 0,
                            borderWidth: 3,
                            data: [300, 310, 316, 322, 330, 326, 333, 345, 338, 354, 300, 310, 316, 322, 330, 326, 333, 345, 338, 354, 300, 310, 316, 322]
                        },
                            {
                                borderColor: "#f17e5d",
                                backgroundColor: "#f17e5d",
                                pointRadius: 0,
                                pointHoverRadius: 0,
                                borderWidth: 3,
                                data: [320, 340, 365, 360, 370, 385, 390, 384, 408, 420, 320, 340, 365, 360, 370, 385, 390, 384, 408, 420, 320, 340, 365, 360]
                            },
                            {
                                borderColor: "#fcc468",
                                backgroundColor: "#fcc468",
                                pointRadius: 0,
                                pointHoverRadius: 0,
                                borderWidth: 3,
                                data: [370, 394, 415, 409, 425, 445, 460, 450, 478, 484, 370, 394, 415, 409, 425, 445, 460, 450, 478, 484, 370, 394, 415, 409]
                            }
                        ]
                    },
                    options: {
                        legend: {
                            display: false
                        },

                        tooltips: {
                            enabled: false
                        },

                        scales: {
                            yAxes: [{

                                ticks: {
                                    fontColor: "#9f9f9f",
                                    beginAtZero: false,
                                    maxTicksLimit: 5,
                                    //padding: 20
                                },
                                gridLines: {
                                    drawBorder: false,
                                    zeroLineColor: "#ccc",
                                    color: 'rgba(255,255,255,0.05)'
                                }

                            }],

                            xAxes: [{
                                barPercentage: 1.6,
                                gridLines: {
                                    drawBorder: false,
                                    color: 'rgba(255,255,255,0.1)',
                                    zeroLineColor: "transparent",
                                    display: false,
                                },
                                ticks: {
                                    padding: 20,
                                    fontColor: "#9f9f9f"
                                }
                            }]
                        },
                    }
                });

            }
            // Javascript method's body can be found in assets/assets-for-demo/js/demo.js
            initDocChart();
            demo.initChartsPages();
        });
    </script>
@endpush
