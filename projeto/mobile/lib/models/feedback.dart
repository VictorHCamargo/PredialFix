class Feedback {
  final int id;
  final int idChamado;
  final int idUsuario;
  final int classificacao;
  final String? comentario;
  final DateTime? dataCriacao;

  Feedback({
    required this.id,
    required this.idChamado,
    required this.idUsuario,
    required this.classificacao,
    this.comentario,
    this.dataCriacao,
  });

  factory Feedback.fromJson(Map<String, dynamic> json) {
    return Feedback(
      id: json['id'] as int,
      idChamado: json['id_chamado'] as int,
      idUsuario: json['id_usuario'] as int,
      classificacao: json['classificacao'] as int,
      comentario: json['comentario'] as String?,
      dataCriacao: json['data_criacao'] != null
          ? DateTime.parse(json['data_criacao'] as String)
          : null,
    );
  }

  Map<String, dynamic> toJson() {
    return {
      'id': id,
      'id_chamado': idChamado,
      'id_usuario': idUsuario,
      'classificacao': classificacao,
      'comentario': comentario,
      'data_criacao': dataCriacao?.toIso8601String(),
    };
  }

  @override
  String toString() =>
      'Feedback(id: $id, idChamado: $idChamado, classificacao: $classificacao)';
}
