<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 container">
                    <div class="row">
                        <h2 class="text-xl font-semibold">Approval Requests</h2>
                    </div>
                    <br/>
                    <div class="row table-responsive">
                        <table class="table">
                            <thead>
                              <tr>
                                <th scope="col">#</th>
                                <th scope="col">Store Name</th>
                                <th scope="col">Email</th>
                                <th scope="col">Arabic Name</th>
                                <th scope="col">Country</th>
                                <th scope="col">Region</th>
                                <th scope="col">Icon</th>
                                <th scope="col">Created At</th>
                                <th scope="col">Actions</th>
                              </tr>
                            </thead>
                            <tbody>
                              @foreach ($stores as $store)
                                <tr>
                                    <th scope="row">{{$store->id}}</th>
                                    <td>{{$store->store_name}}</td>
                                    <td>{{$store->userd->email}}</td>
                                    <td>{{$store->store_ar_name}}</td>
                                    <td>{{$store->userd->countryd->title}}</td>
                                    <td>{{$store->userd->regiond ? $store->userd->regiond->title : 'No Region'}}</td>
                                    <td>
                                        <div style="border-radius:50%; width:35px; height:35px; overflow:hidden; object-fit: cover; object-position:center;">
                                            <img src="{{ asset($store->store_icon) }}" style="width:100%; height:100%;"/>
                                        </div>
                                    </td>
                                    <td>{{$store->created_at}}</td>
                                    <td>
                                        <a class="btn btn-default" href="{{ route('approve',['id'=>$store->id]) }}">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="green" class="bi bi-check-circle-fill" viewBox="0 0 16 16">
                                                <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z"/>
                                            </svg>
                                        </a>
                                    </td>
                                </tr>
                              @endforeach
                            </tbody>
                          </table>
                          {{ $stores->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
