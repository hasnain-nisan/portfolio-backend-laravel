<?php

namespace App\Http\Controllers;

use App\Mail\ContactReply;
use View;
use App\Models\About;
use App\Models\Contact;
use App\Models\Experience;
use App\Models\Hero;
use App\Models\Skill;
use App\Models\TechStack;
use App\Models\Work;
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

    public function hero()
    {
        $hero = Hero::first();
        return view('hero', compact('hero'));
    }

    public function heroPost(Request $request)
    {
        $hero = Hero::first();
        if(!$hero) $hero = new Hero();

        // storing_image
        $image_to_remove = null;
        if(isset($request->image)){
            $imageName = time().'.'.$request->image->extension();  
            $request->image->move(public_path('img/hero'), $imageName);
            $image_to_remove = $hero->image;
        }

        $hero->tags = json_encode($request->tags);
        $hero->image = isset($request->image) ? 'img/hero/' . $imageName : $hero->image;
        $hero->save();

        if($image_to_remove != null){
            if(file_exists($image_to_remove)){
                unlink(getcwd() . '/' . $image_to_remove);
            }
        }

        return redirect()->route('hero');
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

    public function technologyStack()
    {
        $stacks = TechStack::all();
        return view('techstacks', compact('stacks'));
    }

    public function addTechStack(Request $request)
    {
        $exist = TechStack::where('name', $request->name)->first();
        if($exist) return redirect()->route('technology-stack')->with('error', 'Technology already exists');
        $tech = new TechStack();

        // storing_image
        if(isset($request->image)){
            $imageName = time().'.'.$request->image->extension();  
            $request->image->move(public_path('img/techStack'), $imageName);
        }

        $tech->name = $request->name;
        $tech->image = isset($request->image) ? 'img/techStack/' . $imageName : "";

        $tech->save();
        return redirect()->route('technology-stack');
    }

    public function deleteTechStack(Request $request)
    {
        $id = $request->id;
        $tech = TechStack::find($id);
        $image_to_remove = $tech->image;

        //remove brand image from the server
        if(file_exists($image_to_remove)){
            unlink(getcwd() . '/' . $image_to_remove);
        }

        $tech->delete();
        return redirect()->route('technology-stack');
    }

    public function experiences()
    {
        $experiences = Experience::orderBy('start_date', 'asc')->get();
        foreach ($experiences as $key => $exp) {
            $experiences[$key] = $this->techStackToExperience($exp);
        }
        $stacks = TechStack::all();
        return view('experiences', compact('experiences', 'stacks'));
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
        $experience->tech_used = json_encode($request->used_tech);
        $experience->key_points = $request->key_points;

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
        $experience->tech_used = json_encode($request->used_tech);
        $experience->key_points = $request->key_points;
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

    public function works()
    {
        $works = Work::orderBy('id', 'desc')->get();
        foreach ($works as $key => $work) {
            $works[$key] = $this->techStackToExperience($work);
        }
        $stacks = TechStack::all();
        return view('works', compact('works', 'stacks'));
    }

    public function addWorks(Request $request)
    {
        $works = new Work();

        // storing_image
        if(isset($request->image)){
            $imageName = time().'.'.$request->image->extension();  
            $request->image->move(public_path('img/works'), $imageName);
        }

        $works->name = $request->name;
        $works->image = isset($request->image) ? 'img/works/' . $imageName : "";
        $works->url = $request->url;
        $works->tech_used =  json_encode($request->used_tech);
        $works->description = $request->desc;

        $works->save();
        return redirect()->route('works');
    }

    public function editWorks(Request $request)
    {
        $id = $request->id;
        $works = Work::find($id);
        $image_to_remove = null;

        // storing_image
        if(isset($request->image)){
            $imageName = time().'.'.$request->image->extension();  
            $request->image->move(public_path('img/works'), $imageName);
            $image_to_remove = $works->image;
        }

        $works->name = $request->name;
        $works->image = isset($request->image) ? 'img/works/' . $imageName : $works->image;
        $works->url = $request->url;
        $works->tech_used =  json_encode($request->used_tech);
        $works->description = $request->desc;
        $works->save();

        if($image_to_remove != null){
            if(file_exists($image_to_remove)){
                unlink(getcwd() . '/' . $image_to_remove);
            }
        }

        return redirect()->route('works');
    }

    public function deleteWorks(Request $request)
    {
        $id = $request->id;
        $work = Work::find($id);
        $image_to_remove = $work->image;

        //remove brand image from the server
        if(file_exists($image_to_remove)){
            unlink(getcwd() . '/' . $image_to_remove);
        }

        $work->delete();
        return redirect()->route('works');
    }

    public function techStackToExperience($experience)
    {
        $techs = json_decode($experience->tech_used);

        if(count($techs) > 0){
            $arr = [];
            foreach ($techs as $tech) {
                $techStack = TechStack::find($tech);
                if($techStack){
                    array_push($arr, $techStack);
                }
            }
            $experience['tech_stacks'] = $arr;
        }

        return $experience;
    }
}
