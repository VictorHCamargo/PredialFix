import 'api_service.dart';
import '../models/feedback.dart';

class FeedbackService {
  final ApiService _apiService;

  FeedbackService({required ApiService apiService}) : _apiService = apiService;

  Future<List<Feedback>> getFeedbacks() async {
    try {
      final response = await _apiService.getFeedbacks();
      
      if (response.statusCode == 200) {
        List<Feedback> feedbacks = [];
        final data = response.data;
        
        if (data is List) {
          feedbacks = data.map((item) => Feedback.fromJson(item)).toList();
        } else if (data is Map && data.containsKey('data')) {
          feedbacks = List<Feedback>.from(
            (data['data'] as List).map((item) => Feedback.fromJson(item)),
          );
        }
        
        return feedbacks;
      }
      return [];
    } catch (e) {
      print('Get feedbacks error: $e');
      return [];
    }
  }

  Future<Feedback?> createFeedback({
    required int idChamado,
    required int avaliacao,
    String? comentario,
  }) async {
    try {
      final data = {
        'avaliacao': avaliacao,
        'comentario': comentario,
      };

      final response = await _apiService.createFeedback(idChamado, data);
      
      if (response.statusCode == 201 || response.statusCode == 200) {
        final responseData = response.data;
        if (responseData is Map) {
          return Feedback.fromJson(responseData.containsKey('data') ? responseData['data'] : responseData);
        }
      }
      return null;
    } catch (e) {
      print('Create feedback error: $e');
      return null;
    }
  }
}
