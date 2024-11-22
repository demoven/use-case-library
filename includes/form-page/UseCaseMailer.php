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
         * 
         * Send email confirmation of acceptance to the submitter.
         * 
         *@param string $to
         *@return void
         */
        public function send_email_confirmation($to)
        {
            $subject = 'Use case submission';
            $headers = array('Content-Type: text/html; charset=UTF-8');
    
            $body = '';
    
            $body .= '<p>Dear submitter,</p>';
            $body .= '<p>Your case study has been accepted !</p>';
            $body .= '<p>Many thanks.</p>';
            $body .= '<p>Kind regards,</p>';
            $body .= '<p>Windesheim Technology Radar</p>';
    
            wp_mail($to, $subject, $body, $headers);
        }

        /**
         * 
         * Send email confirmation of submission to the submitter.
         * 
         *@param string $to
         *@return void
         */
        public function send_email_reception($to)
        {
            $subject = 'Use case submission';
            $headers = array('Content-Type: text/html; charset=UTF-8');
    
            $body = '';
    
            $body .= '<p>Dear submitter,</p>';
            $body .= '<p>Your case study has been submitted !</p>';
            $body .= '<p>Many thanks.</p>';
            $body .= '<p>Kind regards,</p>';
            $body .= '<p>Windesheim Technology Radar</p>';
    
            wp_mail($to, $subject, $body, $headers);
        }

        /**
         * Send email to admin when a new use case is submitted.
         * 
         * @param string $to
         * @param string $use_case_link
         * @return void
         */
        public function send_admin_email($to, $use_case_link)
        {
            $subject = 'Use case submission';
            $headers = array('Content-Type: text/html; charset=UTF-8');
    
            $body = '';
    
            $body .= '<p>Dear admin,</p>';
            $body .= '<p>A new case study has been submitted !</p>';
            $body .= '<p>You can view it <a href="' . $use_case_link . '">here</a>.</p>';
            $body .= '<p>Kind regards,</p>';
            $body .= '<p>Windesheim Technology Radar</p>';
    
            wp_mail($to, $subject, $body, $headers);
        }
    }
}