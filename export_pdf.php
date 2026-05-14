<?php
require_once 'includes/config.php';
require_login();

$query = [];
if (!empty($_GET['report_id'])) {
    $query['report_id'] = (int) $_GET['report_id'];
}
if (!empty($_GET['valuation_id'])) {
    $query['valuation_id'] = (int) $_GET['valuation_id'];
}
if (empty($query) && !empty($_GET['id'])) {
    $query['valuation_id'] = (int) $_GET['id'];
}

$target = 'view_report.php';
if (!empty($query)) {
    $target .= '?' . http_build_query($query);
}

redirect($target);
?>
