<?php
require_once '/laragon/www/store/components/conect.php';
require_once '/laragon/www/store/components/alerts.php';




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
    <section class="view-product">
        <h1 class="heading">
            Todos os produtos
        </h1>
        <div class="box-container">
            <div class="box">
                <?php
                $select_products = $conn->prepare("SELECT * FROM `products`");
                $select_products->execute();
                if ($select_products->rowCount() > 0) {
                    while ($fetch_product = $select_products->fetch(PDO::FETCH_ASSOC)) {

                ?>
                <form action="" method="post" class="box <?php if ($fetch_product['stock'] == 0) {
                    echo 'desabilitado';
                    } ?>">
                    <img src="upload_files/<?= $fetch_product['image'] ?>" alt="" class="image">
                    <?php if ($fetch_product['stock'] > 9) { ?>
                        <span class="stock" style="color: green;"><i class="fas fa-check"></i> Em estoque</span>
                        <?php } elseif ($fetch_product['stock'] == 0) { ?>
                            <span class="stock" style="color:red;"><i class="fas fa-times"></i> Fora de estoque</span>
                            <?php } else { ?>
                                <span class="stock" style="color:red;"> Solicitar reposiçao de estoque, só tem <?= $fetch_product['stock']; ?></span>
                            <?php } ?>
                            <h3 class="name">
                                <?= $fetch_product['name']; ?>
                            </h3>                            
                            <div class="flex">
                                <p class="price">
                                    <i class="fa-solid fa-brazilian-real-sign"></i> <?= $fetch_product['price']; ?>
                                </p>                                
                                <input type="number" name="qty" class="qty" value="1" min="1" max="99" maxlength="2" required>
                            </div>
                            <?php if ($fetch_product['stock']!=0){?>
                            <span class="stock" style="color: red;"><i class="fas fa-times"></i>Fora de estoque</span>
                            <a href="#" class="btn">Compre agora</a>
                            <input type="submit" value="Adicionar ao carrinho" name="add_to_cart" class="option-btn">
                            <?php };?>
                        </form>

                <?php
                    }
                } else {
                    echo '<p class="empty">Nenhum produto adicionado!</p>';
                }


                ?>
            </div>
        </div>

    </section>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js">
    </script>
    <script src="../js/script.js"></script>
    <?php
    include '/laragon/www/store/components/alerts.php';
    ?>
</body>

</html>