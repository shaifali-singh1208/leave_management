  @inject('Userobj', 'App\Models\User');
  @php
      $page_title = $aRow ? 'Update Leave Type' : 'Create Leave Type';
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
<div class="row justify-content-center">
          <div class="col-6">
              <div class="py-10 bg-gray-100 min-h-screen">
                  <div class="max-w-3xl mx-auto">

                      <div class="bg-white  rounded-xl overflow-hidden">
                          <div class="px-6 py-4 border-b bg-gray-50">
                              <h2 class="font-semibold text-2xl text-gray-800 leading-tight">
                                  {{ $page_title }}
                              </h2>
                          </div>


                          <div class="p-6">
                              @if ($aRow)
                                  <form action="{{ route('admin.leave-type.update', $aRow->id) }}" method="POST"
                                      enctype="multipart/form-data">
                                      @method('PATCH')
                                  @else
                                      <form action="{{ route('admin.leave-type.store') }}" method="POST"
                                          enctype="multipart/form-data">
                              @endif

                              @csrf

                              <div class="mb-4">
                                  <label class="block font-medium mb-1">Leave Type Name</label>
                                  <input type="text" name="name" class="form-control"
                                      value="{{ old('name', $aRow->name ?? '') }}">
                              </div>
                              @error('name')
                                  <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                              @enderror

                              <div class="mb-4">
                                  <label class="block font-medium mb-1">Total Days</label>
                                  <input type="number" name="entitlement_days" class="form-control"
                                      value="{{ old('entitlement_days', $aRow->entitlement_days ?? '') }}">
                              </div>
                              @error('entitlement_days')
                                  <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                              @enderror

                              <div class="mt-6">
                                  <button type="submit"
                                      class="btn btn-success ">
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
