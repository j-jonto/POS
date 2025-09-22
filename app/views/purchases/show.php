<h1><?php echo $title; ?></h1>

<div class="card">
    <div class="card-header">
        Purchase Details
    </div>
    <div class="card-body">
        <p><strong>Supplier:</strong> <?php echo htmlspecialchars($purchase['supplier_name']); ?></p>
        <p><strong>Date:</strong> <?php echo date('Y-m-d H:i', strtotime($purchase['created_at'])); ?></p>
        <p><strong>Total Amount:</strong> <?php echo htmlspecialchars(number_format($purchase['total_amount'], 2)); ?></p>
    </div>
</div>

<h3 class="mt-4">Items Purchased</h3>
<table class="table table-striped">
    <thead>
        <tr>
            <th>Product</th>
            <th>Quantity</th>
            <th>Unit Price</th>
            <th>Total</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($purchase['items'] as $item): ?>
            <tr>
                <td><?php echo htmlspecialchars($item['product_name']); ?></td>
                <td><?php echo htmlspecialchars($item['quantity']); ?></td>
                <td><?php echo htmlspecialchars(number_format($item['price'], 2)); ?></td>
                <td><?php echo htmlspecialchars(number_format($item['total'], 2)); ?></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<a href="/purchases" class="btn btn-secondary mt-3">Back to Purchases List</a>
