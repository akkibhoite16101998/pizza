@extends('dashboard.layout.main_wrapper')
@section('main')
  <style>
    .pizza-card {
      cursor: pointer;
      transition: transform 0.3s;
    }
    .pizza-card:hover {
      transform: scale(1.05);
    }
    .pizza-image {
      width: 50px;
      height: 50px;
      object-fit: cover;
    }
    .grand-total {
      font-weight: bold;
      font-size: 1.2rem;
    }
    .cart-item {
      display: flex;
      align-items: center;
      justify-content: space-between;
      flex-wrap: wrap;
    }
    .cart-item span.pizza-name {
      flex: 2;
      white-space: nowrap;
      overflow: hidden;
      text-overflow: ellipsis;
    }
    .cart-item .btn-group {
      flex: 1;
      justify-content: center;
    }
    .cart-item .price {
      flex: 1;
      text-align: right;
      white-space: nowrap;
    }
    .remove-btn {
      width: 70px;
    }
  </style>

  <div class="container mt-5">
    <h1 class="text-center mb-4">Pizza Menu</h1>
    <div class="row">
      <div class="col-md-6 col-12 mb-4">
        <div class="card shadow-sm">
          <div class="card-body">
            <h3 class="card-title">Available Pizzas</h3>
            <div id="pizzaList" class="list-group">
              @foreach($data as $menu)
              <div class="list-group-item d-flex align-items-center pizza-card" 
                   data-id="{{ $menu->id }}" 
                   data-pizza="{{ $menu->name }}" 
                   data-price="{{ $menu->price }}">
                   @php
                    $imagePath = public_path('dashboard/images/menu-img/' . $menu->image);
                  @endphp
                <img src="{{ !empty($menu->image) && file_exists($imagePath) ? asset('dashboard/images/menu-img/' . $menu->image) : asset('frontend/images/pizza-1.jpg') }}" alt="{{ $menu->name }}" class="pizza-image me-3">
                <span><b>{{ $menu->name }}</b></span>
                <span class="ms-auto text-dark">{{ $menu->price }} </span>
              </div>
              @endforeach
            </div>
          </div>
        </div>
      </div>

      <div class="col-md-6 col-12 mb-4">
        <div class="card shadow-sm">
          <div class="card-body">
            <h3 class="card-title">Cart</h3>
            <form id="cartForm" method="POST" action="{{ route('create.order') }}">
              @csrf
              
              <!-- Phone Number & Payment Mode in One Row -->
              <div class="row mb-3">
                <div class="col-7">
                  <label for="phone">Phone Number:</label>
                  <input type="tel" id="phone" name="phone" class="form-control"  placeholder="Enter phone number" maxlength="10" pattern="\d{10}">
                </div>
                <div class="col-5">
                  <label for="payMode">Payment Mode:</label>
                  <select id="payMode" name="payMode" class="form-control" required>
                    <option value="online">Online Payment</option>
                    <option value="cash">Cash</option>
                  </select>
                </div>
              </div>

              <ul id="cart" class="list-group mb-3"></ul>
              
              <div class="d-flex justify-content-between align-items-center">
                <span class="grand-total">Grand Total: ₹</span>
                <input type="text" id="grandTotalDisplay" class="form-control d-inline-block" style="width: 100px;" value="0" readonly>
                <input type="hidden" id="grandTotal" name="grandTotal" value="0">
                <div>
                  <button type="submit" id="saveOrder" class="btn btn-success me-2">Save</button>
                  <button type="button" id="cancelOrder" class="btn btn-danger">Cancel</button>
                </div>
              </div>
              <div id="hiddenInputs"></div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>

  <script>
    const pizzaList = document.getElementById('pizzaList');
    const cart = document.getElementById('cart');
    const grandTotalDisplay = document.getElementById('grandTotalDisplay');
    const grandTotalHidden = document.getElementById('grandTotal');
    const hiddenInputs = document.getElementById('hiddenInputs');
    const cartData = {};

    pizzaList.addEventListener('click', (event) => {
      const card = event.target.closest('.pizza-card');
      if (!card) return;

      const pizzaId = card.getAttribute('data-id'); 
      const pizzaName = card.getAttribute('data-pizza');
      const pizzaPrice = parseInt(card.getAttribute('data-price'));

      if (!cartData[pizzaId]) {
        cartData[pizzaId] = { id: pizzaId, name: pizzaName, count: 1, price: pizzaPrice };

        const listItem = document.createElement('li');
        listItem.className = 'list-group-item cart-item';
        listItem.setAttribute('data-id', pizzaId);
        listItem.innerHTML = `
          <span class="pizza-name">${pizzaName}</span>
          <div class="btn-group">
            <button type="button" class="btn btn-sm btn-outline-secondary decrease-btn">-</button>
            <span class="badge bg-primary rounded-pill mx-2">1</span>
            <button type="button" class="btn btn-sm btn-outline-secondary increase-btn">+</button>
            <button type="button" class="btn btn-sm btn-outline-danger remove-btn ms-2">Remove</button>
          </div>
          <span class="price text-success">₹<span class="item-total">${pizzaPrice}</span></span>
        `;
        cart.appendChild(listItem);
      } else {
        cartData[pizzaId].count++;
        updateCartItem(pizzaId);
      }
      updateGrandTotal();
      updateHiddenInputs();
    });

    cart.addEventListener('click', (event) => {
      const listItem = event.target.closest('.list-group-item');
      if (!listItem) return;

      const pizzaId = listItem.getAttribute('data-id');
      if (event.target.classList.contains('increase-btn')) {
        cartData[pizzaId].count++;
      } else if (event.target.classList.contains('decrease-btn') && cartData[pizzaId].count > 1) {
        cartData[pizzaId].count--;
      } else if (event.target.classList.contains('remove-btn')) {
        delete cartData[pizzaId];
        listItem.remove();
      }

      if (cartData[pizzaId]) updateCartItem(pizzaId);
      updateGrandTotal();
      updateHiddenInputs();
    });

    function updateHiddenInputs() {
      hiddenInputs.innerHTML = '';
      Object.values(cartData).forEach(({ id, name, count, price }) => {
        hiddenInputs.innerHTML += `
          <input type="hidden" name="cart[${id}][id]" value="${id}">
          <input type="hidden" name="cart[${id}][name]" value="${name}">
          <input type="hidden" name="cart[${id}][count]" value="${count}">
          <input type="hidden" name="cart[${id}][price]" value="${price}">
        `;
      });
    }

    function updateCartItem(pizzaId) {
      const listItem = cart.querySelector(`[data-id="${pizzaId}"]`);
      listItem.querySelector('.badge').textContent = cartData[pizzaId].count;
      listItem.querySelector('.item-total').textContent = cartData[pizzaId].count * cartData[pizzaId].price;
    }

    function updateGrandTotal() {
      const grandTotal = Object.values(cartData).reduce((sum, item) => sum + item.count * item.price, 0);
      grandTotalDisplay.value = grandTotal;
      grandTotalHidden.value = grandTotal;
    }
  </script>
@endsection
