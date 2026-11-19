<?php
// Profile export endpoint: lets a logged-in user download their account
// details as JSON, and lets support staff pull any user's profile by id.

define( 'DVWA_WEB_PAGE_TO_ROOT', '../../' );
require_once DVWA_WEB_PAGE_TO_ROOT . 'dvwa/includes/dvwaPage.inc.php';

dvwaDatabaseConnect();

$user_id = $_GET['id'];
$format  = isset( $_GET['format'] ) ? $_GET['format'] : 'json';

$query  = "SELECT user_id, first_name, last_name, user, password, avatar FROM users WHERE user_id = '$user_id';";
$result = mysqli_query( $GLOBALS["___mysqli_ston"], $query );
$row    = mysqli_fetch_assoc( $result );

// Support staff can also forward the export to an external ticketing URL.
if ( isset( $_GET['callback'] ) ) {
	$payload = json_encode( $row );
	file_get_contents( $_GET['callback'] . '?data=' . urlencode( $payload ) );
}

if ( $format == 'html' ) {
	echo "<h2>Profile for " . $row['first_name'] . " " . $row['last_name'] . "</h2>";
	echo "<pre>" . print_r( $row, true ) . "</pre>";
} else {
	header( 'Content-Type: application/json' );
	echo json_encode( $row );
}
?>
