<?php
setcookie(md5('login_info'), '', time() - 3600);
?>
<script>
	parent.location.reload();
</script>