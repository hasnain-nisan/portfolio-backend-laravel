<?php

namespace App\Http\Controllers;

use App\Mail\ContactReply;
use View;
use App\Models\About;
use App\Models\Brand;
use App\Models\Contact;
use App\Models\Experience;
use App\Models\Skill;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

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
        $contacts = Contact::where('is_seen', 0)->get();
        $about = About::first();
        View::share ( 'unread_contacts', count($contacts) );
        View::share ( 'about', $about );
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
            if(file_exists($image_to_remove)){
                unlink(getcwd() . '/' . $image_to_remove);
            }
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
        $brand->image = isset($request->image) ? 'img/brand/' . $imageName : "";
        $brand->save();

        return redirect()->route('brands');
    }

    public function editBrand(Request $request)
    {
        $id = $request->id;
        $image_to_remove = null;
        $brand = Brand::find($id);

        // storing_image
        if(isset($request->image)){
            $imageName = time().'.'.$request->image->extension();  
            $request->image->move(public_path('img/brand'), $imageName);
            $image_to_remove = $brand->image;
        }

        $brand->name = $request->name;
        $brand->image = isset($request->image) ? 'img/brand/' . $imageName : $brand->image;
        $brand->save();

        if($image_to_remove != null){
            if(file_exists($image_to_remove)){
                unlink(getcwd() . '/' . $image_to_remove);
            }
        }

        return redirect()->route('brands');
    }

    public function deleteBrand(Request $request)
    {
        $id = $request->id;
        $brand = Brand::find($id);
        $image_to_remove = $brand->image;

        $brand->delete();

        //remove brand image from the server
        if(file_exists($image_to_remove)){
            unlink(getcwd() . '/' . $image_to_remove);
        }

        return redirect()->route('brands');
    }

    public function contacts()
    {
        $contacts = Contact::all();
        return view('contacts', compact('contacts'));
    }

    public function updateContact(Request $request)
    {
        $type = $request->type;
        $id = $request->id;

        if($type == 'is_seen'){
            $contact = Contact::find($id);
            if($contact->is_seen == 0){
                $contact->is_seen = 1;
                $contact->save();
            }
            $contacts = Contact::where('is_seen', 0)->get();
            $count = count($contacts);
            return [
                'status' => 'success',
                'unread_contacts' => $count
            ];
        }

        // replying and updating contact message
        if($type == 'is_replying'){
            $contact = Contact::find($id);
            
            //send mail
            Mail::to($contact->email)->send(new ContactReply($contact->email, $request->reply, $contact));
            
            //save is replied to true
            $contact->is_replied = 1;
            $contact->save();

            return redirect()->route('contacts');
        }
    }

    public function deleteContact(Request $request)
    {
        $id = $request->id;
        $contact = Contact::find($id);
        $contact->delete();
        return redirect()->route('contacts');
    }

    public function experiences()
    {
        $experiences = Experience::orderBy('start_date', 'asc')->get();
        return view('experiences', compact('experiences'));
    }

    public function addExperience(Request $request)
    {
        $experience = new Experience();

        // storing_image
        if(isset($request->image)){
            $imageName = time().'.'.$request->image->extension();  
            $request->image->move(public_path('img/experience'), $imageName);
        }

        $experience->company_name = $request->company_name;
        $experience->company_logo = isset($request->image) ? 'img/experience/' . $imageName : "";
        $experience->position = $request->company_position;
        $experience->start_date = $request->start_date;
        $experience->end_date = $request->end_date_radio == 'present' ? null : $request->end_date;
        $experience->is_present = $request->end_date_radio == 'present' ? 1 : 0;

        $experience->save();
        return redirect()->route('experiences');
    }

    public function editExperience(Request $request)
    {
        $id = $request->id;
        $experience = Experience::find($id);
        $image_to_remove = null;

        // storing_image
        if(isset($request->image)){
            $imageName = time().'.'.$request->image->extension();  
            $request->image->move(public_path('img/experience'), $imageName);
            $image_to_remove = $experience->company_logo;
        }

        $experience->company_name = $request->company_name;
        $experience->company_logo = isset($request->image) ? 'img/experience/' . $imageName : $experience->company_logo;;
        $experience->position = $request->company_position;
        $experience->start_date = $request->start_date;
        $experience->end_date = $request->end_date_radio == 'present' ? null : $request->end_date;
        $experience->is_present = $request->end_date_radio == 'present' ? 1 : 0;
        $experience->save();

        if($image_to_remove != null){
            if(file_exists($image_to_remove)){
                unlink(getcwd() . '/' . $image_to_remove);
            }
        }

        return redirect()->route('experiences');
    }

    public function deleteExperience(Request $request)
    {
        $id = $request->id;
        $experience = Experience::find($id);
        $image_to_remove = $experience->company_logo;

        //remove brand image from the server
        if(file_exists($image_to_remove)){
            unlink(getcwd() . '/' . $image_to_remove);
        }

        $experience->delete();
        return redirect()->route('experiences');
    }

    public function skills()
    {
        $skills = Skill::orderBy('expertise', 'desc')->get();
        return view('skills', compact('skills'));
    }

    public function addskill(Request $request)
    {
        $skill = new Skill();

        // storing_image
        if(isset($request->image)){
            $imageName = time().'.'.$request->image->extension();  
            $request->image->move(public_path('img/skill'), $imageName);
        }

        $skill->name = $request->skill_name;
        $skill->icon = isset($request->image) ? 'img/skill/' . $imageName : "";
        $skill->expertise = $request->skill_expertise;

        $skill->save();
        return redirect()->route('skills');
    }

    public function editSkill(Request $request)
    {
        $id = $request->id;
        $skill = Skill::find($id);
        $image_to_remove = null;

        // storing_image
        if(isset($request->image)){
            $imageName = time().'.'.$request->image->extension();  
            $request->image->move(public_path('img/skill'), $imageName);
            $image_to_remove = $skill->icon;
        }

        $skill->name = $request->skill_name;
        $skill->icon = isset($request->image) ? 'img/skill/' . $imageName : $skill->icon;
        $skill->expertise = $request->skill_expertise;
        $skill->save();

        if($image_to_remove != null){
            if(file_exists($image_to_remove)){
                unlink(getcwd() . '/' . $image_to_remove);
            }
        }

        return redirect()->route('skills');
    }

    public function deleteSkill(Request $request)
    {
        $id = $request->id;
        $skill = Skill::find($id);
        $image_to_remove = $skill->icon;

        //remove brand image from the server
        if(file_exists($image_to_remove)){
            unlink(getcwd() . '/' . $image_to_remove);
        }

        $skill->delete();
        return redirect()->route('skills');
    }
}
