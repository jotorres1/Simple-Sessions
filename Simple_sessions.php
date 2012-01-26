<?php if(!defined('BASEPATH')) exit('No direct script access allowed');
/**********************************************************************************************
 * Author: Jorge Torres - jotorres1@gmail.com
 * Website: http://www.jotorres.com 
 * Date:   1/1/2012
 * Version: 1.0
 * Description:  Simple sessions class that can be used with regular PHP projects
 *  			 or can be integrated as a custom library for codeigniter framework.
 * Documentation: http://www.jotorres.com/myprojects/simple-sessions-class 
 ***********************************************************************************************/

class Simple_sessions{
	
	private $my_sess;
	
	public function  __construct($sess_name = 'simple_sessions'){
		// Name the session
		session_name($sess_name);
		// Start the session
		session_start();
		// Pass session variables to my_sess attribute
		// Session is passed by reference, in order
		// to edit global session variable directly
		$this->my_sess = &$_SESSION;		
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
}
/* End of file Simple_sessions.php */
?>