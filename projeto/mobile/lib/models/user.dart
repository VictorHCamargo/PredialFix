class User {
  final int id;
  final String nome;
  final String email;
  final String? cpf;
  final String? telefone;
  final String? role;
  final String? avatar;

  User({
    required this.id,
    required this.nome,
    required this.email,
    this.cpf,
    this.telefone,
    this.role,
    this.avatar,
  });

  factory User.fromJson(Map<String, dynamic> json) {
    return User(
      id: json['id'] ?? 0,
      nome: json['nome'] ?? '',
      email: json['email'] ?? '',
      cpf: json['cpf'],
      telefone: json['telefone'],
      role: json['role'],
      avatar: json['avatar'],
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
      'avatar': avatar,
    };
  }
}
