<html>
<head>
<title>
<?php

// Include the config settings so we can get the title
include('config.php');

// Show the actual page title specified in the config
echo $page_title;

?>
</title>
<!-- Ask search engines not to index the site -->
<meta name="robots" content="noindex">
<!-- jQuery and CSS components necessary for datatables to function -->
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/jq-2.2.4/dt-1.10.13/b-1.2.4/b-html5-1.2.4/cr-1.3.2/r-2.1.1/datatables.min.css"/>
<script type="text/javascript" src="https://cdn.datatables.net/v/dt/jq-2.2.4/dt-1.10.13/b-1.2.4/b-html5-1.2.4/cr-1.3.2/r-2.1.1/datatables.min.js"></script>
<!-- Local stylesheet in case you want to add your own styles -->
<link rel="stylesheet" type="text/css" href="includes/styles.css">
</head>
<body>