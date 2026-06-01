import 'api_service.dart';
import '../models/feedback.dart';

class FeedbackService {
  final ApiService _apiService;

  FeedbackService({required ApiService apiService}) : _apiService = apiService;

  Future<List<Feedback>> getFeedbacks() async {
    final response = await _apiService.getFeedbacks();

    // response is List<Map<String, dynamic>>
    return response.map((item) => Feedback.fromJson(item)).toList();
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
      return Feedback.fromJson(
        response.containsKey('data') ? response['data'] : response,
      );
    } catch (_) {
      return null;
    }
  }
}
