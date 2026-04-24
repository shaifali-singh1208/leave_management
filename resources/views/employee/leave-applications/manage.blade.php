<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('New Leave Application') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-gray-100 min-h-screen">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">

            @if ($errors->any())
                <div class="alert alert-danger shadow-sm mb-4">
                    <ul class="mb-0 small">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="card shadow-sm border-0 rounded-lg">
                <div class="card-header bg-primary text-white py-3">
                    <h5 class="mb-0 fw-bold">Leave Request Form</h5>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('employee.leave-applications.store') }}" method="POST">
                        @csrf

                        <div class="mb-4">
                            <label class="form-label font-bold text-gray-700">Leave Type</label>
                            <select name="leave_type_id" class="form-select @error('leave_type_id') is-invalid @enderror">
                                <option value="">-- Select Category --</option>
                                @foreach ($leaveTypes as $type)
                                    <option value="{{ $type->id }}" {{ old('leave_type_id') == $type->id ? 'selected' : '' }}>
                                        {{ $type->name }} (Available: {{ $type->entitlement_days }} days/year)
                                    </option>
                                @endforeach
                            </select>
                            @error('leave_type_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-6 mb-3 mb-md-0">
                                <label class="form-label font-bold text-gray-700">Start Date</label>
                                <input type="date" name="start_date" value="{{ old('start_date', date('Y-m-d')) }}" 
                                       class="form-control @error('start_date') is-invalid @enderror">
                                @error('start_date') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label font-bold text-gray-700">End Date</label>
                                <input type="date" name="end_date" value="{{ old('end_date', date('Y-m-d')) }}" 
                                       class="form-control @error('end_date') is-invalid @enderror">
                                @error('end_date') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label font-bold text-gray-700">Reason for Absence</label>
                            <textarea name="reason" rows="4" class="form-control @error('reason') is-invalid @enderror" 
                                      placeholder="Briefly explain the reason for your request...">{{ old('reason') }}</textarea>
                            @error('reason') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="d-flex justify-content-between align-items-center mt-5">
                            <a href="{{ route('employee.leave-applications.index') }}" class="text-secondary small">Cancel & Go Back</a>
                            <button type="submit" class="btn btn-primary px-5 shadow-sm">Submit Application</button>
                        </div>
                    </form>
                </div>
            </div>
            
            <div class="mt-4 p-3 bg-blue-50 border-start border-4 border-blue-400 rounded text-sm text-blue-800">
                <strong>Note:</strong> Your request will be sent to your assigned manager for review. Overlapping with existing approved leaves is not permitted.
            </div>
        </div>
    </div>
</x-app-layout>