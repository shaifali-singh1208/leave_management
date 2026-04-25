<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('New Leave Application') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-gray-50 min-h-screen">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">

            @if ($errors->any())
                <div class="alert alert-danger -sm mb-4 border-l-4">
                    <ul class="mb-0 small">
                        @foreach ($errors->all() as $error)
                            <li><i class="fa fa-exclamation-circle mr-1"></i> {{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="card  border-0 rounded-lg overflow-hidden">
                <div class="card-header bg-white border-b py-4 px-5">
                    <div class="d-flex align-items-center">
                        
                        <h4 class="mb-0 fw-bold text-gray-800">Apply for Leave</h4>
                    </div>
                </div>
                <div class="card-body p-5">
                    <form action="{{ route('employee.leave-request.store') }}" method="POST">
                        @csrf

                        <div class="mb-4">
                            <label class="form-label ">
                                Leave Category
                            </label>
                            <select name="leave_type_id" class=" form-control">
                                <option value="">-- Choose Leave Type --</option>
                                @foreach ($leaveTypes as $type)
                                    <option value="{{ $type->id }}" {{ old('leave_type_id') == $type->id ? 'selected' : '' }}>
                                        {{ $type->name }} (Available: {{ $type->entitlement_days }} days/year)
                                    </option>
                                @endforeach
                            </select>
                            @error('leave_type_id') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-6 mb-3 mb-md-0">
                                <label class="form-label ">
                                   Start Date
                                </label>
                                <input type="date" name="start_date" value="{{ old('start_date', date('Y-m-d')) }}" 
                                       class="form-control">
                                @error('start_date') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label ">
                                     End Date
                                </label>
                                <input type="date" name="end_date" value="{{ old('end_date', date('Y-m-d')) }}" 
                                       class="form-control">
                                @error('end_date') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                            </div>
                        </div>

                        <div class="mb-5">
                            <label class="form-label ">
                                 Reason for Leave
                            </label>
                            <textarea name="reason" rows="4" class="form-control" 
                                      placeholder="Please provide a brief reason for your leave ...">{{ old('reason') }}</textarea>
                            @error('reason') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">
                                Submit Application
                            </button>
                        </div>
                    </form>
                </div>
               
            </div>
        </div>
    </div>
</x-app-layout>