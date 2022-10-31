<?php

  $page_config = array(
    'active' => 'tours-list',
    'title' => 'Book Tour',
    'is_book_tour' => true
  );


  include('../config.php');
  include('functions.php');
  include('inc/header.inc.php');

  $dateFormat = 'd.m.Y H:i';
  $start_time = new \DateTime('now', new \DateTimeZone('Europe/Zagreb'));
  $start_time->setTimestamp($tour['timestamp']);
?>

<!-- <h2 class="h4">Tour edit</h2> -->

<div>

  <form class="js-form" action="<?php echo $base_path; ?>book-tour/" method="post">

    <input type="hidden" name="id" value="<?php echo $_GET['id']; ?>">

    <?php // var_dump($tour); ?>

    <div class="card">
      <div class="card-header">
        Book Tour (<?php echo $tour['tour_name']; ?> from <?php echo $tour['pickup_point']; ?> at <?php echo $start_time->format($dateFormat); ?>)
      </div>
      <div class="card-body" style="padding: 20px;">
        <div class="form-group row">
          <label class="col-sm-3 offset-sm-1 text-sm-right col-form-label" for="b2b_impersonate">Company name</label>
          <div class="col-sm-6">
            <select class="form-control" name="b2b_impersonate" id="b2b_impersonate">
              <option>---</option>
              <?php foreach($all_companies as $k => $single_company): ?>
              <option value="<?php echo $single_company['id']; ?>"><?php echo $single_company['name']; ?></option>
              <?php endforeach; ?>
            </select>
          </div>
        </div>
        <div class="form-group row">
          <label class="col-sm-3 offset-sm-1 text-sm-right col-form-label" for="adults">Persons</label>
          <div class="col-sm-2">
            <input type="text" class="form-control" id="adults" name="adults" placeholder="Adults*">
          </div>
          <div class="col-sm-2">
            <input type="text" class="form-control" id="kids" name="kids" placeholder="Kids">
          </div>
          <div class="col-sm-2">
            <input type="text" class="form-control" id="infants" name="infants" placeholder="Infants">
          </div>
        </div>
        <div class="form-group row">
          <label class="col-sm-3 offset-sm-1 text-sm-right col-form-label" for="name">Person name</label>
          <div class="col-sm-6">
            <input type="text" class="form-control" id="name" name="name">
          </div>
        </div>
        <div class="form-group row">
          <label class="col-sm-3 offset-sm-1 text-sm-right col-form-label" for="email">Email</label>
          <div class="col-sm-6">
            <input type="text" class="form-control" id="email" name="email">
          </div>
        </div>
        <div class="form-group row">
          <label class="col-sm-3 offset-sm-1 text-sm-right col-form-label" for="phone">Phone</label>
          <div class="col-sm-6">
            <input type="text" class="form-control" id="phone" name="phone">
          </div>
        </div>
        <div class="form-group row">
          <div class="col-sm-6 offset-sm-4">
            <button type="submit" class="btn btn-primary">Make Order</button>
          </div>
        </div>
      </div>
    </div>
  </form>

</div>

<?php include('inc/footer.inc.php'); ?>