<?php

namespace App\Http\Controllers;

use App\Models\ManutencaoProgramada;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ManutencaoController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $sortBy = $request->input('sort_by');        // exemplo: tipo.nome
        $sortOrder = $request->input('sort_order', 'asc');

        // Começa a query
        $query = ManutencaoProgramada::query()
            ->with(['tipo', 'condominio', 'bloco', 'apartamento'])
            ->select('manutencao_programadas.*'); // importante!

        // Aplica joins conforme o campo de ordenação
        switch ($sortBy) {
            case 'tipo.nome':
                $query->join('tipo_manutencaos', 'tipo_manutencaos.id', '=', 'manutencao_programadas.tipo_manutencao_id')
                    ->orderBy('tipo_manutencaos.nome', $sortOrder);
                break;
            case 'condominio.nome':
                $query->join('condominios', 'condominios.id', '=', 'manutencao_programadas.condominio_id')
                    ->orderBy('condominios.nome', $sortOrder);
                break;
            case 'bloco.nome':
                $query->join('blocos', 'blocos.id', '=', 'manutencao_programadas.bloco_id')
                    ->orderBy('blocos.nome', $sortOrder);
                break;
            case 'apartamento.numero':
                $query->join('apartamentos', 'apartamentos.id', '=', 'manutencao_programadas.apartamento_id')
                    ->orderBy('apartamentos.numero', $sortOrder);
                break;
            case 'data_agendada':
                $query->orderBy('manutencao_programadas.data_agendada', $sortOrder);
                break;
            default:
                $query->orderBy('manutencao_programadas.created_at', 'desc');
        }

        if ($request->filled('nome')) {
            $query->join('condominios', 'condominios.id', '=', 'manutencao_programadas.condominio_id')
                ->where('condominios.nome', 'LIKE', '%' . $request->input('condominios.nome') . '%')
                ->select('manutencao_programadas.*');
        }

        if ($request->filled('data_agendada')) {
            $query->whereDate('manutencao_programadas.data_agendada', $request->input('data_agendada'));
        }

        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        return response()->json(
            $query->paginate(10)
        );
    }

    public function show(ManutencaoProgramada $manutencao): JsonResponse
    {
        return response()->json($manutencao->load('tipo', 'condominio', 'bloco', 'apartamento'));
    }

    public function store(Request $request): JsonResponse
    {
        $manutencao = ManutencaoProgramada::create($request->all());
        return response()->json($manutencao, 201);
    }

    public function update(Request $request, ManutencaoProgramada $manutencao): JsonResponse
    {
        $data = $request->only([
            'status',
            'data_conclusao',
            'descricao',
            'data_agendada'
        ]);

        $manutencao->update($data);

        return response()->json($manutencao);
    }
    public function destroy(ManutencaoProgramada $manutencao): JsonResponse
    {
        $manutencao->delete();
        return response()->json(null, 204);
    }
}
