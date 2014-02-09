<?php
namespace ModernMedia\WPLib;
use \Swift_SmtpTransport;
use \Swift_Message;
class Mailer {
	/**
	 * @var Mailer
	 */
	private static $instance = null;

	/**
	 * @return Mailer
	 */
	public static function inst(){
		if (! self::$instance instanceof Mailer){
			self::$instance = new Mailer;
		}
		return self::$instance;
	}

	/**
	 * @var Swift_SmtpTransport;
	 */
	private $transport = null;

	/**
	 * Constructor
	 */
	private function __construct(){
		require_once(Utils::get_lib_path('includes/pluggable/wp_mail.php'));
	}

	/**
	 * @return Swift_SmtpTransport
	 */
	private function get_transport(){
		if (! $this->transport instanceof Swift_SmtpTransport){
			$opts = WPLib::inst()->get_settings();
			$this->transport = Swift_SmtpTransport::newInstance($opts->smtp_server, $opts->smtp_port)
				->setUsername($opts->smtp_username)
				->setPassword($opts->smtp_password);
		}
		return $this->transport;
	}

	public function mail_it( $to, $subject, $body) {
		$opts = WPLib::inst()->get_settings();

		$message = Swift_Message::newInstance();
		$message->setTo($to);
		$message->setSubject($subject);
		$message->setFrom($opts->from_email, $opts->from_name);
//		if (is_string($body)){
//			$body = array(
//				'text' => $body,
//				'html' => wpautop($body)
//			);
//		}
//		$message->setBody($body['html'], 'text/html');
		$message->setBody($body, 'text/plain');

		$transport = $this->get_transport();
		$transport->send($message);
	}
} 