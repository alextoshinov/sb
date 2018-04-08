<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Hike;
use App\Models\Photo;
use App\Http\Requests;
use Illuminate\Support\Facades\Log;

class PhotoController extends Controller
{
    public function delete($id=null, $type = null, Request $request)
    {
        $photo = Photo::where('id', $id)->first();

    	$hike_img_dir = public_path('hike-images/');

    	if ($type) {
    		switch($type) {
    			case 'landscape':
    			    $hike = Hike::where('photo_landscape_id', $id)->with('photo_landscape')->first();
    			    $hike->photo_landscape_id = null;
    			    $hike->save();
                    $photo->delete();
    				$this->unlinkPic($hike_img_dir.$request['string_id'].$type.'.jpg');
                    $this->unlinkPic($hike_img_dir.$request['string_id'].'-original.jpg');
                    $this->unlinkPic($hike_img_dir.$request['string_id'].'-large.jpg');
                    $this->unlinkPic($hike_img_dir.$request['string_id'].'-medium.jpg');
                    $this->unlinkPic($hike_img_dir.$request['string_id'].'-small.jpg');
                    $this->unlinkPic($hike_img_dir.$request['string_id'].'-tiny.jpg');
                    $this->unlinkPic($hike_img_dir.$request['string_id'].'-thumb-medium.jpg');
                    $this->unlinkPic($hike_img_dir.$request['string_id'].'-thumb-small.jpg');
                    $this->unlinkPic($hike_img_dir.$request['string_id'].'-thumb-tiny.jpg');
    			break;
    			case 'facts':
                    $hike = Hike::where('photo_facts_id', $id)->with('photo_facts')->first();
                    $hike->photo_facts_id = null;
                    $hike->save();
                    $photo->delete();
    				$this->unlinkPic($hike_img_dir.$request['string_id'].$type.'.jpg');
                    $this->unlinkPic($hike_img_dir.$request['string_id'].'-original.jpg');
                    $this->unlinkPic($hike_img_dir.$request['string_id'].'-large.jpg');
                    $this->unlinkPic($hike_img_dir.$request['string_id'].'-medium.jpg');
                    $this->unlinkPic($hike_img_dir.$request['string_id'].'-small.jpg');
                    $this->unlinkPic($hike_img_dir.$request['string_id'].'-tiny.jpg');
                    $this->unlinkPic($hike_img_dir.$request['string_id'].'-thumb-medium.jpg');
                    $this->unlinkPic($hike_img_dir.$request['string_id'].'-thumb-small.jpg');
                    $this->unlinkPic($hike_img_dir.$request['string_id'].'-thumb-tiny.jpg');
    			break;
    			case 'preview':
                    $hike = Hike::where('photo_preview_id', $id)->with('photo_preview')->first();
                    $hike->photo_preview_id = null;
                    $hike->save();
                    $photo->delete();
    				$this->unlinkPic($hike_img_dir.$request['string_id'].'-original.jpg');
    				$this->unlinkPic($hike_img_dir.$request['string_id'].'-large.jpg');
    				$this->unlinkPic($hike_img_dir.$request['string_id'].'-medium.jpg');
    				$this->unlinkPic($hike_img_dir.$request['string_id'].'-small.jpg');
    				$this->unlinkPic($hike_img_dir.$request['string_id'].'-tiny.jpg');
    				$this->unlinkPic($hike_img_dir.$request['string_id'].'-thumb-medium.jpg');
    				$this->unlinkPic($hike_img_dir.$request['string_id'].'-thumb-small.jpg');
    				$this->unlinkPic($hike_img_dir.$request['string_id'].'-thumb-tiny.jpg');
    			break;
    			case 'generic':
    			    $hike = Hike::with('photos_generic')->first();
                    $hike->photos_generic()->detach($id);
                    $photo->delete();
                    $this->unlinkPic($hike_img_dir.$request['string_id'].'-original.jpg');
                    $this->unlinkPic($hike_img_dir.$request['string_id'].'-large.jpg');
                    $this->unlinkPic($hike_img_dir.$request['string_id'].'-medium.jpg');
                    $this->unlinkPic($hike_img_dir.$request['string_id'].'-small.jpg');
                    $this->unlinkPic($hike_img_dir.$request['string_id'].'-tiny.jpg');
                    $this->unlinkPic($hike_img_dir.$request['string_id'].'-thumb-medium.jpg');
                    $this->unlinkPic($hike_img_dir.$request['string_id'].'-thumb-small.jpg');
                    $this->unlinkPic($hike_img_dir.$request['string_id'].'-thumb-tiny.jpg');
    			break;
    		}
    	}
    }
    //
   	private function unlinkPic($filename)
   	{
   		if (file_exists($filename)) {
   			unlink($filename);
   			Log::info('File '.$filename. 'was deleted.');
		} else {
			Log::info('File '.$filename. 'NOT exists!.');
		}
   	}
    //
}
