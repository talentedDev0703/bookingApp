<?php

  $page_config = array(
    'active' => 'orders-list',
    'title' => 'Orders',
    'is_orders' => true
  );

  include('../config.php');
  include('functions.php');
  include('inc/header.inc.php');

  $datetimeFormat = 'd.m.Y H:i';
  $start_time = new \DateTime('now', new \DateTimeZone('Europe/Zagreb'));

?>

<div>

  <h2 class="h4 d-inline align-middle">Orders List</h2>

</div>

<div class="mt-3">
  <mark class="highlihght">@TODO</mark> Currently not available: Edit, Cancellation
</div>

<?php if($all_orders): ?>
<table class="table js-periodTable table-striped mt-4">
  <thead>
    <tr>
      <th>#</th>
      <th width="200">Tour</th>
      <th>Buyer</th>
      <th>Seats</th>
      <th>Phone</th>
      <th>Order date</th>
      <th>B2B</th>
      <th>Paid</th>
      <th>Cancelled</th>
      <?php /*
      <th></th>
      */ ?>
    </tr>
  </thead>
  <tbody>
    <?php foreach($all_orders as $order): ?>
    <tr id="user-<?php echo $order['id']; ?>">
      <th scope="row"><?php echo $order['id']; ?></th>
      <td>
        <?php echo $order['tour_name']; ?><br>
        <small>
            <?php
                $start_time->setTimestamp($order['tour_timestamp']);
                echo $order['pickup_point'] . ', ' . $start_time->format($datetimeFormat);
            ?>
        </small>
      </td>
      <td>
          <?php echo $order['name']; ?> (<?php echo $order['email']; ?>)
          <?php if(in_array($order['email'], array('marko.zvono@gmail.com','neven.duranec@gmail.com','igor.resetic@gmail.com', 'ikovacic1@gmail.com'))): ?><br><span class="badge badge-pill badge-info">Test</span><?php endif; ?>
      </td>
      <td><?php echo $order['adults'] + $order['kids']; ?></td>
      <td><?php echo $order['phone']; ?></td>
      <td><?php
        $start_time->setTimestamp($order['timestamp']);
        echo $start_time->format($datetimeFormat); ?>
      </td>
      <td><?php echo $order['company_name'] ? $order['company_name'] : '---'; ?></td>
      <td><?php echo $order['paid'] ? 'Yes' : '---'; ?></td>
      <td><?php echo $order['cancelled'] ? 'Yes' : '---'; ?></td>
      <?php /*
      <td class="text-right text-nowrap">
        <?php if($user['active'] != 0): ?>
          <a href="<?php echo $base_path ?>admin/users/?disable_user=<?php echo $user['id']; ?>" class="btn btn-outline-danger btn-sm ml-1">Disable</a>
        <?php else: ?>
          <a href="<?php echo $base_path ?>admin/users/?activate_user=<?php echo $user['id']; ?>" class="btn btn-outline-success btn-sm ml-1">Enable</a>
        <?php endif; ?>
        <a href="<?php echo $base_path ?>admin/user-edit/?id=<?php echo $user['id']; ?>" class="btn btn-secondary btn-sm">Edit</a>
      </td>
      */ ?>
    </tr>
    <?php endforeach; ?>
  </tbody>
</table>
<?php else: ?>
<div class="mt-3">
  <div class="alert alert-danger" role="alert">
    There are no orders!
  </div>
</div>
<?php endif; ?>

<?php include('inc/footer.inc.php'); ?>