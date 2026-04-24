@inject('LeaveApplication', 'App\Models\LeaveApplication')

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('My Leave History') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-gray-100 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- Summary Card --}}
            <div class="row mb-4">
                <div class="col-md-4">
                    <div class="card shadow-sm border-0">
                        <div class="card-body bg-light">
                            <h6 class="text-muted text-uppercase small font-bold">Total Leaves Taken ({{ date('Y') }})</h6>
                            <h2 class="mb-0 fw-bold">{{ $daysTaken }} Days</h2>
                            <p class="text-muted small mt-2 italic">*Based on total approved applications</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-8 d-flex align-items-center justify-content-end">
                    <a href="{{ route('employee.leave-applications.create') }}" class="btn btn-primary shadow-sm px-4">
                        <i class="fa fa-plus mr-2"></i> Apply for Leave
                    </a>
                </div>
            </div>

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show mb-4">{{ session('success') }}</div>
            @endif

            <div class="bg-white rounded-lg shadow">
                <div class="p-4 border-b d-flex justify-content-between align-items-center bg-gray-50">
                    <h5 class="mb-0 fw-bold">Application History</h5>
                    <form action="{{ route('employee.leave-applications.index') }}" method="GET" class="d-flex gap-2">
                        <select name="status" class="form-select form-select-sm" onchange="this.form.submit()">
                            <option value="">All Status</option>
                            @foreach($LeaveApplication::$leave_status as $val => $label)
                                <option value="{{ $val }}" {{ request('status') == $val ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                        <a href="{{ route('employee.leave-applications.index') }}" class="btn btn-sm btn-link text-danger">Clear</a>
                    </form>
                </div>

                <div class="table-responsive">
                    <table class="table table-bordered table-hover mb-0">
                        <thead class="bg-gray-50 text-xs uppercase text-gray-500">
                            <tr>
                                <th>Type</th>
                                <th>Dates</th>
                                <th class="text-center">Days</th>
                                <th>Status</th>
                                <th>Applied On</th>
                                <th class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($aRows as $item)
                                <tr class="align-middle text-sm">
                                    <td class="fw-bold">{{ $item->leaveType->name }}</td>
                                    <td>
                                        {{ $item->start_date->format('d/m/Y') }} to {{ $item->end_date->format('d/m/Y') }}
                                    </td>
                                    <td class="text-center">{{ $item->start_date->diffInDays($item->end_date) + 1 }}</td>
                                    <td>
                                        @php
                                            $badgeClass = match($item->status) {
                                                $LeaveApplication::STATUS_ACTIVE => 'bg-success',
                                                $LeaveApplication::STATUS_REJECT => 'bg-danger',
                                                default => 'bg-warning text-dark',
                                            };
                                        @endphp
                                        <span class="badge {{ $badgeClass }}">{{ $LeaveApplication::$leave_status[$item->status] }}</span>
                                    </td>
                                    <td class="text-muted small">{{ $item->created_at->format('d/m/Y H:i') }}</td>
                                    <td class="text-center">
                                        @if($item->status == $LeaveApplication::STATUS_PENDING)
                                            <form action="{{ route('employee.leave-applications.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Cancel this request?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger">Cancel</button>
                                            </form>
                                        @else
                                            <span class="text-muted small">-</span>
                                        @endif
                                    </td>
                                </tr>
                                @if($item->manager_comment)
                                    <tr class="bg-light">
                                        <td colspan="6" class="small italic py-2 px-4 shadow-inner">
                                            <strong>Manager's Note:</strong> {{ $item->manager_comment }}
                                        </td>
                                    </tr>
                                @endif
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-4 text-muted small">No applications found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>