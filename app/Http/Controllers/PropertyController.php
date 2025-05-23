<?php

namespace App\Http\Controllers;

use App\Models\Property;
use App\Models\PropertyLocation;
use App\Models\PropertyType;
use App\Models\PropertyStatus;
use App\Models\Location;
use App\Models\Type;
use App\Models\Status;
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

        // Validate the request parameters
        $validated = $request->validate([
            'id' => 'nullable|integer|min:1',
            'keyword' => 'nullable|string|max:255',
            'featured' => 'nullable|boolean',
            'location_id' => 'nullable|integer|exists:locations,id',
            'type_id' => 'nullable|integer|exists:types,id',
            'status_id' => 'nullable|integer|exists:statuses,id',
        ]);

        $query = Property::query();

        if ($request->id) {
            $query->where('id', $request->id);
        }

        // 🔎 Search by keyword (example: search in title or description)
        if ($request->keyword) {
            $query->where(function ($q) use ($request) {
                $q->where('title', 'like', "%{$request->keyword}%")
                  ->orWhere('description', 'like', "%{$request->keyword}%");
            });
        }

        if ($request->featured) {
            $query->where('featured', filter_var($request->featured, FILTER_VALIDATE_BOOLEAN));
        }

        if($request->location_id){
            $query->whereIn('id', function($q) use ($request){
                $q->select('property_id')
                    ->from('property_location')
                    ->where('location_id', $request->location_id);
            })->with(['location' => function($q) {
                $q->select('location'); // Assuming 'name' is the column with the location name
            }]);
        }

        if($request->type_id){
            $query->whereIn('id', function($q) use ($request){
                $q->select('property_id')
                    ->from('property_type')
                    ->where('type_id', $request->type_id);
            })->with(['type' => function($q) {
                $q->select('type'); // Assuming 'name' is the column with the location name
            }]);
        }

        if($request->status_id){
            $query->whereIn('id', function($q) use ($request){
                $q->select('property_id')
                    ->from('property_status')
                    ->where('status_id', $request->status_id);
            })->with(['status' => function($q) {
                $q->select('status'); // Assuming 'name' is the column with the location name
            }]);
        }

        $properties = $query
        // Include names in the result (no eager loading needed)
        ->addSelect([
            // Location name
            'location' => Location::select('location')
                ->join('property_location', 'locations.id', '=', 'property_location.location_id')
                ->whereColumn('property_location.property_id', 'properties.id')
                ->limit(1),
            // Type name
            'type' => Type::select('type')
                ->join('property_type', 'types.id', '=', 'property_type.type_id')
                ->whereColumn('property_type.property_id', 'properties.id')
                ->limit(1),
            // Status name
            'status' => Status::select('status')
                ->join('property_status', 'statuses.id', '=', 'property_status.status_id')
                ->whereColumn('property_status.property_id', 'properties.id')
                ->limit(1),
        ])
        ->orderBy('created_at', 'desc')
        ->get();

        return response()->json([
            'data' => $properties,
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
            'images.*' => 'image|mimes:jpeg,png,jpg,gif,webp|max:11048',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'space' => 'required|numeric|min:0',
            'bedrooms' => 'required|integer|min:0',
            'bathrooms' => 'required|integer|min:0',
            'salons' => 'required|integer|min:0',
            'kitchens' => 'required|integer|min:0',
            'terraces' => 'boolean',
            'terraces_count' => 'nullable|integer|required_if:terraces,true|min:0',
            'floors' => 'required|integer|min:1',
            'living_rooms' => 'required|integer|min:0',
            'swimming_pools' => 'boolean',
            'swimming_pools_count' => 'nullable|integer|required_if:swimming_pools,true|min:0',
            'parking' => 'boolean',
            'parking_count' => 'nullable|integer|required_if:parking,true|min:0',
            'garden' => 'boolean',
            'garden_count' => 'nullable|integer|required_if:garden,true|min:0',
            'condition' => 'required|string',
            'link' => 'nullable|url',
            'featured' => 'boolean',
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
            'link' => $validated['link'] ?? null,
            'featured' => $validated['featured'] ?? false,
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

                Location::where('id', $validated['location_id'])
                    ->increment('count');

                DB::table('property_type')->insert([
                    'property_id' => $property->id,
                    'type_id' => $validated['type_id'],
                    'created_at' => now(),
                    'updated_at' => now()
                ]);

                Type::where('id', $validated['type_id'])
                    ->increment('count');

                DB::table('property_status')->insert([
                    'property_id' => $property->id,
                    'status_id' => $validated['status_id'],
                    'created_at' => now(),
                    'updated_at' => now()
                ]);

                Status::where('id', $validated['status_id'])
                    ->increment('count');

                return $property;
            });

            // Eager load with nested relationships (CHANGED)
            $property->load([
                'location',
                'type',
                'status'
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
        // Load the property with additional computed fields
        $propertyWithDetails = Property::where('id', $property->id)
            ->addSelect([
                // Location name
                'location' => Location::select('location')
                    ->join('property_location', 'locations.id', '=', 'property_location.location_id')
                    ->whereColumn('property_location.property_id', 'properties.id')
                    ->limit(1),

                // Type name
                'type' => Type::select('type')
                    ->join('property_type', 'types.id', '=', 'property_type.type_id')
                    ->whereColumn('property_type.property_id', 'properties.id')
                    ->limit(1),

                // Status name
                'status' => Status::select('status')
                    ->join('property_status', 'statuses.id', '=', 'property_status.status_id')
                    ->whereColumn('property_status.property_id', 'properties.id')
                    ->limit(1),
            ])
            ->first(); // Get the single property with added fields

        return response()->json($propertyWithDetails, 200);
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
            'terraces'  => 'boolean|',
            'terraces_count'    => 'nullable|integer|required_if:terraces,true|min:0',
            'floors'   => 'required|integer',
            'living_rooms' => 'required|integer',
            'swimming_pools' => 'boolean',
            'swimming_pools_count' => 'nullable|integer|required_if:swimming_pools,true|min:0',
            'parking' => 'boolean',
            'parking_count' => 'nullable|integer|required_if:parking,true|min:0',
            'garden' => 'boolean',
            'garden_count' => 'nullable|integer|required_if:garden,true|min:0',
            'condition' => 'required|string',
            'link' => 'nullable|url',
            'featured' => 'boolean',
            'type_id' => 'sometimes|exists:types,id',
            'location_id' => 'sometimes|exists:locations,id',
            'status_id' => 'sometimes|exists:statuses,id',
        ]);

        try {
            $imagePaths = json_decode($property->images, true) ?? [];
            DB::transaction(function () use ($request, $validated, $property, &$imagePaths) {
                // Handle image updates if present

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
                    'link' => $validated['link'] ?? null,
                    'featured' => $validated['featured'] ?? false,
                ];

                if ($request->hasFile('images')) {
                    $propertyData['images'] = json_encode($imagePaths);
                }

                // Update property
                $property->update($propertyData);

                // Update pivot relationships if provided
                if (isset($validated['type_id'])) {
                    $propertyType = PropertyType::where('property_id', $property->id)->first();

                    // Decrement counts in related tables
                    if ($propertyType) {
                        Type::where('id', $propertyType->type_id)
                            ->decrement('count');
                    }

                    PropertyType::updateOrCreate(
                        ['property_id' => $property->id],
                        ['type_id' => $validated['type_id']]
                    );

                    Type::where('id', $validated['type_id'])
                        ->increment('count');
                }
                if (isset($validated['location_id'])) {
                    $propertyLocation = PropertyLocation::where('property_id', $property->id)->first();
                    // Decrement counts in related tables
                    if ($propertyLocation) {
                        Location::where('id', $propertyLocation->location_id)
                            ->decrement('count');
                    }

                    PropertyLocation::updateOrCreate(
                        ['property_id' => $property->id],
                        ['location_id' => $validated['location_id']]
                    );

                    Location::where('id', $validated['location_id'])
                        ->increment('count');
                }
                if (isset($validated['status_id'])) {
                    $propertyStatus = PropertyStatus::where('property_id', $property->id)->first();
                    // Decrement counts in related tables
                    if ($propertyStatus) {
                        Status::where('id', $propertyStatus->status_id)
                            ->decrement('count');
                    }

                    PropertyStatus::updateOrCreate(
                        ['property_id' => $property->id],
                        ['status_id' => $validated['status_id']]
                    );

                    Status::where('id', $validated['status_id'])
                        ->increment('count');
                }
            });

            $property->load([
                'location',
                'type',
                'status'
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
                $response['image_urls'] = array_map(fn($path) => asset("storage/$path"), $imagePaths);
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

                $propertyLocation = PropertyLocation::where('property_id', $property->id)->first();
                $propertyType = PropertyType::where('property_id', $property->id)->first();
                $propertyStatus = PropertyStatus::where('property_id', $property->id)->first();

                // Decrement counts in related tables
                if ($propertyLocation) {
                    Location::where('id', $propertyLocation->location_id)
                        ->decrement('count');
                }
                if ($propertyType) {
                    Type::where('id', $propertyType->type_id)
                        ->decrement('count');
                }
                if ($propertyStatus) {
                    Status::where('id', $propertyStatus->status_id)
                        ->decrement('count');
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
            ], 401);
        }
    }
}
