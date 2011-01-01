<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Login extends Controller_Layout {

	protected $auth_required = false;
	
	public function action_index()
	{
		$auth = Auth::instance();
		$redir = @($_GET['redir']);
		$content = View::factory('notemplate_login');
		$content->error = "";
		$post_user = @($_POST['user']);
		$post_pass = @($_POST['asdf']);
		$failures_row = DB::select('timestamp','failures')->from('login_failures')->where('ip','=',$_SERVER["REMOTE_ADDR"])->execute()->current();
				
		if ( ! $redir)
			$redir = 'home';
			
		//check if the user is already logged in
		if ($auth->logged_in())
			Request::instance()->redirect($redir);
		
		//check for an automated login bot - buggy, disabled for now
		/*if ($failures_row && $_POST && $failures_row['failures'] > 10) {
			$now = time() + (9 * 60 * 60); //fix for weird time bug
			$lastfail = strtotime($failures_row['timestamp']);
			//$reconvert = date("Y-m-d H:i:s",$now);			
			if ($now - $lastfail < 5) {
				$content->error = "Sorry, you need to wait a couple seconds before you can try logging in again.";				
			}
			
		
		}*/
		
		//check credentials and redirect if successful
		if ($post_user && $post_pass && ! $content->error) {
			if ($auth->login($post_user, $post_pass)) {
				Request::instance()->redirect($redir);
			} else {				
				$content->error = "Sorry, that username and password didn't work.";
				if ($failures_row) {
					DB::update('login_failures')->set(array(
						'failures' => $failures_row['failures'] + 1))
						->where('ip','=',$_SERVER["REMOTE_ADDR"])->execute();					
				} else {
					DB::insert('login_failures')->columns(array('ip','failures'))->values(array($_SERVER["REMOTE_ADDR"],1))->execute();
				}
			}
		}
		
		$this->template = $content;
	}
	
	public function action_logout() {
		$redir = 'home';
		$auth = Auth::instance();
		
		if ($auth->logged_in())
			$auth->logout(true);
			
		Request::instance()->redirect($redir);			
	}

}