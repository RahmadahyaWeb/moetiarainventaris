<div>
    <div class="row mb-3">
        <div class="col-md-3">
            <form wire:submit="search">
                <label class="form-label">Item Code</label>
                <input type="text" class="form-control" wire:model.live.debounce.150ms="code" id="search"
                    placeholder="Search">
            </form>
        </div>
        <div class="col-md-3">
            <form wire:submit="search">
                <label class="form-label">Item Name</label>
                <input type="text" class="form-control" wire:model.live.debounce.150ms="name" id="search"
                    placeholder="Search">
            </form>
        </div>
        <div class="col-md-3">
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
        <div class="col-md-3">
            <form wire:submit="search">
                <label class="form-label">Status</label>
                <select class="form-select" wire:model.change="status">
                    <option value="">All</option>
                    <option value="0">Pending</option>
                    <option value="1">Approved</option>
                    <option value="-1">Rejected</option>
                </select>
            </form>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table table-bordered table-hover table-sm m-0">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Item Code</th>
                    <th>Item Name</th>
                    <th>Quantity</th>
                    <th>Category</th>
                    <th>Input By</th>
                    <th>Division</th>
                    <th>Status</th>
                    <th>Description</th>
                    <th>Request Date</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $no = $items->firstItem();
                @endphp
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
                            <td>{{ $item->qty }}</td>
                            <td class="text-uppercase">{{ $item->type }}</td>
                            <td>{{ $item->user->name }}</td>
                            <td class="text-capitalize">{{ $item->division->role->name }}</td>
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
                            <td>{{ date('Y-m-d', strtotime($item->created_at)) }}</td>

                            <td>
                                @if ($item->status == 0)
                                    <div class="d-flex gap-1">
                                        <div class="d-grid">
                                            <a href="{{ route('approvals.edit', ['kode_barang' => $item->kode_barang, 'code_id' => $item->code_id, 'id' => $item->id, 'division' => $item->division->role->name, 'input_by' => $item->user->name]) }}"
                                                class="btn btn-sm btn-warning">
                                                <i class='bx bxs-edit'></i>
                                                <div class="ms-1">
                                                    Edit
                                                </div>
                                            </a>
                                        </div>
                                    </div>
                                @endif
                            </td>
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
