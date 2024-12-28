<?php 

if (!defined('ABSPATH')) {
    exit;
}

if (!class_exists('UseCaseMailer'))
{
    /**
     * Class UseCaseMailer for sending emails.
     */
    class UseCaseMailer
    {
        public function __construct()
        {
        }

        /**
         * The function `send_email_confirmation` sends an email with a specific subject and body
         * content to the specified recipient using WordPress `wp_mail` function.
         * 
         * @param to The `send_email_confirmation` function is used to send an email confirmation to
         * the specified recipient. The `` parameter is the email address of the recipient to whom
         * the confirmation email will be sent.
         */
        public function send_email_confirmation($to)
        {
            $subject = 'Use case submission';
            $headers = array('Content-Type: text/html; charset=UTF-8');
    
            $body = '';
    
            $body .= '<p>Dear submitter,</p>';
            $body .= '<p>Your case study has been sent!</p>';
            $body .= '<p>Many thanks.</p>';
            $body .= '<p>Kind regards,</p>';
            $body .= '<p>Windesheim Technology Radar</p>';
    
            wp_mail($to, $subject, $body, $headers);
        }

        /**
         * The function `send_admin_email` sends an email to the admin with a notification about a new
         * case study submission, including a link to view it.
         * 
         * @param to The `to` parameter in the `send_admin_email` function represents the email address
         * of the recipient to whom the email will be sent. This is typically the email address of the
         * admin who should receive notifications about the submitted use case.
         * @param use_case_link The `use_case_link` parameter in the `send_admin_email` function is a
         * URL that points to the location where the newly submitted case study can be viewed. This
         * link is included in the email body sent to the admin so they can easily access and review
         * the submitted case study.
         */
        public function send_admin_email($to, $use_case_link)
        {
            $subject = 'Use case submission';
            $headers = array('Content-Type: text/html; charset=UTF-8');
    
            $body = '';
    
            $body .= '<p>Dear admin,</p>';
            $body .= '<p>A new case study has been submitted!</p>';
            $body .= '<p>You can view it <a href="' . $use_case_link . '">here</a>.</p>';
            $body .= '<p>Kind regards,</p>';
            $body .= '<p>Windesheim Technology Radar</p>';
    
            wp_mail($to, $subject, $body, $headers);
        }
    }
}