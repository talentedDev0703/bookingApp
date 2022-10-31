<?php

  $page_config = array(
    'active' => 'reporting',
    'title' => 'Reporting',
    'is_reporting' => true
  );

  include('../config.php');
  include('functions.php');
  include('inc/header.inc.php');

?>

<div>

  <h2 class="h4 d-inline align-middle">B2B Reporting</h2>

</div>

<div class="mt-5">

  <ul class="nav nav-tabs" role="tablist">
    <li class="nav-item">
      <a class="nav-link active" data-toggle="tab" href="#current-month">Current month</a>
    </li>
    <li class="nav-item">
      <a class="nav-link" data-toggle="tab" href="#prev-month">Previous month</a>
    </li>
  </ul>

  <div class="tab-content mt-4">
    <div class="tab-pane active" id="current-month" role="tabpanel">

      <p class="mt-5 lead">Total B2B sales in <?php echo date('F'); ?>: <strong><mark><?php echo number_format($total_sales, 2, ',', '.'); ?> kn</mark></strong></p>

      <?php if($all_companies): ?>
      <table class="table js-periodTable table-striped mt-5">
        <thead>
          <tr>
            <th>Company</th>
            <th class="text-right">Sold tours</th>
            <th class="text-right">Commission (kn)</th>
            <th class="text-right">Total (kn)</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach($all_companies as $company): ?>
          <tr>
            <td><?php echo $company['name']; ?></td>
            <td class="text-right"><?php echo $company['tours']; ?></td>
            <td class="text-right"><?php echo number_format($company['value']*$company['commission']/100, 2, ',', '.'); ?></td>
            <td class="text-right"><?php echo number_format($company['value'], 2, ',', '.'); ?></td>
          </tr>
          <?php endforeach; ?>
        </tbody>
        <?php /*
        <tfoot>
          <tr>
            <td colspan="3" class="text-right">
              <strong>Total B2B sales: <?php echo number_format($total_sales, 2, ',', '.'); ?></strong>
            </td>
          </tr>
        </tfoot>
        */ ?>
      </table>
      <?php else: ?>
      <div class="mt-3">
        <div class="alert alert-danger" role="alert">
          There are no B2B sales!
        </div>
      </div>
      <?php endif; ?>

    </div>
    <div class="tab-pane" id="prev-month" role="tabpanel">

      <p class="mt-5 lead">Total B2B sales in <?php echo date('F', strtotime ( '-1 month' )); ?>: <strong><mark><?php echo number_format($prev_total_sales, 2, ',', '.'); ?> kn</mark></strong></p>

      <?php if($prev_all_companies): ?>
      <table class="table js-periodTable table-striped mt-5">
        <thead>
          <tr>
            <th>Company</th>
            <th class="text-right">Sold tours</th>
            <th class="text-right">Commission (kn)</th>
            <th class="text-right">Total (kn)</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach($prev_all_companies as $company): ?>
          <tr>
            <td><?php echo $company['name']; ?></td>
            <td class="text-right"><?php echo $company['tours']; ?></td>
            <td class="text-right"><?php echo number_format($company['value']*$company['commission']/100, 2, ',', '.'); ?></td>
            <td class="text-right"><?php echo number_format($company['value'], 2, ',', '.'); ?></td>
          </tr>
          <?php endforeach; ?>
        </tbody>
        <?php /*
        <tfoot>
          <tr>
            <td colspan="3" class="text-right">
              <strong>Total B2B sales: <?php echo number_format($total_sales, 2, ',', '.'); ?></strong>
            </td>
          </tr>
        </tfoot>
        */ ?>
      </table>
      <?php else: ?>
      <div class="mt-3">
        <div class="alert alert-danger" role="alert">
          There are no B2B sales!
        </div>
      </div>
      <?php endif; ?>

    </div>
  </div>
</div>

<?php include('inc/footer.inc.php'); ?>