<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{

    /**
     * Dec. 06, 2019
     * @author john kevin paunel
     * display dashboard pages
     * */
    public function dashboard()
    {
        return view('pages.dashboard');
    }

    public function is_connected()
    {
        if(!$sock = @fsockopen('doctorapp.devouterbox.com', 80))
        {
            echo 'Not Connected';
        }
        else
        {
            echo 'Connected';
        }

    }
}
