<?php
require 'config.php';
header('Content-Type: application/json');

$method = $_SERVER['REQUEST_METHOD'];
$endpoint = $_GET['endpoint'] ?? '';

// GET /products.json?page=1&query=keyword
if ($endpoint === 'products.json' && $method === 'GET') {
    $page = max(1, (int)($_GET['page'] ?? 1));
    $perPage = 10;
    $offset = ($page - 1) * $perPage;
    $query = $_GET['query'] ?? '';
    $where = "WHERE p.is_hidden = 0";
    $params = [];
    if ($query) {
        $where .= " AND (p.name LIKE ? OR p.name_fr LIKE ? OR p.description LIKE ? OR p.description_fr LIKE ?)";
        $q = "%$query%";
        $params = [$q, $q, $q, $q];
    }
    $countsql = $pdo->prepare("SELECT COUNT(*) FROM products p $where");
    $countsql->execute($params);
    $total = $countsql->fetchColumn();
    $sql = $pdo->prepare("
        SELECT p.*, c.company_name, c.company_address, c.company_telephone_number, c.company_email_address,
               c.owner_name, c.owner_mobile_number, c.owner_email_address,
               c.contact_name, c.contact_mobile_number, c.contact_email_address
        FROM products p
        JOIN companies c ON p.company_id = c.id
        $where
        ORDER BY p.name
        LIMIT $perPage OFFSET $offset
    ");
    $sql->execute($params);
    $products = $sql->fetchAll();
    $data = [];
    foreach ($products as $p) {
        $data[] = [
            'name' => ['en' => $p['name'], 'fr' => $p['name_fr']],
            'description' => ['en' => $p['description'], 'fr' => $p['description_fr']],
            'gtin' => $p['gtin'],
            'brand' => $p['brand_name'],
            'countryOfOrigin' => $p['country_origin'],
            'weight' => [
                'gross' => (float)$p['gross_weight'],
                'net' => (float)$p['net_content_weight'],
                'unit' => $p['weight_unit']
            ],
            'company' => [
                'companyName' => $p['company_name'],
                'companyAddress' => $p['company_address'],
                'companyTelephone' => $p['company_telephone_number'],
                'companyEmail' => $p['company_email_address'],
                'owner' => [
                    'name' => $p['owner_name'],
                    'mobileNumber' => $p['owner_mobile_number'],
                    'email' => $p['owner_email_address']
                ],
                'contact' => [
                    'name' => $p['contact_name'],
                    'mobileNumber' => $p['contact_mobile_number'],
                    'email' => $p['contact_email_address']
                ]
            ]
        ];
    }
    $totalPages = ceil($total / $perPage);
    $baseUrl = (isset($_SERVER['HTTPS']) ? 'https' : 'http') . "://{$_SERVER['HTTP_HOST']}/stii_module_b/products.json";
    echo json_encode([
        'data' => $data,
        'pagination' => [
            'current_page' => $page,
            'total_pages' => $totalPages,
            'per_page' => $perPage,
            'next_page_url' => $page < $totalPages ? $baseUrl . '?page=' . ($page+1) . ($query ? '&query='.urlencode($query) : '') : null,
            'prev_page_url' => $page > 1 ? $baseUrl . '?page=' . ($page-1) . ($query ? '&query='.urlencode($query) : '') : null
        ]
    ]);
    exit;
}

// GET /products/{gtin}.json
if (preg_match('#^products/(\d{13,14})\.json$#', $endpoint, $matches) && $method === 'GET') {
    $gtin = $matches[1];
    $sql = $pdo->prepare("
        SELECT p.*, c.company_name, c.company_address, c.company_telephone_number, c.company_email_address,
               c.owner_name, c.owner_mobile_number, c.owner_email_address,
               c.contact_name, c.contact_mobile_number, c.contact_email_address
        FROM products p
        JOIN companies c ON p.company_id = c.id
        WHERE p.gtin = ? AND p.is_hidden = 0
    ");
    $sql->execute([$gtin]);
    $p = $sql->fetch();
    if (!$p) {
        http_response_code(404);
        echo json_encode(['error' => 'Product not found']);
        exit;
    }
    echo json_encode([
        'name' => ['en' => $p['name'], 'fr' => $p['name_fr']],
        'description' => ['en' => $p['description'], 'fr' => $p['description_fr']],
        'gtin' => $p['gtin'],
        'brand' => $p['brand_name'],
        'countryOfOrigin' => $p['country_origin'],
        'weight' => [
            'gross' => (float)$p['gross_weight'],
            'net' => (float)$p['net_content_weight'],
            'unit' => $p['weight_unit']
        ],
        'company' => [
            'companyName' => $p['company_name'],
            'companyAddress' => $p['company_address'],
            'companyTelephone' => $p['company_telephone_number'],
            'companyEmail' => $p['company_email_address'],
            'owner' => [
                'name' => $p['owner_name'],
                'mobileNumber' => $p['owner_mobile_number'],
                'email' => $p['owner_email_address']
            ],
            'contact' => [
                'name' => $p['contact_name'],
                'mobileNumber' => $p['contact_mobile_number'],
                'email' => $p['contact_email_address']
            ]
        ]
    ]);
    exit;
}

http_response_code(404);
echo json_encode(['error' => 'Endpoint not found']);