<x-app-layout>
<x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Manage Leave types') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-lg font-medium text-gray-900">Leave types List</h3>
                    <a href="{{ route('admin.manager.create') }}" class="btn btn-success rounded-0">
                        <i class="fa fa-plus"></i> Add 
                    </a>
                </div>

                @if(session('success'))
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                        {{ session('success') }}
                    </div>
                @endif


                <div class="table-responsive">
                <table class="projects table table-bordered table-hover table-striped">
                    <thead class="ty-1">
                        <tr>
                            <th style="width:5%">Sn</th>
                            <th style="width:5%">Name</th>
                            <th>Days</th>
                            <th>Date</th>
                            <th style="width:15%">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($aRows as $key => $item)
                            <tr>
                                <td>{{ ++$key }}</td>
                                <td>{{ $item->name }}</td>
                                <td>{{ $item->entitlement_days }}</td>
                                <td>{{ $item->created_at->format('d-m-y') }}</td>

                                <td>
                                    <div><a href="{{ route('admin.leave-type.edit', $item->id) }}"class="btn btn-primary  btn-sm"><i class="fa fa-edit"></i> Edit</a></td></div>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </section>

</x-app-layout>
