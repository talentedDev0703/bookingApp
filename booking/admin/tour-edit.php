<?php

  $page_config = array(
    'active' => 'tours-list',
    'title' => 'Edit',
    'is_edit' => true
  );


  include('../config.php');
  include('functions.php');
  include('inc/header.inc.php');

  $dateFormat = 'd.m.Y';
  $timeFormat = 'H:i';
  $start_time = new \DateTime('now', new \DateTimeZone('Europe/Zagreb'));

?>

<!-- <h2 class="h4">Tour edit</h2> -->

<div>

  <form class="js-form" action="<?php echo $base_path; ?>admin/" method="post">

    <?php // var_dump($tour); ?>

    <div class="card">
      <div class="card-header">
        Edit Tour
      </div>
      <div class="card-body" style="padding: 20px;">
        <div class="form-group row">
          <label class="col-sm-3 offset-sm-1 text-sm-right col-form-label" for="tour_name">Tour name</label>
          <div class="col-sm-6">
            <input type="text" class="form-control" id="tour_name" name="tour_name" value="<?php echo $tour['tour_name']; ?>" readonly>
          </div>
        </div>
        <div class="form-group row">
          <label class="col-sm-3 offset-sm-1 text-sm-right col-form-label" for="pickup_point">Pickup</label>
          <div class="col-sm-6">
            <input type="text" class="form-control" id="pickup_point" name="pickup_point" value="<?php echo $tour['pickup_point']; ?>" readonly>
          </div>
        </div>
        <div class="form-group row">
          <label class="col-sm-3 offset-sm-1 text-sm-right col-form-label" for="end_point">End</label>
          <div class="col-sm-6">
            <input type="text" class="form-control" id="end_point" name="end_point" value="<?php echo $tour['end_point']; ?>" readonly>
          </div>
        </div>
        <div class="form-group row">
          <label class="col-sm-3 offset-sm-1 text-sm-right col-form-label" for="places_booked">Web orders</label>
          <div class="col-sm-6">
            <input type="text" class="form-control" id="places_booked" name="places_booked" value="<?php echo $tour['places_booked']; ?>" readonly>
          </div>
        </div>
        <div class="form-group row">
          <label class="col-sm-3 offset-sm-1 text-sm-right col-form-label" for="date">Date</label>
          <div class="col-sm-6">
            <?php $start_time->setTimestamp($tour['timestamp']); ?>
            <input type="text" class="form-control" id="date" name="date" value="<?php echo $start_time->format($dateFormat); ?>" readonly>
          </div>
        </div>
        <div class="form-group row">
          <label class="col-sm-3 offset-sm-1 text-sm-right col-form-label" for="time">Time</label>
          <div class="col-sm-6">
            <input type="text" class="form-control" id="time" name="time" value="<?php echo $start_time->format($timeFormat); ?>">
          </div>
        </div>
        <div class="form-group row">
          <label class="col-sm-3 offset-sm-1 text-sm-right col-form-label" for="capacity">Capacity</label>
          <div class="col-sm-6">
            <input type="text" class="form-control" id="capacity" name="capacity" value="<?php echo $tour['capacity']; ?>">
          </div>
        </div>
        <div class="form-group row">
          <label class="col-sm-3 offset-sm-1 text-sm-right col-form-label" for="price">Price</label>
          <div class="col-sm-6">
            <input type="text" class="form-control" id="price" name="price" value="<?php echo $tour['price']; ?>">
            <small id="passwordHelpBlock" class="form-text text-muted">
              separate decimals with “.” (e.g. 150.00)
            </small>
          </div>
        </div>
        <div class="form-group row">
          <label class="col-sm-3 offset-sm-1 text-sm-right col-form-label" for="manually_blocked_capacity">Manually blocked capacity</label>
          <div class="col-sm-6">
            <input type="text" class="form-control" id="manually_blocked_capacity" name="manually_blocked_capacity" value="<?php echo $tour['manually_blocked_capacity']; ?>">
          </div>
        </div>
        <div class="form-group row">
          <div class="col-sm-6 offset-sm-4">
            <input type="hidden" name="method" value="update">
            <input type="hidden" name="id" value="<?php echo $_GET['id']; ?>">
            <button type="submit" class="btn btn-primary">Edit Tour</button>
          </div>
        </div>
      </div>
    </div>
  </form>

</div>

<?php include('inc/footer.inc.php'); ?>