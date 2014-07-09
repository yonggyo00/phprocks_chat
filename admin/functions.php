<?php
function di ( $v ) {
	echo "<pre>";
	print_r ( $v );
	echo "</pre>";
}

function alert( $msg ) {
	echo "
		<script>
			alert('$msg');
		</script>
	";
}

function location ( $msg, $url ) {
	echo "
		<script>
			alert('$msg');
			window.location.href='$url';
		</script>
	";
}

function db_rows ( $query ) {
	global $db;
	
	$result = $db->query($query);
	$rows = array();
	while ( $row =  $result->fetch_assoc() ) {
		$rows[] = $row;
	}
	
	$result->free();
	
	return $rows;
}

function date2stamp ( $date = null ) {
	if ( empty ( $date ) ) $date = date('Y-m-d');
	
	$date_split = explode( '-', $date );

	return $stamp = mktime ( 0, 0, 0, $date_split[1], $date_split[2], $date_split[0] );
}
?>