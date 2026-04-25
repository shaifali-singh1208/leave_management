@php
    $page_title = $aRow ? 'Update Employee' : 'Create Employee';
@endphp

<x-app-layout>
    <div class="py-10 bg-gray-100 min-h-screen">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">

            <div class="bg-white rounded-xl shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b bg-gray-50 flex justify-between items-center">
                    <h2 class="font-semibold text-2xl text-gray-800 leading-tight">
                        {{ $page_title }}
                    </h2>
                </div>

                <div class="p-6">
                    @if ($aRow)
                        <form action="{{ route('admin.employee.update', $aRow->id) }}" method="POST">
                            @method('PATCH')
                    @else
                        <form action="{{ route('admin.employee.store') }}" method="POST">
                    @endif

                    @csrf

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                        <div class="mb-4">
                            <label class="block font-medium mb-1">Full Name</label>
                            <input type="text" name="name" class="form-control"
                                value="{{ old('name', $aRow->name ?? '') }}" placeholder="Enter full name">
                            @error('name')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label class="block font-medium mb-1">Email Address</label>
                            <input type="email" name="email" class="form-control"
                                value="{{ old('email', $aRow->email ?? '') }}" placeholder="Enter email address">
                            @error('email')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label class="block font-medium mb-1">Assign Manager</label>
                            <select name="manager_id" class="form-control bg-white border-gray-300 rounded-md shadow-sm">
                                <option value="">-- No Manager Assigned --</option>
                                @foreach($managers as $manager)
                                    <option value="{{ $manager->id }}"
                                        {{ old('manager_id', $aRow->manager_id ?? '') == $manager->id ? 'selected' : '' }}>
                                        {{ $manager->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('manager_id')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label class="block font-medium mb-1">Department</label>
                            <select name="department_id" class="form-control bg-white border-gray-300 rounded-md shadow-sm">
                                <option value="">-- Select Department --</option>
                                @foreach($departments as $dept)
                                    <option value="{{ $dept->id }}"
                                        {{ old('department_id', $aRow->department_id ?? '') == $dept->id ? 'selected' : '' }}>
                                        {{ $dept->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('department_id')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label class="block font-medium mb-1">Password</label>
                            <input type="password" name="password" class="form-control"
                                placeholder="Minimum 8 characters">
                            @error('password')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label class="block font-medium mb-1">Confirm Password</label>
                            <input type="password" name="password_confirmation" class="form-control"
                                placeholder="Re-type password">
                        </div>

                    </div>

                    <div class="mt-6">
                        <button type="submit" class="btn btn-success">Submit</button>
                    </div>
                    </form>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>