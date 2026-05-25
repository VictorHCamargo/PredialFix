class TipoProblema {
  final int id;
  final String nome;
  final String? descricao;

  TipoProblema({
    required this.id,
    required this.nome,
    this.descricao,
  });

  factory TipoProblema.fromJson(Map<String, dynamic> json) {
    return TipoProblema(
      id: json['id_tipo'] ?? json['id'] ?? 0,
      nome: json['nome'] ?? '',
      descricao: json['descricao'],
    );
  }

  Map<String, dynamic> toJson() {
    return {
      'id_tipo': id,
      'nome': nome,
      'descricao': descricao,
    };
  }
}
