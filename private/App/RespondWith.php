<?php

namespace Blog\App;

abstract class RespondWith {

    /**
     * Stops script execution and displays an error page
     * @param int $code
     * @return never
     */
    public static function error_page(int $code = 404): never {
        view('error', code: $code);
        exit;
    }

    /**
     * Stops script execution and displays a JSON response
     * @param int $code
     * @return never
     */
    public static function json(int $code = 404): never {
        header('Content-Type: application/json');
        http_response_code($code);
        exit(json_encode([
            'message' => self::code_to_name($code),
            'code' => $code,
            'info' => "https://http.cat/$code"
        ], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
    }

    /**
     * Stops script execution and redirects to a URL
     * @param string $location
     * @param bool $permanent Whether to return a 308 status code or not (307)
     * @return never
     */
    public static function redirect(string $location, bool $permanent = false): never {
        http_response_code($permanent ? 308 : 307);
        header("Location: $location");
        exit;
    }

    /**
     * Returns the name of the given HTTP code, or 'Unknown' if such
     * @param int $code
     * @return string
     */
    public static function code_to_name(int $code): string {
        return match($code) {
            100 => 'Continue',
            101 => 'Switching Protocols',
            102 => 'Processing',
            200 => 'OK',
            201 => 'Created',
            202 => 'Accepted',
            203 => 'Non-authoritative Information',
            204 => 'No Content',
            205 => 'Reset Content',
            206 => 'Partial Content',
            207 => 'Multi-Status',
            300 => 'Multiple Choices',
            301 => 'Moved Permanently',
            302 => 'Found',
            303 => 'See Other',
            304 => 'Not Modified',
            305 => 'Use Proxy',
            307 => 'Temporary Redirect',
            308 => 'Permanent Redirect',
            400 => 'Bad Request',
            401 => 'Unauthorized',
            402 => 'Payment Required',
            403 => 'Forbidden',
            404 => 'Not Found',
            405 => 'Method Not Allowed',
            406 => 'Not Acceptable',
            407 => 'Proxy Authentication Required',
            408 => 'Request Timeout',
            409 => 'Conflict',
            410 => 'Gone',
            411 => 'Length Required',
            412 => 'Precondition Failed',
            413 => 'Payload Too Large',
            414 => 'Request-URI Too Long',
            415 => 'Unsupported Media Type',
            416 => 'Requested Range Not Satisfiable',
            417 => 'Expectation Failed',
            418 => 'I\'m a teapot',
            420 => 'Enhance Your Calm',
            421 => 'Misdirected Request',
            422 => 'Unprocessable Entity',
            423 => 'Locked',
            424 => 'Failed Dependency',
            426 => 'Upgrade Required',
            428 => 'Precondition Required',
            429 => 'Too Many Requests',
            431 => 'Request Header Fields Too Large',
            444 => 'Connection Closed Without Response',
            451 => 'Unavailable For Legal Reasons',
            499 => 'Client Closed Request',
            500 => 'Internal Server Error',
            501 => 'Not Implemented',
            502 => 'Bad Gateway',
            503 => 'Service Unavailable',
            504 => 'Gateway Timeout',
            505 => 'HTTP Version Not Supported',
            506 => 'Variant Also Negotiates',
            507 => 'Insufficient Storage',
            508 => 'Loop Detected',
            510 => 'Not Extended',
            511 => 'Network Authentication Required',
            599 => 'Network Connect Timeout Error',
            default => 'Unknown'
        };
    }

}