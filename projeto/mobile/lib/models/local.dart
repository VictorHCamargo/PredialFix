class Local {
  final int id;
  final String nome;
  final String? descricao;

  Local({
    required this.id,
    required this.nome,
    this.descricao,
  });

  factory Local.fromJson(Map<String, dynamic> json) {
    return Local(
      id: json['id_local'] ?? json['id'] ?? 0,
      nome: json['nome'] ?? '',
      descricao: json['descricao'],
    );
  }

  Map<String, dynamic> toJson() {
    return {
      'id_local': id,
      'nome': nome,
      'descricao': descricao,
    };
  }
}
