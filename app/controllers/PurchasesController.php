<?php

class PurchasesController extends Controller {
    private $purchaseModel;

    public function __construct() {
        $this->purchaseModel = $this->model('Purchase');
    }

    // Show all purchases
    public function index() {
        $purchases = $this->purchaseModel->getAll();
        $data = [
            'title' => 'Purchases',
            'purchases' => $purchases
        ];
        $this->view('purchases/index', $data);
    }

    // Show form to add a new purchase
    public function create() {
        // Just show the form. The form will use AJAX to fetch products.
        $data = [
            'title' => 'New Purchase'
        ];
        $this->view('purchases/create', $data);
    }

    // Store a new purchase
    public function store() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // The items will be submitted as a JSON string
            $itemsJson = $_POST['items'] ?? '[]';
            $items = json_decode($itemsJson, true);

            $data = [
                'supplier_name' => trim($_POST['supplier_name'] ?? ''),
                'total_amount' => (float) ($_POST['total_amount'] ?? 0),
                'items' => $items
            ];

            // Basic validation
            if (empty($data['supplier_name']) || empty($data['items']) || $data['total_amount'] <= 0) {
                // In a real app, you'd redirect back with an error message
                die('Invalid data provided. Please fill out all fields.');
            }

            // Call the model to create the purchase
            $newPurchaseId = $this->purchaseModel->create($data);

            if ($newPurchaseId) {
                // Redirect to the new purchase's detail page
                header('Location: /purchases/show/' . $newPurchaseId);
                exit();
            } else {
                // Handle failure
                die('Error creating purchase. The transaction was rolled back.');
            }

        } else {
            header('Location: /purchases/create');
            exit();
        }
    }

    // Show details of a single purchase
    public function show($id) {
        $purchase = $this->purchaseModel->findById($id);

        if (!$purchase) {
            // Handle not found
            header('Location: /purchases');
            exit();
        }

        $data = [
            'title' => 'Purchase Details #' . $purchase['id'],
            'purchase' => $purchase
        ];
        $this->view('purchases/show', $data);
    }
}
