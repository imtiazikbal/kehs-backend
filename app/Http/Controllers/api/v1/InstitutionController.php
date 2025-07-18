<?php

namespace App\Http\Controllers\api\v1;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class InstitutionController extends ResponseController
{
    public function storeOrUpdateInstitution(Request $request)
{
    try {
         $request->validate([
        'institution_about' => 'nullable|string',
        'institution_history' => 'nullable|string',
        'institution_head_teacher_advice' => 'nullable|string',
    ]);

    // Check if there's already an Institution (assuming only 1)
    $institution = DB::table('institutions_info')->first();

    if ($institution) {
        // Update
        DB::table('institutions_info')->where('id', $institution->id)->update([
            'institution_about' => $request->input('institution_about') ?? $institution->institution_about ,
            'institution_history' => $request->input('institution_history') ?? $institution->institution_history,
            'institution_head_teacher_advice' => $request->input('institution_head_teacher_advice') ?? $institution->institution_head_teacher_advice,
                    'updated_at' => now(),

            ]);
    } else {
        // Create
        DB::table('institutions_info')->insert([
            'institution_about' => $request->input('institution_about'),
            'institution_history' => $request->input('institution_history'),
            'institution_head_teacher_advice' => $request->input('institution_head_teacher_advice'),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    return $this->sendResponse('Institution updated successfully', 'Institution updated successfully.');

    } catch (\Throwable $th) {
        return $this->sendError('Error', $th->getMessage());
    }
}

   // get for admin 
   public function getInstitution(){
    try {
        $institution = DB::table('institutions_info')->first();
        return $this->sendResponse($institution,'Institution', );
    } catch (Exception $exception) {
        return $this->sendError('Error', $exception->getMessage());
    }
   }
}
