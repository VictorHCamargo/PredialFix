class TipoProblema {
  final int id;
  final String nome;
  final String descricao;
  final String? categoria;
  final DateTime? dataCriacao;

  TipoProblema({
    required this.id,
    required this.nome,
    required this.descricao,
    this.categoria,
    this.dataCriacao,
  });

  factory TipoProblema.fromJson(Map<String, dynamic> json) {
    return TipoProblema(
      id: json['id'] as int,
      nome: json['nome'] as String,
      descricao: json['descricao'] as String,
      categoria: json['categoria'] as String?,
      dataCriacao: json['data_criacao'] != null 
        ? DateTime.parse(json['data_criacao'] as String)
        : null,
    );
  }

  Map<String, dynamic> toJson() {
    return {
      'id': id,
      'nome': nome,
      'descricao': descricao,
      'categoria': categoria,
      'data_criacao': dataCriacao?.toIso8601String(),
    };
  }

  @override
  String toString() => nome;
}
