<?php
declare(strict_types=1);

namespace Den\BootstrapElements;

class BootstrapCDN
{
    /**
     * Returns the HTML <link> tag for Bootstrap CSS.
     */
    public static function css(): string
    {
        return '<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">';
    }

    /**
     * Returns the HTML <script> tag for Bootstrap JS bundle.
     */
    public static function js(): string
    {
        return '<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>';
    }

    /**
     * Returns both CSS and JS tags combined.
     */
    public static function all(): string
    {
        return self::css() . self::js();
    }
}


