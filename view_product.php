<?php
require_once '/laragon/www/store/components/conect.php';
require_once '/laragon/www/store/components/alerts.php';

if(isset($_POST['add_to_cart'])){
    $id = create_unique_id();
    $product_id = $_POST['product_id'];
    $product_id =  htmlspecialchars($product_id, ENT_QUOTES, 'UTF-8');
    $qty = $_POST['qty'];
    $qty = filter_var($qty, FILTER_SANITIZE_NUMBER_INT);

    $verify_cart = $conn->prepare("SELECT * FROM `cart` WHERE user_id = ? AND product_id = ?");
    $verify_cart->execute([$user_id, $product_id]);

    $max_cart_limit = $conn->prepare("SELECT * FROM `cart` WHERE user_id = ?");
    $max_cart_limit ->execute([$user_id]);

    if($verify_cart->rowCount()>0){
        $warning_msg[]= 'Já está no carrinho!';        
    }elseif($max_cart_limit->rowCount()==10){
        $warning_msg[]= 'Carrinho está cheio';
    }else{
    $select_p = $conn->prepare("SELECT * FROM `products` WHERE id = ?");
        $select_p->execute([$product_id]);
        $fetch_p = $select_p->fetch(PDO::FETCH_ASSOC);

        $total_stock = ($fetch_p['stock'] - $qty);

        if($qty >$fetch_p['stock']){
            $warning_msg[] = 'Restam '.$fetch_p['stock'].' em estoque.';
        }else{
            $insert_cart = $conn ->prepare("INSERT INTO `cart` (id, user_id, product_id, qty) VALUES(?,?,?,?)");
            $insert_cart->execute([$id, $user_id, $product_id, $qty]);

            $update_stock = $conn->prepare("UPDATE `products` SET stock = ? WHERE id = ?");
            $update_stock->execute([$total_stock,$product_id]);
            $sucess_msg[] = 'Adicionado ao carrinho!';
        }
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
    <section class="view-product">
        <h1 class="heading">
            Todos os produtos
        </h1>
        <div class="box-container">
           
                <?php
                $select_products = $conn->prepare("SELECT * FROM `products`");
                $select_products->execute();
                if ($select_products->rowCount() > 0) {
                    while ($fetch_product = $select_products->fetch(PDO::FETCH_ASSOC)) {
                ?>
                <form action="" method="post" class="box <?php if ($fetch_product['stock'] == 0) {
                    echo 'disabled';
                    } ?>">
                    <input type="hidden" name="product_id" value="<?=$fetch_product['id'];?>">

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
                            <?php if ($fetch_product['stock'] != 0){?>
                            <a href="#" class="btn">Comprar agora</a>
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



    </section>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js">
    </script>
    <script src="../js/script.js"></script>
    <?php
    include '/laragon/www/store/components/alerts.php';
    ?>
</body>

</html>