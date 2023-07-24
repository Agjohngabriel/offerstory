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
                        <div class="col">
                            <h2 class="text-xl font-semibold">Approval Requests</h2>
                        </div>
                        <div class="col">

                        </div>
                        <div class="col">

                        </div>
                        <div class="col">
                            <form method="GET">
                                <div class="input-group mb-3">
                                    <input type="text" value="{{ $search }}" class="form-control"
                                        name="search" placeholder="Search here..." aria-label="Search"
                                        aria-describedby="button-addon2">
                                    <button class="btn btn-outline-secondary" type="submit"
                                        id="button-addon2">Search</button>
                                </div>
                            </form>
                        </div>
                    </div>
                    <br />
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
                                        <th scope="row">{{ $store->id }}</th>
                                        <td>{{ $store->store_name }}</td>
                                        <td>{{ $store->userd->email }}</td>
                                        <td>{{ $store->store_ar_name }}</td>
                                        <td>{{ $store->userd->countryd->title }}</td>
                                        <td>{{ $store->userd->regiond ? $store->userd->regiond->title : 'No Region' }}
                                        </td>
                                        <td>
                                            <div
                                                style="border-radius:50%; width:35px; height:35px; overflow:hidden; object-fit: cover; object-position:center;">
                                                @if ($store->store_icon)
                                                    <img src="{{ asset($store->store_icon) }}"
                                                        style="width:100%; height:100%;" />
                                                @else
                                                    <img src="https://static.wikia.nocookie.net/two-piecerp/images/5/52/Noimg.png/revision/latest?cb=20210705071141"
                                                        style="width:100%; height:100%;" />
                                                @endif
                                            </div>
                                        </td>
                                        <td>{{ $store->created_at }}</td>
                                        <td>
                                            <a class="btn btn-default"
                                                href="{{ route('disapprove', ['id' => $store->id]) }}">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                    fill="red" class="bi bi-x-circle-fill" viewBox="0 0 16 16">
                                                    <path
                                                        d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zM5.354 4.646a.5.5 0 1 0-.708.708L7.293 8l-2.647 2.646a.5.5 0 0 0 .708.708L8 8.707l2.646 2.647a.5.5 0 0 0 .708-.708L8.707 8l2.647-2.646a.5.5 0 0 0-.708-.708L8 7.293 5.354 4.646z" />
                                                </svg>
                                            </a>
                                            <a class="btn btn-default" href="{{ route('delete', ['id' => $store->id]) }}">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                    fill="currentColor" class="bi bi-trash3-fill" viewBox="0 0 16 16">
                                                    <path
                                                        d="M11 1.5v1h3.5a.5.5 0 0 1 0 1h-.538l-.853 10.66A2 2 0 0 1 11.115 16h-6.23a2 2 0 0 1-1.994-1.84L2.038 3.5H1.5a.5.5 0 0 1 0-1H5v-1A1.5 1.5 0 0 1 6.5 0h3A1.5 1.5 0 0 1 11 1.5Zm-5 0v1h4v-1a.5.5 0 0 0-.5-.5h-3a.5.5 0 0 0-.5.5ZM4.5 5.029l.5 8.5a.5.5 0 1 0 .998-.06l-.5-8.5a.5.5 0 1 0-.998.06Zm6.53-.528a.5.5 0 0 0-.528.47l-.5 8.5a.5.5 0 0 0 .998.058l.5-8.5a.5.5 0 0 0-.47-.528ZM8 4.5a.5.5 0 0 0-.5.5v8.5a.5.5 0 0 0 1 0V5a.5.5 0 0 0-.5-.5Z" />
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
