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
  final DateTime dataAbertura;
  final DateTime? dataFechamento;
  final DateTime? dataPrazo;
  final String? nomeTecnicoResponsavel;
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
    DateTime? dataAbertura,
    this.dataFechamento,
    this.dataPrazo,
    this.nomeTecnicoResponsavel,
    this.usuario,
    this.local,
    this.tipoProblema,
    this.historico,
    this.feedback,
  }) : dataAbertura = dataAbertura ?? DateTime.now();

  // Getter para exibir o status de forma legível
  String get displayStatus {
    switch (status.toLowerCase()) {
      case 'pendente':
        return 'Pendente';
      case 'em andamento':
      case 'em_andamento':
        return 'Em Andamento';
      case 'concluído':
      case 'concluido':
        return 'Concluído';
      case 'cancelado':
        return 'Cancelado';
      default:
        return status;
    }
  }

  // Getter para exibir a prioridade de forma legível
  String get displayPrioridade {
    if (prioridade == null) return 'Não definida';
    switch (prioridade!.toLowerCase()) {
      case 'alta':
        return 'Alta';
      case 'média':
      case 'media':
        return 'Média';
      case 'baixa':
        return 'Baixa';
      default:
        return prioridade!;
    }
  }

  factory Chamado.fromJson(Map<String, dynamic> json) {
    return Chamado(
      id: (json['id'] ?? json['id_chamado']) as int,
      idUsuario: json['id_usuario'] as int,
      descricao: json['descricao'] as String,
      idLocal: json['id_local'] as int,
      idTipo: json['id_tipo'] as int,
      status: json['status'] as String,
      prioridade: json['prioridade'] as String?,
      dataAbertura: json['data_abertura'] != null
          ? DateTime.parse(json['data_abertura'] as String)
          : null,
      dataFechamento:
          (json['data_fechamento'] ?? json['data_conclusao']) != null
          ? DateTime.parse(
              (json['data_fechamento'] ?? json['data_conclusao']) as String,
            )
          : null,
      dataPrazo: json['data_prazo'] != null
          ? DateTime.parse(json['data_prazo'] as String)
          : null,
      nomeTecnicoResponsavel: json['nome_tecnico_responsavel'] as String?,
      usuario: json['usuario'] != null
          ? User.fromJson(json['usuario'] as Map<String, dynamic>)
          : null,
      local: json['local'] != null
          ? Local.fromJson(json['local'] as Map<String, dynamic>)
          : null,
      tipoProblema: (json['tipo_problema'] ?? json['tipoProblema']) != null
          ? TipoProblema.fromJson(
              (json['tipo_problema'] ?? json['tipoProblema'])
                  as Map<String, dynamic>,
            )
          : null,
    );
  }

  Map<String, dynamic> toJson() {
    return {
      'id': id,
      'id_usuario': idUsuario,
      'descricao': descricao,
      'id_local': idLocal,
      'id_tipo': idTipo,
      'status': status,
      'prioridade': prioridade,
      'data_abertura': dataAbertura.toIso8601String(),
      'data_fechamento': dataFechamento?.toIso8601String(),
      'data_prazo': dataPrazo?.toIso8601String(),
      'nome_tecnico_responsavel': nomeTecnicoResponsavel,
      'usuario': usuario?.toJson(),
      'local': local?.toJson(),
      'tipo_problema': tipoProblema?.toJson(),
    };
  }

  // Dados de exemplo
  static List<Chamado> exemplosChamados() {
    return [
      Chamado(
        id: 1,
        idUsuario: 1,
        descricao: 'Tomada em Curto Circuito',
        idLocal: 1,
        idTipo: 1,
        status: 'Em Andamento',
        prioridade: 'Alta',
        dataAbertura: DateTime(2026, 1, 2),
      ),
      Chamado(
        id: 2,
        idUsuario: 2,
        descricao: 'Vazamento de Água',
        idLocal: 2,
        idTipo: 2,
        status: 'Concluído',
        prioridade: 'Alta',
        dataAbertura: DateTime(2026, 1, 1),
      ),
      Chamado(
        id: 3,
        idUsuario: 1,
        descricao: 'Lâmpada Queimada',
        idLocal: 3,
        idTipo: 1,
        status: 'Em Andamento',
        prioridade: 'Média',
        dataAbertura: DateTime(2025, 12, 31),
      ),
      Chamado(
        id: 4,
        idUsuario: 3,
        descricao: 'Limpeza Geral',
        idLocal: 4,
        idTipo: 3,
        status: 'Pendente',
        prioridade: 'Baixa',
        dataAbertura: DateTime(2025, 12, 30),
      ),
    ];
  }
}
