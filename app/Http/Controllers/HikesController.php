<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Intervention\Image\ImageManagerStatic as Image;
use Illuminate\Support\Facades\Input;
use File;
use Validator;
use Uuid;
use App\Models\Hike;
use App\Models\Location;
use App\Models\Keyword;
use App\Models\Photo;
use App\Models\User;
use Firebase\JWT\JWT;

class HikesController extends Controller
{
    //Define search
    const KEYWORD_MATCH_THRESHOLD = 0.30;
    const BEST_KEYWORD_THRESHOLD = 0.4;
    var $word_weight = null;
    //
    /**
     * Generate JSON Web Token.
     */
    protected function createToken($user)
    {
        $payload = [
            'sub' => $user->id,
            'iat' => time(),
            'exp' => time() + (2 * 7 * 24 * 60 * 60)
        ];
        return JWT::encode($payload, Config::get('app.token_secret'));
    }
    //

    protected function isAuthenticated($request) {
        if ($request->header('Authorization'))
        {
            $token = explode(' ', $request->header('Authorization'))[1];
            $payload = (array) JWT::decode($token, Config::get('app.token_secret'), array('HS256'));
            $request['user'] = $payload;
            return User::find($request['user']['sub']);
        } else {
            return false;
        }
    }
    //
    protected function has_best_result($search_results = null)
    {
       if( count($search_results) == 1 or 
			(count($search_results) > 1 and 
				$search_results[0] >= $search_results[1] + self::BEST_KEYWORD_THRESHOLD * $word_weight)) {
           return true;
       } else { 
           return false;
       }
	
    }
    //
    public function index(Request $request)
    {
        $fields = $request->input('fields');

//        echo 'fields: '.$fields;
        return Hike::where('is_featured', true)->with('location','photos_generic','photo_facts','photo_landscape','photo_preview')->get();
        
//        return  File::get(public_path().'/data/hikes.json');
//        $hikesArr = json_decode($json, true);

//        foreach ($hikesArr as $k => $v) 
//        {
//            $hike = new Hike();
//            $photo = new Photo();
//            $hike->string_id = $v['string_id'];
//            $hike->distance = $v['distance'];
//            $hike->is_featured = $v['is_featured'];
//            $hike->locality = $v['locality'];
//            $hike->name = $v['name'];
//            
//            if(array_key_exists("photo_facts",$v))
//            {			
//                foreach($v['photo_facts'] as $facts)
//                {
//                    $hike->photo_facts_id = $facts['id'];
//                    $hike->photos()->attach($facts['id']);
//                    $photo->id = $facts['id'];
//                    $photo->string_id = $facts['string_id'];
//                    $photo->alt = $facts['alt'];
//                    $photo->width = $facts['width'];
//                    $photo->height = $facts['height'];
//                    $photo->attribution_link = $facts['attribution_link'];
//                }
//            }		
//            //
//            if(array_key_exists("photo_landscape",$v))
//            {
//                foreach($v['photo_landscape'] as $landscape)
//                {
//                    $hike->photo_landscape_id = $landscape['id'];
//                    $hike->photos()->attach($landscape['id']);
//                    $photo->id = $landscape['id'];
//                    $photo->string_id = $landscape['string_id'];
//                    $photo->alt = $landscape['alt'];
//                    $photo->width = $landscape['width'];
//                    $photo->height = $landscape['height'];
//                    $photo->attribution_link = $landscape['attribution_link'];
//                }
//            }
//            //
//            if(array_key_exists("photo_landscape",$v))
//            {
//                foreach($v['photo_preview'] as $preview)
//                {
//                    $hike->photo_preview_id = $preview['id'];
//                    $hike->photos()->attach($preview['id']);
//                    $photo->id = $preview['id'];
//                    $photo->string_id = $preview['string_id'];
//                    $photo->alt = $preview['alt'];
//                    $photo->width = $preview['width'];
//                    $photo->height = $preview['height'];
//                    $photo->attribution_link = $preview['attribution_link'];
//                }
//            }
//        $hike->save();    
//        $photo->save();

//        echo 'Hike '.$k.' is saved! '.$v ;
//        }

    }
    //
    public function search(Request $request)
    {
        $keywords = [];
        $search_results = [];
        $query = trim(preg_replace("/(\s+)+/", " ", $request->input('q')));        
        $fields = $request->input('fields');
        // expand this list with your words.
        $list = array("in","it","a","the","of","or","I","you","he","me","us","they","she","to","but","that","this","those","then");
        $c = 0;
        foreach(explode(" ", $query) as $key){
            if (in_array($key, $list)){
                continue;
            }
            $keywords[] = Keyword::where('keyword','LIKE','%'.$key.'%')->get();
            if ($c >= 15){
                break;
            }
            $c++;
        }
        //
        $this->word_weight = 1.0 / count($keywords);
        //
        if(isset($keywords))
        {
            foreach($keywords as $keyword)
            {
                $search_results[] = $keyword;
            }
        }
        
       return response()->json($keywords) ;
    }
    //
    public function show($hike_id)
    {
//        return  File::get(public_path().'/data/salkantay-trek.json');
        $hike = Hike::where('string_id', $hike_id)->with('location','photo_landscape', 'photo_facts','photo_preview','photos_generic')->first();
        $hike->route = json_decode($hike->route);
        return $hike;
    }
    //
    public function store(Request $request)
    {
    	$validator = Validator::make($request->all(), [
            'name' => 'required|max:255',
            'distance' => 'numeric',
            'elevation_gain' => 'numeric',
            'elevation_max' => 'numeric',
            'location.latitude' => 'numeric|min:-90|max:90',
            'location.longitude' => 'numeric|min:-180|max:180',
        ]);
        //
        if ($validator->fails()) {
            return response()->json($validator->messages());
        }
        //
        $location = new Location([
            'latitude' => $request['location']['latitude'],
            'longitude' => $request['location']['longitude'],
        ]);
        $location->save();
        $hike = new Hike();
        $hike->name = $request->input('name');
        $hike->string_id = $this->getUniqueSlug($hike, $request->input('name'));
        $hike->location_id = $location->id;
        $hike->distance = $request->input('distance');
        $hike->elevation_gain = $request->input('elevation_gain');
        $hike->elevation_max = $request->input('elevation_max');
        $hike->locality = $request->input('locality');
        $hike->creation_time = date("Y-m-d H:i:s");       
        $hike->save();
        //
        $parts = $pieces = explode("-", $hike->string_id);
        //Add keyword
        $keyword_exist = Keyword::where('keyword', '=', $parts[0])->first();
        if($keyword_exist === null) {
            $keywords = $hike->keywords()->create([
                'keyword' => $parts[0]
            ]);
        }    
        //
        if(isset($parts[1]) && $keyword_exist === null) {
            $keywords = $hike->keywords()->create([
                'keyword' => $parts[1]
            ]);
        }
        
        
        
    	return response('',202)
                ->header('Content-Type', 'application/json')
                ->header('Hikeio-Hike-String-Id', $hike->string_id);
    }
    //
    public function update(Request $request, $id)
    {
        $user = User::find($request['user']['sub']);
        echo '<pre>'; print_r($user);
    }
    //
    public function upload(Request $request, $hike_id) 
    {
//        $user = User::find($request['user']['sub']);
        $uuid = Uuid::generate(4);
//        dd($uuid->string);
        $hike = Hike::where('string_id',$hike_id)->first();
        $type = $request['type'];
        //define the image paths
        $store_path = 'hike-images/';
        $hike_img_dir      =   public_path('hike-images/'. $hike_id. '/');
        //
        if(Input::hasFile('file')) {
            $image = Input::file('file'); 
//            $tmpFileName = $image->getClientOriginalName(); //real name
            $tmpFileName = $uuid . '.' . $image->getClientOriginalExtension();
            $image->move($hike_img_dir, $tmpFileName);
            $img = Image::make($hike_img_dir . $tmpFileName);
            $width = Image::make($hike_img_dir . $tmpFileName)->width();
            $height = Image::make($hike_img_dir . $tmpFileName)->height();
            $photo = new Photo();
            $photo->string_id = $hike_id. '/'. $uuid;
            $photo->width = $width;
            $photo->height = $height;
            $photo->alt = $hike_id;
            $photo->attribution_link = null;
            $photo->save();
            switch($type)
            {
                case "landscape":
                        $hike->photo_landscape_id = $photo->id;                        
                        $hike->save();
                        break;
                case "facts":
                        $hike->photo_facts_id = $photo->id;
                        $hike->save();
                        break;
                case "preview":
                        $hike->photo_preview_id = $photo->id;
                        break;
                case "generic":
                        $hike->photos_generic()->attach($photo->id);
                        break;
            }
            
 //           echo '<pre>'; print_r($img);exit;
//            $tmpFileName = $image->getClientOriginalName() . '.' . $image->getClientOriginalExtension();

            //

            
            
//            Image::make($image->getRealPath())->save($path);
//            Image::make($image->getRealPath())->resize(300, 200)->save($thumb);
//            Image::make($image->getRealPath())->resize(144, 144)->save($mobile);


            $relativePath = $store_path . $tmpFileName;
              return response()->json(array('path'=> $relativePath), 200);
        } else {
              return response()->json(false, 200);
        }
        return response()->json(['hike_id' => $hike_id, 'request'=>$request->all(),'pic'=>$image->getClientOriginalName()]);
    }
    //
    protected function getUniqueSlug(\Illuminate\Database\Eloquent\Model $model, $value)
    {
        $slug = Str::slug($value);
        $slugCount = count($model->whereRaw("string_id REGEXP '^{$slug}(-[0-9]+)?$' and id != '{$model->id}'")->get());

        return ($slugCount > 0) ? "{$slug}-{$slugCount}" : $slug;
    }
    //
    protected function get_rendition_suffix($rendition)
    {
        return "-" + $rendition + ".jpg";
    }
    //
}
