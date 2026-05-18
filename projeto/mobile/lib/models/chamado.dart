import 'user.dart';
import 'local.dart';
import 'tipo_problema.dart';

class Chamado {
  final int id;
  final int idUsuario;
  final String descricao;
  final int idLocal;
  final int idTipo;
  final String status;
  final String? prioridade;
  final DateTime? dataAbertura;
  final DateTime? dataFechamento;
  final DateTime? dataPrazo;
  final User? usuario;
  final Local? local;
  final TipoProblema? tipoProblema;
  final List<dynamic>? historico;
  final List<dynamic>? feedback;

  Chamado({
    required this.id,
    required this.idUsuario,
    required this.descricao,
    required this.idLocal,
    required this.idTipo,
    required this.status,
    this.prioridade,
    this.dataAbertura,
    this.dataFechamento,
    this.dataPrazo,
    this.usuario,
    this.local,
    this.tipoProblema,
    this.historico,
    this.feedback,
  });

  factory Chamado.fromJson(Map<String, dynamic> json) {
    return Chamado(
      id: json['id_chamado'] ?? json['id'] ?? 0,
      idUsuario: json['id_usuario'] ?? 0,
      descricao: json['descricao'] ?? '',
      idLocal: json['id_local'] ?? 0,
      idTipo: json['id_tipo'] ?? 0,
      status: json['status'] ?? 'pendente',
      prioridade: json['prioridade'],
      dataAbertura: json['data_abertura'] != null ? DateTime.parse(json['data_abertura']) : null,
      dataFechamento: json['data_fechamento'] != null ? DateTime.parse(json['data_fechamento']) : null,
      dataPrazo: json['data_prazo'] != null ? DateTime.parse(json['data_prazo']) : null,
      usuario: json['usuario'] != null ? User.fromJson(json['usuario']) : null,
      local: json['local'] != null ? Local.fromJson(json['local']) : null,
      tipoProblema: json['tipoProblema'] != null ? TipoProblema.fromJson(json['tipoProblema']) : null,
      historico: json['historico'],
      feedback: json['feedback'],
    );
  }

  Map<String, dynamic> toJson() {
    return {
      'id_chamado': id,
      'id_usuario': idUsuario,
      'descricao': descricao,
      'id_local': idLocal,
      'id_tipo': idTipo,
      'status': status,
      'prioridade': prioridade,
      'data_abertura': dataAbertura?.toIso8601String(),
      'data_fechamento': dataFechamento?.toIso8601String(),
      'data_prazo': dataPrazo?.toIso8601String(),
    };
  }

  String get displayStatus {
    switch (status) {
      case 'pendente':
        return 'Pendente';
      case 'em_andamento':
        return 'Em Andamento';
      case 'concluido':
        return 'Concluído';
      case 'cancelado':
        return 'Cancelado';
      default:
        return status;
    }
  }

  String get displayPrioridade {
    switch (prioridade) {
      case 'alta':
        return 'Alta';
      case 'media':
        return 'Média';
      case 'baixa':
        return 'Baixa';
      default:
        return prioridade ?? 'N/A';
    }
  }
}
