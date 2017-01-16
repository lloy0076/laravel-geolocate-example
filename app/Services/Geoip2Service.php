<?php

namespace App\Services;

use GeoIp2\Database\Reader;
use GeoIp2\Exception\AddressNotFoundException;

class Geoip2Service
{
    const GEOIP2_DB_ENV = 'GEOIP2_DB';
    const GEOIP2_DB     = 'geoip2/GeoLite2-City.mmdb';

    /**
     * The database location.
     */
    private $db;

    /**
     * The reader
     */
    private $reader;

    /**
     * Construct the service.
     *
     * @return The service.
     */
    public function __construct()
    {
        $db_rel = env(self::GEOIP2_DB_ENV, self::GEOIP2_DB);
        $db_loc = resource_path($db_rel);

        if (! file_exists($db_loc)) {
            throw new \Exception("Unable to find the Geoip2 database at '$db_loc'.");
        }

        $this->db = $db_loc;
        
        $reader = new Reader($db_loc);
        $this->reader = $reader;

        return $this;
    }

    /**
     * Finds the location.
     *
     * @param  $ip A properly formatted IP address.
     * @return The location.
     */
     public function findLocation($ip) {
        $record = $this->reader->city($ip);

        return $record;
    }

    /**
     * Finds the location for a list of IPs.
     *
     * @param  $ips An array of properly formatted IP address.
     * @return The locations.
     */
    public function findLocations($ips = []) {
        $records = [];

        if (! isset($ips)) {
            return $records;
        }

        if (! is_array($ips)) {
            return $records;
        }

        foreach ($ips as $ip) {
            try {
               $record    = $this->findLocaton($ip);
               $records[] = $record;
            } catch (AddressNotFoundException $e) {
                $record[] = [
                    'ip'    => $ip,
                    'error' => $e,
                ];
            }
        }

        return $records;
    }
}
