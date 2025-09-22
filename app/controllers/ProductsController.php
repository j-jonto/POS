<?php

class ProductsController extends Controller {
    private $productModel;

    public function __construct() {
        // The model() method is from the base Controller class
        $this->productModel = $this->model('Product');
    }

    // Show all products
    public function index() {
        $products = $this->productModel->getAll();
        $data = [
            'title' => 'Products',
            'products' => $products
        ];
        $this->view('products/index', $data);
    }

    // Show form to add a new product
    public function create() {
        $data = [
            'title' => 'Add Product',
            'name' => '',
            'description' => '',
            'sku' => '',
            'purchase_price' => '',
            'sale_price' => '',
            'quantity' => '',
            'reorder_level' => '',
            'errors' => []
        ];
        $this->view('products/create', $data);
    }

    // Store a new product
    public function store() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Sanitize POST data
            $_POST = filter_input_array(INPUT_POST);

            $data = [
                'title' => 'Add Product',
                'name' => trim($_POST['name']),
                'description' => trim($_POST['description']),
                'sku' => trim($_POST['sku']),
                'purchase_price' => trim($_POST['purchase_price']),
                'sale_price' => trim($_POST['sale_price']),
                'quantity' => trim($_POST['quantity']),
                'reorder_level' => trim($_POST['reorder_level']),
                'errors' => []
            ];

            // --- Validation ---
            if (empty($data['name'])) {
                $data['errors']['name'] = 'Please enter a name.';
            } elseif (!$this->productModel->isNameUnique($data['name'])) {
                $data['errors']['name'] = 'Product name already exists.';
            }

            if (empty($data['sale_price'])) {
                $data['errors']['sale_price'] = 'Please enter a sale price.';
            } elseif (!is_numeric($data['sale_price']) || $data['sale_price'] < 0) {
                $data['errors']['sale_price'] = 'Price must be a positive number.';
            }

            if (empty($data['quantity'])) {
                $data['errors']['quantity'] = 'Please enter a quantity.';
            } elseif (!is_numeric($data['quantity']) || $data['quantity'] < 0) {
                $data['errors']['quantity'] = 'Quantity must be a positive number.';
            }

            // If there are no errors, proceed with creation
            if (empty($data['errors'])) {
                $productData = [
                    'name' => $data['name'],
                    'description' => $data['description'],
                    'sku' => $data['sku'],
                    'purchase_price' => $data['purchase_price'] ?: 0,
                    'sale_price' => $data['sale_price'],
                    'quantity' => $data['quantity'],
                    'reorder_level' => $data['reorder_level'] ?: 10,
                ];
                if ($this->productModel->create($productData)) {
                    // Redirect to index page (flash message would be good here)
                    header('Location: /products');
                    exit();
                } else {
                    die('Something went wrong.');
                }
            } else {
                // Load view with errors
                $this->view('products/create', $data);
            }

        } else {
            // Not a POST request, redirect
            header('Location: /products/create');
            exit();
        }
    }

    // Show form to edit a product
    public function edit($id) {
        $product = $this->productModel->findById($id);

        if (!$product) {
            header('Location: /products');
            exit();
        }

        $data = [
            'title' => 'Edit Product',
            'id' => $id,
            'name' => $product['name'],
            'description' => $product['description'],
            'sku' => $product['sku'],
            'purchase_price' => $product['purchase_price'],
            'sale_price' => $product['sale_price'],
            'quantity' => $product['quantity'],
            'reorder_level' => $product['reorder_level'],
            'errors' => []
        ];
        $this->view('products/edit', $data);
    }

    // Update a product
    public function update($id) {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $_POST = filter_input_array(INPUT_POST);

            $data = [
                'title' => 'Edit Product',
                'id' => $id,
                'name' => trim($_POST['name']),
                'description' => trim($_POST['description']),
                'sku' => trim($_POST['sku']),
                'purchase_price' => trim($_POST['purchase_price']),
                'sale_price' => trim($_POST['sale_price']),
                'quantity' => trim($_POST['quantity']),
                'reorder_level' => trim($_POST['reorder_level']),
                'errors' => []
            ];

            // --- Validation ---
            if (empty($data['name'])) {
                $data['errors']['name'] = 'Please enter a name.';
            } elseif (!$this->productModel->isNameUnique($data['name'], $id)) {
                $data['errors']['name'] = 'Product name already exists.';
            }

            if (empty($data['sale_price'])) {
                $data['errors']['sale_price'] = 'Please enter a sale price.';
            } elseif (!is_numeric($data['sale_price']) || $data['sale_price'] < 0) {
                $data['errors']['sale_price'] = 'Price must be a positive number.';
            }

            if (empty($data['quantity'])) {
                $data['errors']['quantity'] = 'Please enter a quantity.';
            } elseif (!is_numeric($data['quantity']) || $data['quantity'] < 0) {
                $data['errors']['quantity'] = 'Quantity must be a positive number.';
            }

            if (empty($data['errors'])) {
                 $productData = [
                    'name' => $data['name'],
                    'description' => $data['description'],
                    'sku' => $data['sku'],
                    'purchase_price' => $data['purchase_price'] ?: 0,
                    'sale_price' => $data['sale_price'],
                    'quantity' => $data['quantity'],
                    'reorder_level' => $data['reorder_level'] ?: 10,
                ];
                if ($this->productModel->update($id, $productData)) {
                    header('Location: /products');
                    exit();
                } else {
                    die('Something went wrong.');
                }
            } else {
                $this->view('products/edit', $data);
            }

        } else {
            header('Location: /products');
            exit();
        }
    }

    // Delete a product
    public function destroy($id) {
         if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if ($this->productModel->isInUse($id)) {
                // Can't delete, redirect with an error message (would use flash messages)
                // For now, just redirect. A real app should inform the user.
                header('Location: /products');
                exit();
            }

            if ($this->productModel->delete($id)) {
                header('Location: /products');
                exit();
            } else {
                die('Something went wrong.');
            }
         } else {
            header('Location: /products');
            exit();
         }
    }
}
