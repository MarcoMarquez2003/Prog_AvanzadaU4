<?php
session_start();

class ProductsController {
    private function sendRequest($url, $method, $data = null) {
        $curl = curl_init();
        $headers = [
            'Authorization: Bearer ' . $_SESSION['user_data']->token,
        ];

        curl_setopt_array($curl, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => $method,
            CURLOPT_HTTPHEADER => $headers,
        ]);

        if ($method === 'POST' || $method === 'PUT') {
            curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        }

        $response = curl_exec($curl);

        if (curl_errno($curl)) {
            echo json_encode(['success' => false, 'message' => 'Error cURL: ' . curl_error($curl)]);
            exit;
        }

        curl_close($curl);
        return json_decode($response);
    }

    public function get() {
        $response = $this->sendRequest('https://crud.jonathansoto.mx/api/products', 'GET');
        return isset($response->code) && $response->code > 0 ? $response->data : [];
    }

    public function create($name_var, $slug_var, $description_var, $features_var, $image_path) {
        $data = [
            'name' => $name_var,
            'slug' => $slug_var,
            'description' => $description_var,
            'features' => $features_var,
            'cover' => new CURLFile($image_path)
        ];
        
        $response = $this->sendRequest('https://crud.jonathansoto.mx/api/products', 'POST', $data);

        if (isset($response->code) && $response->code > 0) {
            echo json_encode(['success' => true, 'product' => $response->data]);
        } else {
            echo json_encode(['success' => false, 'message' => $response->message ?? 'Error al crear el producto.']);
        }
    }

    public function delete($id) {
        $response = $this->sendRequest('https://crud.jonathansoto.mx/api/products/' . $id, 'DELETE');

        if (isset($response->code) && $response->code == 200) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'message' => $response->message ?? 'Error al eliminar el producto.']);
        }
    }

    public function getBrands() {
        $response = $this->sendRequest('https://crud.jonathansoto.mx/api/brands', 'GET');
        return isset($response->code) && $response->code > 0 ? $response->data : [];
    }
}

$controller = new ProductsController();
$action = $_POST['action'] ?? null;

if ($action === 'crear_producto') {
    $controller->create($_POST['name'], $_POST['slug'], $_POST['description'], $_POST['features'], $_FILES['cover']['tmp_name']);
} elseif ($action === 'eliminar_producto') {
    $controller->delete($_POST['id']);
} elseif ($action === 'obtener_marcas') {
    $brands = $controller->getBrands();
    echo json_encode(['success' => true, 'brands' => $brands]);
}
?>
