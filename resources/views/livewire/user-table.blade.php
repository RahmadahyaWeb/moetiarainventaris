<div>
    <div class="table-responsive">
        <table class="table table-bordered table-hover table-sm m-0">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $no = $users->firstItem();
                @endphp
                <tr>
                    <td></td>
                    <td>
                        <form wire:submit="search">
                            <input type="text" class="form-control" wire:model.live.debounce.150ms="query" id="search"
                                placeholder="Search">
                        </form>
                    </td>
                    <td></td>
                    <td>
                        <form wire:submit="search">
                            <select class="form-select" wire:model.change="role_id">
                                <option value="">All</option>
                                @foreach ($roles as $role)
                                    <option value="{{ $role->id }}">{{ $role->name }}</option>
                                @endforeach
                            </select>
                        </form>
                    </td>
                    <td></td>
                </tr>
                @if ($users->isEmpty())
                    <tr>
                        <td colspan="5" class="text-center fw-bold">Data not found</td>
                    </tr>
                @else
                    @foreach ($users as $user)
                        <tr>
                            <td>{{ $no++ }}</td>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
                            <td>{{ $user->role->name }}</td>
                            <td>
                                <div class="d-flex gap-1">
                                    @if (Auth::user()->role_id)
                                        <div class="d-grid">
                                            <a href="{{ route('users.edit', $user) }}" class="btn btn-sm btn-warning">
                                                <i class='bx bxs-edit'></i>
                                                <div class="ms-1">
                                                    Edit
                                                </div>
                                            </a>
                                        </div>
                                    @endif

                                    <div class="d-grid">
                                        <a href="" class="btn btn-sm btn-secondary" data-bs-toggle="modal"
                                            data-bs-target="#resetModal">
                                            <i class='bx bx-reset'></i>
                                            <div class="ms-1">
                                                Reset
                                            </div>
                                        </a>
                                    </div>
                                </div>
                            </td>
                        </tr>

                        <!-- Reset Modal -->
                        <div class="modal fade" id="resetModal" tabindex="-1" aria-labelledby="resetModal"
                            aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h1 class="modal-title fs-5" id="resetModal">Reset User Password</h1>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="text-center">
                                            Are you sure you want to reset <span
                                                class="fw-bold">{{ $user->name }}</span>
                                            password? This action will
                                            change <span class="fw-bold">{{ $user->name }}</span>
                                            account password, and <span class="fw-bold">{{ $user->name }}</span> will
                                            need to use the new password to
                                            log in. Do you
                                            want to proceed?
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <div class="d-flex gap-2">
                                            <button type="button" class="btn btn-secondary"
                                                data-bs-dismiss="modal">Cancel</button>
                                            <a href="{{ route('users.reset-password', $user) }}"
                                                class="btn btn-primary">Yes</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Reset Modal -->
                    @endforeach
                @endif
            </tbody>
        </table>
    </div>

    <div>
        @if ($users->hasPages())
            <div class="card-footer">
                {{ $users->links() }}
            </div>
        @endif
    </div>
</div>
