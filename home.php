<?php 
include_once "app/ProductsController.php";

$productsController = new ProductsController();
$productos = array_reverse($productsController->get());
$brands = $productsController->getBrands(); 
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>TIENDA</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>
<body> 
    <div class="container-fluid min-vh-100 d-flex flex-column">
        <div class="row">
            <nav class="navbar navbar-expand-lg bg-dark bg-body-tertiary" data-bs-theme="dark">
                <div class="container-fluid">
                    <a class="navbar-brand" href="#">Navbar scroll</a>
                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarScroll" aria-controls="navbarScroll" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    <div class="collapse navbar-collapse" id="navbarScroll">
                        <ul class="navbar-nav me-auto my-2 my-lg-0 navbar-nav-scroll" style="--bs-scroll-height: 100px;">
                            <li class="nav-item">
                                <a class="nav-link active" aria-current="page" href="#">Home</a>
                            </li>
                        </ul>
                        <form class="d-flex" role="search">
                            <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search">
                            <button class="btn btn-outline-success" type="submit">Search</button>
                        </form>
                    </div>
                </div>
            </nav>
        </div>
        <div class="row">
            <div class="col-2 flex-grow-1 g-0">
                <div class="d-flex flex-column min-vh-100 flex-shrink-0 p-3 text-white bg-dark">
                    <a href="/" class="d-flex align-items-center mb-3 mb-md-0 me-md-auto text-white text-decoration-none">
                        <span class="fs-4">Sidebar</span>
                    </a>
                    <hr>
                    <ul class="nav nav-pills flex-column mb-auto">
                        <li class="nav-item">
                            <a href="#" class="nav-link active" aria-current="page">Home</a>
                        </li>
                        <li>
                            <a href="#" class="nav-link text-white">Dashboard</a>
                        </li>
                    </ul>
                    <hr>
                </div>
            </div>

            <div class="col-10">
                <div class="main p-2">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item active" aria-current="page">Home</li> 
                        </ol>
                        <button type="button" class="btn btn-primary float-end" data-bs-toggle="modal" data-bs-target="#exampleModal">Añadir</button> 
                    </nav>
                    
                    <div class="row" id="product-list">
                        <?php if (isset($productos) && count($productos)): ?>
                        <?php foreach ($productos as $product): ?> 
                        <div class="col-3 product-item" id="product-<?= $product->id ?>">
                            <div class="card mb-3" style="width: 18rem;">
                                <img src="<?= $product->cover ?>" class="card-img-top" alt="...">
                                <div class="card-body">
                                    <h5 class="card-title"><?= $product->name ?></h5>
                                    <p class="card-text"><?= $product->description ?></p>
                                    <a href="javascript:void(0);" onclick="eliminar(<?= $product->id ?>)" class="m-1 btn btn-danger">Eliminar</a>
                                    <a onclick="editar(this)" data-product='<?= json_encode($product) ?>' data-bs-toggle="modal" data-bs-target="#updateModal" class="m-1 btn btn-warning">Editar</a>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                        <?php endif; ?>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Añadir Producto</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="addProductForm" enctype="multipart/form-data">
                        <div class="mb-3">
                            <label for="name" class="form-label">Nombre</label>
                            <input type="text" name="name" class="form-control" id="name" required> 
                        </div>
                        <div class="mb-3">
                            <label for="slug" class="form-label">Slug</label>
                            <input type="text" name="slug" class="form-control" id="slug" required>
                        </div>
                        <div class="mb-3">
                            <label for="description" class="form-label">Descripción</label>
                            <textarea name="description" required class="form-control" id="description"></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="features" class="form-label">Características</label>
                            <input type="text" name="features" required class="form-control" id="features">
                        </div>
                        <div class="mb-3">
                            <label for="brand" class="form-label">Marca</label>
                            <select name="brand" class="form-select" id="brand" required>
                                <option value="">Seleccione una marca</option>
                                <?php if (isset($brands) && count($brands)): ?>
                                    <?php foreach ($brands as $brand): ?>
                                        <option value="<?= $brand->id ?>"><?= $brand->name ?></option>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="cover" class="form-label">Subir Imagen</label>
                            <input type="file" class="form-control shadow-sm" id="cover" name="cover" accept="image/*" required />
                        </div>
                        <button type="submit" class="btn btn-primary">Crear producto</button>
                        <input type="hidden" name="action" value="crear_producto">
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button> 
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script>
        document.getElementById('addProductForm').addEventListener('submit', function(e) {
            e.preventDefault();

            const formData = new FormData(this);

            fetch('app/ProductsController.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    window.location.href = 'home.php'; 
                } else {
                    alert(data.message || 'Error al crear el producto.');
                }
            })
            .catch(error => {
                console.error('Error:', error);
            });
        });

        function eliminar(id) {
            if (confirm('¿Estás seguro de que deseas eliminar este producto?')) {
                fetch('app/ProductsController.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: new URLSearchParams({
                        action: 'eliminar_producto',
                        id: id
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const productItem = document.getElementById('product-' + id);
                        if (productItem) {
                            productItem.remove(); 
                        }
                    } else {
                        alert(data.message || 'Error al eliminar el producto.');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                });
            }
        }
    </script>
</body>
</html>
