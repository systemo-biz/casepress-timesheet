<?php
/*
Plugin Name: TimeSheet for CasePress
Version: 1.1
Author: CasePress
Author URI: http://casepress.org/
*/
 
/*  Copyright 2013  CasePress
 
    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.
 
    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.
 
    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/
add_action('comment_form', 'cp_timesheet_form');
function cp_timesheet_form() {
?>
<script type="text/javascript">
function pokaz(){
var vid = document.getElementById("descr").style;
if (vid.visibility =="hidden") {vid.visibility = "visible";}
else {vid.visibility = "hidden";}
}
</script>
<div id="cp_timesheet">
	<p><a href="#" onClick="pokaz(); return false">Трудозатраты</a></p>
	<div id="descr" style="visibility: hidden">
		<p>Введите дату:<br>
		<input type="date" id="cp_timesheet_date" name="cp_timesheet_date" value="<?php echo date( 'Y-m-d', time() ); ?>"/></p>
		<p>Введите часы:<br>
		<input type="number" id="cp_timesheet_hours" name="cp_timesheet_hours" min="0" /></p>
		<p>Введите минуты:<br>
		<input type="number" id="cp_timesheet_minutes" name="cp_timesheet_minutes" max="60" min="0" /></p>
	</div>
</div>
 
<?php
}

add_action('comment_post', 'cp_timesheet_value_save');
function cp_timesheet_value_save($comment_id) {
	$time = $_POST[ 'cp_timesheet_hours' ] + $_POST[ 'cp_timesheet_minutes' ]/60; 
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