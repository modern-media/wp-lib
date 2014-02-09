<?php
namespace ModernMedia\WPLib;
function wp_mail( $to, $subject, $message) {
	Mailer::inst()->mail_it($to, $subject, $message);
}