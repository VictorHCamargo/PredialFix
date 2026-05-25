import 'api_service.dart';
import '../models/local.dart';
import '../models/tipo_problema.dart';

class ReferenceService {
  final ApiService _apiService;

  ReferenceService({required ApiService apiService}) : _apiService = apiService;

  Future<List<Local>> getLocais() async {
    try {
      final response = await _apiService.getDio().get('/locais');
      
      if (response.statusCode == 200) {
        List<Local> locais = [];
        final data = response.data;
        
        if (data is List) {
          locais = data.map((item) => Local.fromJson(item)).toList();
        } else if (data is Map && data.containsKey('data')) {
          locais = List<Local>.from(
            (data['data'] as List).map((item) => Local.fromJson(item)),
          );
        }
        
        return locais;
      }
      return [];
    } catch (e) {
      print('Get locais error: $e');
      return [];
    }
  }

  Future<List<TipoProblema>> getTiposProblema() async {
    try {
      final response = await _apiService.getDio().get('/tipos-problema');
      
      if (response.statusCode == 200) {
        List<TipoProblema> tipos = [];
        final data = response.data;
        
        if (data is List) {
          tipos = data.map((item) => TipoProblema.fromJson(item)).toList();
        } else if (data is Map && data.containsKey('data')) {
          tipos = List<TipoProblema>.from(
            (data['data'] as List).map((item) => TipoProblema.fromJson(item)),
          );
        }
        
        return tipos;
      }
      return [];
    } catch (e) {
      print('Get tipos problema error: $e');
      return [];
    }
  }
}
