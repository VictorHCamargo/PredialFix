import 'api_service.dart';
import '../models/chamado.dart';
import '../models/historico_status_chamado.dart';

class ChamadoService {
  final ApiService _apiService;

  ChamadoService({required ApiService apiService}) : _apiService = apiService;

  Future<List<Chamado>> getChamados() async {
    final response = await _apiService.getChamados();

    // response is List<Map<String, dynamic>>
    return response.map((item) => Chamado.fromJson(item)).toList();
  }

  Future<Chamado?> getChamado(int id) async {
    try {
      final response = await _apiService.getChamado(id);

      // response is Map<String, dynamic>
      // Handle both direct data and nested 'data' key
      return Chamado.fromJson(
        response.containsKey('data') ? response['data'] : response,
      );
    } catch (_) {
      return null;
    }
  }

  Future<Chamado?> createChamado({
    required String descricao,
    required int idLocal,
    required int idTipo,
    int? idEquipamento,
    String? tipoChamado,
    String? prioridade,
    String? secaoTecnica,
    String? complexidade,
    String? tipoTrabalho,
  }) async {
    try {
      final data = {
        'descricao': descricao,
        'id_local': idLocal,
        'id_tipo': idTipo,
        'id_equipamento': idEquipamento,
        'tipo_chamado': tipoChamado ?? 'interno',
        'prioridade': prioridade ?? 'baixa',
        'secao_tecnica': secaoTecnica,
        'complexidade': complexidade,
        'tipo_trabalho': tipoTrabalho,
      };

      final response = await _apiService.createChamado(data);

      // response is Map<String, dynamic>
      return Chamado.fromJson(
        response.containsKey('data') ? response['data'] : response,
      );
    } catch (_) {
      return null;
    }
  }

  Future<Chamado?> updateChamado(
    int id, {
    required String descricao,
    required int idLocal,
    required int idTipo,
    int? idEquipamento,
    String? tipoChamado,
    String? secaoTecnica,
    String? complexidade,
    String? tipoTrabalho,
  }) async {
    try {
      final data = {
        'descricao': descricao,
        'id_local': idLocal,
        'id_tipo': idTipo,
        'id_equipamento': idEquipamento,
        'tipo_chamado': tipoChamado,
        'secao_tecnica': secaoTecnica,
        'complexidade': complexidade,
        'tipo_trabalho': tipoTrabalho,
      };

      final response = await _apiService.updateChamado(id, data);

      // response is Map<String, dynamic>
      return Chamado.fromJson(
        response.containsKey('data') ? response['data'] : response,
      );
    } catch (_) {
      return null;
    }
  }

  Future<Chamado?> updateStatus(
    int id, {
    required String status,
    String? descricao,
    String? prioridade,
  }) async {
    try {
      final chamadoAtual = await _apiService.getChamado(id);
      final response = await _apiService.updateChamado(id, {
        'status': status,
        if (prioridade != null) 'prioridade': prioridade,
      });

      try {
        final descricaoHistorico = descricao?.trim();
        await _apiService.addHistorico({
          'id_chamado': id,
          'status_anterior': chamadoAtual['status'],
          'status_novo': status,
          'descricao': descricaoHistorico == null || descricaoHistorico.isEmpty
              ? 'Status atualizado pelo aplicativo mobile'
              : descricaoHistorico,
          'prioridade': prioridade ?? chamadoAtual['prioridade'],
        });
      } catch (_) {}

      return Chamado.fromJson(
        response.containsKey('data') ? response['data'] : response,
      );
    } catch (_) {
      return null;
    }
  }

  Future<List<HistoricoStatusChamado>> getHistorico(int idChamado) async {
    final response = await _apiService.getHistorico(idChamado);
    return response
        .map((item) => HistoricoStatusChamado.fromJson(item))
        .toList();
  }

  Future<bool> deleteChamado(int id) async {
    try {
      await _apiService.deleteChamado(id);
      return true;
    } catch (_) {
      return false;
    }
  }
}
