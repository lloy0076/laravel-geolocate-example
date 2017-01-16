<?php 

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Storage;

use App\Services\Geoip2Service;

use GeoIp2\Database\Reader;
use GeoIp2\Exception\AddressNotFoundException;

class GeoBaseController extends Controller
{
    const INDEX_VIEW        = 'index';
    const GET_LOCATION_VIEW = 'get_location';

    const BULK_VIEW              = 'bulk';
    const GET_BULK_LOCATION_VIEW = 'get_bulk_location'; 

    const FILE_LOCATION     = 'ips';

    public function getLocationArgs(Request $req) {
        $geo = resolve(Geoip2Service::class);

        $ip = $req->input('ip', '');
        $ip = preg_replace('/\s+/', '', $ip);

        if (filter_var($ip, FILTER_VALIDATE_IP) === false) {
            if ($ip === '') {
                $err = 'IP must be set.';
            } else {
                $err = "Invalid IP: '$ip'.";
            }

            $args = [
                'city'        => 'ERROR',
                'app_errors'  => $err,
            ];

            return $args;
        }

        try {
            $record = $geo->findLocation($ip);

            $args = [
                'ip'      => $ip,
                'city'    => $record->city->name,
                'country' => $record->country->name,
            ];

            $args['subdivision'] = $this->generateSubdivision($record->subdivisions);
        } catch (AddressNotFoundException $e) {
            $args = [
                'ip'           => $ip,
                'city'         => "IP $ip Not found",
                'app_warnings' => "IP $ip Not Found",
            ];
        } catch (\Exception $e) {
            $args = [
                'city'       => 'Unexpected Error',
                'app_errors' => $e->getMessage(),
            ];
        }

        return $args;
    }

    /**
     * Process the bulk file.
     */
    public function getBulkLocationArgs(Request $req) {
        $geo = resolve(Geoip2Service::class);

        $file = $req->file();
        dd($req);

        if (! isset($file)) {
            $args = [
                'city'       => 'Unexpected Error',
                'app_errors' => 'No File Passed.',
            ];
        } else if (! $file->isValid()) {
            $args = [
                'city'       => 'Unexpected Error',
                'app_errors' => 'Invalid File Passed.',
            ];
        } else if ($file->getMimeType() !== 'text/plain') {
            $file_type = $file->getMimeType();

            $args = [
                'city'       => 'Invalid File Type',
                'app_errors' => "Invalid File Type ($file_type).",
            ];
        } else {
            $data = $this->processFile($file, $geo);

            $args = [
                'data' => $data,
            ];

            $new_data     = []; 
            $app_errors   = [];
            $app_warnings = [];
            foreach ($data as $data_item) {
                if ($data_item['city'] === 'Invalid IP') {
                    $app_errors[]= "IP starting with '" . $data_item['ip'] . "' is invalid.";
                } else if ($data_item['city'] === 'Not Found') {
                    $app_warnings[] = "IP " . $data_item['ip'] . " not found.";
                } else {
                    $new_data[] = $data_item;
                }
            }

            if (count($app_errors) > 0) {
                $args['app_errors'] = $app_errors;
            }

            if (count($app_warnings) > 0) {
                $args['app_warnings'] = $app_warnings;
            }

            $args['data'] = $new_data;
        }

        return $args;
    }

    /**
     * Processes the file.
     *
     * The file format is a single IP per line.
     *
     * @param $file The file object.
     * @param Returns an array of hashes containing:
     *        - @key ip
     *          The IP Address.
     *        - @key city
     *          The city.
     *        - @key subdivision.
     *          The subdivision as a string.
     *        - @key country
     *          The country.
     *        .
     */
    private function processFile($file, Geoip2Service $geo) {
        $filename     = $file->store(self::FILE_LOCATION);
        $storage_path = storage_path("app/$filename");

        if (! file_exists($storage_path)) {
            throw new \Exception("File '$storage_path' does not exist.");
        }

        $res = fopen($storage_path, 'r');

        $data = [];
        while (($buffer = fgets($res)) !== false) {
            $string = trim($buffer);

            try {
                if (filter_var($string, FILTER_VALIDATE_IP) !== false) {
                    $record = $geo->findLocation($string);
                    
                    $data[] = [
                        'ip'      => $string,
                        'city'    => $record->city->name,
                        'country' => $record->country->name,
                    ];

                    $args['subdivision'] = $this->generateSubdivision($record->subdivisions);
                } else {
                    $invalid_ip = substr($string, 0, 12);
                    $data[] = [
                        'ip'   => $invalid_ip,
                        'city' => 'Invalid IP',
                    ];
                }
            } catch (AddressNotFoundException $e) {
                $data[] = [
                    'ip'   => $string,
                    'city' => 'Not Found',
                ];
            }
        }

        return $data;
    }

    /**
     * Generate subdivision field.
     *
     * @param  $record The subdivision array.
     * @return The field.
     */
    private function generateSubdivision($subdivisions = []) {
        $text = '';
        if (count($subdivisions > 0)) {
            $divisions = [];
            foreach ($subdivisions as $subdivision) {
               $divisions[] = $subdivision->name; 
            }

            $text = implode(', ', $divisions);
        }

        return $text;
    }
}
