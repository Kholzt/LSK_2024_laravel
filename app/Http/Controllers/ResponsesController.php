<?php

namespace App\Http\Controllers;

use App\Models\Answers;
use App\Models\Forms;
use App\Models\Questions;
use App\Models\Responses;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ResponsesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($slug)
    {
        $form = Forms::where("slug", $slug)->first();
        if (!$form) {
            return response()->json(["message" => "Form not found"], 404);
        } else if ($form->creator_id != auth()->user()->id) {
            return response()->json(["message" => "Forbidden access"], 403);
        }
        $response = Responses::where("form_id", $form->id)->get();
        $tmpData = [];
        foreach ($response as $res) {
            $questionList = Questions::where("form_id", $form->id)->get();
            $answers = [];
            foreach ($questionList as $question) {
                $answer =   Answers::where("question_id", $question->id)->where("response_id", $res->id)->first();
                $answers = [...$answers, $question->name => $answer ? $answer->value : null];
            }
            $tmpData[] = [
                "date" => $res->date,
                "user" => $res->user()->select("id", "name", "email", "email_verified_at")->get(),
                "answer" => $answers,
            ];
        }
        return response()->json(["message" => "Get responses success", "responses" => $tmpData], 200);
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
    public function store(string $slug)
    {

        $form = Forms::where("slug", $slug)->first();

        //validasi answer
        foreach (request()->answers as $answer) {
            $question = Questions::find($answer["question_id"]);
            if ($question && $question->is_required && $answer["value"] == null) {
                return response()->json([
                    "message" => "Invalid Field",
                    "errors" => ["answers" => ["The answers field is required."]]
                ], 422);
            }
        }
        if (!$form) {
            return response()->json(["message" => "Form not found"], 404);
        } else if ($form->creator_id != auth()->user()->id) {
            return response()->json(["message" => "Forbidden access"], 403);
        } else if ($form->limit_one_response) {
            $response =   Responses::where("form_id", $form->id)->where("user_id", auth()->user()->id)->count();
            if ($response > 0) {
                return response()->json(["message" => "You can not submit form twice"], 422);
            }
        }

        $responseId =     Responses::create(["form_id" => $form->id, "user_id" => auth()->user()->id, "date" => date("Y-m-d H:i")])->id;
        foreach (request()->answers as $answer) {
            if ($answer["value"]) {
                Answers::create([
                    "response_id" => $responseId,
                    "question_id" => $answer["question_id"],
                    "value" => $answer["value"],

                ]);
            }
        }
        return response()->json(["message" => "Submit response success"], 422);
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
        //
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
    public function destroy(string $id)
    {
        //
    }
}
