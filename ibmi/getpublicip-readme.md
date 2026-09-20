# Public IP PHP CLI

A small PHP command-line utility that uses cURL to print the computer's current public IP address.

## Requirements

- PHP 8.0 or later
- PHP cURL extension enabled (`php -m | grep curl`)

## Run

```bash
php getpublicip.php
```

Optional address-family modes:

```bash
php getpublicip.php --ipv4
php getpublicip.php --ipv6
```

The script returns the IP address on standard output. It returns a non-zero exit code and writes an explanatory error to standard error if the request fails.

It uses the public `ipify` endpoint over HTTPS.
