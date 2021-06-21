<?php

namespace App\Http\Controllers;

use App\ZTools\GeoUtils\IPToLocation;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class LocationController extends Controller
{

    private IPToLocation $ipToLocation;

    public function __construct()
    {
        $this->ipToLocation = new IPToLocation('');
    }

    /**
     * @throws GuzzleException
     */
    public function getLocationByIp(Request $request): JsonResponse
    {
        $ip = $request->query('ip');
        $city = $this->ipToLocation->iPToLocation($ip)->getCity();
        return response()->json($city);
    }
}
