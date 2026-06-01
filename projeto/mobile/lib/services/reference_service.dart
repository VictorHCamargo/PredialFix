import 'api_service.dart';
import '../models/local.dart';
import '../models/tipo_problema.dart';

class ReferenceService {
  final ApiService _apiService;

  ReferenceService({required ApiService apiService}) : _apiService = apiService;

  Future<List<Local>> getLocais() async {
    try {
      final response = await _apiService.getLocais();
      
      // response is List<Map<String, dynamic>>
      return response.map((item) => Local.fromJson(item)).toList();
    } catch (_) {
      return [];
    }
  }

  Future<List<TipoProblema>> getTiposProblema() async {
    try {
      final response = await _apiService.getTiposProblema();
      
      // response is List<Map<String, dynamic>>
      return response.map((item) => TipoProblema.fromJson(item)).toList();
    } catch (_) {
      return [];
    }
  }
}
