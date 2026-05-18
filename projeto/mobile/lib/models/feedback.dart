import 'user.dart';
import 'chamado.dart';

class Feedback {
  final int id;
  final int idChamado;
  final int idUsuario;
  final int avaliacao;
  final String? comentario;
  final DateTime? criadoEm;
  final DateTime? atualizadoEm;
  final Chamado? chamado;
  final User? usuario;

  Feedback({
    required this.id,
    required this.idChamado,
    required this.idUsuario,
    required this.avaliacao,
    this.comentario,
    this.criadoEm,
    this.atualizadoEm,
    this.chamado,
    this.usuario,
  });

  factory Feedback.fromJson(Map<String, dynamic> json) {
    return Feedback(
      id: json['id_feedback'] ?? json['id'] ?? 0,
      idChamado: json['id_chamado'] ?? 0,
      idUsuario: json['id_usuario'] ?? 0,
      avaliacao: json['avaliacao'] ?? 0,
      comentario: json['comentario'],
      criadoEm: json['created_at'] != null ? DateTime.parse(json['created_at']) : null,
      atualizadoEm: json['updated_at'] != null ? DateTime.parse(json['updated_at']) : null,
      chamado: json['chamado'] != null ? Chamado.fromJson(json['chamado']) : null,
      usuario: json['usuario'] != null ? User.fromJson(json['usuario']) : null,
    );
  }

  Map<String, dynamic> toJson() {
    return {
      'id_feedback': id,
      'id_chamado': idChamado,
      'id_usuario': idUsuario,
      'avaliacao': avaliacao,
      'comentario': comentario,
      'created_at': criadoEm?.toIso8601String(),
      'updated_at': atualizadoEm?.toIso8601String(),
    };
  }
}
