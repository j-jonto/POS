<h1><?php echo $title; ?></h1>

<form action="/products/update/<?php echo $id; ?>" method="POST">
    <div class="mb-3">
        <label for="name" class="form-label">Product Name</label>
        <input type="text" class="form-control <?php echo !empty($errors['name']) ? 'is-invalid' : ''; ?>" id="name" name="name" value="<?php echo htmlspecialchars($name); ?>" required>
        <?php if (!empty($errors['name'])): ?>
            <div class="invalid-feedback"><?php echo $errors['name']; ?></div>
        <?php endif; ?>
    </div>
    <div class="mb-3">
        <label for="description" class="form-label">Description</label>
        <textarea class="form-control" id="description" name="description" rows="3"><?php echo htmlspecialchars($description); ?></textarea>
    </div>
    <div class="mb-3">
        <label for="sku" class="form-label">SKU (Stock Keeping Unit)</label>
        <input type="text" class="form-control" id="sku" name="sku" value="<?php echo htmlspecialchars($sku); ?>">
    </div>
    <div class="row">
        <div class="col-md-6 mb-3">
            <label for="purchase_price" class="form-label">Purchase Price</label>
            <input type="number" step="0.01" class="form-control" id="purchase_price" name="purchase_price" value="<?php echo htmlspecialchars($purchase_price); ?>">
        </div>
        <div class="col-md-6 mb-3">
            <label for="sale_price" class="form-label">Sale Price</label>
            <input type="number" step="0.01" class="form-control <?php echo !empty($errors['sale_price']) ? 'is-invalid' : ''; ?>" id="sale_price" name="sale_price" value="<?php echo htmlspecialchars($sale_price); ?>" required>
            <?php if (!empty($errors['sale_price'])): ?>
                <div class="invalid-feedback"><?php echo $errors['sale_price']; ?></div>
            <?php endif; ?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6 mb-3">
            <label for="quantity" class="form-label">Quantity</label>
            <input type="number" class="form-control <?php echo !empty($errors['quantity']) ? 'is-invalid' : ''; ?>" id="quantity" name="quantity" value="<?php echo htmlspecialchars($quantity); ?>" required>
            <?php if (!empty($errors['quantity'])): ?>
                <div class="invalid-feedback"><?php echo $errors['quantity']; ?></div>
            <?php endif; ?>
        </div>
        <div class="col-md-6 mb-3">
            <label for="reorder_level" class="form-label">Reorder Level</label>
            <input type="number" class="form-control" id="reorder_level" name="reorder_level" value="<?php echo htmlspecialchars($reorder_level); ?>">
        </div>
    </div>
    <button type="submit" class="btn btn-primary">Save Changes</button>
    <a href="/products" class="btn btn-secondary">Cancel</a>
</form>
