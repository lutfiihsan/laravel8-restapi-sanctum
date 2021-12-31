<?php

namespace App\Http\Controllers\API;

use App\Models\Program;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\ProgramResource;
use Illuminate\Support\Facades\Validator;

class ProgramController extends Controller
{

    public function index()
    {
        $data = Program::latest()->get();
        return response()->json([
            ProgramResource::collection($data), "Program fetched."
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'name' => 'required|string|max:255',
            'desc' => 'required'
        ]);

        if($validator->fails()){
            return response()->json($validator->errors());
        }

        $program = Program::create([
            'name' => $request->name,
            'desc' => $request->desc
        ]);

        return response()->json([
            'Program created successfully.', new ProgramResource($program)
        ]);
    }

    public function show($id)
    {
        $program = Program::find($id);
        if(is_null($program)){
            return response()->json([
                'Program not found', 404
            ]);
        }

        return response()->json([
            new ProgramResource($program)
        ]);
    }

    public function update(Request $request, Program $program)
    {
        $validator = Validator::make($request->all(),[
            'name' => 'required||string|max:255',
            'desc' => 'required'
        ]);

        if($validator->fails()){
            return response()->json(
                $validator->errors()
            );
        }

        $program->name = $request->name;
        $program->desc = $request->desc;
        $program->save();

        return response()->json([
            'Program updated successfully.', new ProgramResource($program)
        ]);
    }

    public function destroy(Program $program)
    {
        $program->delete();

        return response()->json('Program deleted successfully.');
    }

}
