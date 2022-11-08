<?php

namespace App\Http\Controllers;

use App\Models\About;
use App\Models\Experience;
use App\Models\Hero;
use App\Models\Skill;
use App\Models\Work;
use Illuminate\Http\Request;

class ApiController extends Controller
{

    public $token = "f240ad10-2d42-4d20-b361-4f4dc419b360";

    public function getAllData(Request $request) {
        $token = $request->token;

        if($token !== $this->token){
            return response("Unauthorized", 401);
        }

        $hero = Hero::first();
        $about = About::first();
        $skills = Skill::orderBy('expertise', 'desc')->get();
        $experiences = Experience::orderBy('start_date', 'asc')->get();
        foreach ($experiences as $key => $exp) {
            $experiences[$key] = (new HomeController)->techStackToExperience($exp);
        }
        $works = Work::orderBy('id', 'desc')->get();
        foreach ($works as $key => $work) {
            $works[$key] = (new HomeController)->techStackToExperience($work);
        }

        return response([
            "hero" => $hero,
            "about" => $about,
            "skills" => $skills,
            "experiences" => $experiences,
            "works" => $works
        ], 200);
    }
}
