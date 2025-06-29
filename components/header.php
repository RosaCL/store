<header class="header">
    <section class="flex">
        <a class="logo" href="add_product.php"><i class="fa-regular fa-moon"></i></a>
        <nav class="navbar">
            <a href="add_product.php"><i class="fas fa-plus"></i></a>
            <a href="view_product.php"><i class="fas fa-eye"></i></a>
            <?php
            $count_total_cart_items = $conn->prepare("SELECT * FROM `cart` WHERE user_id = ?");
            $count_total_cart_items->execute([$user_id]);
            $total_cart_items = $count_total_cart_items->rowCount();
            ?>
            <a href="#" class="cart-icon"><i class="fas fa-shopping-cart"></i><span>(<?= $total_cart_items; ?>)</span></a>
        </nav>
    </section>
</header>