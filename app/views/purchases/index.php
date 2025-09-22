<div class="d-flex justify-content-between align-items-center mb-3">
    <h1><?php echo $title; ?></h1>
    <a href="/purchases/create" class="btn btn-primary">Add New Purchase</a>
</div>

<?php if (empty($purchases)): ?>
    <p>No purchases found. <a href="/purchases/create">Add one now</a>.</p>
<?php else: ?>
    <table class="table table-striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>Supplier</th>
                <th>Total Amount</th>
                <th>Date</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($purchases as $purchase): ?>
                <tr>
                    <td>#<?php echo htmlspecialchars($purchase['id']); ?></td>
                    <td><?php echo htmlspecialchars($purchase['supplier_name']); ?></td>
                    <td><?php echo htmlspecialchars(number_format($purchase['total_amount'], 2)); ?></td>
                    <td><?php echo date('Y-m-d H:i', strtotime($purchase['created_at'])); ?></td>
                    <td>
                        <a href="/purchases/show/<?php echo $purchase['id']; ?>" class="btn btn-sm btn-info">View Details</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>
