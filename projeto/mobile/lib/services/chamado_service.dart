import 'api_service.dart';
import '../models/chamado.dart';

class ChamadoService {
  final ApiService _apiService;

  ChamadoService({required ApiService apiService}) : _apiService = apiService;

  Future<List<Chamado>> getChamados() async {
<<<<<<< HEAD
    final response = await _apiService.getChamados();

    // response is List<Map<String, dynamic>>
    return response.map((item) => Chamado.fromJson(item)).toList();
=======
    try {
      final response = await _apiService.getChamados();
      
      // response is List<Map<String, dynamic>>
      return response.map((item) => Chamado.fromJson(item)).toList();
    } catch (_) {
      return [];
    }
>>>>>>> b9a8ab59d7a16e74e76cf2281ee20cddd6f3568e
  }

  Future<Chamado?> getChamado(int id) async {
    try {
      final response = await _apiService.getChamado(id);

      // response is Map<String, dynamic>
      // Handle both direct data and nested 'data' key
<<<<<<< HEAD
      return Chamado.fromJson(
        response.containsKey('data') ? response['data'] : response,
      );
=======
      return Chamado.fromJson(response.containsKey('data') ? response['data'] : response);
>>>>>>> b9a8ab59d7a16e74e76cf2281ee20cddd6f3568e
    } catch (_) {
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
<<<<<<< HEAD
      return Chamado.fromJson(
        response.containsKey('data') ? response['data'] : response,
      );
=======
      return Chamado.fromJson(response.containsKey('data') ? response['data'] : response);
>>>>>>> b9a8ab59d7a16e74e76cf2281ee20cddd6f3568e
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
<<<<<<< HEAD
      return Chamado.fromJson(
        response.containsKey('data') ? response['data'] : response,
      );
=======
      return Chamado.fromJson(response.containsKey('data') ? response['data'] : response);
>>>>>>> b9a8ab59d7a16e74e76cf2281ee20cddd6f3568e
    } catch (_) {
      return null;
    }
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
