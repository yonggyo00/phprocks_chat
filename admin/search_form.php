<?php
if ( $_member ) {
	ob_start();
	$op = array(1, 5, 10, 30, 50, 100, 150, 200, 300, 500, 1000);
?>	
	<select name='no_of_posts'>
		<option value=''></option>
		<option value=''>행수</option>
	<?php
		foreach( $op as $o ) {
			$selected = null;
			if ( $o == $in['no_of_posts'] ) $selected = "selected";
			
			echo "<option value={$o} {$selected}>{$o}</option>";
		}
	?>
	</select>
<?php	
	$sel_no_of_posts = ob_get_clean();
?>
<form method='get' autocomplete='off'>
	<fieldset>
		<legend>검색 날짜</legend>
		<span class='sub-title'>시작일</span> <input type='text' class='datepicker' name='date_begin' value='<?=$in['date_begin']?>' placeholder='시작일'/>
		<span class='sub-title'>종료일</span> <input type='text' class='datepicker' name='date_end' value='<?=$in['date_end']?>' placeholder='종료일' />
	</fieldset>
	<fieldset>
		<input type='checkbox' name='mode' value=1 <?=$in['mode']?"checked":""?> /> 귓속말 
		<span class='sub-title'>페이지당 행수</span> <?=$sel_no_of_posts?>
		<span class='sub-title'>아이디 또는 닉네임</span> <input type='text' name='username' value='<?=$in['username']?>' placeholder='아이디 또는 닉네임' />
		<span class='sub-title'>방이름</span> <input type='text' name='roomname' value='<?=$in['roomname']?>' placeholder='방이름' />
		<div id='keyword'>
			<span class='sub-title'>키워드</span> <input type='text' name='keyword' value='<?=$in['keyword']?>' placeholder='검색할 키워드를 입력하세요'/>
			<input type='submit' value='검색' />
		</div>
	</fieldset>
</form>
<?php
	$q = array();
	if ( $in['date_begin'] ) $q[] = "stamp >= ".date2stamp($in['date_begin']);
	if ( $in['date_end'] ) $q[] = "stamp < ".date2stamp($in['date_end']);

	if ( $in['username'] ) {
		$q[] = "( from_user LIKE '%".$in['username']."%' OR from_nick LIKE '%".$in['username']."%' )";
	}

	if ( $in['mode'] ) $q[] = "mode = 1";
	if ( $in['keyword'] ) $q[] = "message LIKE '%".$in['keyword']."%'";
	if ( $in['roomname'] ) $q[] = "roomname LIKE '%".$in['roomname']."%'";
	
	$conds = null;
	if ( $q ) {
		$conds = " WHERE " . implode(' AND ', $q );
	}

	$result = $db->query("SELECT COUNT(*) as cnt FROM chat_log $conds");
	$row = $result->fetch_assoc();
		
	$total_post = $row['cnt'];
	if ( !$no_of_post = $in['no_of_posts'] ) $no_of_post = 30;
		
	if ( empty($in['page_no']) ) $in['page_no'] = 1;
		
	$start = ( $in['page_no'] - 1 );
		
	$query = "SELECT * FROM chat_log $conds ORDER BY stamp DESC LIMIT $start,$no_of_post";
	$rows = db_rows ( $query );

}?>
