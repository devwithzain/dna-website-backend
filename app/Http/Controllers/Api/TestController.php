<?php

namespace App\Http\Controllers\Api;

use App\Models\Test;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class TestController extends Controller
{
   public function index()
   {
      $tests = Test::all();
      return response()->json([
         'data' => $tests,
      ], 200);
   }
   public function store(Request $request)
   {
      $validatedService = $request->validate([
         'title' => 'required|string|max:255',
         'short_description' => 'required|string',
         'description' => 'required|string',
         'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:10240',
      ]);

      if ($request->hasFile('image')) {
         $imageName = Str::random(32) . '.' . $request->image->getClientOriginalExtension();
         Storage::disk('public')->put($imageName, file_get_contents($request->image));
         $validatedService['image'] = $imageName;
      }

      $test = Test::create([
         'title' => $validatedService['title'],
         'short_description' => $validatedService['short_description'],
         'description' => $validatedService['description'],
         'image' => $validatedService['image']
      ]);

      return response()->json([
         'message' => 'Test created successfully!',
         'test' => $test,
      ], 201);
   }
   public function show(string $id)
   {
      $test = Test::find($id);

      if (!$test) {
         return response()->json([
            'error' => 'Not Found.',
         ], 404);
      }

      return response()->json([
         'data' => $test,
      ], 200);
   }
   public function update(Request $request, string $id)
   {
      try {
         $test = Test::find($id);

         if (!$test) {
            return response()->json([
               'error' => 'Not Found.',
            ], 404);
         }

         // Validate the request data
         $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'short_description' => 'required|string',
            'description' => 'required|string',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:10240',
         ]);

         // Handle image upload
         if ($request->hasFile('image')) {
            // Delete the old image if it exists
            if ($test->image) {
               Storage::disk('public')->delete($test->image);
            }

            // Upload the new image
            $imageName = Str::random(32) . '.' . $request->image->getClientOriginalExtension();
            Storage::disk('public')->put($imageName, file_get_contents($request->image));
            $validatedData['image'] = $imageName;
         }

         // Update the test model with the validated data
         $test->update($validatedData);

         return response()->json([
            'message' => 'Test successfully updated.',
            'test' => $validatedData,
         ], 200);
      } catch (\Exception $e) {
         return response()->json([
            'error' => 'Something went wrong!',
            'message' => $e->getMessage(),
         ], 500);
      }
   }
   public function destroy(string $id)
   {
      $test = Test::find($id);

      if (!$test) {
         return response()->json([
            'error' => 'Not Found.',
         ], 404);
      }

      $test->delete();

      return response()->json([
         'success' => 'Test successfully deleted.',
      ], 200);
   }
}