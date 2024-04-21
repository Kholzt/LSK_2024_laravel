<?php

namespace App\Http\Controllers;

use App\Models\AllowedDomains;
use App\Models\Forms;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class FormsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $forms = Forms::where("creator_id", auth()->user()->id)->get();
        return response()->json(["message" => "Get all form success", "forms" => $forms], 200);
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
        $validator =   Validator::make(request()->all(), [
            "name" => "required",
            "slug" => "required|unique:forms,slug|regex:/^[a-zA-Z0-9.-]+$/",
            "allowed_domains" => "array"
        ]);

        if ($validator->fails()) {
            return response()->json(["message" => "Invalid Field", "errors" => $validator->errors()], 422);
        }

        //menggabungkan data array menjadi property biasa
        $data = [
            ...request()->except("allowed_domains"),
            "creator_id" => auth()->user()->id
        ];


        $form =   Forms::create($data);
        foreach (request()->allowed_domains as $domain) {
            $idDomain = AllowedDomains::create(["domain" => $domain, "form_id" => $form->id]);
        }
        return response()->json(["message" => "Create form success"], 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $slug)
    {
        $form = Forms::where("slug", $slug)->first();

        if ($form) {
            $domains = $form->domains()->pluck("domain")->toArray();
            $questions = $form->questions;
            $emailUser = self::explodeEmail(auth()->user()->email);
            if (!in_array($emailUser, $domains)) {
                return response()->json([
                    "message" => "Forbidden access"
                ], 403);
            }
            //menghapus data domains di form
            unset($form["domains"]);
            $form["allowed_domains"] = $domains;
            $form["questions"] = $questions;
            return response()->json(["message" => "Get form success", "form" => $form], 200);
        }
        return response()->json(["message" => "Form not found"], 404);
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



    private function explodeEmail($email)
    {
        $arr = explode("@", $email);
        return $arr[1];
    }
}
