<div class="card">
    <div class="card-header ">
        <h5 class="card-title">Top CoSpaces by Disk Usage</h5>
        <p class="card-category">NFS Statistics</p>
    </div>
    <div class="card-body ">
        <canvas id="coSpaceUsage"></canvas>
    </div>
    <div class="card-footer ">
        <div class="legend">
            @foreach($topCoSpaceStorageUsages as $key => $topCoSpaceStorageUsage)
                <i class="fa fa-circle text-{{ $topCoSpaceStorageUsage['style'] }}"></i>
                {{ $topCoSpaceStorageUsage['name'] }} ({{ $topCoSpaceStorageUsage['size'] }})
                <br>
            @endforeach
        </div>
        <hr>
        <div class="stats">
            <i class="fa fa-calendar"></i> Updated every hour
        </div>
    </div>
</div>

@push('scripts')
    <script>
        $(document).ready(function() {
            chartColor = "#FFFFFF";

            coSpaceUsage = document.getElementById('coSpaceUsage').getContext("2d");

            new Chart(coSpaceUsage, {
                type: 'pie',
                data: {
                    labels: [1, 2, 3],
                    datasets: [{
                        label: "Co Space Usage",
                        pointRadius: 0,
                        pointHoverRadius: 0,
                        backgroundColor: [
                            {!! implode(',', array_values(array_column($topCoSpaceStorageUsages, 'chartBackgroundColor'))); !!}
                        ],
                        borderWidth: 0,
                        data: [
                            {!! implode(',', array_values(array_column($topCoSpaceStorageUsages, 'rawSize'))); !!}
                        ]
                    }]
                },

                options: {

                    legend: {
                        display: false
                    },

                    pieceLabel: {
                        render: 'percentage',
                        fontColor: ['white'],
                        precision: 2
                    },

                    tooltips: {
                        enabled: false
                    },

                    scales: {
                        yAxes: [{

                            ticks: {
                                display: false
                            },
                            gridLines: {
                                drawBorder: false,
                                zeroLineColor: "transparent",
                                color: 'rgba(255,255,255,0.05)'
                            }

                        }],

                        xAxes: [{
                            barPercentage: 1.6,
                            gridLines: {
                                drawBorder: false,
                                color: 'rgba(255,255,255,0.1)',
                                zeroLineColor: "transparent"
                            },
                            ticks: {
                                display: false,
                            }
                        }]
                    },
                }
            });
        });
    </script>
@endpush
