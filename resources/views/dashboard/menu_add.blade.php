@extends('dashboard.layout.main_wrapper')
@section('main')
<div class="container-fluid">
    <div class="container-fluid">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title fw-semibold mb-4">Add Menu Details</h5>
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('menu_store') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                <div class="mb-3 col-6">
                                    <label for="name" class="form-label">Menu Name</label>
                                    <input type="text" value="{{ old('menu_name') }}" class="form-control @error('menu_name') is-invalid @enderror" id="menu_name" name="menu_name">
                                    @error('menu_name')
                                    <p class="invalid-feedback">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div class="mb-3 col-6">
                                    <label for="price" class="form-label">Price</label>
                                    <input type="number" value="{{ old('price') }}" class="form-control @error('price') is-invalid @enderror" id="price" name="price" min="0">
                                    @error('price')
                                    <p class="invalid-feedback">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                            <div class="row">
                                <div class="mb-3 col-6">
                                    <label for="menu_image" class="form-label">Image</label>
                                    <input type="file" class="form-control @error('menu_image') is-invalid @enderror" id="menu_image" name="menu_image">
                                    @error('menu_image')
                                        <p class="invalid-feedback">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div class="mb-3 col-6">
                                    <label for="status" class="form-label">Type</label>
                                    <select name="status" id="status" class="form-control">
                                        <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Active</option>
                                        <option value="deactive" {{ old('status') == 'deactive' ? 'selected' : '' }}>De-active</option>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-primary">Submit</button>
                                <a href="" class="btn btn-secondary">Cancel</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection