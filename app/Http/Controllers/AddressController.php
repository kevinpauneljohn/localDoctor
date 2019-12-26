<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AddressController extends Controller
{
    /**
     * Dec. 12, 2019
     * @author john kevin paunel
     * Get all the region data
     * route:region
     * @param int $regCode
     * @return object
     * */
    public function getRegion($regCode)
    {
        $region = DB::table('refregion')->where('regCode',$regCode)->get();
        return $region;
    }

    /**
     * Dec. 12, 2019
     * @author john kevin paunel
     * Get all the state data
     * route:state
     * @param int
     * @return object
     * */
    public function getState($regCode)
    {
        $state = DB::table('refprovince')->where('regCode',$regCode)->get();
        return $state;
    }

    /**
     * Dec. 12, 2019
     * @author john kevin paunel
     * Get all the city data
     * route:city
     * @param int
     * @return object
     * */
    public function getCity($provCode)
    {
        $city = DB::table('refcitymun')->where('provCode',$provCode)->get();
        return $city;
    }

    /**
     * Dec. 16, 2019
     * @author john kevin paunel
     * get the region name
     * @param int regCode
     * @return object
     * */
    public function getRegionName($regCode)
    {
        return DB::table('refregion')->where('regCode',$regCode)->first()->regDesc;
    }

    /**
     * Dec. 16, 2019
     * @author john kevin paunel
     * get the region name
     * @param int regCode
     * @return object
     * */
    public function getStateName($provCode)
    {
        return DB::table('refprovince')->where('provCode',$provCode)->first()->provDesc;
    }

    /**
     * Dec. 16, 2019
     * @author john kevin paunel
     * get the region name
     * @param int regCode
     * @return object
     * */
    public function getCityName($citymunCode)
    {
        return DB::table('refcitymun')->where('citymunCode',$citymunCode)->first()->citymunDesc;
    }
}
