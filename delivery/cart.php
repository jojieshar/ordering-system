<?php
session_start();
if (!isset($_SESSION['cart'])) $_SESSION['cart'] = [];

$cart   = $_SESSION['cart'];
$subtotal = 0;
foreach ($cart as $item) $subtotal += $item['price'] * $item['qty'];
$delivery = $subtotal > 0 ? 3.99 : 0;
$tax      = $subtotal * 0.08;
$total    = $subtotal + $delivery + $tax;
$cartCount = array_sum(array_column($cart, 'qty'));
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Your Cart — Ember &amp; Salt</title>
  <link rel="preconnect" href="https://fonts.googleapis.com"/>
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin/>
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,500;0,700;0,900;1,500;1,700&family=Instrument+Sans:wght@400;500;600&family=DM+Mono:wght@400;500&display=swap" rel="stylesheet"/>
  <link rel="stylesheet" href="style.css"/>
</head>
<body class="cart-page">

<header class="site-header">
  <div class="header-inner">
    <div class="logo">
      <a href="index.php" style="display:flex;align-items:center;gap:.6rem;text-decoration:none;color:inherit">
        <span class="logo-icon">🔥</span>
        <div>
          <span class="logo-name">Ember &amp; Salt</span>
          <span class="logo-sub">Kitchen &amp; Bar</span>
        </div>
      </a>
    </div>
    <nav class="header-meta">
      <a href="index.php" class="back-link">← Back to Menu</a>
    </nav>
  </div>
</header>

<main class="cart-main">
  <div class="cart-container">

    <!-- LEFT: Items -->
    <div class="cart-items-col">
      <h1 class="cart-title">Your Order</h1>
      <?php if (empty($cart)): ?>
        <div class="empty-cart">
          <div class="empty-icon">🛒</div>
          <p>Your cart is empty.</p>
          <a href="index.php" class="btn-primary">Browse the Menu</a>
        </div>
      <?php else: ?>
        <ul class="cart-list" id="cartList">
          <?php foreach ($cart as $key => $item): ?>
          <li class="cart-item" data-id="<?= $item['id'] ?>" data-key="<?= htmlspecialchars($key) ?>">
            <span class="ci-emoji"><?= $item['emoji'] ?></span>
            <div class="ci-info">
              <span class="ci-name"><?= htmlspecialchars($item['name']) ?></span>
              <span class="ci-price">$<?= number_format($item['price'], 2) ?></span>
            </div>
            <div class="ci-qty">
              <button class="qty-btn minus" data-id="<?= $item['id'] ?>">−</button>
              <span class="qty-num"><?= $item['qty'] ?></span>
              <button class="qty-btn plus" data-id="<?= $item['id'] ?>">+</button>
            </div>
            <span class="ci-subtotal">$<?= number_format($item['price'] * $item['qty'], 2) ?></span>
            <button class="ci-remove" data-id="<?= $item['id'] ?>">✕</button>
          </li>
          <?php endforeach; ?>
        </ul>

        <div class="special-note">
          <label for="noteInput">Special instructions</label>
          <textarea id="noteInput" placeholder="Allergies, cooking preferences, extra napkins…" rows="3"></textarea>
        </div>
      <?php endif; ?>
    </div>

    <!-- RIGHT: Summary -->
    <?php if (!empty($cart)): ?>
    <div class="cart-summary-col">
      <div class="summary-card">
        <h2 class="summary-title">Order Summary</h2>

        <div class="summary-line">
          <span>Subtotal</span>
          <span id="sumSubtotal">$<?= number_format($subtotal, 2) ?></span>
        </div>
        <div class="summary-line">
          <span>Delivery fee</span>
          <span>$<?= number_format($delivery, 2) ?></span>
        </div>
        <div class="summary-line">
          <span>Tax (8%)</span>
          <span id="sumTax">$<?= number_format($tax, 2) ?></span>
        </div>
        <div class="summary-divider"></div>
        <div class="summary-line total">
          <span>Total</span>
          <span id="sumTotal">$<?= number_format($total, 2) ?></span>
        </div>

        <!-- Delivery Info -->
        <div class="delivery-info">
          <div class="di-row">
            <span>📍</span>
            <input type="text" id="addressInput" placeholder="Enter delivery address…"/>
          </div>
          <div class="di-row">
            <span>📞</span>
            <input type="text" id="phoneInput" placeholder="Phone number"/>
          </div>
        </div>

        <button class="btn-checkout" id="checkoutBtn" onclick="placeOrder()">
          Place Order · $<?= number_format($total, 2) ?>
        </button>
        <p class="eta-note">🕐 Estimated delivery: 25–40 min</p>
      </div>
    </div>
    <?php endif; ?>

  </div>
</main>

<div class="toast" id="toast">
  <span class="toast-emoji" id="toastEmoji"></span>
  <span class="toast-msg" id="toastMsg"></span>
</div>

<footer class="site-footer">
  <p>© <?= date('Y') ?> Ember &amp; Salt. All rights reserved.</p>
</footer>

<script>
// Cart page JS — quantity updates, remove, checkout
document.querySelectorAll('.qty-btn.plus').forEach(btn => {
  btn.addEventListener('click', () => updateQty(btn.dataset.id, 1));
});
document.querySelectorAll('.qty-btn.minus').forEach(btn => {
  btn.addEventListener('click', () => updateQty(btn.dataset.id, -1));
});
document.querySelectorAll('.ci-remove').forEach(btn => {
  btn.addEventListener('click', () => removeItem(btn.dataset.id));
});

function updateQty(id, delta) {
  fetch('api.php', {
    method: 'POST',
    headers: {'Content-Type':'application/json'},
    body: JSON.stringify({action:'update', id:parseInt(id), delta})
  })
  .then(r => r.json())
  .then(data => {
    if (data.success) location.reload();
  });
}

function removeItem(id) {
  const row = document.querySelector(`.cart-item[data-id="${id}"]`);
  if (row) { row.style.opacity='0'; row.style.transform='translateX(40px)'; }
  setTimeout(() => {
    fetch('api.php', {
      method: 'POST',
      headers: {'Content-Type':'application/json'},
      body: JSON.stringify({action:'remove', id:parseInt(id)})
    })
    .then(r => r.json())
    .then(data => { if (data.success) location.reload(); });
  }, 300);
}

function placeOrder() {
  const address = document.getElementById('addressInput').value.trim();
  const phone   = document.getElementById('phoneInput').value.trim();
  if (!address) { showToast('📍','Please enter a delivery address'); return; }
  if (!phone)   { showToast('📞','Please enter your phone number'); return; }

  const btn = document.getElementById('checkoutBtn');
  btn.textContent = 'Placing order…';
  btn.disabled = true;

  fetch('api.php', {
    method: 'POST',
    headers: {'Content-Type':'application/json'},
    body: JSON.stringify({action:'checkout', address, phone})
  })
  .then(r => r.json())
  .then(data => {
    if (data.success) window.location.href = 'success.php?order=' + data.order_id;
  });
}

function showToast(emoji, msg) {
  const t = document.getElementById('toast');
  document.getElementById('toastEmoji').textContent = emoji;
  document.getElementById('toastMsg').textContent   = msg;
  t.classList.add('show');
  setTimeout(() => t.classList.remove('show'), 3000);
}
</script>
</body>
</html>