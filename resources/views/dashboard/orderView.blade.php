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
      <!-- Pizza List -->
      <div class="col-md-6 col-12 mb-4">
        <div class="card shadow-sm">
          <div class="card-body">
            <h3 class="card-title">Available Pizzas</h3>
            <div id="pizzaList" class="list-group">
              <div class="list-group-item d-flex align-items-center pizza-card" data-pizza="Margherita Extra Cheese" data-price="100">
                <img src="{{ asset('frontend/images/pizza-1.jpg') }}" alt="Margherita Extra Cheese" class="pizza-image me-3">
                <span>Margherita Extra Cheese</span>
                <span class="ms-auto text-dark">₹100</span>
              </div>
              <div class="list-group-item d-flex align-items-center pizza-card" data-pizza="Pepperoni Special" data-price="150">
                <img src="{{ asset('frontend/images/pizza-2.jpg') }}" alt="Pepperoni Special" class="pizza-image me-3">
                <span>Pepperoni Special</span>
                <span class="ms-auto text-dark">₹150</span>
              </div>
              <div class="list-group-item d-flex align-items-center pizza-card" data-pizza="Veggie Supreme Delight" data-price="120">
                <img src="{{ asset('frontend/images/pizza-3.jpg') }}" alt="Veggie Supreme Delight" class="pizza-image me-3">
                <span>Veggie Supreme Delight</span>
                <span class="ms-auto text-dark">₹120</span>
              </div>
              <div class="list-group-item d-flex align-items-center pizza-card" data-pizza="Cheese Burst Deluxe" data-price="180">
                <img src="{{ asset('frontend/images/pizza-4.jpg') }}" alt="Cheese Burst Deluxe" class="pizza-image me-3">
                <span>Cheese Burst Deluxe</span>
                <span class="ms-auto text-dark">₹180</span>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Cart -->
      <div class="col-md-6 col-12 mb-4">
        <div class="card shadow-sm">
          <div class="card-body">
            <h3 class="card-title">Cart</h3>
            <form id="cartForm" method="POST" action="{{ route('create.order') }}">
              @csrf
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

      const pizzaName = card.getAttribute('data-pizza');
      const pizzaPrice = parseInt(card.getAttribute('data-price'));

      if (!cartData[pizzaName]) {
        cartData[pizzaName] = { count: 1, price: pizzaPrice };
        const listItem = document.createElement('li');
        listItem.className = 'list-group-item cart-item';
        listItem.setAttribute('data-pizza', pizzaName);
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
        cartData[pizzaName].count++;
        updateCartItem(pizzaName);
      }
      updateGrandTotal();
      updateHiddenInputs();
    });

    cart.addEventListener('click', (event) => {
      const listItem = event.target.closest('.list-group-item');
      if (!listItem) return;

      const pizzaName = listItem.getAttribute('data-pizza');
      if (event.target.classList.contains('increase-btn')) {
        cartData[pizzaName].count++;
      } else if (event.target.classList.contains('decrease-btn') && cartData[pizzaName].count > 1) {
        cartData[pizzaName].count--;
      } else if (event.target.classList.contains('remove-btn')) {
        delete cartData[pizzaName];
        listItem.remove();
      }

      if (cartData[pizzaName]) updateCartItem(pizzaName);
      updateGrandTotal();
      updateHiddenInputs();
    });

    function updateHiddenInputs() {
      hiddenInputs.innerHTML = '';
      Object.keys(cartData).forEach((pizzaName) => {
        const inputName = document.createElement('input');
        inputName.type = 'hidden';
        inputName.name = `cart[${pizzaName}][name]`;
        inputName.value = pizzaName;

        const inputCount = document.createElement('input');
        inputCount.type = 'hidden';
        inputCount.name = `cart[${pizzaName}][count]`;
        inputCount.value = cartData[pizzaName].count;

        const inputPrice = document.createElement('input');
        inputPrice.type = 'hidden';
        inputPrice.name = `cart[${pizzaName}][price]`;
        inputPrice.value = cartData[pizzaName].price;

        hiddenInputs.appendChild(inputName);
        hiddenInputs.appendChild(inputCount);
        hiddenInputs.appendChild(inputPrice);
      });
    }

    function updateCartItem(pizzaName) {
      const listItem = cart.querySelector(`[data-pizza="${pizzaName}"]`);
      const countBadge = listItem.querySelector('.badge');
      const itemTotalElement = listItem.querySelector('.item-total');
      countBadge.textContent = cartData[pizzaName].count;
      itemTotalElement.textContent = cartData[pizzaName].count * cartData[pizzaName].price;
    }

    function updateGrandTotal() {
      const grandTotal = Object.values(cartData).reduce((sum, item) => sum + item.count * item.price, 0);
      grandTotalDisplay.value = grandTotal;
      grandTotalHidden.value = grandTotal;
    }

    document.getElementById('cancelOrder').addEventListener('click', () => {
      alert('Order canceled!');
      clearCart();
    });

    function clearCart() {
      cart.innerHTML = '';
      Object.keys(cartData).forEach((key) => delete cartData[key]);
      updateGrandTotal();
      updateHiddenInputs();
    }
  </script>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
@endsection
