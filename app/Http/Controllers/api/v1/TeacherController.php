<?php

namespace App\Http\Controllers\api\v1;

use Exception;
use Illuminate\Http\Request;
use App\Services\StoreFileInLocal;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class TeacherController extends ResponseController
{
    // Get all teachers (for admin)
public function index(Request $request)
{
    try {
        // Optional: paginate, default 10 per page, or you can customize

        $teachers = DB::table('teachers')->orderBy('id', 'desc')->get();
return $this->sendResponse($teachers,'Teachers', 200);
    } catch (\Exception $e) {
        return response()->json([
            'message' => 'Failed to fetch teachers',
            'error' => $e->getMessage(),
        ], 500);
    }
}

    // Store a new teacher
public function store(Request $request)
{
    try {
        $request->validate([
            'name' => 'required|string|max:255',
            'designation' => 'nullable|string|max:255',
            'image' => 'nullable|image|max:5048', // max 5MB
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'status' => 'nullable|string|max:20',
        ]);

        $data = $request->only(['name', 'designation', 'phone', 'email', 'status']);

        // Handle image upload
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageUrl = StoreFileInLocal::localUploadSingle($image, 'images/teachers');
        }

        $data['image'] = $imageUrl;
        $teacher =  DB::table('teachers')->insert($data);
        return $this->sendResponse($teacher,'Teacher created successfully', 200);

    } catch (\Exception $e) {
        return response()->json([
            'message' => 'Failed to create teacher',
            'error' => $e->getMessage()
        ], 500);
    }
}



// Update teacher
public function update(Request $request, $id)
{
    try {
       
        $teacher = DB::table('teachers')->where('id', $id)->first();

        if (!$teacher) {
            return response()->json([
                'message' => 'Teacher not found',
            ], 404);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'designation' => 'nullable|string|max:255',
            'image' => 'nullable|image|max:2048', // max 2MB
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'status' => 'nullable|string|max:20',
        ]);

        $data = $request->only(['name', 'designation', 'phone', 'email', 'status']);

        // Default image is old image
        $newImageUrl = $teacher->image;

        // Handle image upload
        if ($request->hasFile('image')) {
            // Delete old image if exists
            StoreFileInLocal::deleteLocalFile(public_path($teacher->image));

            $image = $request->file('image');
            $uploadedImage = StoreFileInLocal::localUploadSingle($image, 'images/teachers');

            if ($uploadedImage) {
                $newImageUrl = $uploadedImage;
            }
        }

        // Prepare data for update
        DB::table('teachers')->where('id', $id)->update([
            'name' => $data['name'],
            'designation' => $data['designation'] ?? $teacher->designation,
            'phone' => $data['phone'] ?? $teacher->phone,
            'email' => $data['email'] ?? $teacher->email,
            'status' => $data['status'] ?? $teacher->status,
            'image' => $newImageUrl,
        ]);

        $updatedTeacher = DB::table('teachers')->where('id', $id)->first();

        return $this->sendResponse($updatedTeacher, 'Teacher updated successfully', 200);

    } catch (\Exception $e) {
        return response()->json([
            'message' => 'Failed to update teacher',
            'error' => $e->getMessage()
        ], 500);
    }
}


// Delete teacher
public function destroy($id)
{
    try {
        $teacher = DB::table('teachers')->where('id', $id)->first();

        if (!$teacher) {
            return $this->sendError('Teacher not found', [], 404);
        }
        // Delete image if exists
        StoreFileInLocal::deleteLocalFile(public_path($teacher->image));

        DB::table('teachers')->where('id', $id)->delete();
        return response()->json(['message' => 'Teacher deleted successfully']);
    } catch (\Exception $e) {
        return response()->json([
            'message' => 'Failed to delete teacher',
            'error' => $e->getMessage()
        ], 500);
    }
}
// getTeacherById
public function getTeacherById($id){
    try {
        $teacher = DB::table('teachers')->where('id', $id)->first();
        return $this->sendResponse($teacher,'Teacher', 200);
    } catch (Exception $exception) {
        return $this->sendError('Error', $exception->getMessage());
    }

}
}