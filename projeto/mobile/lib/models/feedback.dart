class Feedback {
  final int id;
  final int idChamado;
  final int idUsuario;
  final int avaliacao;
  final String? comentario;
  final DateTime dataCriacao;

  Feedback({
    required this.id,
    required this.idChamado,
    required this.idUsuario,
    required this.avaliacao,
    this.comentario,
    required this.dataCriacao,
  });

  factory Feedback.fromJson(Map<String, dynamic> json) {
    return Feedback(
      id: json['id_feedback'] ?? json['id'] ?? 0,
      idChamado: json['id_chamado'] ?? 0,
      idUsuario: json['id_usuario'] ?? 0,
      avaliacao: json['avaliacao'] ?? 0,
      comentario: json['comentario'],
      dataCriacao: DateTime.parse(json['created_at'] ?? json['data_criacao'] ?? DateTime.now().toString()),
    );
  }

  Map<String, dynamic> toJson() {
    return {
      'id_chamado': idChamado,
      'id_usuario': idUsuario,
      'avaliacao': avaliacao,
      'comentario': comentario,
    };
  }
}
