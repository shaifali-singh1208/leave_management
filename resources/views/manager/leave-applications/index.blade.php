@inject('LeaveApplication', 'App\Models\LeaveApplication')

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Employee Application Status') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-gray-50">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="bg-white rounded -sm p-4 mb-6">
                <div class="d-flex justify-content-between align-items-center mb-0">
                    <h5 class="mb-0 fw-bold">Manage Leave Applications</h5>

                    <form method="GET" action="{{ route('manager.leave-applications.index') }}" class="d-flex gap-2">
                        <select name="status" class="form-select form-select-sm" style="width: 150px">
                            <option value="">All Status</option>
                            @foreach ($LeaveApplication::$leave_status as $val => $label)
                                <option value="{{ $val }}" {{ request('status') == $val ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>

                        <button type="submit" class="btn btn-sm btn-primary">Filter</button>
                        <a href="{{ route('manager.leave-applications.index') }}" class="btn btn-warning">Reset</a>
                    </form>
                </div>
            </div>

            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show shadow-sm mb-4">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            {{-- Table --}}
            <div class="bg-white overflow-hidden  sm:rounded-lg">
                <div class="table-responsive">
                    <table class="projects table table-bordered table-hover table-striped">
                        <thead class="ty-1 text-xs uppercase text-gray-500">
                            <tr>
                                <th>Employee</th>
                                <th>Type</th>
                                <th>Dates</th>
                                <th class="text-center">Days</th>
                                <th>Status</th>
                                <th style="width: 20%">Action / Response</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($aRows as $item)
                                @php
                                    $badgeClass = match ($item->status) {
                                        $LeaveApplication::STATUS_ACTIVE => 'bg-success',
                                        $LeaveApplication::STATUS_REJECT => 'bg-danger',
                                        default => 'bg-warning text-dark',
                                    };
                                @endphp

                                <tr class="align-middle">
                                    <td>
                                        <div class="fw-bold text-primary">{{ $item->user->name }}</div>
                                        <small class="text-muted">{{ $item->user->email }}</small>
                                    </td>

                                    <td><span class="fw-bold">{{ $item->leaveType->name }}</span></td>

                                    <td>
                                        {{ $item->start_date->format('d-m-Y') }}
                                        <br>
                                        <small class="text-muted">to {{ $item->end_date->format('d-m-Y') }}</small>
                                    </td>

                                    <td class="text-center">
                                        <span
                                            class="badge bg-secondary">{{ $item->start_date->diffInDays($item->end_date) + 1 }}</span>
                                    </td>

                                    <td>
                                        <span class="badge {{ $badgeClass }}">
                                            {{ $LeaveApplication::$leave_status[$item->status] }}
                                        </span>
                                    </td>

                                    <td>
                                        <button class="btn btn-primary" data-bs-toggle="modal"
                                            data-bs-target="#reviewModal{{ $item->id }}"
                                            {{ $item->status != $LeaveApplication::STATUS_PENDING ? 'disabled' : '' }}>
                                            <i class="fa fa-eye"></i> Review
                                        </button>

                                        @if ($item->status == $LeaveApplication::STATUS_PENDING)
                                            <div class="modal fade" id="reviewModal{{ $item->id }}" tabindex="-1">
                                                <div class="modal-dialog modal-dialog-centered">
                                                    <div class="modal-content text-start">
                                                        <form
                                                            action="{{ route('manager.leave-applications.review', $item->id) }}"
                                                            method="POST">
                                                            @csrf
                                                            @method('PATCH')

                                                            <div class="modal-header bg-light">
                                                                <h5 class="modal-title fw-bold">
                                                                    Review Application: {{ $item->user->name }}
                                                                </h5>
                                                                <button type="button" class="btn-close"
                                                                    data-bs-dismiss="modal"></button>
                                                            </div>

                                                            <div class="modal-body">
                                                                <div class="mb-4">
                                                                    <label class="form-label fw-bold">Employee
                                                                        Reason</label>
                                                                    <div class="border rounded p-3 bg-light">
                                                                        {{ $item->reason }}
                                                                    </div>
                                                                </div>

                                                                <div class="mb-3">
                                                                    <label class="form-label fw-bold">Manager
                                                                        Comment</label>
                                                                    <textarea name="comment" rows="3" class="form-control"></textarea>
                                                                </div>
                                                            </div>

                                                            <div class="modal-footer bg-light">
                                                                <button type="submit" name="status"
                                                                    value="{{ $LeaveApplication::STATUS_REJECT }}"
                                                                    class="btn btn-danger">
                                                                    Reject
                                                                </button>
                                                                <button type="submit" name="status"
                                                                    value="{{ $LeaveApplication::STATUS_ACTIVE }}"
                                                                    class="btn btn-success">
                                                                    Approve
                                                                </button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-5 text-muted">
                                        <i class="fa fa-inbox fa-3x mb-3 text-light"></i><br>
                                        No applications to show.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
</x-app-layout>
