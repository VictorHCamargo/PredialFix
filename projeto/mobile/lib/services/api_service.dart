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
      'nome': 'Gerente de ManutenÃ§Ã£o',
      'email': 'gerente@predialfix.com',
      'senha': 'gerente123',
      'role': 'gerente_manutencao',
      'telefone': '11988888888',
      'cpf': '22222222222',
    },
    {
      'id': 3,
      'nome': 'TÃ©cnico de ManutenÃ§Ã£o',
      'email': 'tecnico@predialfix.com',
      'senha': 'tecnico123',
      'role': 'tecnico_manutencao',
      'telefone': '11977777777',
      'cpf': '33333333333',
    },
    {
      'id': 4,
      'nome': 'Professor JoÃ£o Silva',
      'email': 'professor@predialfix.com',
      'senha': 'prof123',
      'role': 'professor',
      'telefone': '11966666666',
      'cpf': '44444444444',
    },
    {
      'id': 5,
      'nome': 'JoÃ£o Aluno',
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
      'prioridade': 'mÃ©dia',
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
      'prioridade': 'mÃ©dia',
      'data_abertura': '2024-05-15T09:00:00',
      'data_fechamento': '2024-05-18T16:30:00',
      'data_prazo': '2024-05-20T18:00:00',
    },
  ];

  static final List<Map<String, dynamic>> _mockLocais = [
    {
      'id': 1,
      'nome': 'Bloco A, Sala 1',
      'descricao': 'Sala de aula A1',
      'bloco': 'A',
      'andar': 1,
      'data_criacao': '2024-01-15T08:00:00',
    },
    {
      'id': 2,
      'nome': 'Bloco A, Sala 2',
      'descricao': 'Sala de aula A2',
      'bloco': 'A',
      'andar': 1,
      'data_criacao': '2024-01-15T08:00:00',
    },
    {
      'id': 3,
      'nome': 'Bloco B, Sala 1',
      'descricao': 'LaboratÃ³rio de EletrÃ´nica',
      'bloco': 'B',
      'andar': 2,
      'data_criacao': '2024-01-15T08:00:00',
    },
    {
      'id': 4,
      'nome': 'Bloco B, Sala 2',
      'descricao': 'LaboratÃ³rio de HidrÃ¡ulica',
      'bloco': 'B',
      'andar': 2,
      'data_criacao': '2024-01-15T08:00:00',
    },
    {
      'id': 5,
      'nome': 'Bloco C, Sala 1',
      'descricao': 'Sala de Aula C1',
      'bloco': 'C',
      'andar': 1,
      'data_criacao': '2024-01-15T08:00:00',
    },
  ];

  static final List<Map<String, dynamic>> _mockTiposProblema = [
    {
      'id': 1,
      'nome': 'ElÃ©trica',
      'descricao': 'Problemas com sistemas elÃ©tricos',
      'categoria': 'Infraestrutura',
      'data_criacao': '2024-01-15T08:00:00',
    },
    {
      'id': 2,
      'nome': 'HidrÃ¡ulica',
      'descricao': 'Problemas com sistemas hidrÃ¡ulicos',
      'categoria': 'Infraestrutura',
      'data_criacao': '2024-01-15T08:00:00',
    },
    {
      'id': 3,
      'nome': 'HVAC',
      'descricao': 'Ar condicionado e ventilaÃ§Ã£o',
      'categoria': 'ClimatizaÃ§Ã£o',
      'data_criacao': '2024-01-15T08:00:00',
    },
    {
      'id': 4,
      'nome': 'Civil',
      'descricao': 'Problemas estruturais e alvenaria',
      'categoria': 'Estrutura',
      'data_criacao': '2024-01-15T08:00:00',
    },
    {
      'id': 5,
      'nome': 'MecÃ¢nica',
      'descricao': 'Problemas com equipamentos mecÃ¢nicos',
      'categoria': 'Equipamentos',
      'data_criacao': '2024-01-15T08:00:00',
    },
  ];

  static final List<Map<String, dynamic>> _mockEquipamentos = [
    {
      'id': 1,
      'tag_identificacao': 'EQUIP-001',
      'nome': 'Ar Condicionado',
      'marca': 'LG',
      'status': 'ativo',
    },
    {
      'id': 2,
      'tag_identificacao': 'EQUIP-002',
      'nome': 'Bomba HidrÃ¡ulica',
      'marca': 'Bosch',
      'status': 'ativo',
    },
    {
      'id': 3,
      'tag_identificacao': 'EQUIP-003',
      'nome': 'Painel ElÃ©trico',
      'marca': 'Schneider',
      'status': 'manutencao',
    },
    {
      'id': 4,
      'tag_identificacao': 'EQUIP-004',
      'nome': 'Motor TrifÃ¡sico',
      'marca': 'WEG',
      'status': 'ativo',
    },
    {
      'id': 5,
      'tag_identificacao': 'EQUIP-005',
      'nome': 'Compressor',
      'marca': 'Atlas Copco',
      'status': 'inativo',
    },
  ];

  static final List<Map<String, dynamic>> _mockEstoque = [
    {
      'id': 1,
      'nome_item': 'Cabo ElÃ©trico 2.5mm',
      'descricao': 'Cabo de cobre para instalaÃ§Ã£o elÃ©trica',
      'quantidade': 50,
      'categoria': 'ElÃ©trica',
      'localizacao': 'Bloco A - Almoxarifado',
      'valor_unitario': 12.50,
      'valor_total': 625.00,
      'codigo_patrimonio': 'PAT-ELE-001',
      'status_item': 'disponivel',
      'data_entrada': '2024-01-15T08:00:00',
      'observacoes': 'Em bom estado',
    },
    {
      'id': 2,
      'nome_item': 'LÃ¢mpada LED 20W',
      'descricao': 'LÃ¢mpada LED branca fria',
      'quantidade': 120,
      'categoria': 'ElÃ©trica',
      'localizacao': 'Bloco B - Almoxarifado',
      'valor_unitario': 8.00,
      'valor_total': 960.00,
      'codigo_patrimonio': 'PAT-ELE-002',
      'status_item': 'disponivel',
      'data_entrada': '2024-02-01T10:30:00',
      'observacoes': null,
    },
    {
      'id': 3,
      'nome_item': 'Mangueira HidrÃ¡ulica',
      'descricao': 'Mangueira de pressÃ£o sÃ©rie SAE 100 R2',
      'quantidade': 15,
      'categoria': 'HidrÃ¡ulica',
      'localizacao': 'Bloco C - Almoxarifado',
      'valor_unitario': 45.00,
      'valor_total': 675.00,
      'codigo_patrimonio': 'PAT-HID-001',
      'status_item': 'indisponivel',
      'data_entrada': '2024-01-20T09:00:00',
      'observacoes': 'Em manutenÃ§Ã£o',
    },
    {
      'id': 4,
      'nome_item': 'Ã“leo HidrÃ¡ulico ISO 46',
      'descricao': 'Ã“leo hidrÃ¡ulico para sistemas de pressÃ£o',
      'quantidade': 8,
      'categoria': 'HidrÃ¡ulica',
      'localizacao': 'Bloco A - Almoxarifado',
      'valor_unitario': 85.00,
      'valor_total': 680.00,
      'codigo_patrimonio': 'PAT-HID-002',
      'status_item': 'disponivel',
      'data_entrada': '2024-03-05T14:20:00',
      'observacoes': null,
    },
    {
      'id': 5,
      'nome_item': 'Parafuso Estrutural M16',
      'descricao': 'Parafuso de aÃ§o para estrutura civil',
      'quantidade': 500,
      'categoria': 'Civil',
      'localizacao': 'Bloco B - Almoxarifado',
      'valor_unitario': 2.50,
      'valor_total': 1250.00,
      'codigo_patrimonio': 'PAT-CIV-001',
      'status_item': 'disponivel',
      'data_entrada': '2024-01-10T11:00:00',
      'observacoes': 'Estoque crÃ­tico',
    },
  ];

  static final List<Map<String, dynamic>> _mockOrcamentos = [
    {
      'id': 1,
      'id_chamado': 1,
      'valor': 1500.00,
      'descricao': 'SubstituiÃ§Ã£o de lÃ¢mpadas LED em 5 salas',
      'data_verificacao': '2024-05-22T10:00:00',
      'aprovacao': true,
    },
    {
      'id': 2,
      'id_chamado': 2,
      'valor': 3200.00,
      'descricao': 'Reparo da bomba hidrÃ¡ulica',
      'data_verificacao': '2024-05-23T14:30:00',
      'aprovacao': false,
    },
  ];

  static final List<Map<String, dynamic>> _mockHistorico = [
    {
      'id': 1,
      'id_chamado': 1,
      'status_anterior': 'pendente',
      'status_novo': 'em_andamento',
      'descricao': 'Iniciada manutenÃ§Ã£o preventiva',
      'id_usuario': 3,
      'prioridade': 'alta',
      'data_mudanca': '2024-05-20T11:30:00',
    },
    {
      'id': 2,
      'id_chamado': 3,
      'status_anterior': 'em_andamento',
      'status_novo': 'concluido',
      'descricao': 'ManutenÃ§Ã£o finalizada com sucesso',
      'id_usuario': 3,
      'prioridade': 'mÃ©dia',
      'data_mudanca': '2024-05-18T16:30:00',
    },
  ];

  String? _token;
  Map<String, dynamic>? _currentUser;

  ApiService();

  // Token management
  void setToken(String token) {
    _token = token;
  }

  void setCurrentUser(Map<String, dynamic> user) {
    _currentUser = Map<String, dynamic>.from(user)..remove('senha');
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
    // Simula delay de requisiÃ§Ã£o
    await Future.microtask(() {});

    // Procura o usuÃ¡rio nos dados mock
    final userMap = _mockUsers.firstWhere(
      (u) => u['email'] == email && u['senha'] == password,
      orElse: () => <String, dynamic>{},
    );

    if (userMap.isEmpty) {
      throw Exception('Email ou senha invÃ¡lidos');
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
    await Future.microtask(() {});

    // Valida campos
    if (password != passwordConfirmation) {
      throw Exception('Senhas nÃ£o conferem');
    }

    // Verifica se email jÃ¡ existe
    if (_mockUsers.any((u) => u['email'] == email)) {
      throw Exception('Email jÃ¡ cadastrado');
    }

    // Cria novo usuÃ¡rio mock
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

    // Faz login automÃ¡tico
    return login(email, password);
  }

  Future<void> logout() async {
    // Simula delay
    await Future.microtask(() {});
    clearToken();
  }

  // User methods
  Future<Map<String, dynamic>> getCurrentUser() async {
    await Future.microtask(() {});

    if (_currentUser == null) {
      throw Exception('UsuÃ¡rio nÃ£o autenticado');
    }

    return {'user': _currentUser};
  }

  // Chamado methods
  Future<List<Map<String, dynamic>>> getChamados() async {
    await Future.microtask(() {});

    if (_currentUser == null) {
      throw Exception('UsuÃ¡rio nÃ£o autenticado');
    }

    final userId = _currentUser!['id'];

    // Filtra chamados do usuÃ¡rio
    return _mockChamados
        .where((c) => c['id_usuario'] == userId)
        .map((c) => Map<String, dynamic>.from(c))
        .toList();
  }

  Future<Map<String, dynamic>> getChamado(int id) async {
    await Future.microtask(() {});

    if (_currentUser == null) {
      throw Exception('UsuÃ¡rio nÃ£o autenticado');
    }

    final chamado = _mockChamados.firstWhere(
      (c) => c['id'] == id,
      orElse: () => <String, dynamic>{},
    );

    if (chamado.isEmpty) {
      throw Exception('Chamado nÃ£o encontrado');
    }

    return Map<String, dynamic>.from(chamado);
  }

  Future<Map<String, dynamic>> createChamado(
      Map<String, dynamic> data) async {
    await Future.microtask(() {});

    if (_currentUser == null) {
      throw Exception('UsuÃ¡rio nÃ£o autenticado');
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
      'prioridade': data['prioridade'] ?? 'mÃ©dia',
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
    await Future.microtask(() {});

    if (_currentUser == null) {
      throw Exception('UsuÃ¡rio nÃ£o autenticado');
    }

    final index = _mockChamados.indexWhere((c) => c['id'] == id);
    if (index == -1) {
      throw Exception('Chamado nÃ£o encontrado');
    }

    // Atualiza chamado
    _mockChamados[index].addAll(data);

    return Map<String, dynamic>.from(_mockChamados[index]);
  }

  Future<void> deleteChamado(int id) async {
    await Future.microtask(() {});

    if (_currentUser == null) {
      throw Exception('UsuÃ¡rio nÃ£o autenticado');
    }

    _mockChamados.removeWhere((c) => c['id'] == id);
  }

  // Feedback methods
  Future<List<Map<String, dynamic>>> getFeedbacks() async {
    await Future.microtask(() {});

    if (_currentUser == null) {
      throw Exception('UsuÃ¡rio nÃ£o autenticado');
    }

    // Retorna lista vazia de feedbacks (apenas estrutura)
    return [];
  }

  Future<Map<String, dynamic>> createFeedback(
    int idChamado,
    Map<String, dynamic> data,
  ) async {
    await Future.microtask(() {});

    if (_currentUser == null) {
      throw Exception('UsuÃ¡rio nÃ£o autenticado');
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
    await Future.microtask(() {});
    return _mockLocais.map((l) => Map<String, dynamic>.from(l)).toList();
  }

  Future<List<Map<String, dynamic>>> getTiposProblema() async {
    await Future.microtask(() {});
    return _mockTiposProblema
        .map((t) => Map<String, dynamic>.from(t))
        .toList();
  }

  // Equipamentos methods
  Future<List<Map<String, dynamic>>> getEquipamentos() async {
    await Future.microtask(() {});

    if (_currentUser == null || _currentUser!['role'] != 'administrador') {
      throw Exception('Acesso negado');
    }

    return _mockEquipamentos
        .map((e) => Map<String, dynamic>.from(e))
        .toList();
  }

  Future<Map<String, dynamic>> createEquipamento(
      Map<String, dynamic> data) async {
    await Future.microtask(() {});

    if (_currentUser == null || _currentUser!['role'] != 'administrador') {
      throw Exception('Acesso negado');
    }

    final newId =
        _mockEquipamentos.fold<int>(0, (max, e) => e['id'] > max ? e['id'] : max)
                .toInt() +
            1;
    final newEquipamento = {
      'id': newId,
      'tag_identificacao': data['tag_identificacao'],
      'nome': data['nome'],
      'marca': data['marca'],
      'status': data['status'] ?? 'ativo',
    };

    _mockEquipamentos.add(newEquipamento);
    return Map<String, dynamic>.from(newEquipamento);
  }

  Future<Map<String, dynamic>> updateEquipamento(
    int id,
    Map<String, dynamic> data,
  ) async {
    await Future.microtask(() {});

    if (_currentUser == null || _currentUser!['role'] != 'administrador') {
      throw Exception('Acesso negado');
    }

    final index = _mockEquipamentos.indexWhere((e) => e['id'] == id);
    if (index == -1) throw Exception('Equipamento nÃ£o encontrado');

    _mockEquipamentos[index].addAll(data);
    return Map<String, dynamic>.from(_mockEquipamentos[index]);
  }

  Future<void> deleteEquipamento(int id) async {
    await Future.microtask(() {});

    if (_currentUser == null || _currentUser!['role'] != 'administrador') {
      throw Exception('Acesso negado');
    }

    _mockEquipamentos.removeWhere((e) => e['id'] == id);
  }

  // Estoque methods
  Future<List<Map<String, dynamic>>> getEstoque() async {
    await Future.microtask(() {});

    if (_currentUser == null ||
        (_currentUser!['role'] != 'administrador' &&
            _currentUser!['role'] != 'gerente_manutencao')) {
      throw Exception('Acesso negado');
    }

    return _mockEstoque.map((e) => Map<String, dynamic>.from(e)).toList();
  }

  Future<Map<String, dynamic>> createEstoque(
      Map<String, dynamic> data) async {
    await Future.microtask(() {});

    if (_currentUser == null ||
        (_currentUser!['role'] != 'administrador' &&
            _currentUser!['role'] != 'gerente_manutencao')) {
      throw Exception('Acesso negado');
    }

    final newId =
        _mockEstoque.fold<int>(0, (max, e) => e['id'] > max ? e['id'] : max)
                .toInt() +
            1;
    final quantidade = data['quantidade'] as int? ?? 0;
    final valorUnitario = data['valor_unitario'] as double? ?? 0.0;
    final valorTotal = quantidade * valorUnitario;

    final newItem = {
      'id': newId,
      'nome_item': data['nome_item'],
      'descricao': data['descricao'],
      'quantidade': quantidade,
      'categoria': data['categoria'],
      'localizacao': data['localizacao'],
      'valor_unitario': valorUnitario,
      'valor_total': valorTotal,
      'codigo_patrimonio': data['codigo_patrimonio'],
      'status_item': data['status_item'] ?? 'disponivel',
      'data_entrada': DateTime.now().toIso8601String(),
      'observacoes': data['observacoes'],
    };

    _mockEstoque.add(newItem);
    return Map<String, dynamic>.from(newItem);
  }

  Future<Map<String, dynamic>> updateEstoque(
    int id,
    Map<String, dynamic> data,
  ) async {
    await Future.microtask(() {});

    if (_currentUser == null ||
        (_currentUser!['role'] != 'administrador' &&
            _currentUser!['role'] != 'gerente_manutencao')) {
      throw Exception('Acesso negado');
    }

    final index = _mockEstoque.indexWhere((e) => e['id'] == id);
    if (index == -1) throw Exception('Item nÃ£o encontrado');

    final itemAtual = _mockEstoque[index];
    itemAtual.addAll(data);

    // Recalcula valor total
    if (data.containsKey('quantidade') || data.containsKey('valor_unitario')) {
      final quantidade = itemAtual['quantidade'] as int;
      final valorUnitario = itemAtual['valor_unitario'] as double;
      itemAtual['valor_total'] = quantidade * valorUnitario;
    }

    return Map<String, dynamic>.from(itemAtual);
  }

  Future<void> deleteEstoque(int id) async {
    await Future.microtask(() {});

    if (_currentUser == null ||
        (_currentUser!['role'] != 'administrador' &&
            _currentUser!['role'] != 'gerente_manutencao')) {
      throw Exception('Acesso negado');
    }

    _mockEstoque.removeWhere((e) => e['id'] == id);
  }

  // OrÃ§amento methods
  Future<List<Map<String, dynamic>>> getOrcamentos() async {
    await Future.microtask(() {});

    if (_currentUser == null ||
        (_currentUser!['role'] != 'administrador' &&
            _currentUser!['role'] != 'gerente_manutencao')) {
      throw Exception('Acesso negado');
    }

    return _mockOrcamentos.map((o) => Map<String, dynamic>.from(o)).toList();
  }

  Future<Map<String, dynamic>> createOrcamento(
      Map<String, dynamic> data) async {
    await Future.microtask(() {});

    if (_currentUser == null) throw Exception('UsuÃ¡rio nÃ£o autenticado');

    final newId =
        _mockOrcamentos.fold<int>(0, (max, o) => o['id'] > max ? o['id'] : max)
                .toInt() +
            1;
    final newOrcamento = {
      'id': newId,
      'id_chamado': data['id_chamado'],
      'valor': data['valor'],
      'descricao': data['descricao'],
      'data_verificacao': DateTime.now().toIso8601String(),
      'aprovacao': false,
    };

    _mockOrcamentos.add(newOrcamento);
    return Map<String, dynamic>.from(newOrcamento);
  }

  Future<Map<String, dynamic>> approveOrcamento(int id) async {
    await Future.microtask(() {});

    if (_currentUser == null ||
        (_currentUser!['role'] != 'administrador' &&
            _currentUser!['role'] != 'gerente_manutencao')) {
      throw Exception('Acesso negado');
    }

    final index = _mockOrcamentos.indexWhere((o) => o['id'] == id);
    if (index == -1) throw Exception('OrÃ§amento nÃ£o encontrado');

    _mockOrcamentos[index]['aprovacao'] = true;
    return Map<String, dynamic>.from(_mockOrcamentos[index]);
  }

  // HistÃ³rico methods
  Future<List<Map<String, dynamic>>> getHistorico(int idChamado) async {
    await Future.microtask(() {});

    return _mockHistorico
        .where((h) => h['id_chamado'] == idChamado)
        .map((h) => Map<String, dynamic>.from(h))
        .toList();
  }

  Future<Map<String, dynamic>> addHistorico(
      Map<String, dynamic> data) async {
    await Future.microtask(() {});

    if (_currentUser == null) throw Exception('UsuÃ¡rio nÃ£o autenticado');

    final newId =
        _mockHistorico.fold<int>(0, (max, h) => h['id'] > max ? h['id'] : max)
                .toInt() +
            1;
    final newHistorico = {
      'id': newId,
      'id_chamado': data['id_chamado'],
      'status_anterior': data['status_anterior'],
      'status_novo': data['status_novo'],
      'descricao': data['descricao'],
      'id_usuario': _currentUser!['id'],
      'prioridade': data['prioridade'],
      'data_mudanca': DateTime.now().toIso8601String(),
    };

    _mockHistorico.add(newHistorico);
    return Map<String, dynamic>.from(newHistorico);
  }
}

