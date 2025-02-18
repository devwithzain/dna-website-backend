<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\ServiceRequest;
use App\Models\Service;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\ServiceOptions;
use App\Http\Controllers\Controller;
use App\Http\Resources\ServiceResource;
use Illuminate\Support\Facades\Storage;

class ServiceController extends Controller
{
   public function index()
   {
      $services = Service::with('options')->get();

      return response()->json([
         'data' => ServiceResource::collection($services),
      ], 200);
   }
   public function store(Request $request)
   {
      // Step 1: Validate and Create Service
      $validatedService = $request->validate([
         'title' => 'required|string|max:255',
         'description' => 'required|string',
         'price' => 'required|string',
         'shipping' => 'nullable|string',
         'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
      ]);

      if ($request->hasFile('image')) {
         $imageName = Str::random(32) . '.' . $request->image->getClientOriginalExtension();
         Storage::disk('public')->put($imageName, file_get_contents($request->image));
         $validatedService['image'] = $imageName;
      }

      // Create the service
      $service = Service::create([
         'title' => $validatedService['title'],
         'description' => $validatedService['description'],
         'price' => $validatedService['price'],
         'shipping' => $validatedService['shipping'],
         'image' => $validatedService['image']
      ]);

      // Step 2: Add Options (if provided)
      if ($request->has('options')) {
         foreach ($request->options as $option) {
            $validatedOption = [
               'services_id' => $service->id,
               'title' => $option['title'],  // Assuming you're passing 'title' in each option
            ];

            ServiceOptions::create($validatedOption);
         }
      }

      // Step 3: Return Response
      return response()->json([
         'message' => 'Service created successfully!',
         'service' => $service->load('options'),
      ], 201);
   }

   public function show(string $id)
   {
      $service = Service::with('options')->find($id);

      if (!$service) {
         return response()->json([
            'error' => 'Not Found.',
         ], 404);
      }

      return response()->json([
         'data' => new ServiceResource($service),
      ], 200);
   }
   public function update(ServiceRequest $request, string $id)
   {
      try {
         $service = Service::with('options')->find($id);

         if (!$service) {
            return response()->json([
               'error' => 'Not Found.',
            ], 404);
         }

         $validatedData = $request->validated();

         if ($request->hasFile('image')) {
            if ($service->image) {
               Storage::disk('public')->delete($service->image);
            }

            $imageName = Str::random(32) . '.' . $request->image->getClientOriginalExtension();
            Storage::disk('public')->put($imageName, file_get_contents($request->image));
            $validatedData['image'] = $imageName;
         }

         $service->update([
            'title' => $validatedData['title'],
            'description' => $validatedData['description'],
            'price' => $validatedData['price'],
            'shipping' => $validatedData['shipping'],
            'image' => $validatedData['image'] ?? $service->image,
         ]);

         if ($request->has('steps')) {
            $service->options()->delete();

            foreach ($request->steps as $step) {
               ServiceOptions::create([
                  'services_id' => $service->id,
                  'title' => $step['step_title'],
               ]);
            }
         }

         return response()->json([
            'message' => 'Service successfully updated.',
            'service' => new ServiceResource($service->load('options')),
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
      $service = Service::find($id);

      if (!$service) {
         return response()->json([
            'error' => 'Not Found.',
         ], 404);
      }

      $service->delete();

      return response()->json([
         'success' => 'Service successfully deleted.',
      ], 200);
   }
}