<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Manage Employees') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-lg font-medium text-gray-900">Employees List</h3>
                    <a href="{{ route('admin.employee.create') }}" class="btn btn-success rounded-0">
                        <i class="fa fa-plus"></i> Add Employee
                    </a>
                </div>

                @if(session('success'))
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4 border-l-4">
                        {{ session('success') }}
                    </div>
                @endif

                <div class="table-responsive">
                    <table class="table table-bordered table-hover table-striped">
                        <thead class="bg-gray-50">
                            <tr>
                                <th style="width:5%">Sn</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Assigned Manager</th>
                                <th>Date Joined</th>
                                <th style="width:20%">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($aRows as $key => $item)
                                <tr>
                                    <td>{{ ++$key }}</td>
                                    <td>{{ $item->name }}</td>
                                    <td>{{ $item->email }}</td>
                                    <td>
                                        @if($item->manager)
                                            <span class="text-gray-700">{{ $item->manager->name }}</span>
                                        @else
                                            <span class="text-red-500 italic">Not Assigned</span>
                                        @endif
                                    </td>
                                    <td>{{ $item->created_at->format('d-m-Y') }}</td>
                                    <td>
                                        <div class="flex space-x-2">
                                            <a href="{{ route('admin.employee.edit', $item->id) }}" class="btn btn-primary btn-sm">
                                                <i class="fa fa-edit"></i> Edit
                                            </a>
                                            <form action="{{ route('admin.employee.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Are you sure Want To delete this user ?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm">
                                                    <i class="fa fa-trash"></i> Delete
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                            @if($aRows->isEmpty())
                                <tr>
                                    <td colspan="6" class="text-center py-4 text-gray-500">No employees found.</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
