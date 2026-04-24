@inject('Userobj', 'App\Models\User')
@php
    $page_title = $aRow ? 'Update Manager' : 'Create Manager';
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
                    <a href="{{ route('admin.manager.index') }}" class="text-blue-600 hover:underline">Back to List</a>
                </div>

                <div class="p-6">
                    @if ($aRow)
                        <form action="{{ route('admin.manager.update', $aRow->id) }}" method="POST">
                            @method('PATCH')
                    @else
                        <form action="{{ route('admin.manager.store') }}" method="POST">
                    @endif

                    @csrf

                    <div class="mb-4">
                        <label class="block font-medium mb-1 @error('name') text-red-500 @enderror">Full Name</label>
                        <input type="text" name="name" class="w-full border rounded-lg p-3 @error('name') border-red-500 @enderror"
                            value="{{ old('name', $aRow->name ?? '') }}" placeholder="Enter full name">
                        @error('name')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block font-medium mb-1 @error('email') text-red-500 @enderror">Email Address</label>
                        <input type="email" name="email" class="w-full border rounded-lg p-3 @error('email') border-red-500 @enderror"
                            value="{{ old('email', $aRow->email ?? '') }}" placeholder="Enter email address">
                        @error('email')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="mb-4">
                            <label class="block font-medium mb-1 @error('password') text-red-500 @enderror">
                                Password {{ $aRow ? '(Leave blank to keep current)' : '' }}
                            </label>
                            <input type="password" name="password" class="w-full border rounded-lg p-3 @error('password') border-red-500 @enderror"
                                placeholder="Minimum 8 characters">
                            @error('password')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label class="block font-medium mb-1">Confirm Password</label>
                            <input type="password" name="password_confirmation" class="w-full border rounded-lg p-3"
                                placeholder="Re-type password">
                        </div>
                    </div>

                    <div class="mt-6">
                        <button type="submit"
                            class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 rounded-lg transition duration-200">
                            {{ $aRow ? 'Update Manager' : 'Create Manager' }}
                        </button>
                    </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
