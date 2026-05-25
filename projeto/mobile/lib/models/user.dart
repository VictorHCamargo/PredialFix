class User {
  final int id;
  final String nome;
  final String email;
  final String? telefone;
  final String? foto;
  final String? cpf;
  final String role;
  final DateTime? dataCriacao;

  User({
    required this.id,
    required this.nome,
    required this.email,
    this.telefone,
    this.foto,
    this.cpf,
    required this.role,
    this.dataCriacao,
  });

  factory User.fromJson(Map<String, dynamic> json) {
    return User(
      id: json['id'] as int,
      nome: json['nome'] as String,
      email: json['email'] as String,
      telefone: json['telefone'] as String?,
      foto: json['foto'] as String?,
      cpf: json['cpf'] as String?,
      role: json['role'] as String? ?? 'usuario',
      dataCriacao: json['data_criacao'] != null 
        ? DateTime.parse(json['data_criacao'] as String)
        : null,
    );
  }

  Map<String, dynamic> toJson() {
    return {
      'id': id,
      'nome': nome,
      'email': email,
      'telefone': telefone,
      'foto': foto,
      'cpf': cpf,
      'role': role,
      'data_criacao': dataCriacao?.toIso8601String(),
    };
  }
}
