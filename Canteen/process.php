<?php
require 'config.php';
require_once 'data.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.php');
    exit;
}

$name = trim($_POST['customer_name'] ?? '');
$type = $_POST['customer_type'] ?? '';
$budget = filter_var($_POST['budget'] ?? null, FILTER_VALIDATE_FLOAT);
$qtyInput = $_POST['qty'] ?? [];
if (!is_array($qtyInput)) {
    $qtyInput = [];
}

$errors = [];

if ($name === '') {
    $errors[] = 'Please enter your name.';
}
if (!array_key_exists($type, $customerTypes)) {
    $errors[] = 'Invalid customer type.';
}
if ($budget === false || $budget === null || $budget < 0 || !is_finite($budget)) {
    $errors[] = 'Please enter a valid budget.';
}

$cart = [];
$totalItems = 0;

foreach ($products as $code => $info) {
    $qty = filter_var($qtyInput[$code] ?? 0, FILTER_VALIDATE_INT);

    if ($qty === false || $qty < 0 || $qty > $maxQty) {
        $errors[] = $info['name'] . ': quantity must be from 0 to ' . $maxQty . '.';
    } elseif ($qty > $info['stock']) {
        $errors[] = $info['name'] . ': only ' . $info['stock'] . ' left in stock.';
    } elseif ($qty > 0) {
        $cart[$code] = $qty;
        $totalItems += $qty;
    }
}

if ($totalItems === 0 && count($errors) === 0) {
    $errors[] = 'Please order at least one item.';
}

if (count($errors) > 0) {
    include 'header.php';
    echo '<h2>Order Problem</h2>';
    foreach ($errors as $message) {
        echo '<p class="error">' . htmlspecialchars($message) . '</p>';
    }
    echo '<a href="index.php">Return to form</a>';
    include 'footer.php';
    exit;
}

switch ($type) {
    case 'student':
        $typeRate = .10;
        $typeNote = 'Student: 10% off meals';
        break;
    case 'employee':
        $typeRate = .05;
        $typeNote = 'Employee: 5% off meals';
        break;
    default:
        $typeRate = 0;
        $typeNote = 'Guest: no customer-type discount';
}

$lines = [];
$subtotal = 0;
$discountTotal = 0;
$mealQty = 0;
$drinkQty = 0;

foreach ($cart as $code => $qty) {
    $item = $products[$code];
    $lineSubtotal = $item['price'] * $qty;

    $categoryLabel = match ($item['category']) {
        'food'  => 'Meal',
        'drink' => 'Drink'
    };

    $rate = 0;
    if ($item['category'] === 'food') {
        $rate += $typeRate;
    }
    if ($specialRate > 0 && ($specialCategory === 'all' || $specialCategory === $item['category'])) {
        $rate += $specialRate;
    }
    if ($qty >= $bulkQty) {
        $rate += $bulkRate;
    }
    if ($rate > $maxRate) {
        $rate = $maxRate;
    }

    $lineDiscount = $lineSubtotal * $rate;

    if ($item['category'] === 'food') {
        $mealQty += $qty;
    } else {
        $drinkQty += $qty;
    }

    $lines[] = [
        'name'     => $item['name'],
        'label'    => $categoryLabel,
        'qty'      => $qty,
        'price'    => $item['price'],
        'rate'     => $rate,
        'discount' => $lineDiscount,
        'total'    => $lineSubtotal - $lineDiscount
    ];

    $subtotal      += $lineSubtotal;
    $discountTotal += $lineDiscount;
}

$pairs = $mealQty < $drinkQty ? $mealQty : $drinkQty;
$comboDiscount = $pairs * $comboValue;

$total = $subtotal - $discountTotal - $comboDiscount;
$balance = round($budget - $total, 2);

include 'header.php';

echo '<h2>Order Receipt</h2>';
echo '<p>Customer: ' . htmlspecialchars($name, ENT_QUOTES, 'UTF-8') . '</p>';
echo '<p>Customer type: ' . htmlspecialchars($customerTypes[$type]) . '</p>';
echo '<p>Date: ' . date('F j, Y - g:i A') . '</p>';

echo '<h3>Order Summary</h3>';
echo '<table>';
echo '<tr><th>Item</th><th>Type</th><th>Qty</th><th>Unit Price</th><th>Discount</th><th>Line Total</th></tr>';
foreach ($lines as $line) {
    echo '<tr>';
    echo '<td>' . htmlspecialchars($line['name']) . '</td>';
    echo '<td>' . $line['label'] . '</td>';
    echo '<td>' . $line['qty'] . '</td>';
    echo '<td>PHP ' . number_format($line['price'], 2) . '</td>';
    echo '<td>' . number_format($line['rate'] * 100, 0) . '%</td>';
    echo '<td>PHP ' . number_format($line['total'], 2) . '</td>';
    echo '</tr>';
}
echo '</table>';

echo '<h3>Items Ordered</h3>';
$unitNo = 1;
foreach ($lines as $line) {
    for ($i = 1; $i <= $line['qty']; $i++) {
        echo '<p>' . $unitNo . '. ' . htmlspecialchars($line['name']) . ' - PHP ' . number_format($line['price'], 2) . '</p>';
        $unitNo++;
    }
}

echo '<h3>Discounts Applied</h3>';
echo '<p class="note">' . $typeNote . '</p>';
if ($specialRate > 0) {
    echo '<p class="note">Daily special (' . $specialName . '): ' . number_format($specialRate * 100, 0) . '% off</p>';
}
if ($pairs > 0) {
    echo '<p class="note">Combo deal: ' . $pairs . ' Meal + Drink pair(s) x PHP ' . number_format($comboValue, 2) . '</p>';
}
echo '<p class="note">Bulk deal: ' . number_format($bulkRate * 100, 0) . '% off when you buy ' . $bulkQty . ' or more of one item</p>';

echo '<p>Subtotal: PHP ' . number_format($subtotal, 2) . '</p>';
echo '<p>Item discounts: PHP ' . number_format($discountTotal, 2) . '</p>';
echo '<p>Combo discount: PHP ' . number_format($comboDiscount, 2) . '</p>';
echo '<h2>Final Bill: PHP ' . number_format($total, 2) . '</h2>';
echo '<p>Budget: PHP ' . number_format($budget, 2) . '</p>';

if ($balance > 0) {
    echo '<p class="success">Budget is sufficient!</p>';
    echo '<p>Balance: PHP ' . number_format($balance, 2) . '</p>';

    echo '<h3>Change Breakdown</h3>';
    echo '<table><tr><th>Bill / Coin</th><th>Pieces</th></tr>';
    $pesos = (int) floor($balance);
    foreach ($denominations as $denom) {
        $pieces = 0;
        while ($pesos >= $denom) {
            $pesos -= $denom;
            $pieces++;
        }
        if ($pieces > 0) {
            echo '<tr><td>PHP ' . $denom . '</td><td>' . $pieces . '</td></tr>';
        }
    }
    $centavos = round($balance - floor($balance), 2);
    if ($centavos > 0) {
        echo '<tr><td>Centavos</td><td>PHP ' . number_format($centavos, 2) . '</td></tr>';
    }
    echo '</table>';
} elseif ($balance == 0) {
    echo '<p class="success">Exact payment. No change needed!</p>';
} else {
    echo '<p class="error">Insufficient budget!</p>';
    echo '<p>Shortage: PHP ' . number_format(-$balance, 2) . '</p>';
}

echo '<h3>Preparation Progress</h3>';
$counter = 1;
while ($counter <= $totalItems) {
    echo '<p>Preparing item ' . $counter . ' of ' . $totalItems . '</p>';
    $counter++;
}

echo '<h3>Order Status</h3>';
echo '<ol>';
$stage = 0;
do {
    echo '<li>' . $stages[$stage] . '</li>';
    $stage++;
} while ($stage < count($stages));
echo '</ol>';

echo '<h3>Stock After This Order</h3>';
echo '<table><tr><th>Item</th><th>Remaining</th><th>Status</th></tr>';
foreach ($products as $code => $info) {
    $ordered = $cart[$code] ?? 0;
    $remaining = $info['stock'] - $ordered;

    if ($remaining === 0) {
        $status = 'Sold out';
    } elseif ($remaining <= 3) {
        $status = 'Low stock - restock soon';
    } else {
        $status = 'OK';
    }

    echo '<tr><td>' . htmlspecialchars($info['name']) . '</td><td>' . $remaining . '</td><td>' . $status . '</td></tr>';
}
echo '</table>';

echo '<p><a href = "index.php">Place another order</a></p>';
include 'footer.php';
?>