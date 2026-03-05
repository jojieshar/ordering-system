<?php
session_start();
$order = $_SESSION['last_order'] ?? null;
if (!$order) {
  header('Location: index.php');
  exit;
}
$subtotal = 0;
foreach ($order['items'] as $i) $subtotal += $i['price'] * $i['qty'];
$total = $subtotal * 1.08 + 3.99;
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Order Confirmed — Ember &amp; Salt</title>
  <link rel="preconnect" href="https://fonts.googleapis.com"/>
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin/>
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,500;0,700;0,900;1,500;1,700&family=Instrument+Sans:wght@400;500;600&family=DM+Mono:wght@400;500&display=swap" rel="stylesheet"/>
  <link rel="stylesheet" href="style.css"/>
</head>
<body class="success-page">

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
  </div>
</header>

<main class="success-main">
  <div class="success-card">
    <div class="check-anim">
      <svg viewBox="0 0 52 52" class="check-svg">
        <circle cx="26" cy="26" r="25" class="check-circle"/>
        <path d="M14 27l8 8 16-16" class="check-path"/>
      </svg>
    </div>

    <h1 class="success-title">Order Confirmed!</h1>
    <p class="success-sub">Your food is being prepared with love. Sit tight!</p>

    <div class="order-meta">
      <div class="meta-pill">
        <span>Order ID</span>
        <strong class="mono">#<?= htmlspecialchars($order['id']) ?></strong>
      </div>
      <div class="meta-pill">
        <span>Placed at</span>
        <strong><?= htmlspecialchars($order['time']) ?></strong>
      </div>
      <div class="meta-pill">
        <span>ETA</span>
        <strong>~30 min</strong>
      </div>
    </div>

    <div class="tracker">
      <div class="track-step active" id="step1">
        <div class="ts-icon">📋</div>
        <span>Order Received</span>
      </div>
      <div class="track-line"></div>
      <div class="track-step" id="step2">
        <div class="ts-icon">👨‍🍳</div>
        <span>Preparing</span>
      </div>
      <div class="track-line"></div>
      <div class="track-step" id="step3">
        <div class="ts-icon">🛵</div>
        <span>On the Way</span>
      </div>
      <div class="track-line"></div>
      <div class="track-step" id="step4">
        <div class="ts-icon">🏠</div>
        <span>Delivered</span>
      </div>
    </div>

    <div class="ordered-items">
      <h3>What you ordered</h3>
      <ul>
        <?php foreach ($order['items'] as $item): ?>
        <li>
          <span><?= $item['emoji'] ?> <?= htmlspecialchars($item['name']) ?> × <?= $item['qty'] ?></span>
          <span>$<?= number_format($item['price'] * $item['qty'], 2) ?></span>
        </li>
        <?php endforeach; ?>
        <li class="oi-total">
          <span>Total paid</span>
          <span>$<?= number_format($total, 2) ?></span>
        </li>
      </ul>
    </div>

    <div class="delivery-address">
      <span>📍 <?= htmlspecialchars($order['address']) ?></span>
    </div>

    <a href="index.php" class="btn-primary" style="display:inline-block;margin-top:2rem">
      Order Again
    </a>
  </div>
</main>

<footer class="site-footer">
  <p>© <?= date('Y') ?> Ember &amp; Salt. All rights reserved.</p>
</footer>

<script>
// Animate tracker steps
const steps = ['step1','step2','step3','step4'];
let current = 0;
function advanceTracker() {
  if (current < steps.length) {
    document.getElementById(steps[current]).classList.add('active');
    current++;
    if (current < steps.length) setTimeout(advanceTracker, 2500);
  }
}
setTimeout(advanceTracker, 800);
</script>
</body>
</html>