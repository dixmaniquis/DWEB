<?php
require_once 'data.php';
include 'header.php';
include 'menu.php';
?>

<?php
if ($specialRate > 0) {
    echo '<div class = "banner"><strong>Today\'s Special: ' . $specialName . '</strong><br>';
    echo number_format($specialRate * 100, 0) . '% off ';
    echo $specialCategory === 'all' ? 'all items' : ($specialCategory === 'food' ? 'all meals' : 'all drinks');
    echo '</div>';
} else {
    echo '<div class = "banner">' . $specialName . ' - enjoy our regular prices!</div>';
}
?>

<?php include 'menu.php'; ?>

<h2 id = "order">Place Your Order</h2>
<form method = "POST" action = "process.php">
    <label for = "customer_name">Name</label>
    <input id = "customer_name" name = "customer_name" type = "text" required>

    <label for = "customer_type">Customer Type</label>
    <select id = "customer_type" name = "customer_type">

<?php foreach ($customerTypes as $value => $label) { ?>
        <option value="<?= $value ?>"><?= $label ?></option>
<?php } ?>

    </select>

    <h3>How many of each item?</h3>
<?php foreach ($products as $code => $info) {
    $limit = $info['stock'] < $maxQty ? $info['stock'] : $maxQty;
?>
    <label for = "qty_<?= $code ?>">
        <?= htmlspecialchars($info['name']) ?> - ₱<?= number_format($info['price'], 2) ?>
        (max <?= $limit ?>)
    </label>
    <input id = "qty_<?= $code ?>" name = "qty[<?= $code ?>]" type = "number" min="0" max = "<?= $limit ?>" value = "0" required>
<?php } ?>

    <label for = "budget">Budget (PHP)</label>
    <input id = "budget" name = "budget" type = "number" min = "0" step = "0.01" required>

    <button type = "submit">Process Order</button>
</form>

<?php
include 'footer.php';
?>
