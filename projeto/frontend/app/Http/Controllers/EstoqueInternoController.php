<?php

namespace App\Http\Controllers;

use App\Models\EstoqueInterno;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EstoqueInternoController extends Controller {
    /**
     * Mostrar lista de itens do estoque
     */
    public function index(Request $request) {
        $query = EstoqueInterno::with('usuarioCadastro');

        // Filtrar por categoria
        if ($request->filled('categoria')) {
            $query->where('categoria', $request->categoria);
        }

        // Filtrar por status
        if ($request->filled('status_item')) {
            $query->where('status_item', $request->status_item);
        }

        // Buscar por nome
        if ($request->filled('busca')) {
            $query->where('nome_item', 'like', '%' . $request->busca . '%');
        }

        $itens = $query->paginate(15);
        $categorias = EstoqueInterno::distinct()->pluck('categoria');

        return view('estoque.index', compact('itens', 'categorias'));
    }

    /**
     * Mostrar formulário de criação
     */
    public function create() {
        return view('estoque.create');
    }

    /**
     * Salvar novo item
     */
    public function store(Request $request) {
        $request->validate([
            'nome_item' => 'required|string|max:255',
            'descricao' => 'nullable|string',
            'quantidade' => 'required|integer|min:0',
            'categoria' => 'required|string|max:100',
            'localizacao' => 'nullable|string|max:255',
            'valor_unitario' => 'nullable|numeric|min:0',
            'codigo_patrimonio' => 'nullable|string|unique:estoque_interno,codigo_patrimonio',
            'observacoes' => 'nullable|string',
        ]);

        $data = $request->all();
        $data['id_usuario_cadastro'] = Auth::id();
        $data['data_entrada'] = now();

        // Calcular valor total
        if ($request->valor_unitario && $request->quantidade) {
            $data['valor_total'] = $request->valor_unitario * $request->quantidade;
        }

        EstoqueInterno::create($data);

        return redirect()
            ->route('estoque.index')
            ->with('success', 'Item adicionado ao estoque com sucesso!');
    }

    /**
     * Mostrar detalhes de um item
     */
    public function show(string $id) {
        $item = EstoqueInterno::with('usuarioCadastro')->findOrFail($id);
        return view('estoque.show', compact('item'));
    }

    /**
     * Mostrar formulário de edição
     */
    public function edit(string $id) {
        $item = EstoqueInterno::findOrFail($id);
        return view('estoque.edit', compact('item'));
    }

    /**
     * Atualizar item
     */
    public function update(Request $request, string $id) {
        $item = EstoqueInterno::findOrFail($id);

        $request->validate([
            'nome_item' => 'required|string|max:255',
            'descricao' => 'nullable|string',
            'quantidade' => 'required|integer|min:0',
            'categoria' => 'required|string|max:100',
            'localizacao' => 'nullable|string|max:255',
            'valor_unitario' => 'nullable|numeric|min:0',
            'codigo_patrimonio' =>
                'nullable|string|unique:estoque_interno,codigo_patrimonio,' . $id . ',id_estoque',
            'status_item' => 'required|in:disponivel,indisponivel,danificado,descartado',
            'observacoes' => 'nullable|string',
        ]);

        $data = $request->all();

        // Calcular valor total
        if ($request->valor_unitario && $request->quantidade) {
            $data['valor_total'] = $request->valor_unitario * $request->quantidade;
        }

        // Se descartado, registrar data
        if ($request->status_item === 'descartado') {
            $data['data_saida'] = now();
        }

        $item->update($data);

        return redirect()
            ->route('estoque.show', $id)
            ->with('success', 'Item atualizado com sucesso!');
    }

    /**
     * Deletar item
     */
    public function destroy(string $id) {
        $item = EstoqueInterno::findOrFail($id);
        $item->delete();

        return redirect()
            ->route('estoque.index')
            ->with('success', 'Item removido do estoque com sucesso!');
    }
}
