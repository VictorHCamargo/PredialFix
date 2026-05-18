class User {
  final int id;
  final String nome;
  final String email;
  final String? cpf;
  final String? telefone;
  final String? role;
  final bool ativo;
  final DateTime? criadoEm;
  final DateTime? atualizadoEm;

  User({
    required this.id,
    required this.nome,
    required this.email,
    this.cpf,
    this.telefone,
    this.role,
    required this.ativo,
    this.criadoEm,
    this.atualizadoEm,
  });

  factory User.fromJson(Map<String, dynamic> json) {
    return User(
      id: json['id'] ?? json['id_usuario'] ?? 0,
      nome: json['nome'] ?? '',
      email: json['email'] ?? '',
      cpf: json['cpf'],
      telefone: json['telefone'],
      role: json['role'] ?? json['nivel_acesso'],
      ativo: json['ativo'] ?? true,
      criadoEm: json['created_at'] != null ? DateTime.parse(json['created_at']) : null,
      atualizadoEm: json['updated_at'] != null ? DateTime.parse(json['updated_at']) : null,
    );
  }

  Map<String, dynamic> toJson() {
    return {
      'id': id,
      'nome': nome,
      'email': email,
      'cpf': cpf,
      'telefone': telefone,
      'role': role,
      'ativo': ativo,
      'created_at': criadoEm?.toIso8601String(),
      'updated_at': atualizadoEm?.toIso8601String(),
    };
  }
}
