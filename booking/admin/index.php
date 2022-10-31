<?php

  $page_config = array(
    'active' => 'tours-list',
    'title' => ''
  );

  include('../config.php');
  include('functions.php');
  include('inc/header.inc.php');

  $datetimeFormat = 'd.m.Y H:i';
  $start_time = new \DateTime('now', new \DateTimeZone('Europe/Zagreb'));

?>

<div>

  <h2 class="h4 d-inline align-middle">Tours List</h2>

</div>

<form action="<?php echo $base_path; ?>admin/" method="get">

  <input type="hidden" name="search" value="1">

  <div class="mt-4 row">
    <div class="col-sm-3 col-md-4">
      <input class="form-control" name="tour_name" type="text" placeholder="Tour name" value="<?php echo $search['tour_name']; ?>">
      <?php if($search['is_search']): ?>
      <small class="form-text"><a href="<?php echo $base_path; ?>admin/">Clear search</a></small>
      <?php endif; ?>
    </div>
    <div class="col-sm-3 mt-2 mt-sm-0">
      <input class="form-control" name="pickup_point" type="text" placeholder="Pickup" value="<?php echo $search['pickup_point']; ?>">
    </div>
    <div class="col-sm-3 mt-2 mt-sm-0">
      <input class="form-control" name="date" type="text" placeholder="Date" value="<?php echo $search['date']; ?>" data-toggle="datepicker" autocomplete="off">
    </div>
    <div class="col-sm-3 col-md-2 mt-2 mt-sm-0">
      <button type="submit" class="btn btn-primary btn-block">Search</button>
    </div>
  </div>

</form>

<?php if($cancel_success): ?>
<div class="mt-3">
  <div class="alert alert-success" role="alert">
    <strong>Success!</strong> Tour has been cancelled.
  </div>
</div>
<?php elseif($activate_success): ?>
<div class="mt-3">
  <div class="alert alert-success" role="alert">
    <strong>Success!</strong> Tour has been activated.
  </div>
</div>
<?php elseif($insert_success): ?>
  <div class="mt-3">
  <div class="alert alert-success" role="alert">
    <strong>Success!</strong> Tour has been edited.
  </div>
</div>
<?php endif; ?>

<?php if($all_tours): ?>
<table class="table js-periodTable table-striped mt-4">
  <thead>
    <tr>
      <?php /* <th>#</th> */ ?>
      <th>Name</th>
      <th>Pickup</th>
      <th>End</th>
      <th>Date / Time</th>
      <th class="text-center">Bus</th>
      <th class="text-center">Shared</th>
      <th class="text-right">Price (kn)</th>
      <?php /* <th>Lang</th> */ ?>
      <th class="text-center">Capacity</th>
      <th></th>
    </tr>
  </thead>
  <tbody>
    <?php foreach($all_tours as $tour): ?>
    <tr id="tour-<?php echo $tour['id']; ?>">
      <?php /*<th scope="row"><?php echo $tour['id']; ?></th> */?>
      <td>
        <?php echo $tour['tour_name']; ?>
        <?php if($tour['active'] == 0): ?>
          <span class="badge badge-pill badge-danger">Cancelled</span>
        <?php elseif((($tour['places_booked'] + $tour['manually_blocked_capacity']) / $tour['capacity']) * 100 > 75): ?>
          <span class="badge badge-pill badge-warning">Low cap</span>
        <?php /* else: ?>
          <span class="badge badge-pill badge-success">Available</span> 
          */ ?>
        <?php endif; ?>
      </td>
      <td><?php echo $tour['pickup_point']; ?></td>
      <td><?php echo $tour['end_point']; ?></td>
      <td><?php
        $start_time->setTimestamp($tour['timestamp']);
        echo $start_time->format($datetimeFormat);
      ?></td>
      <td class="text-center"><?php echo $tour['bus']; ?></td>
      <td class="text-center"><?php echo $tour['shared_capacity'] ? 'Yes' : '---'; ?></td>
      <td class="text-right"><?php echo number_format($tour['price'], 2, ',', '.'); ?></td>
      <?php /* <td><?php echo $tour['language'] ? $tour['language'] : '---'; ?></td> */ ?>
      <td class="text-center"><?php echo ($tour['places_booked'] + $tour['manually_blocked_capacity']); ?> / <?php echo $tour['capacity']; ?></td>
      <td class="text-right text-nowrap">
        <?php if($tour['active'] != 0): ?>
          <a href="<?php echo $base_path ?>admin/make-order/?id=<?php echo $tour['id']; ?>" class="btn btn-secondary btn-sm ml-1">Book As</a>
        <?php endif; ?>
        <?php if($tour['active'] != 0 && $tour['places_booked'] == 0): ?>
          <a href="<?php echo $base_path ?>admin/?cancel=<?php echo $tour['id']; ?>" class="btn btn-outline-danger btn-sm ml-1">Cancel</a>
        <?php elseif($tour['active'] == 0): ?>
          <a href="<?php echo $base_path ?>admin/?activate=<?php echo $tour['id']; ?>" class="btn btn-outline-success btn-sm ml-1">Activate</a>
        <?php endif; ?>
        <a href="<?php echo $base_path ?>admin/tour-edit/?id=<?php echo $tour['id']; ?>" class="btn btn-secondary btn-sm">Edit</a>
      </td>
    </tr>
    <?php endforeach; ?>
  </tbody>
</table>
<?php else: ?>
<div class="mt-3">
  <div class="alert alert-danger" role="alert">
    There are no tours!
  </div>
</div>
<?php endif; ?>

<?php /*
<div class="mt-5">

  <ul class="nav nav-tabs" role="tablist">
    <li class="nav-item">
      <a class="nav-link active" data-toggle="tab" href="#insert-bundle">Insert Bundle</a>
    </li>
    <li class="nav-item">
      <a class="nav-link" data-toggle="tab" href="#delivery-rules">Delivery Rules</a>
    </li>
  </ul>

  <div class="tab-content mt-4">
    <div class="tab-pane active" id="insert-bundle" role="tabpanel">

      <form class="js-form" action="<?php echo $base_path; ?>?site=<?php echo $active_site; ?>" method="post" data-products="2">
        <div class="form-group row">
          <div class="col-3">
            <label for="bundleName">Bundle Name</label>
            <input type="text" class="form-control" id="bundleName" name="bundleName">
          </div>
          <div class="col-3">
            <label for="bundleSku">Bundle SKU</label>
            <input type="text" class="form-control" id="bundleSku" name="bundleSku">
          </div>
          <div class="col-2">
            <label for="productSku">Product SKU</label>
            <input type="text" class="form-control" id="productSku" name="productSku[]">
          </div>
          <div class="col-2">
            <label for="qty">Quantity</label>
            <input type="text" class="form-control" id="qty" name="qty[]">
          </div>
          <div class="col-2">
            <label for="price">Price</label>
            <input type="text" class="form-control" id="price" name="price[]">
          </div>
        </div>

        <?php for($i=0; $i<9; $i++): ?>
        <div class="form-group row<?php if($i>0): ?> is-hidden<?php endif; ?>">
          <div class="col offset-6">
            <input type="text" class="form-control" name="productSku[]"<?php if($i>0): ?> disabled<?php endif; ?>>
          </div>
          <div class="col">
            <input type="text" class="form-control" name="qty[]"<?php if($i>0): ?> disabled<?php endif; ?>>
          </div>
          <div class="col">
            <input type="text" class="form-control" name="price[]"<?php if($i>0): ?> disabled<?php endif; ?>>
          </div>
        </div>
        <?php endfor; ?>

        <div class="form-group row">
          <div class="col-2 offset-6">
            <a class="small js-newProduct" href="#">+ Add new product</a>
          </div>
          <div class="col-4 text-right">
            <input type="hidden" name="method" value="insert">
            <?php /*
              <input type="hidden" name="shop" value="<?php echo $active_site; ?>">
            *//* ?>
            <button type="submit" class="btn btn-primary">Insert Bundle</button>
          </div>
        </div>
      </form>
    </div>
    <div class="tab-pane" id="delivery-rules" role="tabpanel">
      <mark class="highlihght">@TODO</mark> Currently not available
    </div>
  </div>
</div>
*/ ?>

<?php include('inc/footer.inc.php'); ?>