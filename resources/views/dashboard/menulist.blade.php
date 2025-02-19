@extends('dashboard.layout.main_wrapper')
@section('main')
<h2 class="mb-3" >.</h2>
<div class="container mt-4">
    <div class="row" style="margin-top:40px;">
        <div class="col-md-10">
        <h2 class="mb-4">Menu List</h2>
    </div>
    <div class="col-md-2">
        <a href="{{ route('menu_add') }}" class="btn btn-dark m-1">Add Menu</a>
    </div>
    </div>

    <table class="table table-bordered table-striped">
        <thead class="table-dark">
            <tr>
                <th>#</th>
                <th>Name</th>
                <th>Image</th>
                <th>Price</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data as $key => $menuList)
            <tr>
                <td>{{ ($data->currentPage() - 1) * $data->perPage() + $key + 1 }}</td>
                <td>{{ $menuList->name }}</td>
                <td>
                    @if($menuList->image)
                        <img src="{{ asset('dashboard/images/menu-img/' . $menuList->image) }}" alt="Menu Image" width="50" height="50">
                    @else
                        <span class="text-muted">No Image</span>
                    @endif
                </td>

                <td>{{ $menuList->price }}</td>
                <td>
                    <span class="badge 
                        {{ $menuList->status == 'active' ? 'bg-success' : 'bg-danger' }}">
                        {{ ucfirst($menuList->status) }}
                    </span>
                </td>

            </tr>
            @endforeach
        </tbody>
    </table>
    <!-- Pagination Links -->
    <div class="d-flex justify-content-center mt-3">
        {{ $data->links() }}
    </div>
</div>
@endsection