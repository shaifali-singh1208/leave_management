@inject('LeaveApplication', 'App\Models\LeaveApplication')

<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            My Leave History
        </h2>
    </x-slot>

    <div class="py-10 bg-gray-100 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="row mb-4 g-3">

                @foreach ($leaveTypes as $type)
                    @php
                        $remaining = max(0, $type->entitlement_days - $type->days_taken);
                        $percent =
                            $type->entitlement_days > 0 ? ($type->days_taken / $type->entitlement_days) * 100 : 0;
                    @endphp

                    <div class="col-md-3 col-sm-6">
                        <div class="card shadow-sm border-0">
                            <div class="card-body p-3">

                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <h6 class="mb-0 fw-bold">{{ $type->name }}</h6>

                                    <span class="badge bg-{{ $remaining > 0 ? 'success' : 'danger' }}">
                                        {{ $remaining }} left
                                    </span>
                                </div>

                                <small class="text-muted">
                                    {{ $type->days_taken }} / {{ $type->entitlement_days }} used
                                </small>

                                <div class="progress mt-2" style="height:5px;">
                                    <div class="progress-bar bg-primary" style="width: {{ min(100, $percent) }}%">
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                @endforeach

                <div class="col-md-12 text-end mt-2">
                    <a href="{{ route('employee.leave-request.create') }}" class="btn btn-primary btn-sm px-4">
                        + Apply Leave
                    </a>
                </div>

            </div>

            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show shadow-sm mb-4">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show shadow-sm mb-4">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <div class="bg-white p-2 rounded shadow-sm mb-3">

                <form method="GET" class="d-flex align-items-center gap-2 flex-wrap">

                    <select name="leave_type_id" class="form-control" style="max-width: 160px">
                        <option value="">Leave Type</option>
                        @foreach ($leaveTypes as $type)
                            <option value="{{ $type->id }}"
                                {{ request('leave_type_id') == $type->id ? 'selected' : '' }}>
                                {{ $type->name }}
                            </option>
                        @endforeach
                    </select>

                    <select name="status" class="form-control" style="max-width: 160px">
                        <option value="">Status</option>

                        @foreach ($LeaveApplication::$leave_status as $val => $label)
                            <option value="{{ $val }}" {{ request('status') == $val ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>

                    <button type="submit" class="btn btn-sm btn-primary px-3">
                        Filter
                    </button>

                    <a href="{{ route('employee.leave-request.index') }}" class="btn btn-sm btn-warning px-3">
                        Reset
                    </a>

                </form>

            </div>

            <div class="bg-white rounded shadow-sm overflow-hidden">

                <table class="table table-hover mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th>Type</th>
                            <th>Dates</th>
                            <th class="text-center">Days</th>
                            <th>Status</th>
                            <th>Applied</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>

                    <tbody>

                        @forelse ($aRows as $item)
                            <tr>
                                <td class="fw-bold">{{ $item->leaveType->name }}</td>

                                <td>
                                    {{ $item->start_date->format('d M Y') }}
                                    to
                                    {{ $item->end_date->format('d M Y') }}
                                </td>

                                <td class="text-center">
                                    {{ $item->start_date->diffInDays($item->end_date) + 1 }}
                                </td>

                                <td>
                                    @php
                                        $badge = match ($item->status) {
                                            $LeaveApplication::STATUS_ACTIVE => 'success',
                                            $LeaveApplication::STATUS_REJECT => 'danger',
                                            default => 'warning',
                                        };
                                    @endphp

                                    <span class="badge bg-{{ $badge }}">
                                        {{ $LeaveApplication::$leave_status[$item->status] }}
                                    </span>
                                </td>

                                <td>
                                    {{ $item->created_at->format('d M Y') }}
                                </td>

                                <td class="text-center">
                                    @if ($item->status == $LeaveApplication::STATUS_PENDING)
                                        <form action="{{ route('employee.leave-request.destroy', $item->id) }}"
                                            method="POST"
                                            onsubmit="return confirm('Are you sure you want to cancel this leave request?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger">
                                                Cancel
                                            </button>
                                        </form>
                                    @else
                                        <button type="button" class="btn btn-sm btn-danger" disabled>
                                            Cancel
                                        </button>
                                    @endif
                                </td>

                            </tr>

                            @if ($item->manager_comment)
                                <tr class="bg-light">
                                    <td colspan="6" class="small text-muted">
                                        <strong>Manager:</strong> {{ $item->manager_comment }}
                                    </td>
                                </tr>
                            @endif

                        @empty

                            <tr>
                                <td colspan="6" class="text-center py-5 text-muted">
                                    No leave applications found
                                </td>
                            </tr>
                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>
    </div>

    <script>
        function confirmCancel(btn) {
            if (confirm("Are you sure you want to cancel this leave request?")) {
                btn.closest('form').submit();
            }
        }
    </script>

</x-app-layout>
