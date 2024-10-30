<?php
session_start();

class ProductsController {
    public function get() {
        $curl = curl_init();  

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://crud.jonathansoto.mx/api/products',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array(
                'Authorization: Bearer '.$_SESSION['user_data']->token
            ),
        ));

        $response = curl_exec($curl);
        curl_close($curl);  
        $response = json_decode($response);

        return isset($response->code) && $response->code > 0 ? $response->data : [];
    }

    public function create($name_var, $slug_var, $description_var, $features_var) {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://crud.jonathansoto.mx/api/products',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => array(
                'name' => $name_var,
                'slug' => $slug_var,
                'description' => $description_var,
                'features' => $features_var
            ),
            CURLOPT_HTTPHEADER => array(
                'Authorization: Bearer '.$_SESSION['user_data']->token
            ),
        ));

        $response = curl_exec($curl); 
        curl_close($curl);  
        $response = json_decode($response);

        if (isset($response->code) && $response->code > 0) {
            echo json_encode(['success' => true, 'product' => $response->data]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Error al crear el producto.']);
        }
    }

    public function delete($id) {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://crud.jonathansoto.mx/api/products/' . $id,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'DELETE',
            CURLOPT_HTTPHEADER => array(
                'Authorization: Bearer '.$_SESSION['user_data']->token,
            ),
        ));

        $response = curl_exec($curl);
        curl_close($curl);
        $response = json_decode($response);

        if ($response->code == 200) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Error al eliminar el producto.']);
        }
    }
}

$controller = new ProductsController();
$action = $_POST['action'] ?? null;

if ($action === 'crear_producto') {
    $controller->create($_POST['name'], $_POST['slug'], $_POST['description'], $_POST['features']);
} elseif ($action === 'eliminar_producto') {
    $controller->delete($_POST['id']);
}
