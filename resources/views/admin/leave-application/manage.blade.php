@php
    $page_title = $aRow ? 'Update Leave Application' : 'Apply Leave';
@endphp

<x-app-layout>

    @if ($errors->any())
        <div class="bg-red-100 text-red-700 p-3 rounded mb-4">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="row">
        <div class="col-6">
            <div class="py-10 bg-gray-100 min-h-screen">
                <div class="max-w-3xl mx-auto">

                    <div class="bg-white rounded-xl overflow-hidden shadow">
                        
                        <div class="px-6 py-4 border-b bg-gray-50">
                            <h2 class="font-semibold text-2xl text-gray-800 leading-tight">
                                {{ $page_title }}
                            </h2>
                        </div>

                        <div class="p-6">

                            @if ($aRow)
                                <form action="{{ route('employee.leave.update', $aRow->id) }}" method="POST">
                                    @method('PATCH')
                            @else
                                <form action="{{ route('employee.leave.store') }}" method="POST">
                            @endif

                            @csrf

                            {{-- Leave Type --}}
                            <div class="mb-4">
                                <label class="">Leave Type</label>
                                <select name="leave_type_id" class="w-full border rounded-lg p-3">
                                    <option value="">Select Leave Type</option>
                                    @foreach ($leaveTypes as $type)
                                        <option value="{{ $type->id }}"
                                            {{ old('leave_type_id', $aRow->leave_type_id ?? '') == $type->id ? 'selected' : '' }}>
                                            {{ $type->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('leave_type_id')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Start Date --}}
                            <div class="mb-4">
                                <label class="">Start Date</label>
                                <input type="date" name="start_date"
                                    class="w-full border rounded-lg p-3"
                                    value="{{ old('start_date', $aRow->start_date ?? '') }}">
                                @error('start_date')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label class="">End Date</label>
                                <input type="date" name="end_date"
                                    class="w-full border rounded-lg p-3"
                                    value="{{ old('end_date', $aRow->end_date ?? '') }}">
                                @error('end_date')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label class="">Reason</label>
                                <textarea name="reason" rows="4"
                                    class="w-full border rounded-lg p-3">{{ old('reason', $aRow->reason ?? '') }}</textarea>
                                @error('reason')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="mt-6">
                                <button type="submit"
                                    class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 rounded-lg">
                                    Submit
                                </button>
                            </div>

                            </form>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

</x-app-layout>