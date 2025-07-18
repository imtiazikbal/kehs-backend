<?php

namespace App\Http\Controllers\api\v1;

use Exception;
use Illuminate\Http\Request;
use App\Services\StoreFileInLocal;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class HomeBannerController extends ResponseController
{
    // store and update home banner
    public function store(Request $request){
        try {
            $validator = Validator::make($request->all(), [
                'image' => 'required',
            ]);
            if($validator->fails()){
                return $this->sendError('Validation Error.', $validator->errors());       
            }
            $banner = DB::table('home_banners')->first();
            if($banner){
                $image = $request->image;
              StoreFileInLocal::deleteLocalFile(public_path($banner->image));
                $image_url = StoreFileInLocal::localUploadSingle($image, 'images/home_banners');
                // delete old image
                DB::table('home_banners')->where('id', $banner->id)->update([
                    'image' => $image_url,
                    'updated_at' => now(),
                ]);
                return $this->sendResponse('Home banner updated successfully', 'Home banner updated successfully.');
            }else{
                
                $image = $request->image;
                $image_url = StoreFileInLocal::localUploadSingle($image, 'images/home_banners');
                DB::table('home_banners')->insert([
                    'image' => $image_url,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
                return $this->sendResponse('Home banner added successfully', 'Home banner added successfully.');
            }
            
        } catch (Exception $exception) {
            return $this->sendError('Error', $exception->getMessage());
        }

    }

    // get for admin
    public function index(){
        try {
            $banner = DB::table('home_banners')->first();
            return $this->sendResponse('Home banner', $banner);
        } catch (Exception $exception) {
            return $this->sendError('Error', $exception->getMessage());
        }
    }
}
