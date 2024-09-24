<div>
    <div>
        <div class="table-responsive">
            <table class="table table-bordered table-hover table-sm m-0">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Unit</th>
                        <th>Code</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $no = $units->firstItem();
                    @endphp
                    <tr>
                        <td></td>
                        <td>
                            <form wire:submit="search">
                                <input type="text" class="form-control" wire:model.live.debounce.150ms="query"
                                    id="search" placeholder="Search">
                            </form>
                        </td>
                        <td></td>
                        <td></td>
                    </tr>
                    @if ($units->isEmpty())
                        <tr>
                            <td colspan="5" class="text-center fw-bold">Data not found</td>
                        </tr>
                    @else
                        @foreach ($units as $unit)
                            <tr>
                                <td>{{ $no++ }}</td>
                                <td>{{ $unit->name }}</td>
                                <td>{{ $unit->code }}</td>
                                <td>
                                    <div class="d-flex gap-1">
                                        <div class="d-grid">
                                            <a href="{{ route('units.edit', $unit) }}" class="btn btn-sm btn-warning">
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
            @if ($units->hasPages())
                <div class="card-footer">
                    {{ $units->links() }}
                </div>
            @endif
        </div>
    </div>

</div>
