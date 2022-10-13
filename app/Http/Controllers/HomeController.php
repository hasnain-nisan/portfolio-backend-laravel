<?php

namespace App\Http\Controllers;

use App\Models\About;
use App\Models\Brand;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('home');
    }

    public function about()
    {
        $about = About::first();
        return view('about', compact('about'));
    }

    public function aboutPost(Request $request)
    {
        $about = About::first();
        if(!$about) $about = new About();

        // storing_image
        $image_to_remove = null;
        if(isset($request->image)){
            $imageName = time().'.'.$request->image->extension();  
            $request->image->move(public_path('img/about'), $imageName);
            $image_to_remove = $about->image;
        }

        $about->title = $request->title;
        $about->description = $request->description;
        $about->image = isset($request->image) ? 'img/about/' . $imageName : $about->image;
        $about->save();

        if($image_to_remove != null){
            unlink(getcwd() . '/' . $image_to_remove);
        }

        return redirect()->route('about');
    }

    public function brands()
    {
        $brands = Brand::all();
        return view('brands', compact('brands'));
    }

    public function addBrand(Request $request)
    {
        $brand = new Brand();

        // storing_image
        if(isset($request->image)){
            $imageName = time().'.'.$request->image->extension();  
            $request->image->move(public_path('img/brand'), $imageName);
        }

        $brand->name = $request->name;
        $brand->image = isset($request->image) ? 'img/brand/' . $imageName : $brand->image;
        $brand->save();

        return redirect()->route('brands');
    }
}
