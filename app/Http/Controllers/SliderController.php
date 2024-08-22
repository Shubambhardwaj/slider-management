<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class SliderController extends Controller
{
    // Display the slider management page with existing images
    public function index()
    {
        $images = \DB::table('images')->get();
        return view('manage-slider', compact('images'));
    }
    

    public function searchImages(Request $request)
    {
        // Retrieve the search keyword from the request
        $keyword = $request->input('keyword', 'nature'); // Default to 'nature' if no keyword is provided

        // Make the API call to Shutterstock
        $response = Http::withBasicAuth('f8u5yYc5DoGIrUqPA228n09qVFnQ4Fqn', 'ReEBAgaupayNDCuW')
            ->get('https://api.shutterstock.com/v2/images/search', [
                'query' => $keyword,
                'per_page' => 5, // Number of images to retrieve
            ]);

        // Check if the API call was successful
        if ($response->successful()) {
            $data = $response->json();
            return response()->json(['images' => $data['data']]);
        } else {
            return response()->json(['error' => 'Failed to retrieve images.'], 500);
        }
    }

    // Save selected images to the database
    // public function saveImages(Request $request)
    // {
    //      $images = $request->input('images');
        
    //     foreach ($images as $image) {
    //         \DB::table('images')->insert([
    //             'image_url' => $image,
    //             'created_at' => now(),
    //             'updated_at' => now(),
    //         ]);
    //     }

    //     return response()->json(['success' => 'Images saved successfully.']);
    // }

    public function saveImages(Request $request)
    {
        // Validate the request to ensure 'images' is an array of URLs
        $validatedData = $request->validate([
            'images' => 'required|array',
            'images.*' => 'required|url', // Validate each image as a URL
        ]);
    
        // Retrieve the images array from the request
        $images = $validatedData['images'];
    
        try {
            // Begin a transaction to ensure all or none of the images are saved
            \DB::beginTransaction();
    
            foreach ($images as $image) {
                \DB::table('images')->insert([
                    'image_url' => $image,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
    
            // Commit the transaction
            \DB::commit();
    
            return response()->json(['success' => 'Images saved successfully.']);
        } catch (\Exception $e) {
            // Rollback the transaction if something went wrong
            \DB::rollBack();
    
            // Log the error for debugging
            \Log::error('Failed to save images: ' . $e->getMessage());
    
            // Return a detailed error response
            return response()->json(['error' => 'Failed to save images. Please try again later.'], 500);
        }
    }
    


    // Delete an image from the database
    public function deleteImage($id)
    {
        \DB::table('images')->where('id', $id)->delete();
        return response()->json(['success' => 'Image deleted successfully.']);
    }
}
