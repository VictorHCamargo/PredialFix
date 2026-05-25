class HistoricoStatusChamado {
  final int id;
  final int idChamado;
  final String statusAnterior;
  final String statusNovo;
  final String descricao;
  final int idUsuario;
  final String? prioridade;
  final DateTime dataMudanca;

  HistoricoStatusChamado({
    required this.id,
    required this.idChamado,
    required this.statusAnterior,
    required this.statusNovo,
    required this.descricao,
    required this.idUsuario,
    this.prioridade,
    required this.dataMudanca,
  });

  factory HistoricoStatusChamado.fromJson(Map<String, dynamic> json) {
    return HistoricoStatusChamado(
      id: json['id'] as int,
      idChamado: json['id_chamado'] as int,
      statusAnterior: json['status_anterior'] as String,
      statusNovo: json['status_novo'] as String,
      descricao: json['descricao'] as String,
      idUsuario: json['id_usuario'] as int,
      prioridade: json['prioridade'] as String?,
      dataMudanca: DateTime.parse(json['data_mudanca'] as String),
    );
  }

  Map<String, dynamic> toJson() {
    return {
      'id': id,
      'id_chamado': idChamado,
      'status_anterior': statusAnterior,
      'status_novo': statusNovo,
      'descricao': descricao,
      'id_usuario': idUsuario,
      'prioridade': prioridade,
      'data_mudanca': dataMudanca.toIso8601String(),
    };
  }

  @override
  String toString() =>
      '$statusAnterior → $statusNovo (${dataMudanca.toString()})';
}
