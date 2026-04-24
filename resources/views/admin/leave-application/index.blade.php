@inject('LeaveApplication', 'App\Models\LeaveApplication')

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Organization Leave Applications') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-gray-50">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <!-- Standard Filters -->
            <div class="bg-white rounded shadow-sm p-4 mb-6">
                <h4 class="text-sm font-bold text-gray-600 mb-3"><i class="fa fa-filter mr-1"></i> Filter Records</h4>
                <form action="{{ route('admin.leave-applications.index') }}" method="GET" class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label text-xs">Leave Type</label>
                        <select name="leave_type_id" class="form-select form-select-sm">
                            <option value="">All Types</option>
                            @foreach($leaveTypes as $type)
                                <option value="{{ $type->id }}" {{ request('leave_type_id') == $type->id ? 'selected' : '' }}>{{ $type->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label text-xs">Status</label>
                        <select name="status" class="form-select form-select-sm">
                            <option value="">All Status</option>
                            @foreach($LeaveApplication::$leave_status as $val => $label)
                                <option value="{{ $val }}" {{ request('status') == $val ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label text-xs">Search Employee</label>
                        <input type="text" name="search" value="{{ request('search') }}" class="form-control" placeholder="Name or Email...">
                    </div>
                    <div class="col-md-3 d-flex align-items-end gap-2 mb-2">
                        <button type="submit" class="btn btn-sm btn-primary">Apply</button>
                        <a href="{{ route('admin.leave-applications.index') }}" class="btn btn-sm btn-secondary">Reset</a>
                    </div>
                </form>
            </div>

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow sm:rounded-lg">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover mb-0">
                        <thead class="bg-gray-100 text-xs uppercase text-gray-500">
                            <tr>
                                <th>Employee</th>
                                <th>Manager</th>
                                <th>Type</th>
                                <th>Duration</th>
                                <th>Days</th>
                                <th>Status</th>
                                <th class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($aRows as $item)
                                <tr class="align-middle">
                                    <td class="text-sm">
                                        <div class="fw-bold">{{ $item->user->name }}</div>
                                        <div class="text-muted small">{{ $item->user->email }}</div>
                                    </td>
                                    <td class="text-sm">
                                        {{ $item->user->manager ? $item->user->manager->name : '' }}
                                    </td>
                                    <td class="text-sm">{{ $item->leaveType->name }}</td>
                                    <td class="text-sm">
                                        {{ $item->start_date->format('d/m/Y') }} - {{ $item->end_date->format('d/m/Y') }}
                                    </td>
                                    <td class="text-center fw-bold">{{ $item->start_date->diffInDays($item->end_date) + 1 }}</td>
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
                                    <td class="text-center">
                                        <button class="btn btn-sm btn-outline-info" @click="$dispatch('open-modal', 'override-{{ $item->id }}')">
                                            Override
                                        </button>

                                        <x-modal name="override-{{ $item->id }}">
                                            <div class="p-6">
                                                <h3 class="text-lg font-bold mb-4">Override Status: {{ $item->user->name }}</h3>
                                                <form action="{{ route('admin.leave-applications.status', $item->id) }}" method="POST">
                                                    @csrf
                                                    @method('PATCH')
                                                    
                                                    <div class="mb-3">
                                                        <label class="form-label font-bold text-sm">Update Status</label>
                                                        <select name="status" class="form-select">
                                                            @foreach($LeaveApplication::$leave_status as $val => $label)
                                                                <option value="{{ $val }}" {{ $item->status == $val ? 'selected' : '' }}>{{ $label }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>

                                                    <div class="mb-4">
                                                        <label class="form-label font-bold text-sm">Admin Comment</label>
                                                        <textarea name="comment" class="form-control" rows="3">{{ $item->manager_comment }}</textarea>
                                                    </div>

                                                    <div class="d-flex justify-content-end gap-2">
                                                        <button type="button" x-on:click="$dispatch('close')" class="btn btn-secondary">Cancel</button>
                                                        <button type="submit" class="btn btn-primary">Update</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </x-modal>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center py-5 text-muted">No records found matching filters.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>