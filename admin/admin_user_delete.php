<?php
include_once 'init.php';
if ( empty($in['seq']) ) return alert("잘못된 접근 입니다.");
else {
	if ( $db->query("DELETE FROM admin WHERE `seq`={$in[seq]}") ) {
		echo "
			<script>
				alert('삭제 되었습니다.');
				parent.location.reload();
			</script>
		";
	}
}
?> 
