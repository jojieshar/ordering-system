<?php
session_start();
if (!isset($_SESSION['cart'])) $_SESSION['cart'] = [];

// ── Menu Data 
$menu = [
  'Starters' => [
    ['id'=>1,'name'=>'Crispy Calamari',    'desc'=>'Lightly battered squid rings, served with garlic aioli, lemon wedge & fresh herbs',           'price'=>9.50, 'emoji'=>'🦑','badge'=>'Popular'],
    ['id'=>2,'name'=>'Sisig',              'desc'=>'Sizzling Filipino chopped pork cheeks & liver, calamansi, chilli, topped with a raw egg',       'price'=>11.00,'emoji'=>'🍳','badge'=>'Spicy'],
  ],
  'Mains' => [
    ['id'=>3,'name'=>'Sarmale (Classic)',  'desc'=>'Romanian stuffed cabbage rolls filled with spiced pork & rice, slow-cooked in tomato broth',    'price'=>14.50,'emoji'=>'🥬','badge'=>'Chef\'s Pick'],
    ['id'=>19,'name'=>'Chicken Adobo',    'desc'=>'Classic Filipino braised chicken in soy sauce, vinegar, garlic, bay leaf & black peppercorns, served with steamed rice', 'price'=>13.50,'emoji'=>'🍗','badge'=>'Filipino'],
    ['id'=>5,'name'=>'Shawarma',           'desc'=>'Slow-roasted spiced chicken, garlic toum, pickles, sumac onions, wrapped in warm pita',         'price'=>13.00,'emoji'=>'🌯','badge'=>'Best Seller'],
    ['id'=>6,'name'=>'Tuna Pasta',         'desc'=>'Al dente spaghetti, flaked tuna, capers, cherry tomatoes, olive oil & fresh parsley',           'price'=>12.50,'emoji'=>'🍝','badge'=>''],
    ['id'=>7,'name'=>'Kimbap',             'desc'=>'Korean seaweed rice rolls with sesame beef, pickled radish, egg & spinach, served with gochujang','price'=>11.50,'emoji'=>'🍱','badge'=>'New'],
    ['id'=>8,'name'=>'Chapagetti',         'desc'=>'Korean black-bean noodles stir-fried with chajang sauce, pork, zucchini & onion',               'price'=>10.50,'emoji'=>'🍜','badge'=>'Popular'],
    ['id'=>12,'name'=>'Pancit Gisado',    'desc'=>'Filipino stir-fried egg noodles with pork, shrimp, cabbage, carrots & soy-citrus sauce',          'price'=>12.00,'emoji'=>'🍜','badge'=>'New'],
  ],
  'Desserts' => [
    ['id'=>9, 'name'=>'Halo-Halo',         'desc'=>'Filipino shaved ice dessert with ube ice cream, leche flan, jellies, beans & evaporated milk',   'price'=>8.50, 'emoji'=>'🍧','badge'=>'Must Try'],
    ['id'=>10,'name'=>'Papanași',          'desc'=>'Romanian fried doughnuts, warm sour cream, house sour cherry jam & powdered sugar',              'price'=>8.00, 'emoji'=>'🍩','badge'=>'New'],
    ['id'=>11,'name'=>'Mango Sticky Rice', 'desc'=>'Thai sweet glutinous rice, fresh Ataulfo mango slices, warm coconut cream & toasted sesame',     'price'=>7.50, 'emoji'=>'🥭','badge'=>''],
  ],
  'Drinks' => [
    ['id'=>13,'name'=>'Țuică',            'desc'=>'Traditional Romanian homemade plum brandy, served chilled in a ceramic shot glass',               'price'=>5.00, 'emoji'=>'🥃','badge'=>'Romanian'],
    ['id'=>14,'name'=>'Bere Ciuc',        'desc'=>'Romania\'s most beloved crisp lager, ice-cold served in a frosted glass with a salted rim',       'price'=>4.50, 'emoji'=>'🍺','badge'=>'Romanian'],
    ['id'=>15,'name'=>'Suc de Cătină',    'desc'=>'Romanian sea buckthorn berry juice — tart, vitamins-packed, served over ice with honey',           'price'=>4.00, 'emoji'=>'🧃','badge'=>'Romanian'],
    ['id'=>16,'name'=>'Sago\'t Gulaman',  'desc'=>'Classic Filipino street drink — brown sugar syrup, sago pearls & gulaman jelly over crushed ice',  'price'=>4.00, 'emoji'=>'🧋','badge'=>'Filipino'],
    ['id'=>17,'name'=>'Buko Juice',       'desc'=>'Fresh young coconut water straight from the shell, served chilled with coconut strips',            'price'=>4.50, 'emoji'=>'🥥','badge'=>'Filipino'],
    ['id'=>18,'name'=>'Calamansi Juice',  'desc'=>'Freshly squeezed Filipino calamansi lime, honey, ice — bright, citrusy & refreshing',             'price'=>3.50, 'emoji'=>'🍊','badge'=>'Filipino'],
  ],
];

$cartCount = array_sum(array_column($_SESSION['cart'], 'qty'));
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Adobo & Sarmale — Order Online</title>
  <link rel="preconnect" href="https://fonts.googleapis.com"/>
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin/>
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,500;0,700;0,900;1,500;1,700&family=Instrument+Sans:wght@400;500;600&family=DM+Mono:wght@400;500&display=swap" rel="stylesheet"/>
  <link rel="stylesheet" href="style.css"/>
</head>
<body>

<header class="site-header">
  <div class="header-inner">
    <div class="logo">
      <span class="logo-icon">🔥</span>
      <div>
        <span class="logo-name">Adobo &amp; Sarmale</span>
        <span class="logo-sub">Tradition meets taste in every bite</span>
      </div>
    </div>
    <nav class="header-meta">
      <span class="status-pill">
        <span class="pulse-dot"></span> Open until 10 PM
      </span>
      <a href="cart.php" class="cart-btn" id="cartBtn">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/></svg>
        Cart
        <span class="cart-badge" id="cartCount"><?= $cartCount ?></span>
      </a>
    </nav>
  </div>
</header>

<section class="hero">
  <div class="hero-content">
    <p class="hero-eyebrow">Fresh · Bold · Crafted</p>
    <h1 class="hero-title">Food worth<br/><em>lingering over</em></h1>
    <p class="hero-sub">Order from our kitchen and get it delivered in 25–40 minutes.</p>
  </div>
  <div class="hero-orbs">
    <div class="orb orb-1"></div>
    <div class="orb orb-2"></div>
    <div class="orb orb-3"></div>
  </div>
</section>

<div class="sticky-tabs" id="stickyTabs">
  <div class="tabs-inner">
    <?php foreach (array_keys($menu) as $i => $cat): ?>
    <button class="tab-btn <?= $i===0?'active':'' ?>" data-target="cat-<?= strtolower($cat) ?>">
      <?= htmlspecialchars($cat) ?>
    </button>
    <?php endforeach; ?>
  </div>
</div>

<main class="menu-main">
  <?php foreach ($menu as $category => $items): ?>
  <section class="menu-section" id="cat-<?= strtolower($category) ?>">
    <h2 class="section-title"><?= htmlspecialchars($category) ?></h2>
    <div class="menu-grid">
      <?php foreach ($items as $item): ?>
      <article class="menu-card" data-id="<?= $item['id'] ?>">
        <div class="card-emoji"><?= $item['emoji'] ?></div>
        <?php if ($item['badge']): ?>
        <span class="card-badge"><?= htmlspecialchars($item['badge']) ?></span>
        <?php endif; ?>
        <div class="card-body">
          <h3 class="card-name"><?= htmlspecialchars($item['name']) ?></h3>
          <p class="card-desc"><?= htmlspecialchars($item['desc']) ?></p>
          <div class="card-footer">
            <span class="card-price">$<?= number_format($item['price'], 2) ?></span>
            <button class="add-btn" 
              data-id="<?= $item['id'] ?>" 
              data-name="<?= htmlspecialchars($item['name']) ?>" 
              data-price="<?= $item['price'] ?>"
              data-emoji="<?= $item['emoji'] ?>">
              <span class="add-icon">+</span>
              <span class="add-text">Add</span>
            </button>
          </div>
        </div>
      </article>
      <?php endforeach; ?>
    </div>
  </section>
  <?php endforeach; ?>
</main>

<div class="toast" id="toast">
  <span class="toast-emoji" id="toastEmoji"></span>
  <span class="toast-msg" id="toastMsg"></span>
</div>

<div class="float-bar" id="floatBar">
  <span class="float-count"><span id="floatCount"><?= $cartCount ?></span> item<?= $cartCount!==1?'s':'' ?></span>
  <a href="cart.php" class="float-cta">View Cart →</a>
</div>

<footer class="site-footer">
  <p>© <?= date('Y') ?> Adobo &amp; Sarmale. All rights reserved. Made with ❤️ and PHP.</p>
</footer>

<script src="app.js"></script>
</body>
</html>