<?php
require_once 'data.php';
?>
<h2 id = "menu">Available Menu</h2>

<table>
    <tr>
        <th>Code</th>
        <th>Product</th>
        <th>Type</th>
        <th>Price</th>
        <th>Availability</th>
    </tr>
<?php foreach ($products as $code => $info) {

    if ($info['stock'] === 0) {
        $availability = 'Sold out';
    } elseif ($info['stock'] <= 5) {
        $availability = 'Low stock (' . $info['stock'] . ' left)';
    } else {
        $availability = 'Available';
    }

    $typeLabel = $info['category'] === 'food' ? 'Meal' : 'Drink';
?>
    <tr>
        <td><?= $code ?></td>
        <td><?= htmlspecialchars($info['name']) ?></td>
        <td><?= $typeLabel ?></td>
        <td>₱<?= number_format($info['price'], 2) ?></td>
        <td><?= $availability ?></td>
    </tr>
<?php } ?>
</table>