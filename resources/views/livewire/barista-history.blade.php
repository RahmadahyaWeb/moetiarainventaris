<div>
    <div>
        <div class="row mb-3">
            <div class="col-6 mb-3">
                <form wire:submit="search">
                    <label class="form-label">From Date</label>
                    <input id="fromDate" type="date" class="form-control" wire:model.change="fromDate">
                </form>
            </div>
            <div class="col-6 mb-3">
                <form wire:submit="search">
                    <label class="form-label">To Date</label>
                    <input id="toDate" type="date" class="form-control" wire:model.change="toDate">
                </form>
            </div>
        </div>
        <div class="table-responsive">
            <table class="table table-bordered table-hover  m-0">
                <thead>
                    <tr>
                        <th>No</th>
                        <th style="width: 20%">Code</th>
                        <th style="width: 30%">Item Name</th>
                        <th>Quantity</th>
                        <th>Unit Price</th>
                        <th>Total Price</th>
                        <th style="width: 20%">Category</th>
                        <th>Input By</th>
                        <th>Status</th>
                        <th>Description</th>
                        <th style="width: 20%">Date</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $no = $items->firstItem();
                    @endphp
                    <tr>
                        <td></td>
                        <td>
                            <form wire:submit="search">
                                <input type="text" class="form-control" wire:model.live.debounce.150ms="code"
                                    id="search" placeholder="Search">
                            </form>
                        </td>
                        <td>
                            <form wire:submit="search">
                                <input type="text" class="form-control" wire:model.live.debounce.150ms="name"
                                    id="search" placeholder="Search">
                            </form>
                        </td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td>
                            <form wire:submit="search">
                                <select class="form-select" wire:model.change="type">
                                    <option value="">All</option>
                                    <option value="in">IN</option>
                                    <option value="out">OUT</option>
                                </select>
                            </form>
                        </td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    @if ($items->isEmpty())
                        <tr>
                            <td colspan="11" class="text-center fw-bold">Data not found</td>
                        </tr>
                    @else
                        @foreach ($items as $item)
                            <tr>
                                <td>{{ $no++ }}</td>
                                <td>{{ $item->kode_barang }}</td>
                                <td>{{ $item->nama_barang }}</td>
                                <td>{{ $item->qty }} {{ $item->unit }}</td>
                                <td>{{ number_format($item->price, 0, ',', '.') }}</td>
                                <td>
                                    @if ($item->type == 'in')
                                        {{ number_format($item->price * $item->qty, 0, ',', '.') }}
                                    @else
                                        0
                                    @endif
                                </td>
                                <td class="text-uppercase">{{ $item->type }}</td>
                                <td>{{ $item->user_name }}</td>
                                @if ($item->status == 0)
                                    <td>
                                        <span class="badge text-bg-warning">Pending</span>
                                    </td>
                                @elseif ($item->status == 1)
                                    <td>
                                        <span class="badge text-bg-success">Approved</span>
                                    </td>
                                @else
                                    <td>
                                        <span class="badge text-bg-danger">Rejected</span>
                                    </td>
                                @endif
                                <td>{{ $item->description }}</td>
                                <td>{{ $item->tanggal }}</td>
                            </tr>
                        @endforeach
                    @endif
                </tbody>
            </table>
        </div>

        <div>
            @if ($items->hasPages())
                <div class="card-footer">
                    {{ $items->links() }}
                </div>
            @endif
        </div>
    </div>

</div>

<script>
    document.getElementById('fromDate').addEventListener('change', updateExcelLink);
    document.getElementById('toDate').addEventListener('change', updateExcelLink);
    document.getElementById('excelLink').addEventListener('click', validateAndExport);

    function updateExcelLink() {
        const fromDate = document.getElementById('fromDate').value;
        const toDate = document.getElementById('toDate').value;
        const excelLink = document.getElementById('excelLink');

        let url =
            `{{ route('history.export', ['fromDate' => ':fromDate', 'toDate' => ':toDate', 'division' => 'baristas']) }}`;
        url = url.replace(':fromDate', fromDate).replace(':toDate', toDate).replace(':division', 'baristas');
        console.log(url);
        excelLink.setAttribute('href', url);
    }

    function validateAndExport(event) {
        const fromDate = document.getElementById('fromDate').value;
        const toDate = document.getElementById('toDate').value;

        if (!fromDate || !toDate) {
            event.preventDefault();
            alert('Please fill in both From Date and End Date before exporting.');
        }
    }
</script>
