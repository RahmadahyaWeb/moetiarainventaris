<div>
    <div class="row mb-3">
        <div class="col-md-3 mb-3">
            <form wire:submit="search">
                <label class="form-label">From Date</label>
                <input type="date" class="form-control" wire:model.change="from_date" value="{{ $from_date }}">
            </form>
        </div>
        <div class="col-md-3 mb-3">
            <form wire:submit="search">
                <label class="form-label">To Date</label>
                <input type="date" class="form-control" wire:model.change="to_date" value="{{ $to_date }}">
            </form>
        </div>
        <div class="col-md-3 mb-3">
            <form wire:submit="search">
                <label class="form-label">Item Code</label>
                <input type="text" class="form-control" wire:model.live.debounce.150ms="code" id="search"
                    placeholder="Search">
            </form>
        </div>
        <div class="col-md-3 mb-3">
            <form wire:submit="search">
                <label class="form-label">Division</label>
                <select class="form-select" wire:model.change="division">
                    <option value="">All</option>
                    <option value="1">Bakery</option>
                    <option value="2">Barista</option>
                    <option value="3">Kitchen</option>
                    <option value="4">Operational</option>
                    <option value="5">Waiter</option>
                    <option value="6">Cashier</option>
                </select>
            </form>
        </div>

        <div class="col-md-3 d-grid">
            <button id="downloadChart" class="btn btn-primary mb-3">Download</button>
        </div>

    </div>

    <canvas id="myChart" width="400" height="200"></canvas>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('livewire:init', () => {
            var ctx = document.getElementById('myChart').getContext('2d');
            var myChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: @json($labels), // Nama barang dan tanggal sebagai label
                    datasets: [{
                            label: 'Total In',
                            data: [], // Data qty in
                            backgroundColor: 'rgba(0, 255, 0, 0.8)',
                            fill: true
                        },
                        {
                            label: 'Total Out',
                            data: [], // Data qty out
                            backgroundColor: 'rgba(255, 0, 0, 0.8)',
                            fill: true
                        }
                    ]
                },
                options: {
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    },
                    plugins: {
                        legend: {
                            display: true,
                            position: 'top'
                        }
                    }
                }

            });

            Livewire.on('chart-data-updated', function(event) {
                // Update data chart
                myChart.data.labels = event[0].labels;
                myChart.data.datasets[0].data = event[0].totalInValues;
                myChart.data.datasets[1].data = event[0].totalOutValues;

                myChart.data.datasets[0].hidden = !event[0].showIn;
                myChart.data.datasets[1].hidden = !event[0].showOut;

                // Render chart dengan data baru
                myChart.update();
            });

            document.getElementById('downloadChart').addEventListener('click', () => {
                const config = myChart.config._config;
                const result = {
                    type: config.type,
                    data: config.data
                };

                const link = 'https://quickchart.io/chart?c=' + encodeURIComponent(JSON.stringify(result));
                const anchor = document.createElement('a');
                anchor.href = link;
                anchor.textContent = 'Lihat Chart';
                anchor.target = '_blank';
                anchor.click();

            });
        });
    </script>
</div>
