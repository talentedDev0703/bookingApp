<?php

    $datetimeFormat = 'd.m.Y H:i';
    $today = new \DateTime('today', new \DateTimeZone('Europe/Zagreb'));

    $post_vars = array();

    if ( $_POST ) {

        $post_vars = $_POST;

    }

    $get_vars = array();

    if ( $_GET ) {

        $get_vars = $_GET;

    }

    $conn = new mysqli($servername, $username, $password, $database);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $cancel_id = 0;

    if ( isset($_GET['cancel']) ) {

        $cancel_id = $_GET['cancel'];

    }

    $disable_user = 0;

    if ( isset($_GET['disable_user']) ) {

        $disable_user = $_GET['disable_user'];

    }

    // DELETE IF THERE IS ANYTHING TO DELETE

    $cancel_success = false;

    if( $cancel_id ) {

        $sql = "UPDATE tours SET active = 0 WHERE id = " . $cancel_id;

        if ( $conn->query($sql) === TRUE ) {

            $cancel_success = true;

        }

    }

    $disable_user_success = false;

    if( $disable_user ) {

        $sql = "UPDATE users SET active = 0 WHERE id = " . $disable_user;

        if ( $conn->query($sql) === TRUE ) {

            $disable_user_success = true;

        }

    }

    $activate_id = 0;

    if ( isset($_GET['activate']) ) {

        $activate_id = $_GET['activate'];

    }

    $activate_user = 0;

    if ( isset($_GET['activate_user']) ) {

        $activate_user = $_GET['activate_user'];

    }

    // ACTIVATE IF THERE IS ANYTHING TO ACTIVATE

    $activate_success = false;

    if( $activate_id ) {

        $sql = "UPDATE tours SET active = 1 WHERE id = " . $activate_id;

        if ( $conn->query($sql) === TRUE ) {

            $activate_success = true;

        }

    }

    $activate_user_success = false;

    if( $activate_user ) {

        $sql = "UPDATE users SET active = 1 WHERE id = " . $activate_user;

        if ( $conn->query($sql) === TRUE ) {

            $activate_user_success = true;

        }

    }

    // MAKE ORDER IF THERE IS ANYTHING

    /*if( isset($post_vars['method']) && $post_vars['method'] == 'make_order' ) {


      $url = 'https://www.adriaticsunsets.com/booking/book-tour';

      $options = array(
        'http' => array(
          'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
          'method'  => 'POST',
          'content' => http_build_query($post_vars)
        )
      );

      $context  = stream_context_create($options);
      $result = file_get_contents($url, false, $context);

      //if ($result === FALSE) { }

      var_dump($result);

    }*/

    // INSERT IF THERE IS ANYTHING TO INSERT


    $insert_success = false;

    /*if( isset($post_vars['method']) && $post_vars['method'] == 'insert' ) {

        $sql = "
            INSERT
            INTO bundle_tool
            (
                id,
                bundle_name,
                bundle_sku,
                product_sku,
                quantity,
                price,
                shop
            )
            VALUES
            (
                NULL,
                '" . $post_vars['bundleName'] . "',
                '" . $post_vars['bundleSku'] . "',
                '" . implode(',', array_filter($post_vars['productSku'])) . "',
                '" . implode(',', array_filter($post_vars['qty'])) . "',
                '" . implode(',', array_filter($post_vars['price'])) . "',
                '" . $active_site . "'
            );";

        if ( $conn->query($sql) === TRUE ) {

            $insert_success = true;

        }

    } elseif( isset($post_vars['method']) && $post_vars['method'] == 'update' ) {*/

    if( isset($post_vars['method']) && $post_vars['method'] == 'update_company' ) {

        $sql = "
            UPDATE companies
            SET
                name = '" . $post_vars['name'] . "',
                address = '" . $post_vars['address'] . "',
                oib = '" . $post_vars['oib'] . "',
                commission = '" . $post_vars['commission'] . "',
                voucher_format = '" . $post_vars['voucher_format'] . "'
            WHERE
                id = " . $post_vars['id'];

        // echo $sql;

        if ( $conn->query($sql) === TRUE ) {

            $insert_success = true;

        }

    } elseif( isset($post_vars['method']) && $post_vars['method'] == 'create_company' ) {

        $sql = "
            INSERT INTO companies
                (id, name, address, oib, commission)
            VALUES
                (NULL, '" . $post_vars['name'] . "', '" . $post_vars['address'] . "', '" . $post_vars['oib'] . "', '" . $post_vars['commission'] . "')";

        // echo $sql;

        if ( $conn->query($sql) === TRUE ) {

            $insert_success = true;

        }

    } elseif( isset($post_vars['method']) && $post_vars['method'] == 'create_user' ) {

        $sql = "
            INSERT INTO users
                (id, company_id, username, email, password, name, role, active)
            VALUES
                (NULL, '" . $post_vars['company_id'] . "', '" . $post_vars['username'] . "', '" . $post_vars['email'] . "', '" . sha1($post_vars['password']) . "', '" . $post_vars['name'] . "', '" . $post_vars['role'] . "', 1)";

        // echo $sql;

        if ( $conn->query($sql) === TRUE ) {

            $insert_success = true;

        }

    } elseif( isset($post_vars['method']) && $post_vars['method'] == 'update_user' ) {

        $update_pass = "";

        if($post_vars['password'] != '') {
            $update_pass = ", password = '" . sha1($post_vars['password']) . "'";
        }

        $sql = "
            UPDATE users
            SET
                company_id = '" . $post_vars['company_id'] . "',
                username = '" . $post_vars['username'] . "',
                email = '" . $post_vars['email'] . "',
                name = '" . $post_vars['name'] . "',
                role = '" . $post_vars['role'] . "'
                " . $update_pass . "
            WHERE
                id = " . $post_vars['id'];

        // echo $sql;

        if ( $conn->query($sql) === TRUE ) {

            $insert_success = true;

        }

    } elseif( isset($post_vars['method']) && $post_vars['method'] == 'update' ) {

        $datetime_str = $post_vars['date'] . ' ' . $post_vars['time'];
        $datetime = new \DateTime($datetime_str, new \DateTimeZone('Europe/Zagreb'));

        $sql = "
            UPDATE tours
            SET
                capacity = '" . $post_vars['capacity'] . "',
                price = '" . $post_vars['price'] . "',
                manually_blocked_capacity = '" . $post_vars['manually_blocked_capacity'] . "',
                timestamp = '" . $datetime->format('U') . "'
            WHERE
                id = " . $post_vars['id'];

        // echo $sql;

        if ( $conn->query($sql) === TRUE ) {

            $insert_success = true;

        }

    }

    // SELECT FOR EDIT OR TABLE VIEW SCREEN

    if( isset($page_config['is_edit']) && $page_config['is_edit'] ) {

        // $get_single_row = "SELECT * FROM bundle_tool WHERE id = '" . $_GET['id'] . "' LIMIT 1";
        $get_single_row = "SELECT * FROM tours WHERE id = '" . $_GET['id'] . "' LIMIT 1";

        if ($result = $conn->query( $get_single_row )) {

            $tour = $result->fetch_assoc();

        }

    } elseif( isset($page_config['is_company_edit']) && $page_config['is_company_edit'] ) {

        if(isset($_GET['id'])) {

            $get_single_row = "SELECT * FROM companies WHERE id = '" . $_GET['id'] . "' LIMIT 1";

            if ($result = $conn->query( $get_single_row )) {

                $company = $result->fetch_assoc();

            }

        } else {

            $company = array(
                'id' => '',
                'name' => '',
                'address' => '',
                'oib' => '',
                'commission' => '',
                'voucher_format' => '',
            );

        }

    } elseif( isset($page_config['is_user_edit']) && $page_config['is_user_edit'] ) {

        if(isset($_GET['id'])) {

            $get_single_row = "SELECT * FROM users WHERE id = '" . $_GET['id'] . "' LIMIT 1";

            if ($result = $conn->query( $get_single_row )) {

                $user = $result->fetch_assoc();

            }

        } else {

            $user = array(
                'id' => '',
                'company_id' => '',
                'company' => '',
                'username' => '',
                'email' => '',
                'password' => '',
                'name' => '',
                'role' => '',
                'active' => '',
            );

        }

        $get_rows_query = "SELECT id, name FROM companies";

        $all_companies = array();

        if ($result = $conn->query( $get_rows_query )) {

            while($row = $result->fetch_assoc()) {

                $all_companies[] = $row;

            }
        }

    } elseif(isset($page_config['is_companies']) && $page_config['is_companies']) {


        $get_rows_query = "SELECT * FROM companies";

        $all_companies = array();

        if ($result = $conn->query( $get_rows_query )) {

            while($row = $result->fetch_assoc()) {

                $all_companies[] = $row;

            }
        }

    } elseif(isset($page_config['is_book_tour']) && $page_config['is_book_tour']) {

        if(!isset($_GET['id'])) {
            die('Please define tour');
        }

        $get_single_row = "SELECT * FROM tours WHERE id = '" . $_GET['id'] . "' LIMIT 1";

        if ($result = $conn->query( $get_single_row )) {

            $tour = $result->fetch_assoc();

        }

        $get_rows_query = "SELECT id, name FROM companies";

        $all_companies = array();

        if ($result = $conn->query( $get_rows_query )) {

            while($row = $result->fetch_assoc()) {

                $all_companies[] = $row;

            }
        }

    } elseif( isset($page_config['is_users']) && $page_config['is_users'] ) {


        $get_rows_query = "SELECT u.*, c.name as company_name FROM users as u LEFT JOIN companies as c ON u.company_id = c.id ORDER BY id";

        $all_users = array();

        if ($result = $conn->query( $get_rows_query )) {

            while($row = $result->fetch_assoc()) {

                $all_users[] = $row;

            }
        }

    } elseif( isset($page_config['is_orders']) && $page_config['is_orders'] ) {

        $get_rows_query = "SELECT o.*, c.name as company_name, t.tour_name, t.pickup_point, t.timestamp as tour_timestamp FROM orders o LEFT join companies c ON c.id = o.company_id LEFT JOIN tours t ON t.id = o.tour_id ORDER BY o.id DESC";

        $all_orders = array();

        if ($result = $conn->query( $get_rows_query )) {

            while($row = $result->fetch_assoc()) {

                $all_orders[] = $row;

            }
        }


    } elseif( isset($page_config['is_reporting']) && $page_config['is_reporting'] ) {

        // This month by default
        $report_time = new \DateTime('first day of this month 00:00:00', new \DateTimeZone('Europe/Zagreb'));

        $get_rows_query =  "SELECT
                                c.name,
                                c.commission,
                                SUM(o.value) as value,
                                COUNT(*) as tours
                            FROM
                                orders o
                            LEFT JOIN
                                companies c
                            ON
                                o.company_id = c.id
                            WHERE
                                timestamp >= '" . $report_time->format('U') . "'
                            AND
                                o.company_id > 0
                            AND
                                o.paid = 1
                            AND
                                o.cancelled = 0
                            GROUP BY
                                company_id
                            ORDER BY
                                value DESC";

        $all_companies = array();
        $total_sales = 0;

        if ($result = $conn->query( $get_rows_query )) {

            while($row = $result->fetch_assoc()) {

                $all_companies[] = $row;

                $total_sales += (float)$row['value'];

            }
        }

        $prev_report_time = new \DateTime('first day of last month 00:00:00', new \DateTimeZone('Europe/Zagreb'));

        $get_rows_query =  "SELECT
                                c.name,
                                c.commission,
                                SUM(o.value) as value,
                                COUNT(*) as tours
                            FROM
                                orders o
                            LEFT JOIN
                                companies c
                            ON
                                o.company_id = c.id
                            WHERE
                                timestamp >= '" . $prev_report_time->format('U') . "'
                            AND
                                timestamp < '" . $report_time->format('U') . "'
                            AND
                                o.company_id > 0
                            AND
                                o.cancelled = 0
                            GROUP BY
                                company_id
                            ORDER BY
                                value DESC";

        $prev_all_companies = array();
        $prev_total_sales = 0;

        if ($result = $conn->query( $get_rows_query )) {

            while($row = $result->fetch_assoc()) {

                $prev_all_companies[] = $row;

                $prev_total_sales += (float)$row['value'];

            }
        }

    } else {

        $search = array(
            'is_search' => false,
            'tour_name' => '',
            'pickup_point' => '',
            'date' => '',
        );

        if( isset($get_vars['search']) && $get_vars['search'] == '1' ) {

            $search['is_search'] = true;

            $search = array(
                'is_search' => true,
                'tour_name' => $get_vars['tour_name'] ? trim($get_vars['tour_name']) : '',
                'pickup_point' => $get_vars['pickup_point'] ? trim($get_vars['pickup_point']) : '',
                'date' => $get_vars['date'] ? trim($get_vars['date']) : '',
            );

            $where = '';

            if($search['tour_name']) {
                $where = " tour_name LIKE '" . $search['tour_name'] . "'";
            }

            if($search['pickup_point']) {
                if($where != '') {
                    $where .= " AND";
                }
                $where .= " pickup_point LIKE '" . $search['pickup_point'] . "'";
            }

            if($search['date']) {

                $date_obj = DateTime::createFromFormat('d.m.Y', $search['date']);
                $search_date = $date_obj->format('Y-m-d');

                if($where != '') {
                    $where .= " AND";
                }
                $where .= " date LIKE '" . $search_date . "'";
            }

            if($where == '') {
                $where = '1 = 1';
            }

            $get_rows_query = "SELECT * FROM tours WHERE " . $where . " ORDER BY timestamp LIMIT 100";

        } else {

            $get_rows_query = "SELECT * FROM tours WHERE timestamp >= " . $today->format('U') . " ORDER BY timestamp LIMIT 100";

        }

        $all_tours = array();

        if ($result = $conn->query( $get_rows_query )) {

            while($row = $result->fetch_assoc()) {

                $all_tours[] = $row;

            }
        }
    }

    $conn->close();
