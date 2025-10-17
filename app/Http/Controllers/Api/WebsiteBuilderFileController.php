<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class WebsiteBuilderFileController extends Controller
{
    public function uploadFile(Request $request)
    {
        // Force JSON response for all errors and responses
        $request->headers->set('Accept', 'application/json');
        
        // Check authentication manually using web session
        if (!Auth::check()) {
            Log::error('Authentication failed in upload controller');
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 401);
        }
        
        // Log that the method is being called
        // Log::info('WebsiteBuilderFileController::uploadFile called', [
        //     'has_file' => $request->hasFile('file'),
        //     'field' => $request->input('field'),
        //     'website_id' => $request->input('website_id'),
        //     'user_id' => Auth::id(),
        // ]);

        // Custom validation with JSON response
        $validator = Validator::make($request->all(), [
            'file' => 'required|file|mimes:jpeg,png,jpg,gif,webp,ico|max:5120', // Max 5MB
            'field' => 'required|string',
            'website_id' => 'required|integer',
            'old_file' => 'nullable|string'
        ]);

        if ($validator->fails()) {
            // Log::error('Validation failed', ['errors' => $validator->errors()]);
            // return response()->json([
            //     'success' => false,
            //     'message' => 'Validation failed',
            //     'errors' => $validator->errors()
            // ], 422);
        }
        
        //Log::info('Validation passed successfully');

        try {
            $file = $request->file('file');
            $field = $request->input('field');
            $websiteId = $request->input('website_id');
            $oldFile = $request->input('old_file');
            
            // Log::info('Processing file upload', [
            //     'file_name' => $file->getClientOriginalName(),
            //     'file_size' => $file->getSize(),
            //     'field' => $field,
            //     'website_id' => $websiteId
            // ]);
            
            // Delete old file if it exists and is not a base64 string
            if ($oldFile && !str_starts_with($oldFile, 'data:') && Storage::disk('public')->exists($oldFile)) {
                Storage::disk('public')->delete($oldFile);
                //Log::info('Deleted old file', ['old_file' => $oldFile]);
            }
            
            // Generate unique filename
            $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            $extension = $file->getClientOriginalExtension();
            $filename = Str::slug($originalName) . '_' . time() . '_' . Str::random(8) . '.' . $extension;
            
            // Create directory path based on website ID and field type
            $directory = "website-content/{$websiteId}/" . str_replace(['_', '-'], '/', $field);
            
            // Log::info('Storing file', [
            //     'directory' => $directory,
            //     'filename' => $filename
            // ]);
            
            // Store file in public storage
            $path = $file->storeAs($directory, $filename, 'public');
            
            // Log::info('File stored successfully', [
            //     'path' => $path,
            //     'url' => Storage::url($path)
            // ]);
            
            $response = response()->json([
                'success' => true,
                'path' => $path,
                'url' => Storage::url($path),
                'filename' => $filename
            ]);
            
            //Log::info('Returning JSON response', ['response_data' => $response->getData()]);
            
            return $response;
            
        } catch (\Exception $e) {
            // Log::error('File upload failed: ' . $e->getMessage(), [
            //     'exception' => $e->getTraceAsString()
            // ]);
            
            return response()->json([
                'success' => false,
                'message' => 'File upload failed. Please try again.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}