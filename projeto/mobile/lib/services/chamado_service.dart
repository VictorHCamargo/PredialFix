import 'api_service.dart';
import '../models/chamado.dart';

class ChamadoService {
  final ApiService _apiService;

  ChamadoService({required ApiService apiService}) : _apiService = apiService;

  Future<List<Chamado>> getChamados() async {
    try {
      final response = await _apiService.getChamados();
      
      if (response.statusCode == 200) {
        List<Chamado> chamados = [];
        final data = response.data;
        
        if (data is List) {
          chamados = data.map((item) => Chamado.fromJson(item)).toList();
        } else if (data is Map && data.containsKey('data')) {
          chamados = List<Chamado>.from(
            (data['data'] as List).map((item) => Chamado.fromJson(item)),
          );
        }
        
        return chamados;
      }
      return [];
    } catch (e) {
      print('Get chamados error: $e');
      return [];
    }
  }

  Future<Chamado?> getChamado(int id) async {
    try {
      final response = await _apiService.getChamado(id);
      
      if (response.statusCode == 200) {
        final data = response.data;
        if (data is Map) {
          return Chamado.fromJson(data.containsKey('data') ? data['data'] : data);
        }
      }
      return null;
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
      
      if (response.statusCode == 201 || response.statusCode == 200) {
        final responseData = response.data;
        if (responseData is Map) {
          return Chamado.fromJson(responseData.containsKey('data') ? responseData['data'] : responseData);
        }
      }
      return null;
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
      
      if (response.statusCode == 200) {
        final responseData = response.data;
        if (responseData is Map) {
          return Chamado.fromJson(responseData.containsKey('data') ? responseData['data'] : responseData);
        }
      }
      return null;
    } catch (e) {
      print('Update chamado error: $e');
      return null;
    }
  }

  Future<bool> deleteChamado(int id) async {
    try {
      final response = await _apiService.deleteChamado(id);
      return response.statusCode == 200 || response.statusCode == 204;
    } catch (e) {
      print('Delete chamado error: $e');
      return false;
    }
  }
}
