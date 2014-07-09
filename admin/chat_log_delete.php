<?php
include_once 'init.php';
if ( empty($in['seq']) ) return alert("삭제할 데이터를 선택하세요.");

if ( empty($_member) ) return alert("로그인을 해 주세요.");

$q = array();
foreach ( $in['seq'] as $seq ) {
	$q[] = "`seq`=".$seq;
}

if ( $q ) {
	$conds = " WHERE " . implode( " OR ", $q );
	
	$query = "DELETE FROM chat_log $conds";
	
	if ( $db->query($query) ) {
		echo "
			<script>
				parent.location.reload();
			</script>
		";
	}
}
?>