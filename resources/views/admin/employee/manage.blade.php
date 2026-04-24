@inject('Userobj', 'App\Models\User')
@php
    $page_title = $aRow ? 'Update Employee' : 'Create Employee';
@endphp

<x-app-layout>
    <div class="py-10 bg-gray-100 min-h-screen">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">

            @if ($errors->any())
                <div class="bg-red-100 text-red-700 p-3 rounded mb-4 border border-red-400">
                    <ul class="list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

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
                            <label class="">Full Name</label>
                            <input type="text" name="name" class="w-full border rounded-lg p-3"
                                value="{{ old('name', $aRow->name ?? '') }}" placeholder="Enter name">
                        </div>

                        <div class="mb-4">
                            <label class="">Email Address</label>
                            <input type="email" name="email" class="w-full border rounded-lg p-3"
                                value="{{ old('email', $aRow->email ?? '') }}" placeholder="Enter email">
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="">Assign Manager</label>
                        <select name="manager_id" class="w-full border rounded-lg p-3 bg-white">
                            <option value="">-- No Manager Assigned --</option>
                            @foreach($managers as $manager)
                                <option value="{{ $manager->id }}" {{ (old('manager_id', $aRow->manager_id ?? '') == $manager->id) ? 'selected' : '' }}>
                                    {{ $manager->name }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 border-t pt-4 mt-4">
                        <div class="mb-4">
                            <label class="">
                                Password 
                            </label>
                            <input type="password" name="password" class="w-full border rounded-lg p-3"
                                placeholder="Enter password">
                        </div>

                        <div class="mb-4">
                            <label class="">Confirm Password</label>
                            <input type="password" name="password_confirmation" class="w-full border rounded-lg p-3"
                                placeholder="Re-type password">
                        </div>
                    </div>

                    <div class="mt-6">
                        <button type="submit"
                            class="btn btn btn-success"> Submit
                        </button>
                    </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
