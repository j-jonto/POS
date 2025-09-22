<div class="d-flex justify-content-between align-items-center mb-3">
    <h1><?php echo $title; ?></h1>
    <a href="/products/create" class="btn btn-primary">Add New Product</a>
</div>

<?php if (empty($products)): ?>
    <p>No products found. <a href="/products/create">Add one now</a>.</p>
<?php else: ?>
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Name</th>
                <th>SKU</th>
                <th>Sale Price</th>
                <th>Quantity</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($products as $product): ?>
                <tr>
                    <td><?php echo htmlspecialchars($product['name']); ?></td>
                    <td><?php echo htmlspecialchars($product['sku']); ?></td>
                    <td><?php echo htmlspecialchars($product['sale_price']); ?></td>
                    <td><?php echo htmlspecialchars($product['quantity']); ?></td>
                    <td>
                        <a href="/products/edit/<?php echo $product['id']; ?>" class="btn btn-sm btn-secondary">Edit</a>
                        <form action="/products/destroy/<?php echo $product['id']; ?>" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this product?');">
                            <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>
