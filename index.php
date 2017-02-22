<!--In Class 04-->
<!--index.php-->
<!--Myron Williams-->
<?php
require_once('database.php');

// Get category ID
$category_id = filter_input(INPUT_GET, 'category_id', FILTER_VALIDATE_INT);
if ($category_id == NULL || $category_id == FALSE) {
    $category_id = 1;
}

// Get name for selected category
$queryCategory = 'SELECT * FROM categories
                      WHERE categoryID = :category_id';
$statement1 = $db->prepare($queryCategory);
$statement1->bindValue(':category_id', $category_id);
$statement1->execute();
$category = $statement1->fetch();
$category_name = $category['categoryName'];
$statement1->closeCursor();

// Get all categories
$queryAllCategories = 'SELECT * FROM categories
                           ORDER BY categoryID';
$statement2 = $db->prepare($queryAllCategories);
$statement2->execute();
$categories = $statement2->fetchAll();
$statement2->closeCursor();

// Get products for selected category
$queryProducts = 'SELECT * FROM Instruments
              WHERE categoryID = :category_id
              ORDER BY instrumentID';
$statement3 = $db->prepare($queryProducts);
$statement3->bindValue(':category_id', $category_id);
$statement3->execute();
$products = $statement3->fetchAll();
$statement3->closeCursor();

if(isset($_POST['submit'])){
    $getID = $_POST['getID'];
    $newInstrument = $_POST['instrumentName'];
    $newInstrumentPrice = $_POST['instrumentPrice'];

    $stmt = $db->prepare("INSERT INTO instruments (categoryID, instrumentName, listPrice)
            VALUES
            (:addID, :newInstrument, :newInstrumentPrice)");
    $stmt->bindParam(':addID',$getID , PDO::PARAM_STR);
    $stmt->bindParam(':newInstrument', $newInstrument, PDO::PARAM_STR);
    $stmt->bindParam(':newInstrumentPrice', $newInstrumentPrice, PDO::PARAM_STR);
    $stmt->execute();
}




?>
<!DOCTYPE html>
<html>
<!-- the head section -->
<head>
    <title>My Guitar Shop</title>
    <link rel="stylesheet" type="text/css" href="main.css" />
</head>

<!-- the body section -->
<body>
<main>
    <h1>Instruments List</h1>
    <aside>
        <!-- display a list of categories -->
        <h2>Categories</h2>
        <nav>
            <ul>
                <?php foreach ($categories as $category) : ?>
                    <li>
                        <a href="?category_id=<?php echo $category['categoryID']; ?>">
                            <?php echo $category['categoryName']; ?>
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>
        </nav>
    </aside>

    <section>
        <!-- display a table of products -->
        <h2><?php echo $category_name; ?></h2>
        <table>
            <tr>
                <th>Name</th>
                <th class="right">Price</th>
            </tr>

            <?php foreach ($products as $product) : ?>
                <tr>
                    <td><?php echo $product['instrumentName']; ?></td>
                    <td class="right"><?php echo $product['listPrice']; ?></td>
                </tr>
            <?php endforeach; ?>


            <form action="index.php" method="post">
                  <tr>
                    <td><input name="instrumentName" type='text'></td>
                    <td><input name="instrumentPrice" type="text"</td>
                  </tr>
                  <tr><td colspan="2"><input name="submit" type="submit" value="Insert"></td></tr>
                <input type="hidden" name = "getID" value="<?php echo $category_id?>">
            </form>

        </table>
    </section>
</main>
<footer></footer>
</body>
</html>