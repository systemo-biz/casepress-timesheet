<?php
/*
Plugin Name: CasePress. Учет времени в делах
Plugin URI: http://casepress.org
Description: Учет времени в делах
Author: CasePress
Author URI: http://casepress.org
GitHub Plugin URI: https://github.com/systemo-biz/casepress-timesheet
GitHub Branch: master
Version: 20150810-1
*/


class Case_Timesheet_Singleton {
private static $_instance = null;
private function __construct() {
  add_action('wp_head', array($this, 'hook_css'));
  add_action('comment_form', array($this, 'cp_timesheet_form'));
  add_action('comment_post', array($this, 'cp_timesheet_value_save'));
  add_filter( 'get_comment_text', array($this,'attach_date_to_comment'));

}


  function cp_timesheet_form() {
    ?>
      <div id="cp_timesheet_wrapper">
        <input type="checkbox" id="ts_case_cp" name="ts_case_cp" value="1">
        <label for="ts_case_cp">Указать трудозатраты</label>
      	<div id="ts_case_cp_fields">
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


  function cp_timesheet_value_save($comment_id) {

    if(empty($_POST['ts_case_cp'])) return;

  	$time = $_POST[ 'cp_timesheet_hours' ] + round($_POST[ 'cp_timesheet_minutes' ]/60, 2);
  	if ( $_POST[ 'cp_timesheet_hours' ] or $_POST[ 'cp_timesheet_minutes' ] ) {
       add_comment_meta( $comment_id, 'cp_timesheet_date', $_POST[ 'cp_timesheet_date' ] );
  	   add_comment_meta( $comment_id, 'cp_timesheet', $time);
  	}
  }


  function attach_date_to_comment( $comment_id ) {
      $cp_timesheet_date = get_comment_meta( get_comment_ID(), 'cp_timesheet_date', true );
  		$cp_timesheet = get_comment_meta( get_comment_ID(), 'cp_timesheet', true );
      if ($cp_timesheet_date > 0) {
  				$comment_id .= '<small><p class="muted">учет времени: '.$cp_timesheet_date.', ';
  				$comment_id .=  $cp_timesheet.'</p></small>';
  		}
      return $comment_id;
  }


    function hook_css() {
      ?>
      	<style id="toggle_case_result_style" type="text/css">

          #cp_timesheet_wrapper #ts_case_cp ~ #ts_case_cp_fields {
             display: none;
           }

           #cp_timesheet_wrapper #ts_case_cp:checked ~ #ts_case_cp_fields {
              display: block;
            }
        </style>
      <?php

    }


protected function __clone() {
	// ограничивает клонирование объекта
}
static public function getInstance() {
	if(is_null(self::$_instance))
	{
	self::$_instance = new self();
	}
	return self::$_instance;
}
} $Case_Timesheet = Case_Timesheet_Singleton::getInstance();
