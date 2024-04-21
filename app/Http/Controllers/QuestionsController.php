<?php

namespace App\Http\Controllers;

use App\Models\Forms;
use App\Models\Questions;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class QuestionsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($slug)
    {
        $form = Forms::where("slug", $slug)->first();
        $question = Questions::where("form_id", $form->id)->get();
        return response()->json(["message" => "Get all question", "question" => $question], 200);
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
    public function store(String $slug)
    {
        $validator = Validator::make(request()->all(), [
            "name" => "required",
            'choice_type' => 'required|in:short_answer,paragraph,date,multiple_choice,dropdown,checkboxes',
            'choices' => 'required_if:choice_type,multiple_choice,dropdown,checkboxes|array',
            'choices.*' => 'string',
        ]);

        if ($validator->fails()) {
            return response()->json(["message" => "Invalid Field", "errors" => $validator->errors()], 422);
        }

        $form = Forms::where("slug", $slug)->first();
        if ($form) {
            if ($form->creator_id != auth()->user()->id) {
                return response()->json(["message" => "Forbidden access"], 403);
            }

            $data = [
                ...request()->except("choices"),
                "choices" => join(",", request()->choices),
                "form_id" => $form->id,
            ];
            // return response()->json($data);
            $question = Questions::create($data);
            return response()->json(["message" => "Add question success", "question" => $question], 200);
        }
        return response()->json(["message" => "Form not found"], 404);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $slug, string $questionId)
    {
        $form = Forms::where("slug", $slug)->first();
        $question = Questions::find($questionId);

        if (!$form) {
            return response()->json(["message" => "Form not found"], 404);
        } else if (!$question) {
            return response()->json(["message" => "Question not found"], 404);
        } else if ($form->creator_id != auth()->user()->id) {
            return response()->json(["message" => "Forbidden access"], 403);
        }

        $question->delete();
        return response()->json(["message" => "Remove question success"], 200);
    }
}
