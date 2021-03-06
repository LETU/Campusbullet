<?php defined('SYSPATH') or die('No direct access allowed.');
/**
 * DB Auth driver.
 * [!!] this Auth driver does not support roles nor autologin.
 *
 * @package    Kohana/Auth
 * @author     Kohana Team
 * @copyright  (c) 2007-2008 Kohana Team
 * @license    http://kohanaphp.com/license.html
 */
class Kohana_Auth_Db extends Auth {

	/**
	 * Constructor loads the user list into the class.
	 */
	public function __construct($config = array())
	{
		parent::__construct($config);

	}

	/**
	 * Logs a user in.
	 *
	 * @param   string   username
	 * @param   string   password
	 * @param   boolean  enable autologin (not supported)
	 * @return  boolean
	 */
	protected function _login($username, $password, $remember)
	{		
		//check the user database for the user
		$user_row = DB::select('id','disabled','role')->from('users')->where('username','=',$username)->and_where('userhash','=',$password)->execute()->current();
		
		if ($user_row) {
			if ($user_row['disabled'] == 1) {
				//the user has been disabled by a mod. deny the login.	
				return "disabled";
			} else {
				//the user is clear to log in
				
				if ($user_row['disabled'] == 2) {
					//the user disabled themself. re-enable them.
					DB::update('users')->set(array(
						'disabled' => 0))->where('id','=',$user_row['id'])->execute();
				}
				if ($user_row['role'] == "admin" || $user_row['role'] == "mod")
					$this->_session->set('moderator',TRUE);
				$this->_session->set("user_id", $user_row['id']);
				
				//log the login
				DB::update('users')->set(array(
					'last_login' => Date::formatted_time(),
					'last_ip' => $_SERVER["REMOTE_ADDR"]))->where('id','=',$user_row['id'])->execute();
				
				$this->complete_login($username);
				return "success";
			}
		}
		
		return false; //login failed!
		
	}

	/**
	 * Forces a user to be logged in, without specifying a password.
	 *
	 * @param   mixed    username
	 * @return  boolean
	 */
	public function force_login($username)
	{
		// Complete the login
		return $this->complete_login($username);
	}

	/**
	 * Get the stored password for a username.
	 *
	 * @param   mixed   username
	 * @return  string
	 */
	public function password($username)
	{		
		$user_row = DB::select('userhash')->from('users')->where('username','=',$username)->execute()->current();
		
		if ($user_row ) {			
			return $user_row['userhash'];			
		}
		
		return false; // not found!
	}

	/**
	 * Compare password with original (plain text). Works for current (logged in) user
	 *
	 * @param   string  $password
	 * @return  boolean
	 */
	public function check_password($password)
	{			
		$salt = $this->find_salt($this->password($this->get_user()));
		
		$verifypw = $this->hash_password($password,$salt);
		
		$username = $this->get_user();
		
		if ($username === FALSE)
		{
			return FALSE;
		}

		return ($verifypw == $this->password($username));
	}
	
	public function change_password($new_pw) {
		$user_id = Session::instance()->get('user_id');
		
		//passwords matched, update the row and return successful
		if (DB::update('users')->set(array('userhash' => $this->hash_password($new_pw)))->where('id','=',$user_id)->execute()) 
			return true;	
		else
			return false;
	}

} // End Auth File