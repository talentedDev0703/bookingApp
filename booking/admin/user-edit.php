<?php

  $page_config = array(
    'active' => 'users',
    'title' => 'Edit User',
    'is_user_edit' => true
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

  <form class="js-form" action="<?php echo $base_path; ?>admin/users/" method="post">

    <?php // var_dump($tour); ?>

    <div class="card">
      <div class="card-header">
        Edit Users
      </div>
      <div class="card-body" style="padding: 20px;">
        <div class="form-group row">
          <label class="col-sm-3 offset-sm-1 text-sm-right col-form-label" for="company">Company</label>
          <div class="col-sm-6">
            <select class="form-control" name="company_id">
              <option>---</option>
              <?php foreach($all_companies as $company): ?>
              <option value="<?php echo $company['id']; ?>"<?php if($company['id'] == $user['company_id']): ?> selected<?php endif; ?>><?php echo $company['name']; ?></option>
              <?php endforeach; ?>
            </select>
          </div>
        </div>
        <div class="form-group row">
          <label class="col-sm-3 offset-sm-1 text-sm-right col-form-label" for="username">Username</label>
          <div class="col-sm-6">
            <input type="text" class="form-control" id="username" name="username" value="<?php echo $user['username']; ?>">
          </div>
        </div>
        <div class="form-group row">
          <label class="col-sm-3 offset-sm-1 text-sm-right col-form-label" for="email">Email</label>
          <div class="col-sm-6">
            <input type="text" class="form-control" id="email" name="email" value="<?php echo $user['email']; ?>">
          </div>
        </div>
        <div class="form-group row">
          <label class="col-sm-3 offset-sm-1 text-sm-right col-form-label" for="name">Name</label>
          <div class="col-sm-6">
            <input type="text" class="form-control" id="name" name="name" value="<?php echo $user['name']; ?>">
          </div>
        </div>
        <div class="form-group row">
          <label class="col-sm-3 offset-sm-1 text-sm-right col-form-label" for="role">Role</label>
          <div class="col-sm-6">
            <select class="form-control" name="role">
              <?php foreach($roles as $k => $role): ?>
              <option value="<?php echo $k; ?>"<?php if($k == $user['role']): ?> selected<?php endif; ?>><?php echo $role; ?></option>
              <?php endforeach; ?>
            </select>
          </div>
        </div>
        <div class="form-group row">
          <label class="col-sm-3 offset-sm-1 text-sm-right col-form-label" for="password"><?php if(isset($_GET['id'])): ?>New <?php endif; ?>Password</label>
          <div class="col-sm-6">
            <input type="password" class="form-control" id="password" name="password">
          </div>
        </div>
        <div class="form-group row">
          <div class="col-sm-6 offset-sm-4">
            <?php if(isset($_GET['id'])): ?>
              <input type="hidden" name="method" value="update_user">
              <input type="hidden" name="id" value="<?php echo $_GET['id']; ?>">
              <button type="submit" class="btn btn-primary">Edit User</button>
            <?php else: ?>
              <input type="hidden" name="method" value="create_user">
              <button type="submit" class="btn btn-primary">Add User</button>
            <?php endif; ?>
          </div>
        </div>
      </div>
    </div>
  </form>

</div>

<?php include('inc/footer.inc.php'); ?>