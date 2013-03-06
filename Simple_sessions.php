<?php if(!defined('BASEPATH')) exit('No direct script access allowed');
/**********************************************************************************************
 * Author: Jorge Torres - jotorres1@gmail.com
 * Website: http://www.jotorres.com 
 * Date:   1/1/2012
 * Version: 2.0
 * Description:  Simple sessions class that can be used with regular PHP projects
 *  			 or can be integrated as a custom library for codeigniter framework.
 * Documentation: http://www.jotorres.com/2012/06/simple-sessions-class/ 
 ***********************************************************************************************/

class Simple_sessions{
	
	private $my_sess;
	
	/****************************************
	 * Private variable for session max time
	 ****************************************/
	private $_maxtime;
	
	/****************************************
	 * Private variable for the current time
	 ****************************************/
	private $_current;
	
	
	public function  __construct( $sess_name = 'simple_sessions', $_timeout = FALSE, $max = 300, $login_page = 'login.php' ){
		// Name the session
		session_name( $sess_name );
		// Start the session
		session_start();
		// Pass session variables to my_sess attribute
		// Session is passed by reference, in order
		// to edit global session variable directly
		$this->my_sess = &$_SESSION;
		
		// Verify if timeout is enabled or not
		if( $_timeout ){
			// Run inactivity logic 
			$this->verify_inactivity( $max, $login_page );
		}
	}
	
	public function add_sess($data = array()){		
		
		if(is_array($data) && count($data) > 0){
			// If an array was passed,
			//  then grab all associative names
			//  and their respective values
			//  and place them in session variable
			foreach($data as $key => $value){
				$this->my_sess[$key] = $value;
			}
		}		
	}
	
	public function del_sess($name){
		// Unset the session variable sent
		unset($this->my_sess[$name]);
	}
	
	public function get_sess_id(){
		// Return the session id
		return session_id();
	}
	
	public function edit_sess($name, $value){
		// Edit an existing session variable
		// Will create one if it does not exists
		$this->my_sess[$name] = $value;
	}
	
	public function check_sess($name){
		// Verify if a session variable already exists
		return isset($this->my_sess[$name]);
	}
	
	public function get_value($name){
		// First verify if exists
		if($this->check_sess($name)){
			// if such session exists
			// then return the value
			return $this->my_sess[$name];
		}
		// otherwise return false
		return false;
	}
	
	public function destroy_sess(){
		// Emtpy out the sessions array
		// then destroy the whole session
		$this->my_sess = array();
		session_destroy();
	}
	
	/*****************************************
	 * Logic to verify user inactivity
	******************************************/
	
	private function verify_inactivity( $max, $login ){
		
		if( ! $this->check_sess( 'Activity_Time' ) ){
			// Add Activity time session variable
			$this->add_sess( array( 'Activity_Time' => time() ) );
		}
		// Set Max time in seconds
		// 300  =  5   minutes
		// 600  =  10  minutes
		// 1200 =  20  minutes
		$this->_maxtime = $max;
		
		// Set the current time
		$this->_current = time();
		
		// Set session Life
		$session_life = $this->_current - $this->get_value( 'Activity_Time' );
		
		// Verify that session has not expired
		// If expired, send to login page
		if( $session_life > $this->_maxtime ){
			// Get the page just visited
			$ref = urlencode( $_SERVER['PHP_SELF'] );
			// Destroy the session
			$this->destroy_sess();
			// Redirect the user to login page
			header( 'Location: '.$login.'r='.$ref );
			exit;
		}
		else{
			// If session has not expired, re-assign value to activity time
			$this->edit_sess( 'Activity_Time', time() );
		}
		
	}
}
/* End of file Simple_sessions.php */
?>
