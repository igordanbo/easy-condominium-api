<?php

namespace App\Http\Controllers;

use App\Models\Apartamento;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ApartamentoController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $condominioId = $request->condominio_id;
        $blocoId = $request->bloco_id;

        $query = Apartamento::query();

        if ($condominioId) {
            $query->where('condominio_id', $condominioId);
        }

        if ($blocoId) {
            $query->where('bloco_id', $blocoId);
        }

        $apartamentos = $query->get();

        return response()->json($apartamentos);

        //return response()->json(Apartamento::with('bloco.condominio', 'dono', 'manutencaos')->get());
    }

    public function show(Apartamento $apartamento): JsonResponse
    {
        return response()->json($apartamento->load('bloco.condominio', 'dono', 'manutencaos'));
    }

    public function store(Request $request): JsonResponse
    {
        $apartamento = Apartamento::create($request->all());
        return response()->json($apartamento, 201);
    }

    public function update(Request $request, Apartamento $apartamento): JsonResponse
    {
        $apartamento->update($request->all());
        return response()->json($apartamento);
    }

    public function destroy(Apartamento $apartamento): JsonResponse
    {
        $apartamento->delete();
        return response()->json(null, 204);
    }
}
