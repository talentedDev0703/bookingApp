<!doctype html>
<html lang="en" class="no-js lang-en">
<head>

  <meta charset="utf-8">
  <title><?php echo $page_config['title'] ? $page_config['title'] . ' - ' : ''; ?>Adriatic Sunsets</title>

  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/css/bootstrap.min.css" integrity="sha384-rwoIResjU2yc3z8GV/NPeZWAv56rSmLldC3R/AZzGRnGxQQKnKkoFVhFQhNUwEyJ" crossorigin="anonymous">

  <link rel="stylesheet" href="<?php echo $base_path; ?>admin/css/global.css?v=1">

</head>

<body>

  <div class="container">

    <header class="pt-5">

      <nav class="navbar navbar-inverse navbar-toggleable-md navbar-light bg-faded bg-primary rounded mt-5">
        <button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" data-target="#navbarText" aria-controls="navbarText" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
        <a class="navbar-brand" href="<?php echo $base_path; ?>admin/">Booking Manager</a>
        <div class="collapse navbar-collapse" id="navbarText">
          <a href="https://www.adriaticsunsets.com/" class="navbar-text">
            Adriatic Sunsets
          </a>
          <ul class="navbar-nav ml-auto">
            <li class="nav-item<?php if($page_config['active'] == 'tours-list'): ?> active<?php endif; ?>">
              <a class="nav-link" href="<?php echo $base_path; ?>admin/">Tours</a>
            </li>
            <li class="nav-item<?php if($page_config['active'] == 'orders-list'): ?> active<?php endif; ?>">
              <a class="nav-link" href="<?php echo $base_path; ?>admin/orders/">Orders</a>
            </li>
            <li class="nav-item<?php if($page_config['active'] == 'reporting'): ?> active<?php endif; ?>">
              <a class="nav-link" href="<?php echo $base_path; ?>admin/reporting/">Reporting</a>
            </li>
            <li class="nav-item<?php if($page_config['active'] == 'companies'): ?> active<?php endif; ?>">
              <a class="nav-link" href="<?php echo $base_path; ?>admin/companies/">Companies</a>
            </li>
            <li class="nav-item<?php if($page_config['active'] == 'users'): ?> active<?php endif; ?>">
              <a class="nav-link" href="<?php echo $base_path; ?>admin/users/">Users</a>
            </li>
          </ul>
        </div>
      </nav>

    </header>

    <main class="main">