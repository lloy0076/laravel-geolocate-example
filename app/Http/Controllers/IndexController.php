<?php 

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Storage;

use App\Http\Controllers\GeoBaseController;

class IndexController extends GeoBaseController
{
    const INDEX_VIEW        = 'index';
    const GET_LOCATION_VIEW = 'get_location';

    const BULK_VIEW              = 'bulk';
    const GET_BULK_LOCATION_VIEW = 'get_bulk_location'; 

    public function index(Request $req) {
        $ip = $req->input('ip', '127.0.0.1');

        return view(self::INDEX_VIEW, [ 'ip' => $ip, 'active_page' => 'index', ]);
    }

    public function getLocation(Request $req) {
        $args                = $this->getLocationArgs($req);
        $args['active_page'] = 'index';

        return view(self::GET_LOCATION_VIEW, $args);

    }

    /**
     * Location by bulk.
     */
    public function bulk() {
        $args['active_page'] = 'bulk';
        return view(self::BULK_VIEW, $args);
    }

    /**
     * Process the bulk file.
     */
    public function getBulkLocation(Request $req) {
        $args                = $this->getBulkLocationArgs($req);
        $args['active_page'] = 'bulk';

        return view(self::GET_BULK_LOCATION_VIEW, $args);
    }
}
