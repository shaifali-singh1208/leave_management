<x-app-layout>
<x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Manage Leave types') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-gray-50">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow sm:rounded-lg p-6">
                
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-lg font-medium text-gray-900">Leave Types </h3>
                    <a href="{{ route('admin.leave-type.create') }}" class="btn btn-primary btn-sm px-4">
                        <i class="fa fa-plus me-1"></i> Add Leave Type
                    </a>
                </div>

                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show">
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <div class="table-responsive">
                    <table class="table table-hover table-bordered mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th style="width: 80px" class="text-center">S.No</th>
                                <th>Leave Type Name</th>
                                <th class="text-center">Yearly Entitlement</th>
                                <th>Created Date</th>
                                <th style="width: 200px" class="text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($aRows as $key => $item)
                                <tr class="align-middle">
                                    <td class="text-center">{{ ++$key }}</td>
                                    <td>
                                        {{ $item->name }}
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-info text-dark">{{ $item->entitlement_days }} Days</span>
                                    </td>
                                    <td class="text-muted small">{{ $item->created_at->format('d M Y') }}</td>
                                    <td class="text-center">
                                        <div class="d-flex justify-content-center gap-2">
                                            <a href="{{ route('admin.leave-type.edit', $item->id) }}" class="btn btn-primary btn-sm">
                                                <i class="fa fa-edit"></i> Edit
                                            </a>
                                            
                                            <form id="delete-form-{{ $item->id }}"
                                                action="{{ route('admin.leave-type.destroy', $item->id) }}"
                                                method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button"
                                                    class="btn btn-danger btn-sm"
                                                    onclick="confirmDelete({{ $item->id }})">
                                                    <i class="fa fa-trash"></i> Delete
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-5 text-muted">No leave types found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script>
        function confirmDelete(id) {
            if (confirm("Are you sure you want to delete this leave type?")) {
                document.getElementById('delete-form' + '-' + id).submit();
            }
        }
    </script>
</x-app-layout>
