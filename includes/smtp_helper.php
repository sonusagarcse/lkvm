<?php
/**
 * Simple SMTP Helper for PHP (no dependencies)
 * This function handles SMTP connection, HELO, AUTH, MAIL FROM, RCPT TO, DATA, and QUIT.
 */
function send_smtp_email($to, $subject, $message, $config) {
    $host     = $config['smtp_host'] ?? '';
    $port     = $config['smtp_port'] ?? 587;
    $user     = $config['smtp_user'] ?? '';
    $pass     = $config['smtp_pass'] ?? '';
    $fromName = $config['smtp_from_name'] ?? 'Website';
    $secure   = $config['smtp_secure'] ?? 'tls';

    if (empty($host) || empty($user) || empty($pass)) {
        return ["status" => false, "error" => "SMTP settings not fully configured."];
    }

    $smtpHost = ($secure === 'ssl') ? "ssl://$host" : $host;
    $socket = fsockopen($smtpHost, $port, $errno, $errstr, 15);

    if (!$socket) {
        return ["status" => false, "error" => "Connection failed: $errstr ($errno)"];
    }

    $response = fgets($socket, 512);
    
    // Commands helper
    $sendCommand = function($socket, $cmd) {
        fputs($socket, $cmd . "\r\n");
        return fgets($socket, 512);
    };

    // HELO
    $sendCommand($socket, "EHLO " . $_SERVER['HTTP_HOST']);

    // TLS
    if ($secure === 'tls') {
        $sendCommand($socket, "STARTTLS");
        if (!stream_socket_enable_crypto($socket, true, STREAM_CRYPTO_METHOD_ANY_CLIENT)) {
            fclose($socket);
            return ["status" => false, "error" => "Failed to start TLS"];
        }
        $sendCommand($socket, "EHLO " . $_SERVER['HTTP_HOST']);
    }

    // AUTH
    $sendCommand($socket, "AUTH LOGIN");
    $sendCommand($socket, base64_encode($user));
    $sendCommand($socket, base64_encode($pass));

    // MAIL FROM / RCPT TO
    $sendCommand($socket, "MAIL FROM: <$user>");
    $sendCommand($socket, "RCPT TO: <$to>");

    // DATA
    $sendCommand($socket, "DATA");
    
    $headers  = "MIME-Version: 1.0\r\n";
    $headers .= "Content-type: text/html; charset=utf-8\r\n";
    $headers .= "To: $to\r\n";
    $headers .= "From: $fromName <$user>\r\n";
    $headers .= "Subject: $subject\r\n";
    $headers .= "Message-ID: <" . time() . "@" . $_SERVER['HTTP_HOST'] . ">\r\n";
    $headers .= "Date: " . date("r") . "\r\n";
    $headers .= "X-Mailer: Custom PHP SMTP Mailer\r\n";

    fputs($socket, $headers . "\r\n" . $message . "\r\n.\r\n");
    $response = fgets($socket, 512);

    $sendCommand($socket, "QUIT");
    fclose($socket);

    if (strpos($response, '250') === 0 || strpos($response, '235') === 0 || strpos($response, '354') === 0 || strpos($response, '221') === 0) {
        return ["status" => true];
    }
    
    return ["status" => false, "error" => "SMTP failed: " . $response];
}
