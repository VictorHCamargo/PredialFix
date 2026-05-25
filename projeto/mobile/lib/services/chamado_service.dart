import 'api_service.dart';
import '../models/chamado.dart';

class ChamadoService {
  final ApiService _apiService;

  ChamadoService({required ApiService apiService}) : _apiService = apiService;

  Future<List<Chamado>> getChamados() async {
    try {
      final response = await _apiService.getChamados();
      
      // response is List<Map<String, dynamic>>
      return response.map((item) => Chamado.fromJson(item)).toList();
    } catch (e) {
      print('Get chamados error: $e');
      return [];
    }
  }

  Future<Chamado?> getChamado(int id) async {
    try {
      final response = await _apiService.getChamado(id);
      
      // response is Map<String, dynamic>
      // Handle both direct data and nested 'data' key
      return Chamado.fromJson(response.containsKey('data') ? response['data'] : response);
    } catch (e) {
      print('Get chamado error: $e');
      return null;
    }
  }

  Future<Chamado?> createChamado({
    required String descricao,
    required int idLocal,
    required int idTipo,
    int? idEquipamento,
    String? prioridade,
  }) async {
    try {
      final data = {
        'descricao': descricao,
        'id_local': idLocal,
        'id_tipo': idTipo,
        'id_equipamento': idEquipamento,
        'prioridade': prioridade ?? 'baixa',
      };

      final response = await _apiService.createChamado(data);
      
      // response is Map<String, dynamic>
      return Chamado.fromJson(response.containsKey('data') ? response['data'] : response);
    } catch (e) {
      print('Create chamado error: $e');
      return null;
    }
  }

  Future<Chamado?> updateChamado(
    int id, {
    required String descricao,
    required int idLocal,
    required int idTipo,
    int? idEquipamento,
  }) async {
    try {
      final data = {
        'descricao': descricao,
        'id_local': idLocal,
        'id_tipo': idTipo,
        'id_equipamento': idEquipamento,
      };

      final response = await _apiService.updateChamado(id, data);
      
      // response is Map<String, dynamic>
      return Chamado.fromJson(response.containsKey('data') ? response['data'] : response);
    } catch (e) {
      print('Update chamado error: $e');
      return null;
    }
  }

  Future<bool> deleteChamado(int id) async {
    try {
      await _apiService.deleteChamado(id);
      return true;
    } catch (e) {
      print('Delete chamado error: $e');
      return false;
    }
  }
}
