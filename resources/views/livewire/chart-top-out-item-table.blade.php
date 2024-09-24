<div>
    <div class="row mb-3">
        <div class="col-md-4 mb-3">
            <form wire:submit="search">
                <label class="form-label">From Date</label>
                <input type="date" class="form-control" wire:model.change="from_date" value="{{ $from_date }}">
            </form>
        </div>
        <div class="col-md-4 mb-3">
            <form wire:submit="search">
                <label class="form-label">To Date</label>
                <input type="date" class="form-control" wire:model.change="to_date" value="{{ $to_date }}">
            </form>
        </div>
        <div class="col-md-4 mb-3">
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
            <button id="downloadTopOutItem" class="btn btn-primary mb-3">Download</button>
        </div>

    </div>

    <canvas id="topOutItem"></canvas>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('livewire:init', () => {
            var ctx = document.getElementById('topOutItem').getContext('2d');

            var topOutItem = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: @json($labels), // Nama barang dan tanggal sebagai label
                    datasets: [{
                        label: 'Total Out',
                        data: [], // Data qty in
                        backgroundColor: 'rgba(255, 0, 0, 0.8)',
                        fill: true
                    }, ]
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

            Livewire.on('chart-top-out-updated', function(event) {
                // Update data chart
                topOutItem.data.labels = event[0].labels;
                topOutItem.data.datasets[0].data = event[0].totalOutValues;

                // Render chart dengan data baru
                topOutItem.update();
            });

            document.getElementById('downloadTopOutItem').addEventListener('click', function() {
                const config = topOutItem.config._config;
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
