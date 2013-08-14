<?php
/*
Plugin Name: Сp-Timesheet
Version: 1.0
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
add_action('comment_post', 'cp_timesheet_value_save');
add_filter( 'get_comment_text', 'attach_date_to_comment' );
add_filter( 'get_comment_text', 'attach_value_to_comment' );
 
function cp_timesheet_form() {
/* это все бесполезный код, который тут нафиг не нужен
$cp_timesheet_date = strtotime($_POST['cp_timesheet_date']);
        $value = "";
       
        if ($cp_timesheet_date > 0) {
                $value = date('d.m.Y', $cp_timesheet_date);
        }
*/
 
/*нужно тупо в переменную $value поместить текущую дату в нужном формате*/
$value = date( 'Y-m-d', time() );
 
?>
<script type="text/javascript">
function pokaz(){
var vid = document.getElementById("descr").style;
if (vid.visibility =="hidden") {vid.visibility = "visible";}
else {vid.visibility = "hidden";}
}
</script>
<p><a href="#" onClick="pokaz(); return false">Трудозатраты</a></p>
<div id="descr" style="visibility: hidden">
<p>Введите дату:<br>
<input type="date" id="cp_timesheet_date" name="cp_timesheet_date" value="<?php echo $value; ?>"/></p>
<p>Введите часы:<br>
<input type="number" id="cp_timesheet_hours" name="cp_timesheet_hours" min="0" /></p>
<p>Введите минуты:<br>
<input type="number" id="cp_timesheet_minutes" name="cp_timesheet_minutes" max="60" min="0" /></p>
</div>
 
<?php
 
}
 
function cp_timesheet_value_save($comment_id) {
	if ( $_POST[ 'cp_timesheet_hours' ] or $_POST[ 'cp_timesheet_minutes' ] ){
    add_comment_meta( $comment_id, 'cp_timesheet_date', $_POST[ 'cp_timesheet_date' ] );
	add_comment_meta( $comment_id, 'cp_timesheet_hours', $_POST[ 'cp_timesheet_hours' ] );
	add_comment_meta( $comment_id, 'cp_timesheet_minutes', $_POST[ 'cp_timesheet_minutes' ] );
	}
}
 
 
function attach_date_to_comment( $comment_id ) {
        $cp_timesheet_date = get_comment_meta( get_comment_ID(), 'cp_timesheet_date', true );
        if ($cp_timesheet_date > 0) {
                $comment_id .= '<small><p class="muted">учет времени: '.$cp_timesheet_date.', ';}                
        return $comment_id;
}
 
 
function attach_value_to_comment( $comment_id ) {
	
	
	
	
    $cp_timesheet_hours = get_comment_meta( get_comment_ID(), 'cp_timesheet_hours', true );
    $cp_timesheet_minutes = get_comment_meta( get_comment_ID(), 'cp_timesheet_minutes', true );
    if ($cp_timesheet_hours && $cp_timesheet_minutes)
                        $comment_id .= round((($cp_timesheet_hours * 60 + $cp_timesheet_minutes)/60) ,2) ." часа</p></small>";
        elseif ($cp_timesheet_hours && !$cp_timesheet_minutes) {
                        if((($cp_timesheet_hours % 10)==2) or (($cp_timesheet_hours % 10)==3) or (($cp_timesheet_hours % 10)==4))
                        $comment_id .= $cp_timesheet_hours."  часа</p></small>";    
                        elseif (($cp_timesheet_hours % 10)==1)
                        $comment_id .= $cp_timesheet_hours."  час</p></small>";
                        else $comment_id .= $cp_timesheet_hours."  часов</p></small>";
        }
        elseif (!$cp_timesheet_hours && $cp_timesheet_minutes) {
                        if((($cp_timesheet_minutes % 10)==2) or (($cp_timesheet_minutes % 10)==3) or (($cp_timesheet_minutes % 10)==4))
                        $comment_id .= ", ".$cp_timesheet_minutes."  минуты</p></small>";
                        elseif (($cp_timesheet_minutes % 10)==1)
                        $comment_id .= ", ".$cp_timesheet_minutes."  минута</p></small>";
                        else $comment_id .= ", ".$cp_timesheet_minutes."  минут</p></small>";
        }
        return $comment_id;
}
?>