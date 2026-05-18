class Local {
  final int id;
  final String nome;
  final String? descricao;
  final bool ativo;

  Local({
    required this.id,
    required this.nome,
    this.descricao,
    required this.ativo,
  });

  factory Local.fromJson(Map<String, dynamic> json) {
    return Local(
      id: json['id_local'] ?? json['id'] ?? 0,
      nome: json['nome'] ?? '',
      descricao: json['descricao'],
      ativo: json['ativo'] ?? true,
    );
  }

  Map<String, dynamic> toJson() {
    return {
      'id_local': id,
      'nome': nome,
      'descricao': descricao,
      'ativo': ativo,
    };
  }
}
