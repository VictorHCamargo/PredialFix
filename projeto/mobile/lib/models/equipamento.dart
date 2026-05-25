class Equipamento {
  final int id;
  final String tagIdentificacao;
  final String nome;
  final String marca;
  final String status; // ativo, manutencao, inativo

  Equipamento({
    required this.id,
    required this.tagIdentificacao,
    required this.nome,
    required this.marca,
    required this.status,
  });

  factory Equipamento.fromJson(Map<String, dynamic> json) {
    return Equipamento(
      id: json['id'] as int,
      tagIdentificacao: json['tag_identificacao'] as String,
      nome: json['nome'] as String,
      marca: json['marca'] as String,
      status: json['status'] as String,
    );
  }

  Map<String, dynamic> toJson() {
    return {
      'id': id,
      'tag_identificacao': tagIdentificacao,
      'nome': nome,
      'marca': marca,
      'status': status,
    };
  }

  @override
  String toString() => '$nome ($tagIdentificacao)';
}
