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
use App\Models\Review;
use Firebase\JWT\JWT;
use Config;

/**
 * Class HikesController
 * @package App\Http\Controllers
 */
class HikesController extends Controller
{
    //Define search
    /**
     *
     */
    const KEYWORD_MATCH_THRESHOLD = 0.30;
    /**
     *
     */
    const BEST_KEYWORD_THRESHOLD = 0.4;
    /**
     * @var null
     */
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

    /**
     * @param $request
     * @return bool
     */
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

    /**
     * @param null $search_results
     * @return bool
     */
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

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $fields = $request->input('fields');
        $user = $this->isAuthenticated($request);
        if ($user) {
            $hikesArr = [];
            $myHikes = $user->hikes;
            foreach($myHikes as $h)
            {
                $hikesArr[] = Hike::where('id',$h->pivot->hike_id)->with('users','location','photos_generic','photo_facts','photo_landscape','photo_preview')->first();
            }
            return response()->json($hikesArr);
        } else {
            $hikes = Hike::where('is_featured', true)->with('location','photos_generic','photo_facts','photo_landscape','photo_preview')->get()->toArray();
            return response()->json($hikes);
        }
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
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

    /**
     * @param $hike_id
     * @return mixed
     */
    public function show($hike_id)
    {
        $hike = Hike::where('string_id', $hike_id)->with('location','photo_landscape', 'photo_facts','photo_preview','photos_generic')->first();
        $hike->route = json_decode($hike->route);
        return $hike;
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $user = User::find($request['user']['sub']);
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
        $hike->created_at = date("Y-m-d H:i:s");
        $hike->updated_at = date("Y-m-d H:i:s");
        $hike->save();
        $hike->users()->attach($user->id);
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
        //
        $token = $this->createToken($user);
        //
    	return response('',202)
                ->header('Content-Type', 'application/json')
                ->header('Hikeio-Hike-String-Id', $hike->string_id)
//                ->json(['token' => $token])
            ;
    }

    /**
     * @param Request $request
     * @param $string_id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $string_id)
    {
        $user = User::find($request['user']['sub']);
        $hike = Hike::where('string_id',$string_id)->first();
        $hike->name = $request['name'];
        $hike->description = $request['description'];
        $hike->locality = $request['locality'];
        $hike->permit = $request['permit'];
        $hike->route = $request['route'];
        $hike->distance = $request['distance'];
        $hike->elevation_gain = $request['elevation_gain'];
        $hike->elevation_max = $request['elevation_max'];
        $hike->updated_at = date("Y-m-d H:i:s");
        $hike->save();
        //
        $token = $this->createToken($user);
        //
        return response()->json([
            'token' => $token,
            'hike'  => $hike
        ]);
    }

    public function myHikes(Request $request)
    {
        $hikesArr = [];
        $user = User::find($request['user']['sub']);
        if ($user) {
            $myHikes = $user->hikes;
            foreach($myHikes as $h)
            {
                $hikesArr[] = Hike::where('id',$h->pivot->hike_id)->with('users')->first();
            }
            $token = $this->createToken($user);
            //
            return response()->json([
                'token' => $token,
                'hike'  => $hikesArr
            ]);
        }
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function reviews(Request $request)
    {
        if (isset($request['string_id']) && isset($request['action'])) {
            $review = Review::where('string_id', $request['string_id'])->first();
            $review->status = $request['action'] == 'accept' ? 'accepted':'unreviewed';
            $review->save();
        } else {
            return Review::where('status','unreviewed')->get();
        }
    }

    /**
     * @param Request $request
     * @param $hike_id
     * @return \Illuminate\Http\JsonResponse
     */
    public function upload(Request $request, $hike_id)
    {
        $user = User::find($request['user']['sub']);
        $hike = Hike::where('string_id',$hike_id)->first();

        $uuid = Uuid::generate(4);

        $type = $request['type'];
        $token = $this->createToken($user);
        //define the image paths
        $store_path = 'hike-images/';
        $hike_img_dir      =   public_path('hike-images/'. $hike_id. '/');
        //
        if(Input::hasFile('file') && isset($hike)) {
            $photo = new Photo();
            $photo->string_id = $hike_id. '/'. $uuid;
            $photo->alt = $hike_id;
            $photo->attribution_link = null;
            $photo->save();
            $image = Input::file('file');
            switch($type)
            {
                case "landscape":
                        $original = $uuid . '-original.' . $image->getClientOriginalExtension();
                        $image->move($hike_img_dir, $original);
                        $width = Image::make($hike_img_dir . $original)->width();
                        $height = Image::make($hike_img_dir . $original)->height();
                        $photo->width = $width;
                        $photo->height = $height;
                        $photo->save();
                        //
                        $large = $uuid . '-large.' . $image->getClientOriginalExtension();
                        Image::make($hike_img_dir . $original)->resize(1200, null, function ($constraint) {
                            $constraint->aspectRatio();
                        })->save($hike_img_dir . $large);
                        $medium = $uuid . '-medium.' . $image->getClientOriginalExtension();
                        Image::make($hike_img_dir . $original)->resize(800, null, function ($constraint) {
                            $constraint->aspectRatio();
                        })->save($hike_img_dir . $medium);
                        $small = $uuid . '-small.' . $image->getClientOriginalExtension();
                        Image::make($hike_img_dir . $original)->resize(400, null, function ($constraint) {
                            $constraint->aspectRatio();
                        })->save($hike_img_dir . $small);
                        $tiny = $uuid . '-tiny.' . $image->getClientOriginalExtension();
                        Image::make($hike_img_dir . $original)->resize(200, null, function ($constraint) {
                            $constraint->aspectRatio();
                        })->save($hike_img_dir . $tiny);
                        $hike->photo_landscape_id = $photo->id;
                        $hike->save();
                        break;
                case "facts":
                        $original = $uuid . '-original.' . $image->getClientOriginalExtension();
                        $image->move($hike_img_dir, $original);
                        $width = Image::make($hike_img_dir . $original)->width();
                        $height = Image::make($hike_img_dir . $original)->height();
                        $photo->width = $width;
                        $photo->height = $height;
                        $photo->save();
                        //
                        $large = $uuid . '-large.' . $image->getClientOriginalExtension();
                        Image::make($hike_img_dir . $original)->resize(null, 1200, function($constraint) {
                            $constraint->aspectRatio();
                        })->save($hike_img_dir . $large);
                        $medium = $uuid . '-medium.' . $image->getClientOriginalExtension();
                        Image::make($hike_img_dir . $original)->resize(null, 800, function ($constraint) {
                            $constraint->aspectRatio();
                        })->save($hike_img_dir . $medium);
                        $small = $uuid . '-small.' . $image->getClientOriginalExtension();
                        Image::make($hike_img_dir . $original)->resize(null, 400, function ($constraint) {
                            $constraint->aspectRatio();
                        })->save($hike_img_dir . $small);
                        $tiny = $uuid . '-tiny.' . $image->getClientOriginalExtension();
                        Image::make($hike_img_dir . $original)->resize(null, 200, function ($constraint) {
                            $constraint->aspectRatio();
                        })->save($hike_img_dir . $tiny);
                        //
                        $thumb_medium = $uuid . '-thumb-medium.' . $image->getClientOriginalExtension();
                        Image::make($hike_img_dir . $original)->resize(800, 800)->save($hike_img_dir . $thumb_medium);
                        $thumb_small = $uuid . '-thumb-small.' . $image->getClientOriginalExtension();
                        Image::make($hike_img_dir . $original)->resize(400, 400)->save($hike_img_dir . $thumb_small);
                        $thumb_tiny = $uuid . '-thumb-tiny.' . $image->getClientOriginalExtension();
                        Image::make($hike_img_dir . $original)->resize(200, 200)->save($hike_img_dir . $thumb_tiny);
                        $hike->photo_facts_id = $photo->id;
                        $hike->save();
                        break;
                case "preview":
                        $original = $uuid . '-original.' . $image->getClientOriginalExtension();
                        $image->move($hike_img_dir, $original);
                        $width = Image::make($hike_img_dir . $original)->width();
                        $height = Image::make($hike_img_dir . $original)->height();
                        $photo->width = $width;
                        $photo->height = $height;
                        $photo->save();
                        //
                        $large = $uuid . '-large.' . $image->getClientOriginalExtension();
                        Image::make($hike_img_dir . $original)->resize(null, 1200, function($constraint) {
                            $constraint->aspectRatio();
                        })->save($hike_img_dir . $large);
                        $medium = $uuid . '-medium.' . $image->getClientOriginalExtension();
                        Image::make($hike_img_dir . $original)->resize(null, 800, function ($constraint) {
                            $constraint->aspectRatio();
                        })->save($hike_img_dir . $medium);
                        $small = $uuid . '-small.' . $image->getClientOriginalExtension();
                        Image::make($hike_img_dir . $original)->resize(null, 400, function ($constraint) {
                            $constraint->aspectRatio();
                        })->save($hike_img_dir . $small);
                        $tiny = $uuid . '-tiny.' . $image->getClientOriginalExtension();
                        Image::make($hike_img_dir . $original)->resize(null, 200, function ($constraint) {
                            $constraint->aspectRatio();
                        })->save($hike_img_dir . $tiny);
                        //
                        $thumb_medium = $uuid . '-thumb-medium.' . $image->getClientOriginalExtension();
                        Image::make($hike_img_dir . $original)->resize(800, 800)->save($hike_img_dir . $thumb_medium);
                        $thumb_small = $uuid . '-thumb-small.' . $image->getClientOriginalExtension();
                        Image::make($hike_img_dir . $original)->resize(400, 400)->save($hike_img_dir . $thumb_small);
                        $thumb_tiny = $uuid . '-thumb-tiny.' . $image->getClientOriginalExtension();
                        Image::make($hike_img_dir . $original)->resize(200, 200)->save($hike_img_dir . $thumb_tiny);
                        //
                        $hike->photo_preview_id = $photo->id;
                        $hike->save();
                        break;
                case "generic":
                        $original = $uuid . '-original.' . $image->getClientOriginalExtension();
                        $image->move($hike_img_dir, $original);
                        $width = Image::make($hike_img_dir . $original)->width();
                        $height = Image::make($hike_img_dir . $original)->height();
                        $photo->width = $width;
                        $photo->height = $height;
                        $photo->save();
                        //
                        //
                        $large = $uuid . '-large.' . $image->getClientOriginalExtension();
                        Image::make($hike_img_dir . $original)->resize(1200, 1200)->save($hike_img_dir . $large);
                        $medium = $uuid . '-medium.' . $image->getClientOriginalExtension();
                        Image::make($hike_img_dir . $original)->resize(800, 800)->save($hike_img_dir . $medium);
                        $small = $uuid . '-small.' . $image->getClientOriginalExtension();
                        Image::make($hike_img_dir . $original)->resize(400, 400)->save($hike_img_dir . $small);
                        $tiny = $uuid . '-tiny.' . $image->getClientOriginalExtension();
                        Image::make($hike_img_dir . $original)->resize(200, 200)->save($hike_img_dir . $tiny);
                        //
                        $thumb_medium = $uuid . '-thumb-medium.' . $image->getClientOriginalExtension();
                        Image::make($hike_img_dir . $original)->resize(800, 800)->save($hike_img_dir . $thumb_medium);
                        $thumb_small = $uuid . '-thumb-small.' . $image->getClientOriginalExtension();
                        Image::make($hike_img_dir . $original)->resize(400, 400)->save($hike_img_dir . $thumb_small);
                        $thumb_tiny = $uuid . '-thumb-tiny.' . $image->getClientOriginalExtension();
                        Image::make($hike_img_dir . $original)->resize(200, 200)->save($hike_img_dir . $thumb_tiny);
                        $hike->photos_generic()->attach($photo->id);
                        $hike->save();
                        break;
            }
            
            $relativePath = $store_path . $original;
              return response()->json([
                  'token'   => $token,
                  'path'    => $relativePath,
                  'success' => true,
                  'msg'     => 'The image has been uploaded successfully!'
              ], 200);
        } else {
              return response()->json([
                  'token'   => $token,
                  'success' => false,
                  'msg'     => 'Error! The image has not been uploaded!'
              ], 200);
        }
    }

    /**
     * @param \Illuminate\Database\Eloquent\Model $model
     * @param $value
     * @return string
     */
    protected function getUniqueSlug(\Illuminate\Database\Eloquent\Model $model, $value)
    {
        $slug = Str::slug($value);
        $slugCount = count($model->whereRaw("string_id REGEXP '^{$slug}(-[0-9]+)?$' and id != '{$model->id}'")->get());

        return ($slugCount > 0) ? "{$slug}-{$slugCount}" : $slug;
    }

    /**
     * @param $rendition
     * @return string
     */
    protected function get_rendition_suffix($rendition)
    {
        return "-" + $rendition + ".jpg";
    }
}
