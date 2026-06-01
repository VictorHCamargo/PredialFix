import 'api_service.dart';
import '../models/orcamento.dart';

class OrcamentoService {
  final ApiService _apiService;

  OrcamentoService({required ApiService apiService}) : _apiService = apiService;

  Future<List<Orcamento>> getOrcamentos() async {
<<<<<<< HEAD
    final response = await _apiService.getOrcamentos();
    return response.map((item) => Orcamento.fromJson(item)).toList();
=======
    try {
      final response = await _apiService.getOrcamentos();
      return response.map((item) => Orcamento.fromJson(item)).toList();
    } catch (_) {
      return [];
    }
>>>>>>> b9a8ab59d7a16e74e76cf2281ee20cddd6f3568e
  }

  Future<Orcamento?> createOrcamento({
    required int idChamado,
    required double valor,
    required String descricao,
  }) async {
    try {
      final data = {
        'id_chamado': idChamado,
        'valor': valor,
        'descricao': descricao,
      };

      final response = await _apiService.createOrcamento(data);
      return Orcamento.fromJson(response);
    } catch (_) {
      return null;
    }
  }

  Future<Orcamento?> approveOrcamento(int id) async {
    try {
      final response = await _apiService.approveOrcamento(id);
      return Orcamento.fromJson(response);
    } catch (_) {
      return null;
    }
  }
}
