<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Cliente;
use App\Models\FormaPago;
use App\Models\Ticket;
use App\Services\Scraper;
use App\Services\TicketService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class TicketController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
    }
    public function validateLogin(Request $request)
    {
        return $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);
    }
    /**
     * @param Request $request
     * @return mixed
     * @OA\Post(
     * path="/api/login",
     * tags={"Authentication"},
     * @OA\RequestBody(
     * @OA\MediaType(
     * mediaType="application/json",
     * @OA\Schema(
     * @OA\Property(
     * property="email",
     * type="string"
     * ),
     * @OA\Property(
     * property="password",
     * type="string"
     * ),
     * example={ "email": "user@mail.com" , "password": "12345"}
     * )
     * )
     * ),
     * @OA\Response(response="200", description="Usuario logueado correctamente", @OA\JsonContent(type="object")),
     * @OA\Response(response="201", description="OK", @OA\JsonContent(type="object")),
     * @OA\Response(response="422", description="Unprocessable Content", @OA\JsonContent(type="object")),
     * @OA\Response(response="401", description="Unauthorized", @OA\JsonContent(type="object"))
     * )
     */
    public function login(Request $request)
    {
        $this->validateLogin($request);
        if (!Auth::attempt($request->only('email', 'password'))) {
            return response()->json([
                'message' => 'Usuario o contraseña incorrecta',
                'token' => ""
            ], 401);
        }
        return response()->json([
            'token' => $request->user()->createToken(Str::upper(Str::random(10)))->plainTextToken,
            'message' => 'Success'
        ]);
    }
    /**
     * @param Request $request
     * @return mixed
     * @OA\Post(
     * path="/api/readCsf",
     * tags={"Constancia"},
     *    security={{"bearerAuth":{}}},
     * @OA\SecurityScheme(
     *      securityScheme="bearerAuth",
     *      in="header",
     *      name="bearerAuth",
     *      type="http",
     *      scheme="bearer",
     *      bearerFormat="JWT",
     * ),
     * @OA\RequestBody(
     * @OA\MediaType(
     * mediaType="multipart/form-data",
     * @OA\Schema(
     * @OA\Property(
     * property="constancia",
     * type="string",
     * format="binary"
     * ),
     * example={ "constancia": "file" }
     * )
     * )
     * ),
     * @OA\Response(response="200", description="OK", @OA\JsonContent(type="object")),
     * @OA\Response(response="201", description="OK", @OA\JsonContent(type="object")),
     * @OA\Response(response="422", description="Unprocessable Content", @OA\JsonContent(type="object")),
     * @OA\Response(response="401", description="Unauthorized", @OA\JsonContent(type="object")),

     * )
     */
    public function readCsf(Request $request)
    {
        if ($request->hasFile('constancia')) {
            $file = $request->file('constancia');
            $extension = $file->getClientOriginalExtension();
            $fileName = time() . "." . $extension;
            $uploaded = $file->move(storage_path() . "/constancias", $fileName);
            $scraper = Scraper::create();
            $person = $scraper->obtainFromPdfPath($uploaded);
            return response()->json(['data' => $person], 201);
        }
    }
    /**
     * @param Request $request
     * @return mixed
     * @OA\Post(
     * path="/api/sendticket",
     * tags={"Tickets"},
     *    security={{"bearerAuth":{}}},
     * @OA\SecurityScheme(
     *      securityScheme="bearerAuth",
     *      in="header",
     *      name="bearerAuth",
     *      type="http",
     *      scheme="bearer",
     *      bearerFormat="JWT",
     * ),
     * @OA\RequestBody(
     * @OA\MediaType(
     * mediaType="application/json",
     * @OA\Schema(
     * @OA\Property(
     * property="codigo",
     * type="string"
     * ),
     * example={ "codigo": "1234567890" }
     * )
     * )
     * ),
     * @OA\Response(response="200", description="OK", @OA\JsonContent(type="object")),
     * @OA\Response(response="201", description="OK", @OA\JsonContent(type="object")),
     * @OA\Response(response="422", description="Unprocessable Content", @OA\JsonContent(type="object")),
     * @OA\Response(response="401", description="Unauthorized", @OA\JsonContent(type="object")),

     * )
     */
    public function send(Request $request, TicketService $ticketService)
    {
        $rules = [
            'codigo' => 'required|string|min:10|max:10'
        ];
        try {

            $this->validate($request, $rules);
        } catch (\Illuminate\Validation\ValidationException $th) {
            return response()->json(['errors' => implode(",", $th->validator->getMessageBag()->all())], 201);
        }
        $registro = Ticket::where("codigo", $request->codigo)->first();
        if ($registro == null) {
            return response()->json(['errors' => "No se encuentra el codigo {$request->codigo}"], 201);
        }
        $ticketService->send($registro);
        return response()->json($registro, 201);
    }
    /**
     * Store a newly created resource in storage.
     */
    /**
     * @param Request $request
     * @return mixed
     * @OA\Post(
     * path="/api/ticket",
     * tags={"Tickets"},
     *    security={{"bearerAuth":{}}},
     * @OA\SecurityScheme(
     *      securityScheme="bearerAuth",
     *      in="header",
     *      name="bearerAuth",
     *      type="http",
     *      scheme="bearer",
     *      bearerFormat="JWT",
     * ),
     * @OA\RequestBody(
     * @OA\MediaType(
     * mediaType="application/json",
     * @OA\Schema(
     * @OA\Property(
     * property="forma_pago",
     * type="string"
     * ),
     * @OA\Property(
     * property="tipo",
     * type="string"
     * ),
     * @OA\Property(
     * property="contacto",
     * type="string"
     * ),
     * @OA\Property(
     * property="referencia",
     * type="string"
     * ),
     * @OA\Property(
     * property="cantidad",
     * type="string"
     * ),
     * example={ "forma_pago": "Efectivo","tipo": "Whatsapp","contacto": "3141335870","referencia": "referencia","cantidad": "50.00" }
     * )
     * )
     * ),
     * @OA\Response(response="200", description="OK", @OA\JsonContent(type="object")),
     * @OA\Response(response="201", description="OK", @OA\JsonContent(type="object")),
     * @OA\Response(response="422", description="Unprocessable Content", @OA\JsonContent(type="object")),
     * @OA\Response(response="401", description="Unauthorized", @OA\JsonContent(type="object")),

     * )
     */
    public function store(Request $request, TicketService $ticketService)
    {
        $rules = [
            'forma_pago' => 'required',
            'tipo' => 'required|string|max:15',
            'contacto' => 'required',
            'referencia' => 'string',
            'cantidad' => 'required|numeric',
        ];
        try {
            $this->validate($request, $rules);
        } catch (\Illuminate\Validation\ValidationException $th) {
            return response()->json(['errors' => implode(",", $th->validator->getMessageBag()->all())], 201);
        }
        switch ($request->tipo) {
            case 'Email':
                $rules['contacto'] = $rules['contacto'] . '|email';
                break;
            case 'SMS' || 'Whatsapp':
                $rules['contacto'] = $rules['contacto'] . '|numeric|digits:10';
                break;
            default:
                break;
        }
        try {
            $this->validate($request, $rules);
        } catch (\Illuminate\Validation\ValidationException $th) {
            return response()->json(['errors' => implode(",", $th->validator->getMessageBag()->all())], 201);
        }

        $registro_formapago = FormaPago::where("descripcion", $request->forma_pago)->first();
        if ($registro_formapago == null) {
            return response()->json(['errors' => "La forma de pago es incorrecta"], 201);
        }
        $cliente = Cliente::where("id", Auth::user()->cliente_id)->first();
        $existe_formapago = $cliente->forma_pagos()->find($registro_formapago->id);
        if ($existe_formapago == null) {
            return response()->json(['errors' => "La forma de pago no esta asignada al cliente"], 201);
        }
        try {
            DB::beginTransaction();
            $registro = Ticket::create([
                'codigo' => Str::upper(Str::random(10)),
                'cantidad' => $request->cantidad,
                'cliente_id' => Auth::user()->cliente_id,
                'forma_pago_id' => $registro_formapago->id,
                'tipo_contacto' => $request->tipo,
                'contacto' => $request->contacto,
                'referencia' => $request->referencia,
                'estatus' => 'PENDIENTE',
                'usuario_creado' => Auth::user()->name
            ]);
            try {
                $ticketService->send($registro);
            } catch (\Throwable $th) {
            }
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            Log::error($th->getMessage() . $th->getTraceAsString());
            return response()->json(['errors' => $th->getMessage()], 201);
        }


        return response()->json($registro, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * @param Request $request
     * @return mixed
     * @OA\Post(
     * path="/api/modifyticket/{codigo}",
     * @OA\Parameter(
     *   parameter="codigo",
     *   name="codigo",
     *   description="El código del Ticket",
     *   @OA\Schema(
     *     type="string"
     *   ),
     *   in="query",
     *   required=false
     * ),
     * tags={"Tickets"},
     *    security={{"bearerAuth":{}}},
     * @OA\SecurityScheme(
     *      securityScheme="bearerAuth",
     *      in="header",
     *      name="bearerAuth",
     *      type="http",
     *      scheme="bearer",
     *      bearerFormat="JWT",
     * ),
     * @OA\RequestBody(
     * @OA\MediaType(
     * mediaType="application/json",
     * @OA\Schema(
     * @OA\Property(
     * property="forma_pago",
     * type="string"
     * ),
     * @OA\Property(
     * property="tipo",
     * type="string"
     * ),
     * @OA\Property(
     * property="contacto",
     * type="string"
     * ),
     * @OA\Property(
     * property="referencia",
     * type="string"
     * ),
     * @OA\Property(
     * property="cantidad",
     * type="string"
     * ),
     * example={ "forma_pago": "Efectivo","tipo": "Whatsapp","contacto": "3141335870","referencia": "referencia","cantidad": "50.00" }
     * )
     * )
     * ),
     * @OA\Response(response="200", description="OK", @OA\JsonContent(type="object")),
     * @OA\Response(response="201", description="OK", @OA\JsonContent(type="object")),
     * @OA\Response(response="422", description="Unprocessable Content", @OA\JsonContent(type="object")),
     * @OA\Response(response="401", description="Unauthorized", @OA\JsonContent(type="object")),

     * )
     */
    public function update(Request $request, string $codigo, TicketService $ticketService)
    {
        if ($codigo == null) {
            return response()->json(['errors' => "El codigo es incorrecto"], 201);
        }
        if (empty(trim($codigo))) {
            return response()->json(['errors' => "El codigo es incorrecto"], 201);
        }
        $rules = [
            'forma_pago' => 'required',
            'tipo' => 'required|string|max:15',
            'contacto' => 'required',
            'cantidad' => 'required|numeric',
            'referencia' => 'string',
        ];
        try {
            $this->validate($request, $rules);
        } catch (\Illuminate\Validation\ValidationException $th) {
            return response()->json(['errors' => implode(",", $th->validator->getMessageBag()->all())], 201);
        }
        switch ($request->tipo) {
            case 'Email':
                $rules['contacto'] = $rules['contacto'] . '|email';
                break;
            case 'SMS' || 'Whatsapp':
                $rules['contacto'] = $rules['contacto'] . '|numeric|digits:10';
                break;
            default:
                break;
        }
        try {
            $this->validate($request, $rules);
        } catch (\Illuminate\Validation\ValidationException $th) {
            return response()->json(['errors' => implode(",", $th->validator->getMessageBag()->all())], 201);
        }
        $registro = Ticket::where("codigo", $request->codigo)->first();

        if ($registro == null) {
            return response()->json(['errors' => "No se encuentra el codigo {$request->codigo}"], 201);
        }
        $registro_formapago = FormaPago::where("descripcion", $request->forma_pago)->first();
        if ($registro_formapago == null) {
            return response()->json(['errors' => "La forma de pago es incorrecta"], 201);
        }
        $cliente = Cliente::where("id", Auth::user()->cliente_id)->first();
        $existe_formapago = $cliente->forma_pagos()->find($registro_formapago->id);
        if ($existe_formapago == null) {
            return response()->json(['errors' => "La forma de pago no esta asignada al cliente"], 201);
        }
        try {
            DB::beginTransaction();
            $registro->update([
                'cantidad' => $request->cantidad,
                'cliente_id' => Auth::user()->cliente_id,
                'forma_pago_id' => $registro_formapago->id,
                'tipo_contacto' => $request->tipo,
                'contacto' => $request->contacto,
                'referencia' => $request->referencia,
                'usuario_modificado' => Auth::user()->name
            ]);
            try {
                $ticketService->send($registro);
            } catch (\Throwable $th) {
            }
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            Log::error($th->getMessage() . $th->getTraceAsString());
            return response()->json(['errors' => $th->getMessage()], 201);
        }
        return response()->json($registro, 201);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
