<?php
$hour = (int) date('G');

if ($hour < 12) {
    $greeting = 'Good morning';
} elseif ($hour < 18) {
    $greeting = 'Good afternoon';
} else {
    $greeting = 'Good evening';
}

$isOpen = ($hour >= 7 && $hour < 20);
$statusText = $isOpen ? 'OPEN' : 'CLOSED';
$statusClass = $isOpen ? 'success' : 'error';
?>

<p><strong><?= $greeting ?>!</strong> The canteen is currently
    <strong class = "<?= $statusClass ?>"><?= $statusText ?></strong>
    (hours: 7:00 AM - 8:00 PM). Today is <?= date('l, F j, Y') ?>.</p>