@extends('dashboard.layout.main_wrapper')
@section('main')
<h2 class="mb-3">Expense List</h2>
<div class="container mt-4">
    <div class="row mb-10">
        <div class="col-md-8">
            <span><strong>Total Expense Amount Is: </strong></span>
            <strong><span id="total_amount_span" class="text-danger"> 0.00</span></strong>
        </div>
    </div>
    
    <table class="table table-bordered table-striped">
        <thead class="table-dark">
            <tr>
                <th>#</th>
                <th>Item Name</th>
                <th>Paid by</th>
                <th>Total Amt</th>
                <th>Pay Mode</th>
                <th>Reason</th>
                <th>Image</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @php
                $total_bill = 0;
            @endphp
            @foreach($list as $key => $FList)
                @php 
                    $total_bill += $FList->amount; // कुल राशि जोड़ते रहेंगे
                @endphp
                <tr>    
                    <td>{{ ($list->currentPage() - 1) * $list->perPage() + $key + 1 }}</td>
                    <td>{{ $FList->item_name }}</td>
                    <td>{{ $FList->name }}</td>
                    <td class="amount">{{ number_format($FList->amount, 2) }}</td> 
                    <td>{{ $FList->mode }}</td>
                    <td>{{ $FList->reason }}</td>
                    <td>
                        @if($FList->expense_image)
                            <a href="{{ asset('dashboard/images/fund-img/' . $FList->expense_image) }}" target="_blank">
                                <img src="{{ asset('dashboard/images/fund-img/' . $FList->expense_image) }}" alt="Expense Image" width="50" height="50">
                            </a>
                        @else
                            <span class="text-muted">No Image</span>
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('customer.viewBill',['action' => 'view', 'id' => $FList->id]) }}" class="btn btn-primary" title="View"><i class="fa-solid fa-eye"></i></a>
                        <a href="{{ route('customer.viewBill',['action' => 'edit', 'id' => $FList->id]) }}" class="btn btn-primary" title="Edit"><i class="fa-solid fa-edit"></i></a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="d-flex justify-content-center mt-3">
        {{ $list->links() }}
    </div>
</div>

<script>
    window.onload = function() {
        var totalAmount = 0;

        document.querySelectorAll('.amount').forEach(function(td) {
            totalAmount += parseFloat(td.innerText.replace(/,/g, '')) || 0;
        });

        document.getElementById('total_amount_span').innerText = totalAmount.toLocaleString('en-IN');
    };

</script>

@endsection
