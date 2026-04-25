@inject('LeaveApplication', 'App\Models\LeaveApplication')

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __(' Leave Applications') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-gray-50">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="bg-white rounded  p-4 mb-6">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h4 class="text-sm font-bold mb-0">
                       Filter Records
                    </h4>
                    
                </div>

                <form method="GET" action="{{ route('admin.leave-applications.index') }}">
                    <div class="row g-2 align-items-end">

                        <div class="col-md-4">
                            <label class="form-label ">Leave Type</label>
                            <select name="leave_type_id" class="form-control">
                                <option value="">All Types</option>
                                @foreach ($leaveTypes as $type)
                                    <option value="{{ $type->id }}" {{ request('leave_type_id') == $type->id ? 'selected' : '' }}>
                                        {{ $type->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label ">Status</label>
                            <select name="status" class="form-control">
                                <option value="">All Status</option>
                                @foreach ($LeaveApplication::$leave_status as $val => $label)
                                    <option value="{{ $val }}" {{ request('status') == $val ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label ">Department</label>
                            <select name="department_id" class="form-control">
                                <option value="">All Departments</option>
                                @foreach ($departments as $dept)
                                    <option value="{{ $dept->id }}" {{ request('department_id') == $dept->id ? 'selected' : '' }}>
                                        {{ $dept->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label ">From Date</label>
                            <input type="date" name="date_from" value="{{ request('date_from') }}" class="form-control ">
                        </div>

                        <div class="col-md-4">
                            <label class="form-label ">To Date</label>
                            <input type="date" name="date_to" value="{{ request('date_to') }}" class="form-control ">
                        </div>

                        <div class="col-md-4">
                            <label class="form-label ">Search Employee</label>
                            <input type="text" name="search" value="{{ request('search') }}"
                                class="form-control" placeholder="Name or Email">
                        </div>

                        <div class="col-md-12 text-end mt-3">
                            <button type="submit" class="btn btn-sm btn-primary px-4">Apply Filters</button>
                            <a href="{{ route('admin.leave-applications.index') }}"
                                class="btn btn-sm btn-secondary px-4">Reset</a>
                        </div>

                    </div>
                </form>
            </div>

            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
<div><a href="{{ route('admin.leave-applications.export', request()->all()) }}" class="btn btn-sm btn-success px-3">
                        <i class="fa fa-download me-1"></i> Export CSV
                    </a></div>
            <div class="bg-white  rounded">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th>Employee</th>
                                <th>Manager</th>
                                <th>Type</th>
                                <th>Dates</th>
                                <th>Days</th>
                                <th>Status</th>
                                <th class="text-center">Action</th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse ($aRows as $item)
                                <tr>
                                    <td>
                                        <div class="fw-bold">{{ $item->user->name }}</div>
                                        <small>{{ $item->user->email }}</small>
                                    </td>

                                    <td>{{ $item->user->manager->name ?? '-' }}</td>

                                    <td>{{ $item->leaveType->name }}</td>

                                    <td>
                                        {{ $item->start_date->format('d/m/Y') }} -
                                        {{ $item->end_date->format('d/m/Y') }}
                                    </td>

                                    <td class="text-center fw-bold">
                                        {{ $item->start_date->diffInDays($item->end_date) + 1 }}
                                    </td>

                                    <td>
                                        @php
                                            $badgeClass = match($item->status) {
                                                $LeaveApplication::STATUS_ACTIVE => 'bg-success',
                                                $LeaveApplication::STATUS_REJECT => 'bg-danger',
                                                default => 'bg-warning text-dark',
                                            };
                                        @endphp

                                        <span class="badge {{ $badgeClass }}">
                                            {{ $LeaveApplication::$leave_status[$item->status] }}
                                        </span>
                                    </td>

                                    <td class="text-center">
                                        <button class="btn btn-sm btn-outline-info"
                                            onclick="openStatusModal({{ $item->id }}, '{{ $item->status }}')">
                                            Override
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center py-5 text-muted">
                                        No records found
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>

                    </table>
                </div>
            </div>

        </div>
    </div>

    {{-- MODAL --}}
    <div id="statusModal" class="modal fade" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">

                <form id="statusForm" method="POST">
                    @csrf
                    @method('PATCH')

                    <div class="modal-header">
                        <h5 class="modal-title">Update Leave Status</h5>
                        <button type="button" class="btn-close" onclick="closeStatusModal()"></button>
                    </div>

                    <div class="modal-body">
                        <label>Status</label>
                        <select name="status" id="statusSelect" class="form-select">
                            @foreach($LeaveApplication::$leave_status as $val => $label)
                                <option value="{{ $val }}">{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" onclick="closeStatusModal()">
                            Cancel
                        </button>
                        <button type="submit" class="btn btn-primary">
                            Update
                        </button>
                    </div>

                </form>

            </div>
        </div>
    </div>

    <script>
        let statusModal = new bootstrap.Modal(document.getElementById('statusModal'));

        function openStatusModal(id, status) {
            document.getElementById('statusForm').action =
                `/admin/leave-applications/${id}/status`;

            document.getElementById('statusSelect').value = status;

            statusModal.show();
        }

        function closeStatusModal() {
            statusModal.hide();
        }
    </script>

</x-app-layout>