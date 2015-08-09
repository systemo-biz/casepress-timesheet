<?php
/*
Plugin Name: CasePress. Учет времени в делах
Plugin URI: http://casepress.org
Description: Учет времени в делах
Author: CasePress
Author URI: http://casepress.org
GitHub Plugin URI: https://github.com/systemo-biz/casepress-timesheet
GitHub Branch: master
Version: 20150808-3
*/


add_action('comment_form', 'cp_timesheet_form');
function cp_timesheet_form() {
?>
<script type="text/javascript">
	function pokaz(){
	var vid = document.getElementById("descr").style;
	if (vid.display =="none") {vid.display = "block";}
	else {vid.display = "none";}
	}
</script>
<div id="cp_timesheet">
	<p><a href="#" onClick="pokaz(); return false">Трудозатраты</a></p>
	<div id="descr" style="display: none">
		<p>Введите дату:<br>
		<input type="date" id="cp_timesheet_date" name="cp_timesheet_date" size="30" value="<?php echo date( 'Y-m-d', time() ); ?>"/></p>
		<p>Введите часы:<br>
		<input type="number" id="cp_timesheet_hours" name="cp_timesheet_hours" min="0"  size="30" /></p>
		<p>Введите минуты:<br>
		<input type="number" id="cp_timesheet_minutes" name="cp_timesheet_minutes" max="60" min="0"  size="30" /></p>
	</div>
</div>

<?php
}

add_action('comment_post', 'cp_timesheet_value_save');
function cp_timesheet_value_save($comment_id) {
	$time = $_POST[ 'cp_timesheet_hours' ] + round($_POST[ 'cp_timesheet_minutes' ]/60, 2);
	if ( $_POST[ 'cp_timesheet_hours' ] or $_POST[ 'cp_timesheet_minutes' ] ) {
    add_comment_meta( $comment_id, 'cp_timesheet_date', $_POST[ 'cp_timesheet_date' ] );
	add_comment_meta( $comment_id, 'cp_timesheet', $time);
	}
}

add_filter( 'get_comment_text', 'attach_date_to_comment' );

function attach_date_to_comment( $comment_id ) {
        $cp_timesheet_date = get_comment_meta( get_comment_ID(), 'cp_timesheet_date', true );
		$cp_timesheet = get_comment_meta( get_comment_ID(), 'cp_timesheet', true );
        if ($cp_timesheet_date > 0) {
						$comment_id .= '<small><p class="muted">учет времени: '.$cp_timesheet_date.', ';
						$comment_id .=  $cp_timesheet.'</p></small>';
				}
        return $comment_id;
}
?>
