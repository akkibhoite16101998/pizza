@extends('dashboard.layout.main_wrapper')
@section('main')
<div class="container-fluid">
    <div class="container-fluid">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title fw-semibold mb-4">Add Business Fund</h5>
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('store_fund') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="row">

                                <div class="mb-3 col-6">
                                    <label for="admin_user" class="form-label">Select User</label>
                                    <select name="admin_user" id="admin_user" class="form-select select2-multiple @error('admin_user') is-invalid @enderror" >
                                        <option value="">Select Name</option>   
                                        @foreach($adminList as $arr)
                                        <option value="{{ $arr->id }}">{{ $arr->name }}</option>
                                        @endforeach
                                    </select>
                                        @error('admin_user')
                                            <p class="invalid-feedback">{{ $message }}</p>
                                        @enderror
                                </div>

                                <div class="mb-3 col-6">
                                    <label for="amount" class="form-label">Amount</label>
                                    <input type="number" value="{{ old('amount') }}" class="form-control @error('amount') is-invalid @enderror" id="amount" name="amount" min="0">
                                    @error('amount')
                                    <p class="invalid-feedback">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="mb-3 col-6">
                                    <label for="pay_mode" class="form-label">Payment Mode</label>
                                    <select name="pay_mode" id="pay_mode" class="form-control">
                                        <option value="UPI" {{ old('pay_mode') == 'UPI' ? 'selected' : '' }}>UPI</option>
                                        <option value="cash" {{ old('pay_mode') == 'cash' ? 'selected' : '' }}>Cash</option>
                                    </select>
                                </div>

                                <div class="mb-3 col-6">
                                    <label for="name" class="form-label">Remark</label>
                                    <input type="text" value="{{ old('add_remark') }}" class="form-control @error('add_remark') is-invalid @enderror" id="add_remark" name="add_remark">
                                    @error('add_remark')
                                    <p class="invalid-feedback">{{ $message }}</p>
                                    @enderror
                                </div>
                                
                            </div>
                            <div class="row">
                                <div class="mb-3 col-6">
                                    <label for="payment_image" class="form-label">Payment Photo Proof</label>
                                    <input type="file" class="form-control @error('payment_image') is-invalid @enderror" id="payment_image" name="payment_image">
                                    @error('payment_image')
                                        <p class="invalid-feedback">{{ $message }}</p>
                                    @enderror
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