<?php

namespace Karakus\Cloudflare;

use Ixudra\Curl\Builder as Curl;
use UnexpectedValueException;

class TrustProxies
{
    protected const IP_VERSION_4 = 1 << 0;

    protected const IP_VERSION_6 = 1 << 1;

    protected const IP_VERSION_ANY = self::IP_VERSION_4 | self::IP_VERSION_6;

    /**
     * Retrieve Cloudflare proxy list(s).
     *
     * @param  int  $type
     *
     * @return array
     */
    public function load($type = self::IP_VERSION_ANY)
    {
        $proxies = [];

        if ($type & self::IP_VERSION_4) {
            $proxies = $this->retrieve('ips-v4');
        }

        if ($type & self::IP_VERSION_6) {
            $proxies = array_merge($proxies, $this->retrieve('ips-v6'));
        }

        return $proxies;
    }

    /**
     * Retrieve requested proxy list by name.
     *
     * @param  string  $name
     *
     * @return array
     */
    protected function retrieve($name)
    {
        $curl = new Curl();

        $response = $curl->to('https://www.cloudflare.com/'.$name)
                         ->withOption('SSL_VERIFYHOST', false)
                         ->withOption('SSL_VERIFYPEER', false)
                         ->get();

        if (empty($response)) {
            throw new UnexpectedValueException(
                'Failed to load trust proxies from Cloudflare server.'
            );
        }

        return array_filter(explode("\n", $response));
    }
}
