/// ApiService com dados MOCK (local, sem servidor)
/// O app funciona completamente offline com dados de exemplo
class ApiService {
  // Mock data - simula um servidor local
  static final List<Map<String, dynamic>> _mockUsers = [
    {
      'id': 1,
      'nome': 'Administrador',
      'email': 'admin@predialfix.com',
      'senha': 'admin123',
      'role': 'administrador',
      'telefone': '11999999999',
      'cpf': '11111111111',
    },
    {
      'id': 2,
      'nome': 'Gerente de Manutenção',
      'email': 'gerente@predialfix.com',
      'senha': 'gerente123',
      'role': 'gerente_manutencao',
      'telefone': '11988888888',
      'cpf': '22222222222',
    },
    {
      'id': 3,
      'nome': 'Técnico de Manutenção',
      'email': 'tecnico@predialfix.com',
      'senha': 'tecnico123',
      'role': 'tecnico_manutencao',
      'telefone': '11977777777',
      'cpf': '33333333333',
    },
    {
      'id': 4,
      'nome': 'Professor João Silva',
      'email': 'professor@predialfix.com',
      'senha': 'prof123',
      'role': 'professor',
      'telefone': '11966666666',
      'cpf': '44444444444',
    },
    {
      'id': 5,
      'nome': 'João Aluno',
      'email': 'joao@student.com',
      'senha': 'aluno123',
      'role': 'aluno',
      'telefone': '11955555555',
      'cpf': '55555555555',
    },
    {
      'id': 6,
      'nome': 'Maria Aluna',
      'email': 'maria@student.com',
      'senha': 'aluno123',
      'role': 'aluno',
      'telefone': '11944444444',
      'cpf': '66666666666',
    },
    {
      'id': 7,
      'nome': 'Pedro Aluno',
      'email': 'pedro@student.com',
      'senha': 'aluno123',
      'role': 'aluno',
      'telefone': '11933333333',
      'cpf': '77777777777',
    },
  ];

  static final List<Map<String, dynamic>> _mockChamados = [
    {
      'id': 1,
      'id_usuario': 5,
      'descricao': 'Luz queimada na sala 101',
      'id_local': 1,
      'id_tipo': 1,
      'status': 'em_andamento',
      'prioridade': 'alta',
      'data_abertura': '2024-05-20T10:30:00',
      'data_fechamento': null,
      'data_prazo': '2024-05-25T18:00:00',
    },
    {
      'id': 2,
      'id_usuario': 6,
      'descricao': 'Vazamento no banheiro bloco C',
      'id_local': 5,
      'id_tipo': 2,
      'status': 'pendente',
      'prioridade': 'média',
      'data_abertura': '2024-05-21T14:00:00',
      'data_fechamento': null,
      'data_prazo': '2024-05-28T18:00:00',
    },
    {
      'id': 3,
      'id_usuario': 7,
      'descricao': 'Ar condicionado desligado na sala de aula',
      'id_local': 2,
      'id_tipo': 3,
      'status': 'concluido',
      'prioridade': 'média',
      'data_abertura': '2024-05-15T09:00:00',
      'data_fechamento': '2024-05-18T16:30:00',
      'data_prazo': '2024-05-20T18:00:00',
    },
  ];

  static final List<Map<String, dynamic>> _mockLocais = [
    {
      'id': 1,
      'nome': 'Bloco A, Sala 101',
      'descricao': 'Sala de aula A1',
      'bloco': 'A',
      'andar': 1,
      'data_criacao': '2024-01-15T08:00:00',
    },
    {
      'id': 2,
      'nome': 'Bloco A, Sala 102',
      'descricao': 'Sala de aula A2',
      'bloco': 'A',
      'andar': 1,
      'data_criacao': '2024-01-15T08:00:00',
    },
    {
      'id': 3,
      'nome': 'Bloco B, Lab Eletrônica',
      'descricao': 'Laboratório de Eletrônica',
      'bloco': 'B',
      'andar': 2,
      'data_criacao': '2024-01-15T08:00:00',
    },
    {
      'id': 4,
      'nome': 'Bloco B, Lab Hidráulica',
      'descricao': 'Laboratório de Hidráulica',
      'bloco': 'B',
      'andar': 2,
      'data_criacao': '2024-01-15T08:00:00',
    },
    {
      'id': 5,
      'nome': 'Banheiro Bloco C',
      'descricao': 'Banheiro masculino bloco C',
      'bloco': 'C',
      'andar': 1,
      'data_criacao': '2024-01-15T08:00:00',
    },
  ];

  static final List<Map<String, dynamic>> _mockTiposProblema = [
    {
      'id': 1,
      'nome': 'Elétrica',
      'descricao': 'Problemas relacionados à instalação elétrica',
      'categoria': 'predial',
      'data_criacao': '2024-01-10T08:00:00',
    },
    {
      'id': 2,
      'nome': 'Hidráulica',
      'descricao': 'Problemas relacionados aos sistemas hidráulicos',
      'categoria': 'predial',
      'data_criacao': '2024-01-10T08:00:00',
    },
    {
      'id': 3,
      'nome': 'HVAC',
      'descricao': 'Problemas de ar condicionado e ventilação',
      'categoria': 'predial',
      'data_criacao': '2024-01-10T08:00:00',
    },
    {
      'id': 4,
      'nome': 'Civil',
      'descricao': 'Problemas relacionados à estrutura civil',
      'categoria': 'predial',
      'data_criacao': '2024-01-10T08:00:00',
    },
    {
      'id': 5,
      'nome': 'Mecânica',
      'descricao': 'Problemas relacionados a equipamentos mecânicos',
      'categoria': 'equipamento',
      'data_criacao': '2024-01-10T08:00:00',
    },
  ];

  String? _token;
  Map<String, dynamic>? _currentUser;

  ApiService();

  // Token management
  void setToken(String token) {
    _token = token;
  }

  void clearToken() {
    _token = null;
    _currentUser = null;
  }

  String? getToken() {
    return _token;
  }

  // Authentication methods
  Future<Map<String, dynamic>> login(String email, String password) async {
    // Simula delay de requisição
    await Future.delayed(const Duration(milliseconds: 800));

    // Procura o usuário nos dados mock
    final userMap = _mockUsers.firstWhere(
      (u) => u['email'] == email && u['senha'] == password,
      orElse: () => <String, dynamic>{},
    );

    if (userMap.isEmpty) {
      throw Exception('Email ou senha inválidos');
    }

    // Gera um token fake
    final token =
        'mock_token_${userMap['id']}_${DateTime.now().millisecondsSinceEpoch}';
    _token = token;
    _currentUser = Map.from(userMap)..remove('senha'); // Remove senha

    return {
      'token': token,
      'user': _currentUser,
    };
  }

  Future<Map<String, dynamic>> register(
    String nome,
    String email,
    String password,
    String passwordConfirmation,
  ) async {
    // Simula delay
    await Future.delayed(const Duration(milliseconds: 800));

    // Valida campos
    if (password != passwordConfirmation) {
      throw Exception('Senhas não conferem');
    }

    // Verifica se email já existe
    if (_mockUsers.any((u) => u['email'] == email)) {
      throw Exception('Email já cadastrado');
    }

    // Cria novo usuário mock
    final newId = _mockUsers.length + 1;
    final newUser = {
      'id': newId,
      'nome': nome,
      'email': email,
      'senha': password,
      'role': 'aluno',
      'telefone': null,
      'cpf': null,
    };

    _mockUsers.add(newUser);

    // Faz login automático
    return login(email, password);
  }

  Future<void> logout() async {
    // Simula delay
    await Future.delayed(const Duration(milliseconds: 300));
    clearToken();
  }

  // User methods
  Future<Map<String, dynamic>> getCurrentUser() async {
    await Future.delayed(const Duration(milliseconds: 300));

    if (_currentUser == null) {
      throw Exception('Usuário não autenticado');
    }

    return {'user': _currentUser};
  }

  // Chamado methods
  Future<List<Map<String, dynamic>>> getChamados() async {
    await Future.delayed(const Duration(milliseconds: 600));

    if (_currentUser == null) {
      throw Exception('Usuário não autenticado');
    }

    final userId = _currentUser!['id'];

    // Filtra chamados do usuário
    return _mockChamados
        .where((c) => c['id_usuario'] == userId)
        .map((c) => Map<String, dynamic>.from(c))
        .toList();
  }

  Future<Map<String, dynamic>> getChamado(int id) async {
    await Future.delayed(const Duration(milliseconds: 400));

    if (_currentUser == null) {
      throw Exception('Usuário não autenticado');
    }

    final chamado = _mockChamados.firstWhere(
      (c) => c['id'] == id,
      orElse: () => <String, dynamic>{},
    );

    if (chamado.isEmpty) {
      throw Exception('Chamado não encontrado');
    }

    return Map<String, dynamic>.from(chamado);
  }

  Future<Map<String, dynamic>> createChamado(
      Map<String, dynamic> data) async {
    await Future.delayed(const Duration(milliseconds: 800));

    if (_currentUser == null) {
      throw Exception('Usuário não autenticado');
    }

    final newId = _mockChamados.fold<int>(
      0,
      (max, c) => c['id'] > max ? c['id'] : max,
    ).toInt() + 1;

    final newChamado = {
      'id': newId,
      'id_usuario': _currentUser!['id'],
      'descricao': data['descricao'],
      'id_local': data['id_local'],
      'id_tipo': data['id_tipo'],
      'status': 'pendente',
      'prioridade': data['prioridade'] ?? 'média',
      'data_abertura': DateTime.now().toIso8601String(),
      'data_fechamento': null,
      'data_prazo': data['data_prazo'],
    };

    _mockChamados.add(newChamado);

    return Map<String, dynamic>.from(newChamado);
  }

  Future<Map<String, dynamic>> updateChamado(
    int id,
    Map<String, dynamic> data,
  ) async {
    await Future.delayed(const Duration(milliseconds: 800));

    if (_currentUser == null) {
      throw Exception('Usuário não autenticado');
    }

    final index = _mockChamados.indexWhere((c) => c['id'] == id);
    if (index == -1) {
      throw Exception('Chamado não encontrado');
    }

    // Atualiza chamado
    _mockChamados[index].addAll(data);

    return Map<String, dynamic>.from(_mockChamados[index]);
  }

  Future<void> deleteChamado(int id) async {
    await Future.delayed(const Duration(milliseconds: 500));

    if (_currentUser == null) {
      throw Exception('Usuário não autenticado');
    }

    _mockChamados.removeWhere((c) => c['id'] == id);
  }

  // Feedback methods
  Future<List<Map<String, dynamic>>> getFeedbacks() async {
    await Future.delayed(const Duration(milliseconds: 400));

    if (_currentUser == null) {
      throw Exception('Usuário não autenticado');
    }

    // Retorna lista vazia de feedbacks (apenas estrutura)
    return [];
  }

  Future<Map<String, dynamic>> createFeedback(
    int idChamado,
    Map<String, dynamic> data,
  ) async {
    await Future.delayed(const Duration(milliseconds: 600));

    if (_currentUser == null) {
      throw Exception('Usuário não autenticado');
    }

    return {
      'id': 1,
      'id_chamado': idChamado,
      'id_usuario': _currentUser!['id'],
      'classificacao': data['avaliacao'],
      'comentario': data['comentario'],
      'data_criacao': DateTime.now().toIso8601String(),
    };
  }

  // Reference data methods
  Future<List<Map<String, dynamic>>> getLocais() async {
    await Future.delayed(const Duration(milliseconds: 400));
    return _mockLocais.map((l) => Map<String, dynamic>.from(l)).toList();
  }

  Future<List<Map<String, dynamic>>> getTiposProblema() async {
    await Future.delayed(const Duration(milliseconds: 400));
    return _mockTiposProblema
        .map((t) => Map<String, dynamic>.from(t))
        .toList();
  }
}
