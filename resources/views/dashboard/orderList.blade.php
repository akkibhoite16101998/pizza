@extends('dashboard.layout.main_wrapper')
@section('main')
<h2 class="mb-3" >.</h2>
<div class="container mt-4">
    <div class="row mb-10">
        <div class="col-md-2">
            <h2 class="mb-4">Order List</h2>
        </div>
        <div class="col-md-8">
            <form method="get" action="{{ route('orderlist') }}" class="d-flex flex-wrap gap-2">
                <div class="col-12 col-md-3">
                    <label for="start_date" class="form-label">Start Date</label>
                    <input type="date" value="{{ $start_date }}" class="form-control" id="start_date" name="start_date" required>
                </div>
                <div class="col-12 col-md-3">
                    <label for="end_date" class="form-label">End Date</label>
                    <input type="date" value="{{ $end_date }}" class="form-control" id="end_date" name="end_date" required>
                </div>
                <div class="col-12 col-md-3">
                    <label for="user_id" class="form-label">User ID</label>
                    <select class="form-select" id="user_id" name="user_id">
                        <option value="" disabled selected>Select User</option>
                        @foreach($userList as $list)
                        <option value="{{ $list->id }}" {{ $userId == $list->id ? 'selected' : '' }} >{{ $list->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-12 col-md-2 align-self-end">
                    <button type="submit" class="btn btn-primary w-100">Submit</button>
                </div>
            </form>
        </div>

        <div class="col-md-2">
            <a href="{{ route('order') }}" class="btn btn-dark m-1">Add Order</a>
        </div>
    </div>
    <div class="row mb-10">
        <div class="col-md-8">
        <span><strong>Bill On {{ \Carbon\Carbon::parse($start_date)->format('d-m-Y') }} &nbsp; TO &nbsp;{{ \Carbon\Carbon::parse($end_date)->format('d-m-Y') }}</strong></span><br>
        <span><strong>Total Bill AMT</span> <span id="total_bill_span">0.0</span> <span>   &nbsp; And &nbsp; Total Receive AMT <span id="paid_bill_span">0.0</span></strong></span>

        </div>
    </div>
    <table class="table table-bordered table-striped">
        <thead class="table-dark">
            <tr>
                <th>#</th>
                <th>Order No</th>
                <th>Quantity</th>
                <th>Total Amt</th>
                <th>Total Receive</th>
                <th>pay mode</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @php
                $total_bill = 0; $paid_bill = 0;
                $final_total_bill = 0; $final_paid_bill = 0;
            @endphp
            @foreach($data as $key => $menuList)
            <tr>
                @php 
                    $total_bill = $menuList->sum('grand_total');
                    $paid_bill = $menuList->sum('paid_amt');

                    $final_total_bill += $total_bill;
                    $final_paid_bill += $paid_bill;

                @endphp
                <td>{{ ($data->currentPage() - 1) * $data->perPage() + $key + 1 }}</td>
                <td>{{ $menuList->id }}</td>
                <td>{{ $menuList->order_items->sum('quantity') }}</td>
                <td>
                    {{ $menuList->order_items->sum('total') }}
                    <input type="hidden" value="{{ $final_total_bill }}" id="total_amt_td">

                </td>
                <td>
                    {{ $menuList->paid_amt }}
                    <input type="hidden" value="{{ $final_paid_bill }}" id="paid_amt_td">
                </td>
                <td>
                    <span class="badge 
                        {{ $menuList->pay_mode == 'online' ? 'bg-success' : 'bg-secondary' }}"> 
                        {{ ucfirst($menuList->pay_mode) }}
                    </span>
                </td>
                <td>
                    <a href="{{ route('customer.viewBill',['action' => 'view', 'id' => $menuList->id]) }}" class="btn btn-primary" title="View"><i class="fa-solid fa-eye"></i> </a>
                    <a href="{{ route('customer.viewBill',['action' => 'edit', 'id' => $menuList->id]) }}" class="btn btn-primary" title="View"><i class="fa-solid fa-edit"></i> </a>
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
<script>
    window.onload = function() {

        var totalAmt = parseFloat($('#total_amt_td').val()) || 0.0;
        var paidAmt = parseFloat($('#paid_amt_td').val()) || 0.0;
        
        document.getElementById('total_bill_span').innerText = totalAmt.toFixed(2);
        document.getElementById('paid_bill_span').innerText = paidAmt.toFixed(2);
    };
</script>


@endsection