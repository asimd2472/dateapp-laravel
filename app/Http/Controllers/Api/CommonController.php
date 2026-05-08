<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\City;
use Illuminate\Http\Request;

class CommonController extends Controller
{
    public function cityUpdate(){
        $cities = [
            'Alipurduar',
            'Arambagh',
            'Asansol',
            'Baharampur',
            'Balurghat',
            'Bankura',
            'Baranagar',
            'Barasat',
            'Barrackpore',
            'Basirhat',
            'Beldanga',
            'Berhampore',
            'Bhadreswar',
            'Bhatpara',
            'Bidhannagar',
            'Birbhum',
            'Bolpur',
            'Bongaon',
            'Burdwan',
            'Chakdaha',
            'Champdani',
            'Chandannagar',
            'Cooch Behar',
            'Contai',
            'Dalkhola',
            'Darjeeling',
            'Dhupguri',
            'Diamond Harbour',
            'Digha',
            'Dinhata',
            'Dum Dum',
            'Durgapur',
            'English Bazar',
            'Gangarampur',
            'Ghatal',
            'Habra',
            'Haldia',
            'Howrah',
            'Islampur',
            'Jalpaiguri',
            'Jangipur',
            'Jhargram',
            'Kalimpong',
            'Kalyani',
            'Kamarhati',
            'Kanchrapara',
            'Kharagpur',
            'Koch Bihar',
            'Kolkata',
            'Krishnanagar',
            'Mainaguri',
            'Malda',
            'Mathabhanga',
            'Medinipur',
            'Memari',
            'Midnapore',
            'Murshidabad',
            'Nabadwip',
            'Naihati',
            'New Town',
            'North Barrackpore',
            'Old Malda',
            'Panihati',
            'Purulia',
            'Raiganj',
            'Rampurhat',
            'Ranaghat',
            'Raniganj',
            'Sainthia',
            'Salar',
            'Santipur',
            'Serampore',
            'Siliguri',
            'South Dumdum',
            'Tamluk',
            'Tarakeswar',
            'Titagarh',
            'Uluberia'
        ];

        foreach($cities as $city){
            City::create([
                'name' => $city
            ]);
        }
    }

    public function getCity(Request $request){
        $cities = City::all();

        return response()->json([
            'status' => 1,
            'msg' => 'Cities retrieved successfully.',
            'cities' => $cities
        ]);
    }
}
