<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;

use App\Http\Controllers\GeoBaseController;

class ApiController extends GeoBaseController
{
    /**
     * Get the location.
     * 
     * @return The location as a JSON encoded object.
     */
    public function get_location(Request $req) {
        $args = $this->getLocationArgs($req);
        return response()->json($args);
    }

    /**
     * Process the bulk file.
     */
    public function get_bulk(Request $req) {
        $args = $this->getBulkLocationArgs($req);
        return response()->json($args);
    }
}
