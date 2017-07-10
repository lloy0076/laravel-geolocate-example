<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;

use App\Http\Controllers\GeoBaseController;

class ApiController extends GeoBaseController
{
    /**
     * Get the location.
     * 
     * @param Request $req
     * @return The location as a JSON encoded object.
     */
    public function get_location(Request $req) {
        $location = $this->getLocationArgs($req);
        return response()->json($location);
    }

    /**
     * Process the bulk file.
     * 
     * @param Request The request.
     * @return The location data as a JSON encoded object.
     */
    public function get_bulk(Request $req) {
        $locationData = $this->getBulkLocationArgs($req);
        return response()->json($locationData);
    }
}
