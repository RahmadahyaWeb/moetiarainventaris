<div>
    <div class="table-responsive">
        <table class="table table-bordered table-hover  m-0">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Code</th>
                    <th>Item Name</th>
                    <th>Minimum Stock</th>
                    <th>Initial Stock</th>
                    <th>Inbound</th>
                    <th>Outbound</th>
                    <th>Last Stock</th>
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
                            <input type="text" class="form-control" wire:model.live.debounce.150ms="name"
                                id="search" placeholder="Search">
                        </form>
                    </td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                @if ($items->isEmpty())
                    <tr>
                        <td colspan="9" class="text-center fw-bold">Data not found</td>
                    </tr>
                @else
                    @foreach ($items as $item)
                        <tr
                            class="{{ $item->priority == 'high' && $item->last_stock == $item->minimum ? 'table-warning' : '' }} {{ $item->priority == 'high' && $item->last_stock < $item->minimum ? 'table-danger' : '' }}">
                            <td>{{ $no++ }}</td>
                            <td>{{ $item->kode_barang }}</td>
                            <td>{{ $item->nama_barang }}</td>
                            <td>{{ $item->minimum }} {{ $item->unit->code }}</td>
                            <td>{{ $item->initial_stock }} {{ $item->unit->code }}</td>
                            <td>{{ $item->in }} {{ $item->unit->code }}</td>
                            <td>{{ $item->out }} {{ $item->unit->code }}</td>
                            <td>{{ $item->last_stock }} {{ $item->unit->code }}</td>
                            <td>
                                <div class="d-flex gap-1">
                                    <div class="d-grid ">
                                        <a href="{{ route('cashiers.edit', $item) }}"
                                            class="btn btn-sm btn-warning text-white">
                                            <i class='bx bxs-edit'></i>
                                            <div class="ms-1">
                                                Edit
                                            </div>
                                        </a>
                                    </div>

                                    @if (Auth::user()->role_id == 1)
                                        <div class="d-grid">
                                            <a href="{{ route('cashiers.destroy', $item) }}"
                                                class="btn btn-sm btn-danger text-white" data-confirm-delete="true">
                                                <i class='bx bxs-trash-alt'></i>
                                                <div class="ms-1">
                                                    Delete
                                                </div>
                                            </a>
                                        </div>
                                    @endif
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
