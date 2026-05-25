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
  });

  // Dados de exemplo
  static List<Chamado> exemplosChamados() {
    return [
      Chamado(
        id: '1',
        tipo: 'Elétrica',
        descricao: 'Tomada em Curto Circuito',
        local: 'Bloco A, Sala 1',
        data: '02/01/2026',
        status: 'Em Andamento',
      ),
      Chamado(
        id: '2',
        tipo: 'Hidráulica',
        descricao: 'Vazamento de Água',
        local: 'Bloco B, Sala 5',
        data: '01/01/2026',
        status: 'Concluído',
      ),
      Chamado(
        id: '3',
        tipo: 'Elétrica',
        descricao: 'Lâmpada Queimada',
        local: 'Corredor 1',
        data: '31/12/2025',
        status: 'Em Andamento',
      ),
      Chamado(
        id: '4',
        tipo: 'Limpeza',
        descricao: 'Limpeza Geral',
        local: 'Bloco C',
        data: '30/12/2025',
        status: 'Pendente',
      ),
    ];
  }
}
