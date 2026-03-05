<?php
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['cart'])) $_SESSION['cart'] = [];

$body = json_decode(file_get_contents('php://input'), true);
$action = $body['action'] ?? '';

$prices = [
  1=>9.50,  2=>11.00, 3=>14.50, 19=>15.50, 5=>13.00,
  6=>12.50, 7=>11.50, 8=>10.50, 9=>8.50,  10=>8.00,
  11=>7.50, 12=>12.00,13=>5.00, 14=>4.50, 15=>4.00,
  16=>4.00, 17=>4.50, 18=>3.50,
];
$emojis = [
  1=>'🦑', 2=>'🍳', 3=>'🥬', 19=>'🍗',  5=>'🌯',
  6=>'🍝', 7=>'🍱', 8=>'🍜', 9=>'🍧',  10=>'🍩',
  11=>'🥭',12=>'🍜',13=>'🥃',14=>'🍺', 15=>'🧃',
  16=>'🧋',17=>'🥥',18=>'🍊',
];

if ($action === 'add') {
  $id    = (int)($body['id'] ?? 0);
  $name  = $body['name'] ?? '';
  $price = $prices[$id] ?? 0;
  $emoji = $emojis[$id] ?? '🍽';

  if ($id && $price) {
    if (isset($_SESSION['cart'][$id])) {
      $_SESSION['cart'][$id]['qty']++;
    } else {
      $_SESSION['cart'][$id] = ['id'=>$id,'name'=>$name,'price'=>$price,'emoji'=>$emoji,'qty'=>1];
    }
    echo json_encode([
      'success'   => true,
      'cartCount' => array_sum(array_column($_SESSION['cart'], 'qty')),
    ]);
  } else {
    echo json_encode(['success'=>false,'error'=>'Invalid item']);
  }

} elseif ($action === 'update') {
  $id    = (int)($body['id'] ?? 0);
  $delta = (int)($body['delta'] ?? 0);

  if (isset($_SESSION['cart'][$id])) {
    $_SESSION['cart'][$id]['qty'] += $delta;
    if ($_SESSION['cart'][$id]['qty'] <= 0) unset($_SESSION['cart'][$id]);
  }
  echo json_encode([
    'success'   => true,
    'cartCount' => array_sum(array_column($_SESSION['cart'], 'qty')),
  ]);


} elseif ($action === 'remove') {
  $id = (int)($body['id'] ?? 0);
  unset($_SESSION['cart'][$id]);
  echo json_encode([
    'success'   => true,
    'cartCount' => array_sum(array_column($_SESSION['cart'], 'qty')),
  ]);

// CHECKOUT
} elseif ($action === 'checkout') {
  if (!empty($_SESSION['cart'])) {
    $orderId = strtoupper(substr(md5(uniqid()), 0, 8));
    $_SESSION['last_order'] = [
      'id'      => $orderId,
      'items'   => $_SESSION['cart'],
      'address' => $body['address'] ?? '',
      'phone'   => $body['phone']   ?? '',
      'time'    => date('h:i A'),
    ];
    $_SESSION['cart'] = [];
    echo json_encode(['success'=>true,'order_id'=>$orderId]);
  } else {
    echo json_encode(['success'=>false,'error'=>'Cart is empty']);
  }

} else {
  echo json_encode(['success'=>false,'error'=>'Unknown action']);
19