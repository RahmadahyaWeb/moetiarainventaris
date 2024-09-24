<div>
    <div class="table-responsive">
        <table class="table table-bordered table-hover table-sm m-0">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Item Code</th>
                    <th>Item Name</th>
                    <th>Priority</th>
                    <th>Action</th>
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
                            <input type="text" class="form-control" wire:model.live.debounce.150ms="code" id="search"
                                placeholder="Search">
                        </form>
                    </td>
                    <td>
                        <form wire:submit="search">
                            <input type="text" class="form-control" wire:model.live.debounce.150ms="name" id="search"
                                placeholder="Search">
                        </form>
                    </td>
                    <td></td>
                    <td></td>
                </tr>
                @if ($items->isEmpty())
                    <tr>
                        <td colspan="5" class="text-center fw-bold">Data not found</td>
                    </tr>
                @else
                    @foreach ($items as $item)
                        <tr>
                            <td>{{ $no++ }}</td>
                            <td>{{ $item->kode_barang }}</td>
                            <td>{{ $item->nama_barang }}</td>
                            <td>{{ $item->priority }}</td>
                            <td>
                                <div class="d-flex gap-1">
                                    <div class="d-grid">
                                        <a href="{{ route('items.edit', ['kode_barang' => $item->kode_barang, 'code_id' => $item->code_id]) }}"
                                            class="btn btn-sm btn-warning">
                                            <i class='bx bxs-edit'></i>
                                            <div class="ms-1">
                                                Edit
                                            </div>
                                        </a>
                                    </div>
                                </div>
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
