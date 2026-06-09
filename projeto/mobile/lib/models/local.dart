class Local {
  final int id;
  final String nome;
  final String descricao;
  final String? bloco;
  final int? andar;
  final DateTime? dataCriacao;

  Local({
    required this.id,
    required this.nome,
    required this.descricao,
    this.bloco,
    this.andar,
    this.dataCriacao,
  });

  factory Local.fromJson(Map<String, dynamic> json) {
    return Local(
      id: (json['id'] ?? json['id_local']) as int,
      nome: (json['nome'] ?? json['sala_setor']) as String,
      descricao: (json['descricao'] ?? json['sala_setor']) as String,
      bloco: json['bloco'] as String?,
      andar: json['andar'] as int?,
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
      'bloco': bloco,
      'andar': andar,
      'data_criacao': dataCriacao?.toIso8601String(),
    };
  }

  @override
  String toString() => nome;
}
