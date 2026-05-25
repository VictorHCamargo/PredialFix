import 'package:dio/dio.dart';

class ApiService {
  // Configure com o IP do seu servidor Laravel
  // Para emulador Android: http://10.0.2.2:8000
  // Para físico: http://<seu-ip>:8000
  static const String baseUrl = 'http://10.0.2.2:8000/api';
  
  late Dio _dio;
  String? _token;

  ApiService() {
    _dio = Dio(
      BaseOptions(
        baseUrl: baseUrl,
        connectTimeout: const Duration(seconds: 30),
        receiveTimeout: const Duration(seconds: 30),
      ),
    );

    // Interceptor para adicionar token em todos os requests
    _dio.interceptors.add(
      InterceptorsWrapper(
        onRequest: (options, handler) {
          if (_token != null) {
            options.headers['Authorization'] = 'Bearer $_token';
          }
          options.headers['Accept'] = 'application/json';
          options.headers['Content-Type'] = 'application/json';
          return handler.next(options);
        },
        onError: (error, handler) {
          print('Dio Error: ${error.message}');
          return handler.next(error);
        },
      ),
    );
  }

  void setToken(String token) {
    _token = token;
  }

  void clearToken() {
    _token = null;
  }

  String? getToken() {
    return _token;
  }

  Dio getDio() {
    return _dio;
  }

  // AUTH ENDPOINTS
  Future<Response> login(String email, String password) async {
    try {
      final response = await _dio.post(
        '/login',
        data: {
          'email': email,
          'password': password,
        },
      );
      return response;
    } on DioException catch (e) {
      throw _handleError(e);
    }
  }

  Future<Response> register(String nome, String email, String password, String passwordConfirmation) async {
    try {
      final response = await _dio.post(
        '/register',
        data: {
          'nome': nome,
          'email': email,
          'password': password,
          'password_confirmation': passwordConfirmation,
        },
      );
      return response;
    } on DioException catch (e) {
      throw _handleError(e);
    }
  }

  Future<Response> logout() async {
    try {
      final response = await _dio.post('/logout');
      clearToken();
      return response;
    } on DioException catch (e) {
      throw _handleError(e);
    }
  }

  // USER ENDPOINTS
  Future<Response> getCurrentUser() async {
    try {
      final response = await _dio.get('/profile');
      return response;
    } on DioException catch (e) {
      throw _handleError(e);
    }
  }

  Future<Response> updateProfile(Map<String, dynamic> data) async {
    try {
      final response = await _dio.patch('/profile', data: data);
      return response;
    } on DioException catch (e) {
      throw _handleError(e);
    }
  }

  Future<Response> updatePassword(String currentPassword, String newPassword, String newPasswordConfirmation) async {
    try {
      final response = await _dio.patch(
        '/profile/password',
        data: {
          'current_password': currentPassword,
          'password': newPassword,
          'password_confirmation': newPasswordConfirmation,
        },
      );
      return response;
    } on DioException catch (e) {
      throw _handleError(e);
    }
  }

  // CHAMADO ENDPOINTS
  Future<Response> getChamados() async {
    try {
      final response = await _dio.get('/chamados');
      return response;
    } on DioException catch (e) {
      throw _handleError(e);
    }
  }

  Future<Response> getChamado(int id) async {
    try {
      final response = await _dio.get('/chamados/$id');
      return response;
    } on DioException catch (e) {
      throw _handleError(e);
    }
  }

  Future<Response> createChamado(Map<String, dynamic> data) async {
    try {
      final response = await _dio.post('/chamados', data: data);
      return response;
    } on DioException catch (e) {
      throw _handleError(e);
    }
  }

  Future<Response> updateChamado(int id, Map<String, dynamic> data) async {
    try {
      final response = await _dio.put('/chamados/$id', data: data);
      return response;
    } on DioException catch (e) {
      throw _handleError(e);
    }
  }

  Future<Response> deleteChamado(int id) async {
    try {
      final response = await _dio.delete('/chamados/$id');
      return response;
    } on DioException catch (e) {
      throw _handleError(e);
    }
  }

  // FEEDBACK ENDPOINTS
  Future<Response> getFeedbacks() async {
    try {
      final response = await _dio.get('/avaliar');
      return response;
    } on DioException catch (e) {
      throw _handleError(e);
    }
  }

  Future<Response> createFeedback(int idChamado, Map<String, dynamic> data) async {
    try {
      final response = await _dio.post('/avaliar', data: {...data, 'id_chamado': idChamado});
      return response;
    } on DioException catch (e) {
      throw _handleError(e);
    }
  }

  // HELPER METHODS
  String _handleError(DioException error) {
    if (error.response?.statusCode == 401) {
      return 'Não autenticado. Faça login novamente.';
    } else if (error.response?.statusCode == 422) {
      final errors = error.response?.data['errors'];
      if (errors is Map) {
        return errors.values.first.toString();
      }
      return 'Erro de validação';
    } else if (error.response?.statusCode == 404) {
      return 'Recurso não encontrado';
    } else if (error.type == DioExceptionType.connectionTimeout) {
      return 'Tempo limite de conexão excedido';
    } else if (error.type == DioExceptionType.unknown) {
      return 'Erro de conexão. Verifique sua internet.';
    }
    return error.message ?? 'Erro desconhecido';
  }
}
