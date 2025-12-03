<?php

namespace App\Http\Controllers;


use App\Models\Bloco;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class BlocoController extends Controller
{
    public function index(Request $request): JsonResponse
    {

        $condominioId = $request->condominio_id;

        $query = Bloco::query();

        if ($condominioId) {
            $query->where('condominio_id', $condominioId);
        }

        $blocos = $query->get();

        return response()->json($blocos);

        //return response()->json(Bloco::with('condominio', 'sindico', 'apartamentos', 'manutencoes')->get());
    }

    public function show(Bloco $bloco): JsonResponse
    {
        return response()->json($bloco->load('condominio', 'sindico', 'apartamentos', 'manutencoes'));
    }

    public function store(Request $request): JsonResponse
    {
        $bloco = Bloco::create($request->validated());
        return response()->json($bloco, 201);
    }

    public function update(Request $request, Bloco $bloco): JsonResponse
    {
        $bloco->update($request->validated());
        return response()->json($bloco);
    }

    public function destroy(Bloco $bloco): JsonResponse
    {
        $bloco->delete();
        return response()->json(null, 204);
    }
}
