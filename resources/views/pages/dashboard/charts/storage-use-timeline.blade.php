<div class="col-md-8">
    <div class="card card-chart">
        <div class="card-header">
            <h5 class="card-title">Disk Space Utilization</h5>
            <p class="card-category">NFS Storage usage over time</p>
        </div>
        <div class="card-body">
            <canvas id="nfsUsageTimeline" width="400" height="100"></canvas>
        </div>
        <div class="card-footer">
            <div class="chart-legend">
                <i class="fa fa-circle text-info"></i> Space Used
            </div>
            <hr />
            <div class="stats">
                <i class="fa fa-calendar"></i> Updated every hour
            </div>
        </div>
    </div>
</div>

@push('scripts')
    <script>
        $(document).ready(function() {
            var nfsUsageTimeline = document.getElementById("nfsUsageTimeline");

            var dataFirst = {
                data: [ {!!  $diskUsageForTimelineValues  !!}
                ],
                fill: false,
                borderColor: '#51CACF',
                backgroundColor: 'transparent',
                pointBorderColor: '#51CACF',
                pointRadius: 4,
                pointHoverRadius: 4,
                pointBorderWidth: 8,
            };

            var speedData = {
                labels: [ {!!  $diskUsageForTimelineLabels  !!}
                ],
                datasets: [dataFirst]
            };

            var chartOptions = {
                legend: {
                    display: false,
                    position: 'top'
                },
                scales: {
                    yAxes: [{
                        display: true,
                        ticks: {
                            beginAtZero: true,
                            steps: 10,
                            stepValue: 5,
                            max: 100
                        }
                    }]
                },
            };

            new Chart(nfsUsageTimeline, {
                type: 'line',
                hover: false,
                data: speedData,
                options: chartOptions
            });
        });
    </script>
@endpush
