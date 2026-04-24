@inject('LeaveApplication', 'App\Models\LeaveApplication')

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Employee Application Status') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-gray-50">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- Filter Section --}}
            <div class="bg-white rounded shadow-sm p-4 mb-6 d-flex justify-content-between align-items-center">
                <h5 class="mb-0 fw-bold">Manage Leave Applications</h5>

                
            </div>

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show shadow-sm mb-4">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            {{-- Table --}}
            <div class="bg-white overflow-hidden shadow sm:rounded-lg">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover mb-0">
                        <thead class="bg-gray-100 text-xs uppercase text-gray-500">
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
                                    $badgeClass = match($item->status) {
                                        $LeaveApplication::STATUS_ACTIVE => 'bg-success',
                                        $LeaveApplication::STATUS_REJECT => 'bg-danger',
                                        default => 'bg-warning text-dark',
                                    };
                                @endphp

                                <tr class="align-middle">
                                    <td>
                                        <div class="fw-bold">{{ $item->user->name }}</div>
                                        <small class="text-muted">{{ $item->user->email }}</small>
                                    </td>

                                    <td>{{ $item->leaveType->name }}</td>

                                    <td>
                                        {{ $item->start_date->format('d/m/Y') }}
                                        <br>
                                        <small class="text-muted">to {{ $item->end_date->format('d/m/Y') }}</small>
                                    </td>

                                    <td class="text-center fw-bold">
                                        {{ $item->start_date->diffInDays($item->end_date) + 1 }}
                                    </td>

                                    <td>
                                        <span class="badge {{ $badgeClass }}">
                                            {{ $LeaveApplication::$leave_status[$item->status] }}
                                        </span>
                                    </td>

                                    <td>
                                        @if($item->status == $LeaveApplication::STATUS_PENDING)

                                            <button class="btn btn-primary btn-sm"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#reviewModal{{ $item->id }}">
                                                Review
                                            </button>

                                            

                                        @else
                                            <small class="text-muted">
                                                {{ $item->manager_comment ?: 'No feedback provided.' }}
                                            </small>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-5 text-muted">
                                        No applications to show in this view.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
    <div class="modal fade" id="reviewModal{{ $item->id }}" tabindex="-1">
                                                <div class="modal-dialog modal-dialog-centered">
                                                    <div class="modal-content">

                                                        <form action="{{ route('manager.leave-applications.review', $item->id) }}" method="POST">
                                                            @csrf
                                                            @method('PATCH')

                                                            <div class="modal-header">
                                                                <h5 class="modal-title">Review Leave Application</h5>
                                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                            </div>

                                                            <div class="modal-body">
                                                                <div class="mb-3">
                                                                    <label class="form-label fw-bold">Employee Reason</label>
                                                                    <div class="border rounded p-2 bg-light small">
                                                                        {{ $item->reason }}
                                                                    </div>
                                                                </div>

                                                                <div class="mb-3">
                                                                    <label class="form-label fw-bold">Manager Comment</label>
                                                                    <textarea name="comment"
                                                                              rows="3"
                                                                              class="form-control"
                                                                              placeholder="Write your comment..."></textarea>
                                                                </div>
                                                            </div>

                                                            <div class="modal-footer">
                                                                <button type="submit"
                                                                        name="status"
                                                                        value="{{ $LeaveApplication::STATUS_REJECT }}"
                                                                        class="btn btn-danger">
                                                                    Reject
                                                                </button>

                                                                <button type="submit"
                                                                        name="status"
                                                                        value="{{ $LeaveApplication::STATUS_ACTIVE }}"
                                                                        class="btn btn-success">
                                                                    Approve
                                                                </button>
                                                            </div>
                                                        </form>

                                                    </div>
                                                </div>
                                            </div>
</x-app-layout>
