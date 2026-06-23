<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\StoreIncidentRequest;
use App\Models\Incident;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Http\Resources\IncidentResource;

class IncidentController extends Controller
{
    public function store(StoreIncidentRequest $request)
    {
        $data = $request->validated();
        $user = $request->user();

        $latitude = (float) $data['latitude'];
        $longitude = (float) $data['longitude'];

        $code = 'INC-' . Str::upper(Str::random(8));

        $incident = Incident::create([
    'code' => $code,
    'title' => $data['title'],
    'description' => $data['description'],
    'category_id' => $data['category_id'],
    'priority' => $data['priority'],
    'status' => 'NEW',

    'reporter_id' => $user->id,

    'address' => $data['address'],
    'ward' => $data['ward'] ?? null,
    'district' => $data['district'] ?? null,
    'city' => $data['city'] ?? null,

    'latitude' => $latitude,
    'longitude' => $longitude,
    'location' => DB::raw("ST_GeomFromText('POINT({$longitude} {$latitude})')"),

    'occurred_at' => $data['occurred_at'] ?? now(),
]);



        return response()->json(['message' => 'Incident created successfully', 'incident' => new IncidentResource($incident)], 201);
    }
    public function index(Request $request)
    {
        $user = $request->user();
        $querry = Incident::query()->latest();
        if($user->role->name === 'USER'){
            $querry->where('reporter_id', $user->id);
        }
        if($user->role->name === 'TECHNICIAN') {
            $querry->where('assigned_to', $user->id);
        }
        $incidents = $querry->paginate(10);
        return response()->json([
        'message' => 'Incidents retrieved successfully',
        'incidents' => IncidentResource::collection($incidents),
        'meta' => [
            'current_page' => $incidents->currentPage(),
            'last_page' => $incidents->lastPage(),
            'per_page' => $incidents->perPage(),
            'total' => $incidents->total(),
        ],
    ]);
    }

    public function show(Request $request, $id)
    {
        $user = request()->user();
        $incident = Incident::findOrFail($id);
        
        if(!$incident) {
            return response()->json(['message' => 'Incident not found'], 404);
        }
        $role = $user->role->name;
        if($role === 'USER' && $incident->reporter_id !== $user->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
    }
    
        if($role === 'TECHNICIAN' && $incident->assigned_to !== $user->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        return response()->json(['message' => 'Incident retrieved successfully', 'incident' => new IncidentResource($incident)], 200);
    }
}
