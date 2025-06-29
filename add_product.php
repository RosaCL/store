<?php
require_once '/laragon/www/store/components/conect.php';
require_once '/laragon/www/store/components/alerts.php';

if (isset($_POST['add-product'])) {
    $id = create_unique_id();
    $name = $_POST['name'];
    $name = htmlspecialchars($name, ENT_QUOTES, 'UTF-8');
    $price = $_POST['price'];
    $price = filter_var($price, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
    $stock = $_POST['stock'];
    $stock = filter_var($stock, FILTER_SANITIZE_NUMBER_INT);
    $image = $_FILES['image']['name'];
    $image = htmlspecialchars($image, ENT_QUOTES, 'UTF-8');
    $ext = pathinfo($image, PATHINFO_EXTENSION);
    $rename = create_unique_id() . '.' . $ext;
    $image_size = $_FILES['image']['size'];
    $image_tmp_name = $_FILES['image']['tmp_name'];
    $image_folder = __DIR__ . '/upload_files/' . $rename;
    if (!is_dir(__DIR__ . '/upload_files')) {
        mkdir(__DIR__ . '/upload_files', 0755, true);
    }


    if ($image_size > 2000000) {
        $warning_msg[] = 'Imagem muito grande! Máximo 2MB';
    } else {
        $insert_product = $conn->prepare("INSERT INTO `products` (id, name, price, stock, image) VALUES (?, ?, ?, ?, ?)");
        $insert_product->execute([$id, $name, $price, $stock, $rename]);
        move_uploaded_file($image_tmp_name, $image_folder);
        $sucess_msg[] = 'Produto adicionado com sucesso';
    }
}


?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Store</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="./css/style.css">
</head>

<body>
    <?php
    include '/laragon/www/store/components/header.php';
    ?>
    <section class="add-product">
        <h1 class="heading">
            Adicione o produto
        </h1>
        <form action="" method="post" enctype="multipart/form-data">
            <h3>Detalhes do produto</h3>
            <p>Nome do produto <span>*</span></p>
            <input type="text" name="name" required maxlength="50" placeholder="Digite o nome do produto" class="input">
            <p>Preço do produto <span>*</span></p>
            <input type="number" name="price" required maxlength="10" placeholder="Digite o preço do produto" min="0" max="9999999999" class="input">
            <p>Estoque total<span>*</span></p>
            <input type="number" name="stock" required maxlength="10" placeholder="Total de produtos em estoque" min="0" max="9999999999" class="input">
            <p>Imagem do produto<span>*</span></p>
            <input type="file" name="image" required accept="image/" class="input">
            <input type="submit" value="Adicionar produto" name="add-product" class="btn">
        </form>
    </section>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js">
    </script>
    <script src="../js/script.js"></script>
    <?php
    include '/laragon/www/store/components/alerts.php';
    ?>
</body>

</html>