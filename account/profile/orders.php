<?php
  require_once('../../includes/main.php');

  if(!isset($_SESSION['logged_in']) && $_SESSION['logged_in'] == false) {
    header( "Location: ../login" );
  }
  require_once('../../includes/items/ItemMananger.php');
  require_once('../../includes/commonDesign/CheckoutDesign.php');

  $page = 1;
  if(isset($_GET['page']) && is_numeric($_GET['page'])) {
      $page = (int) $_GET['page'] + 1;
  }
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <title>Orders - Wreckless Reykjavík</title>
  <?php
    include("../../includes/html/header.html");
  ?>
</head>
<body>
  <?php
    include("../../includes/html/navigation.php");
  ?>
  <div class="full-page">
    <div class="centered" style="width: 100%;">
        <div class="top-bar">
            <a href="/account/profile/orders"><img alt="orders" src="/assets/vector/order.svg"></a>
            <a href="/account/logout"><img alt="logout" src="/assets/vector/signout.svg"></a>
        </div>
    </div>
      <?php
      createTitle("ORDERS");

      function idStatusToString($order) {
        switch ($order) {
            case 0:
                return "Order placed";
                break;
            case 1:
                return "Making order";
                break;
            case 2:
                return "Order has been shipped";
                break;
            case 3:
                return "Order recived";
                break;
        }
      }

      $offset = ($page - 1) * 4;
      $sql = "SELECT *FROM orders WHERE payerID=" . $_SESSION['id'] . " AND confirmed = 1 LIMIT " . $offset . "," . 4;
      $result = $conn->query($sql);

      if (mysqli_num_rows($result) > 0) {
          while($row = $result->fetch_assoc()) {
              $info = shippingIDToInfo($row['shipping']);
              info('ORDER: ' . $row['orderID'], '<div><table style="width: 100%;"><th>Item name</th><th>Size</th><th>Color</th>' . tableItems($conn, unserialize($row['cart'])) . '</table><br>' . 'Name: ' . $row['name'] . '<br>Country: ' . $row['country'] . '<br>City: ' . $row['city'] . '<br>Address: ' . $row['address'] . '<br>Shipping: ' . $info['name'] . ", $" . $info['price'] . ($row['promo'] != null ? '<br>Promo: ' . $row["promo"] : '') . '<br>Total: $' . $row['total'] . '<br><br>Order Status: ' . idStatusToString($row['status']) . '</div>');
          }
          echo '<a style="float: right; margin-right: 25px;" href="/account/profile/orders?page=' . $page . '">Next page</a>';
          if($page >= 2) {
              echo '<a style="float: left; margin-left: 25px;" href="/account/profile/orders?page=' . ($page - 2) . '">Previous page</a>';
          }
      } else {
          info('NO ORDERS', 'We could not find any orders');
          if($page > 1) {
              echo '<a style="float: left; margin-left: 25px;" href="/account/profile/orders?page=' . ($page - 2) . '">Previous page</a>';
          }
      }
      $conn->close();
      ?>
      <br>
  </div>
  <?php
    include("../../includes/html/footer.html");
  ?>
</body>
</html>
