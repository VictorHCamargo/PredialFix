import 'api_service.dart';
import '../models/equipamento.dart';

class EquipamentoService {
  final ApiService _apiService;

  EquipamentoService({required ApiService apiService}) : _apiService = apiService;

  Future<List<Equipamento>> getEquipamentos() async {
    try {
      final response = await _apiService.getEquipamentos();
      return response.map((item) => Equipamento.fromJson(item)).toList();
    } catch (e) {
      print('Get equipamentos error: $e');
      return [];
    }
  }

  Future<Equipamento?> createEquipamento({
    required String tagIdentificacao,
    required String nome,
    required String marca,
    String status = 'ativo',
  }) async {
    try {
      final data = {
        'tag_identificacao': tagIdentificacao,
        'nome': nome,
        'marca': marca,
        'status': status,
      };

      final response = await _apiService.createEquipamento(data);
      return Equipamento.fromJson(response);
    } catch (e) {
      print('Create equipamento error: $e');
      return null;
    }
  }

  Future<Equipamento?> updateEquipamento(
    int id, {
    required String tagIdentificacao,
    required String nome,
    required String marca,
    required String status,
  }) async {
    try {
      final data = {
        'tag_identificacao': tagIdentificacao,
        'nome': nome,
        'marca': marca,
        'status': status,
      };

      final response = await _apiService.updateEquipamento(id, data);
      return Equipamento.fromJson(response);
    } catch (e) {
      print('Update equipamento error: $e');
      return null;
    }
  }

  Future<bool> deleteEquipamento(int id) async {
    try {
      await _apiService.deleteEquipamento(id);
      return true;
    } catch (e) {
      print('Delete equipamento error: $e');
      return false;
    }
  }
}
