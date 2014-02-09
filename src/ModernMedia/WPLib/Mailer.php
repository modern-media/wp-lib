<?php
namespace ModernMedia\WPLib;
use ModernMedia\WPLib\Data\WPLibSettings;
use \Swift_SmtpTransport;
use \Swift_Message;
use \Swift_Mailer;
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
	 * Constructor
	 */
	private function __construct(){
		require_once(Utils::get_lib_path('includes/pluggable/wp_mail.php'));
	}



	/**
	 * @param WPLibSettings $opts
	 * @return Swift_Mailer
	 */
	private function get_mailer($opts){
		$transport = Swift_SmtpTransport::newInstance($opts->smtp_server, $opts->smtp_port)
			->setUsername($opts->smtp_username)
			->setPassword($opts->smtp_password)
			->setPort($opts->smtp_port);
		return Swift_Mailer::newInstance($transport);
	}

	public function mail_it( $to, $subject, $body, $opts = null) {
		if (! $opts instanceof WPLibSettings){
			$opts = WPLib::inst()->get_settings();
		}
		$message = Swift_Message::newInstance();
		$message->setTo($to);
		$message->setSubject($subject);
		$message->setFrom($opts->from_email, $opts->from_name);
		if (is_string($body)){
			if (! $f = $this->get_theme_email_template()){
				$f = Utils::get_lib_path('includes/emails/site_emails.php');
			}
			ob_start();
			require $f;
			$html = ob_get_clean();
			$body = array(
				'text' => $body,
				'html' => $html
			);
		}
		$message->setBody($body['html'], 'text/html');
		$message->addPart($body['text'], 'text/plain');

		$mailer = $this->get_mailer($opts);
		$mailer->send($message);
	}

	public function get_theme_email_template(){
		$fn = 'site-emails.php';
		$f = get_stylesheet_directory() . '/' . $fn;
		if (file_exists($f)) return $f;
		$f = get_template_directory() . '/' . $fn;
		if (file_exists($f)) return $f;
		return false;

	}
} 