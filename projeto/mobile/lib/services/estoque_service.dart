import 'api_service.dart';
import '../models/estoque_interno.dart';

class EstoqueService {
  final ApiService _apiService;

  EstoqueService({required ApiService apiService}) : _apiService = apiService;

  Future<List<EstoqueInterno>> getEstoque() async {
    try {
      final response = await _apiService.getEstoque();
      return response.map((item) => EstoqueInterno.fromJson(item)).toList();
    } catch (e) {
      print('Get estoque error: $e');
      return [];
    }
  }

  Future<EstoqueInterno?> createEstoque({
    required String nomeItem,
    required String descricao,
    required int quantidade,
    required String categoria,
    required String localizacao,
    required double valorUnitario,
    required String codigoPatrimonio,
    String statusItem = 'disponivel',
    String? observacoes,
  }) async {
    try {
      final data = {
        'nome_item': nomeItem,
        'descricao': descricao,
        'quantidade': quantidade,
        'categoria': categoria,
        'localizacao': localizacao,
        'valor_unitario': valorUnitario,
        'codigo_patrimonio': codigoPatrimonio,
        'status_item': statusItem,
        'observacoes': observacoes,
      };

      final response = await _apiService.createEstoque(data);
      return EstoqueInterno.fromJson(response);
    } catch (e) {
      print('Create estoque error: $e');
      return null;
    }
  }

  Future<EstoqueInterno?> updateEstoque(
    int id, {
    required String nomeItem,
    required String descricao,
    required int quantidade,
    required String categoria,
    required String localizacao,
    required double valorUnitario,
    required String codigoPatrimonio,
    required String statusItem,
    String? observacoes,
  }) async {
    try {
      final data = {
        'nome_item': nomeItem,
        'descricao': descricao,
        'quantidade': quantidade,
        'categoria': categoria,
        'localizacao': localizacao,
        'valor_unitario': valorUnitario,
        'codigo_patrimonio': codigoPatrimonio,
        'status_item': statusItem,
        'observacoes': observacoes,
      };

      final response = await _apiService.updateEstoque(id, data);
      return EstoqueInterno.fromJson(response);
    } catch (e) {
      print('Update estoque error: $e');
      return null;
    }
  }

  Future<bool> deleteEstoque(int id) async {
    try {
      await _apiService.deleteEstoque(id);
      return true;
    } catch (e) {
      print('Delete estoque error: $e');
      return false;
    }
  }
}
