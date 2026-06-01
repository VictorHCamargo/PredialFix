import 'api_service.dart';
import '../models/feedback.dart';

class FeedbackService {
  final ApiService _apiService;

  FeedbackService({required ApiService apiService}) : _apiService = apiService;

  Future<List<Feedback>> getFeedbacks() async {
<<<<<<< HEAD
    final response = await _apiService.getFeedbacks();

    // response is List<Map<String, dynamic>>
    return response.map((item) => Feedback.fromJson(item)).toList();
=======
    try {
      final response = await _apiService.getFeedbacks();
      
      // response is List<Map<String, dynamic>>
      return response.map((item) => Feedback.fromJson(item)).toList();
    } catch (_) {
      return [];
    }
>>>>>>> b9a8ab59d7a16e74e76cf2281ee20cddd6f3568e
  }

  Future<Feedback?> createFeedback({
    required int idChamado,
    required int avaliacao,
    String? comentario,
  }) async {
    try {
      final data = {'avaliacao': avaliacao, 'comentario': comentario};

      final response = await _apiService.createFeedback(idChamado, data);

      // response is Map<String, dynamic>
<<<<<<< HEAD
      return Feedback.fromJson(
        response.containsKey('data') ? response['data'] : response,
      );
=======
      return Feedback.fromJson(response.containsKey('data') ? response['data'] : response);
>>>>>>> b9a8ab59d7a16e74e76cf2281ee20cddd6f3568e
    } catch (_) {
      return null;
    }
  }
}
