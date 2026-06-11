<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller {
    private function apenasAdmin(): void {
        if (!Auth::user()?->isAdmin()) {
            abort(403);
        }
    }

    public function index() {
        $this->apenasAdmin();

        $usuarios = User::orderBy('nome')->paginate(15);

        return view('admin.usuarios.index', compact('usuarios'));
    }

    public function create() {
        $this->apenasAdmin();

        return view('admin.usuarios.create');
    }

    public function store(Request $request) {
        $this->apenasAdmin();

        $data = $request->validate([
            'nome' => 'required|string|max:255',
            'email' => 'required|email|unique:usuarios,email',
            'senha' => 'required|string|min:8',
            'setor' => 'nullable|string|max:100',
            'nivel_acesso' => 'required|in:administrador,tecnico_manutencao,professor',
            'cod_entrada' => 'nullable|integer',
        ]);

        $data['senha'] = Hash::make($data['senha']);
        $data['ativo'] = true;

        User::create($data);

        return redirect()->route('admin.usuarios.index')->with('success', 'Funcionario cadastrado.');
    }

    public function edit(string $id) {
        $this->apenasAdmin();

        $usuario = User::findOrFail($id);

        return view('admin.usuarios.edit', compact('usuario'));
    }

    public function update(Request $request, string $id) {
        $this->apenasAdmin();

        $usuario = User::findOrFail($id);

        $data = $request->validate([
            'nome' => 'required|string|max:255',
            'email' => ['required', 'email', Rule::unique('usuarios', 'email')->ignore($usuario->id_usuario, 'id_usuario')],
            'setor' => 'nullable|string|max:100',
            'nivel_acesso' => 'required|in:administrador,tecnico_manutencao,professor',
            'cod_entrada' => 'nullable|integer',
            'senha' => 'nullable|string|min:8',
        ]);

        if (!empty($data['senha'])) {
            $data['senha'] = Hash::make($data['senha']);
        } else {
            unset($data['senha']);
        }

        $usuario->update($data);

        return redirect()->route('admin.usuarios.index')->with('success', 'Funcionario atualizado.');
    }

    public function toggleAtivo(string $id) {
        $this->apenasAdmin();

        $usuario = User::findOrFail($id);
        $usuario->ativo = !$usuario->ativo;
        $usuario->save();

        return back()->with('success', 'Status do funcionario alterado.');
    }

    public function destroy(string $id) {
        $this->apenasAdmin();

        $usuario = User::findOrFail($id);
        $usuario->delete();

        return back()->with('success', 'Funcionario deletado.');
    }
}
