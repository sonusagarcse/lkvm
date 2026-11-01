<?php
/**
 * Simple SMTP Helper for PHP (no dependencies)
 * Handles fast SMTP connection, HELO, AUTH, MAIL FROM, RCPT TO, DATA, and QUIT with timeout protections.
 */
function send_smtp_email($to, $subject, $message, $config) {
    $host     = $config['smtp_host'] ?? '';
    $port     = intval($config['smtp_port'] ?? 587);
    $user     = $config['smtp_user'] ?? '';
    $pass     = $config['smtp_pass'] ?? '';
    $fromName = $config['smtp_from_name'] ?? 'Website';
    $secure   = strtolower($config['smtp_secure'] ?? 'tls');
    $clientDomain = $_SERVER['HTTP_HOST'] ?? 'localhost';

    if (empty($host) || empty($user) || empty($pass)) {
        return ["status" => false, "error" => "SMTP settings not fully configured."];
    }

    $smtpHost = ($secure === 'ssl') ? "ssl://$host" : $host;
    
    // Quick connection timeout (max 3 seconds)
    $socket = @fsockopen($smtpHost, $port, $errno, $errstr, 3);

    if (!$socket) {
        return ["status" => false, "error" => "Connection failed: $errstr ($errno)"];
    }

    // Set stream timeout to prevent indefinite socket read freezing
    @stream_set_timeout($socket, 3);

    $response = fgets($socket, 512);
    if (empty($response) || strpos($response, '220') !== 0) {
        @fclose($socket);
        return ["status" => false, "error" => "Invalid SMTP greeting from server: " . trim($response)];
    }
    
    // Commands helper
    $sendCommand = function($socket, $cmd) {
        fputs($socket, $cmd . "\r\n");
        return fgets($socket, 512);
    };

    // HELO / EHLO
    $sendCommand($socket, "EHLO " . $clientDomain);

    // TLS Negotiation
    if ($secure === 'tls') {
        $tlsResp = $sendCommand($socket, "STARTTLS");
        if (strpos($tlsResp, '220') !== 0) {
            @fclose($socket);
            return ["status" => false, "error" => "STARTTLS not accepted by server: " . trim($tlsResp)];
        }

        if (!@stream_socket_enable_crypto($socket, true, STREAM_CRYPTO_METHOD_ANY_CLIENT)) {
            @fclose($socket);
            return ["status" => false, "error" => "Failed to negotiate TLS encryption"];
        }
        $sendCommand($socket, "EHLO " . $clientDomain);
    }

    // AUTH
    $sendCommand($socket, "AUTH LOGIN");
    $sendCommand($socket, base64_encode($user));
    $authResp = $sendCommand($socket, base64_encode($pass));

    if (strpos($authResp, '235') !== 0 && strpos($authResp, '250') !== 0) {
        @fclose($socket);
        return ["status" => false, "error" => "SMTP Authentication failed: " . trim($authResp)];
    }

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
    $headers .= "Message-ID: <" . time() . "." . bin2hex(random_bytes(4)) . "@" . $clientDomain . ">\r\n";
    $headers .= "Date: " . date("r") . "\r\n";
    $headers .= "X-Mailer: LKVM SMTP Mailer\r\n";

    fputs($socket, $headers . "\r\n" . $message . "\r\n.\r\n");
    $response = fgets($socket, 512);

    $sendCommand($socket, "QUIT");
    @fclose($socket);

    if (strpos($response, '250') === 0 || strpos($response, '235') === 0 || strpos($response, '354') === 0 || strpos($response, '221') === 0) {
        return ["status" => true];
    }
    
    return ["status" => false, "error" => "SMTP failed: " . trim($response)];
}
