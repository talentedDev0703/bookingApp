<?php

  $page_config = array(
    'active' => 'companies',
    'title' => 'Edit Company',
    'is_company_edit' => true
  );


  include('../config.php');
  include('functions.php');
  include('inc/header.inc.php');

  // $dateFormat = 'd.m.Y';
  // $timeFormat = 'H:i';
  // $start_time = new \DateTime('now', new \DateTimeZone('Europe/Zagreb'));

?>

<!-- <h2 class="h4">Tour edit</h2> -->

<div>

  <form class="js-form" action="<?php echo $base_path; ?>admin/companies/" method="post">

    <?php // var_dump($tour); ?>

    <div class="card">
      <div class="card-header">
        Edit Company
      </div>
      <div class="card-body" style="padding: 20px;">
        <div class="form-group row">
          <label class="col-sm-3 offset-sm-1 text-sm-right col-form-label" for="name">Company name</label>
          <div class="col-sm-6">
            <input type="text" class="form-control" id="name" name="name" value="<?php echo $company['name']; ?>">
          </div>
        </div>
        <div class="form-group row">
          <label class="col-sm-3 offset-sm-1 text-sm-right col-form-label" for="address">Address</label>
          <div class="col-sm-6">
            <input type="text" class="form-control" id="address" name="address" value="<?php echo $company['address']; ?>">
          </div>
        </div>
        <div class="form-group row">
          <label class="col-sm-3 offset-sm-1 text-sm-right col-form-label" for="oib">OIB</label>
          <div class="col-sm-6">
            <input type="text" class="form-control" id="oib" name="oib" value="<?php echo $company['oib']; ?>">
          </div>
        </div>
        <div class="form-group row">
          <label class="col-sm-3 offset-sm-1 text-sm-right col-form-label" for="commission">Commission</label>
          <div class="col-sm-6">
            <input type="text" class="form-control" id="commission" name="commission" value="<?php echo $company['commission']; ?>">
            <small id="passwordHelpBlock" class="form-text text-muted">
              in percentage without (%)
            </small>
          </div>
        </div>
        <div class="form-group row">
          <label class="col-sm-3 offset-sm-1 text-sm-right col-form-label" for="voucher_format">Voucher format</label>
          <div class="col-sm-6">
            <select class="form-control" name="voucher_format" id="voucher_format">
              <option>---</option>
              <?php foreach($voucher_formats as $k => $format): ?>
              <option value="<?php echo $k; ?>"<?php if($k == $company['voucher_format']): ?> selected<?php endif; ?>><?php echo $format; ?></option>
              <?php endforeach; ?>
            </select>
          </div>
        </div>
        <div class="form-group row">
          <div class="col-sm-6 offset-sm-4">
            <?php if(isset($_GET['id'])): ?>
              <input type="hidden" name="method" value="update_company">
              <input type="hidden" name="id" value="<?php echo $_GET['id']; ?>">
              <button type="submit" class="btn btn-primary">Edit Company</button>
            <?php else: ?>
              <input type="hidden" name="method" value="create_company">
              <button type="submit" class="btn btn-primary">Add Company</button>
            <?php endif; ?>
          </div>
        </div>
      </div>
    </div>
  </form>

</div>

<?php include('inc/footer.inc.php'); ?>