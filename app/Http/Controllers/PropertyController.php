<?php

namespace App\Http\Controllers;

use App\Models\Property;
use App\Models\PropertyLocation;
use App\Models\PropertyType;
use App\Models\PropertyStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class PropertyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Property::query();

        if ($request->id) {
            $query->where('id', $request->id);
        }

        // 🔎 Search by keyword (example: search in title or description)
        if ($request->keyword) {
            $query->where(function ($q) use ($request) {
                $q->where('title', 'ILIKE', "%{$request->keyword}%")
                  ->orWhere('description', 'ILIKE', "%{$request->keyword}%");
            });
        }

        if($request->location_id){
            $query->whereIn('id', function($q) use ($request){
                $q->select('property_id')
                    ->from('property_location')
                    ->where('location_id', $request->location_id);
            });
        }

        if($request->type_id){
            $query->whereIn('id', function($q) use ($request){
                $q->select('property_id')
                    ->from('property_type')
                    ->where('type_id', $request->type_id);
            });
        }

        if($request->status_id){
            $query->whereIn('id', function($q) use ($request){
                $q->select('property_id')
                    ->from('property_status')
                    ->where('status_id', $request->status_id);
            });
        }

        $properties = $query->with(['location', 'type', 'status'])
            ->orderBy('created_at', 'desc')
            ->paginate($request->per_page ?? 10);

            return response()->json([
                'data' => $properties->items(),
                'meta' => [
                    'current_page' => $properties->currentPage(),
                    'per_page' => $properties->perPage(),
                    'total' => $properties->total(),
                ],
            ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'images' => 'required|array',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'space' => 'required|numeric|min:0',
            'bedrooms' => 'required|integer|min:0',
            'bathrooms' => 'required|integer|min:0',
            'salons' => 'required|integer|min:0',
            'kitchens' => 'required|integer|min:0',
            'terraces' => 'required|in:true,false,1,0',
            'terraces_count' => 'nullable|integer|required_if:terraces,true|min:0',
            'floors' => 'required|integer|min:1',
            'living_rooms' => 'required|integer|min:0',
            'swimming_pools' => 'required|in:true,false,1,0',
            'swimming_pools_count' => 'nullable|integer|required_if:swimming_pools,true|min:0',
            'parking' => 'required|in:true,false,1,0',
            'parking_count' => 'nullable|integer|required_if:parking,true|min:0',
            'garden' => 'required|in:true,false,1,0',
            'garden_count' => 'nullable|integer|required_if:garden,true|min:0',
            'condition' => 'required|string|in:new,used,renovated',
            'type_id' => 'required|exists:types,id',
            'location_id' => 'required|exists:locations,id',
            'status_id' => 'required|exists:statuses,id',
        ]);

        // Handle conditional fields
        $propertyData = [
            'title' => $validated['title'],
            'description' => $validated['description'],
            'price' => $validated['price'],
            'space' => $validated['space'],
            'bedrooms' => $validated['bedrooms'],
            'bathrooms' => $validated['bathrooms'],
            'salons' => $validated['salons'],
            'kitchens' => $validated['kitchens'],
            'terraces_enabled' => $validated['terraces'],
            'terraces_count' => $validated['terraces'] ? $validated['terraces_count'] : null,
            'floors' => $validated['floors'],
            'living_rooms' => $validated['living_rooms'],
            'swimming_pools_enabled' => $validated['swimming_pools'],
            'swimming_pools_count' => $validated['swimming_pools'] ? $validated['swimming_pools_count'] : null,
            'parking_enabled' => $validated['parking'],
            'parking_count' => $validated['parking'] ? $validated['parking_count'] : null,
            'garden_enabled' => $validated['garden'],
            'garden_count' => $validated['garden'] ? $validated['garden_count'] : null,
            'condition' => $validated['condition'],
        ];

        // Handle image uploads
        $imagePaths = [];
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('properties/images', 'public');
                $imagePaths[] = $path;
            }
        }

        try {
            $property = DB::transaction(function () use ($propertyData, $imagePaths, $validated) {
                // Create property (same)
                $property = Property::create(array_merge($propertyData, [
                    'images' => json_encode($imagePaths),
                ]));

                DB::table('property_location')->insert([
                    'property_id' => $property->id,
                    'location_id' => $validated['location_id'],
                    'created_at' => now(),
                    'updated_at' => now()
                ]);

                DB::table('property_type')->insert([
                    'property_id' => $property->id,
                    'type_id' => $validated['type_id'],
                    'created_at' => now(),
                    'updated_at' => now()
                ]);

                DB::table('property_status')->insert([
                    'property_id' => $property->id,
                    'status_id' => $validated['status_id'],
                    'created_at' => now(),
                    'updated_at' => now()
                ]);

                return $property;
            });

            // Eager load with nested relationships (CHANGED)
            $property->load([
                'location.location',
                'type.type',
                'status.status'
            ]);

            return response()->json([
                'message' => 'Property created successfully',
                'property' => [
                    'data' => $property,
                    'location' => $property->location->location,
                    'type' => $property->type->type,
                    'status' => $property->status->status
                ],
                'image_urls' => array_map(fn($path) => asset("storage/$path"), $imagePaths)
            ], 201);


        } catch (\Exception $e) {
            Log::error('Property creation failed: ' . $e->getMessage());
            foreach ($imagePaths as $path) {
                Storage::disk('public')->delete($path);
            }

            return response()->json([
                'error' => 'Property creation failed',
                'details' => env('APP_DEBUG') ? $e->getMessage() : null
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Property $property)
    {
        return response()->json($property,201);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Property $property)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Property $property)
    {
        $validated = $request->validate([
            'title'=>'required|string|max:255',
            'images' => 'sometimes|array',
            'description' => 'required|string',
            'price' => 'required|numeric',
            'space' => 'required|numeric',
            'bedrooms'  => 'required|integer',
            'bathrooms' => 'required|integer',
            'salons'    => 'required|integer',
            'kitchens'  => 'required|integer',
            'terraces'  => 'boolean',
            'terraces_count'    => 'nullable|integer',
            'floors'   => 'required|integer',
            'living_rooms' => 'required|integer',
            'swimming_pools' => 'boolean',
            'swimming_pools_count' => 'nullable|integer',
            'parking' => 'boolean',
            'parking_count' => 'nullable|integer',
            'garden' => 'boolean',
            'garden_count' => 'nullable|integer',
            'condition' => 'required|string',
            'type_id' => 'sometimes|exists:types,id',
            'location_id' => 'sometimes|exists:locations,id',
            'status_id' => 'sometimes|exists:statuses,id',
        ]);

        try {
            DB::transaction(function () use ($request, $validated, $property) {
                // Handle image updates if present
                $imagePaths = $property->images ?? [];

                if ($request->hasFile('images')) {
                    // Delete old images
                    foreach ($imagePaths as $path) {
                        Storage::disk('public')->delete($path);
                    }

                    // Store new images
                    $imagePaths = [];
                    foreach ($request->file('images') as $image) {
                        $path = $image->store('properties/images', 'public');
                        $imagePaths[] = $path;
                    }

                    $propertyData = [
                        'title' => $validated['title'],
                        'description' => $validated['description'],
                        'price' => $validated['price'],
                        'space' => $validated['space'],
                        'bedrooms' => $validated['bedrooms'],
                        'bathrooms' => $validated['bathrooms'],
                        'salons' => $validated['salons'],
                        'kitchens' => $validated['kitchens'],
                        'terraces_enabled' => $validated['terraces'],
                        'terraces_count' => $validated['terraces'] ? $validated['terraces_count'] : null,
                        'floors' => $validated['floors'],
                        'living_rooms' => $validated['living_rooms'],
                        'swimming_pools_enabled' => $validated['swimming_pools'],
                        'swimming_pools_count' => $validated['swimming_pools'] ? $validated['swimming_pools_count'] : null,
                        'parking_enabled' => $validated['parking'],
                        'parking_count' => $validated['parking'] ? $validated['parking_count'] : null,
                        'garden_enabled' => $validated['garden'],
                        'garden_count' => $validated['garden'] ? $validated['garden_count'] : null,
                        'condition' => $validated['condition'],
                        'images' => json_encode($imagePaths),
                    ];


                }

                // Prepare property data
                $propertyData = [
                    'title' => $validated['title'],
                    'description' => $validated['description'],
                    'price' => $validated['price'],
                    'space' => $validated['space'],
                    'bedrooms' => $validated['bedrooms'],
                    'bathrooms' => $validated['bathrooms'],
                    'salons' => $validated['salons'],
                    'kitchens' => $validated['kitchens'],
                    'terraces_enabled' => $validated['terraces'],
                    'terraces_count' => $validated['terraces'] ? $validated['terraces_count'] : null,
                    'floors' => $validated['floors'],
                    'living_rooms' => $validated['living_rooms'],
                    'swimming_pools_enabled' => $validated['swimming_pools'],
                    'swimming_pools_count' => $validated['swimming_pools'] ? $validated['swimming_pools_count'] : null,
                    'parking_enabled' => $validated['parking'],
                    'parking_count' => $validated['parking'] ? $validated['parking_count'] : null,
                    'garden_enabled' => $validated['garden'],
                    'garden_count' => $validated['garden'] ? $validated['garden_count'] : null,
                    'condition' => $validated['condition'],
                ];

                // Update property
                $property->update($propertyData);

                // Update pivot relationships if provided
                if (isset($validated['type_id'])) {
                    PropertyType::updateOrCreate(
                        ['property_id' => $property->id],
                        ['type_id' => $validated['type_id']]
                    );
                }
                if (isset($validated['location_id'])) {
                    PropertyLocation::updateOrCreate(
                        ['property_id' => $property->id],
                        ['location_id' => $validated['location_id']]
                    );
                }
                if (isset($validated['status_id'])) {
                    PropertyStatus::updateOrCreate(
                        ['property_id' => $property->id],
                        ['status_id' => $validated['status_id']]
                    );
                }
            });

            $property->load([
                'location.location',
                'type.type',
                'status.status'
            ]);

            $response = [
                'message' => 'Property updated successfully',
                'property' => [
                    'data' => $property,
                    'location' => $property->location->location,
                    'type' => $property->type->type,
                    'status' => $property->status->status,
                ],
            ];

            if ($request->hasFile('images')) {
                $response['image_urls'] = array_map(fn($path) => asset("storage/$path"), $property->images);
            }

            return response()->json($response, 200);


        } catch (\Exception $e) {
            Log::error('Property update failed: ' . $e->getMessage());
            return response()->json([
                'error' => 'Property update failed',
                'details' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Property $property)
    {
        try {
            DB::transaction(function () use ($property) {
                // Delete all associated images from storage first
                if ($property->images && is_array($property->images)) {
                    foreach ($property->images as $imagePath) {
                        Storage::disk('public')->delete($imagePath);
                    }
                }

                // Detach relationships from pivot tables
                PropertyLocation::where('property_id', $property->id)->delete();
                PropertyType::where('property_id', $property->id)->delete();
                PropertyStatus::where('property_id', $property->id)->delete();

                // Finally delete the property
                $property->delete();
            });

            return response()->json([
                'message' => 'Property and all associated data deleted successfully'
            ], 200);

        } catch (\Exception $e) {
            Log::error('Property deletion failed: ' . $e->getMessage());

            return response()->json([
                'error' => 'Property deletion failed',
                'message' => 'Please try again later',
                'details' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }
}
