#!/usr/bin/env php
<?php
/**
 * Print this machine's public IPv4 or IPv6 address.
 *
 * Usage:
 *   php public-ip.php
 *   php public-ip.php --ipv4
 *   php public-ip.php --ipv6
 */

declare(strict_types=1);

$mode = $argv[1] ?? 'any';
if (!in_array($mode, ['any', '--ipv4', '--ipv6'], true)) {
    fwrite(STDERR, "Usage: php public-ip.php [--ipv4|--ipv6]\n");
    exit(64);
}

$url = match ($mode) {
    '--ipv4' => 'https://api.ipify.org',
    '--ipv6' => 'https://api64.ipify.org',
    default  => 'https://api64.ipify.org',
};

$curl = curl_init($url);
if ($curl === false) {
    fwrite(STDERR, "Unable to initialize cURL.\n");
    exit(1);
}

curl_setopt_array($curl, [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_CONNECTTIMEOUT => 5,
    CURLOPT_TIMEOUT => 10,
    CURLOPT_USERAGENT => 'public-ip-cli/1.0',
    CURLOPT_HTTPHEADER => ['Accept: text/plain'],
]);

$body = curl_exec($curl);
$error = curl_error($curl);
$status = (int) curl_getinfo($curl, CURLINFO_RESPONSE_CODE);
curl_close($curl);

$ip = is_string($body) ? trim($body) : '';
if ($body === false || $status < 200 || $status >= 300 || filter_var($ip, FILTER_VALIDATE_IP) === false) {
    $detail = $error !== '' ? $error : "HTTP status {$status}";
    fwrite(STDERR, "Could not determine public IP address: {$detail}\n");
    exit(1);
}

echo $ip . PHP_EOL;
