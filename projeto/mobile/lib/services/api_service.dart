import 'package:http/http.dart' as http;
import 'dart:convert';
import 'dart:developer' as developer;

class ApiService {
  static const String baseUrl = 'http://localhost:8000/api';
  
  // Você pode configurar um baseUrl diferente
  late String _baseUrl;
  String? _token;

  ApiService({String? baseUrl}) {
    _baseUrl = baseUrl ?? ApiService.baseUrl;
  }

  // Token management
  void setToken(String token) {
    _token = token;
  }

  void clearToken() {
    _token = null;
  }

  String? getToken() {
    return _token;
  }

  Map<String, String> _getHeaders({Map<String, String>? customHeaders}) {
    final headers = {
      'Content-Type': 'application/json',
      'Accept': 'application/json',
      ...?customHeaders,
    };
    
    if (_token != null) {
      headers['Authorization'] = 'Bearer $_token';
    }
    
    return headers;
  }

  // Authentication methods
  Future<Map<String, dynamic>> login(String email, String password) async {
    final response = await post<Map<String, dynamic>>(
      '/auth/login',
      {'email': email, 'password': password},
      (json) => json,
    );
    return response;
  }

  Future<Map<String, dynamic>> register(
    String nome,
    String email,
    String password,
    String passwordConfirmation,
  ) async {
    final response = await post<Map<String, dynamic>>(
      '/auth/register',
      {
        'nome': nome,
        'email': email,
        'password': password,
        'password_confirmation': passwordConfirmation,
      },
      (json) => json,
    );
    return response;
  }

  Future<void> logout() async {
    try {
      await delete('/auth/logout');
    } catch (e) {
      developer.log('Erro ao fazer logout: $e');
    }
  }

  // User methods
  Future<Map<String, dynamic>> getCurrentUser() async {
    return get<Map<String, dynamic>>(
      '/user',
      (json) => json,
      headers: _getHeaders(),
    );
  }

  // Chamado methods
  Future<List<Map<String, dynamic>>> getChamados() async {
    return getList<Map<String, dynamic>>(
      '/chamados',
      (json) => json,
      headers: _getHeaders(),
    );
  }

  Future<Map<String, dynamic>> getChamado(int id) async {
    return get<Map<String, dynamic>>(
      '/chamados/$id',
      (json) => json,
      headers: _getHeaders(),
    );
  }

  Future<Map<String, dynamic>> createChamado(Map<String, dynamic> data) async {
    return post<Map<String, dynamic>>(
      '/chamados',
      data,
      (json) => json,
      headers: _getHeaders(),
    );
  }

  Future<Map<String, dynamic>> updateChamado(int id, Map<String, dynamic> data) async {
    return put<Map<String, dynamic>>(
      '/chamados/$id',
      data,
      (json) => json,
      headers: _getHeaders(),
    );
  }

  Future<void> deleteChamado(int id) async {
    return delete(
      '/chamados/$id',
      headers: _getHeaders(),
    );
  }

  // Feedback methods
  Future<List<Map<String, dynamic>>> getFeedbacks() async {
    return getList<Map<String, dynamic>>(
      '/feedbacks',
      (json) => json,
      headers: _getHeaders(),
    );
  }

  Future<Map<String, dynamic>> createFeedback(int idChamado, Map<String, dynamic> data) async {
    return post<Map<String, dynamic>>(
      '/chamados/$idChamado/feedbacks',
      data,
      (json) => json,
      headers: _getHeaders(),
    );
  }

  // Reference data methods
  Future<List<Map<String, dynamic>>> getLocais() async {
    return getList<Map<String, dynamic>>(
      '/locais',
      (json) => json,
      headers: _getHeaders(),
    );
  }

  Future<List<Map<String, dynamic>>> getTiposProblema() async {
    return getList<Map<String, dynamic>>(
      '/tipos-problema',
      (json) => json,
      headers: _getHeaders(),
    );
  }

  // GET request
  Future<T> get<T>(
    String endpoint,
    T Function(Map<String, dynamic>) fromJson, {
    Map<String, String>? headers,
  }) async {
    try {
      final response = await http.get(
        Uri.parse('$_baseUrl$endpoint'),
        headers: headers ?? _getHeaders(),
      );

      if (response.statusCode == 200) {
        final data = jsonDecode(response.body) as Map<String, dynamic>;
        return fromJson(data);
      } else {
        throw Exception('Erro na requisição: ${response.statusCode}');
      }
    } catch (e) {
      developer.log('Erro em GET $endpoint: $e');
      rethrow;
    }
  }

  // GET list
  Future<List<T>> getList<T>(
    String endpoint,
    T Function(Map<String, dynamic>) fromJson, {
    Map<String, String>? headers,
  }) async {
    try {
      final response = await http.get(
        Uri.parse('$_baseUrl$endpoint'),
        headers: headers ?? _getHeaders(),
      );

      if (response.statusCode == 200) {
        final List<dynamic> data = jsonDecode(response.body);
        return data.map((item) => fromJson(item as Map<String, dynamic>)).toList();
      } else {
        throw Exception('Erro na requisição: ${response.statusCode}');
      }
    } catch (e) {
      developer.log('Erro em GET $endpoint: $e');
      rethrow;
    }
  }

  // POST request
  Future<T> post<T>(
    String endpoint,
    dynamic body,
    T Function(Map<String, dynamic>) fromJson, {
    Map<String, String>? headers,
  }) async {
    try {
      final response = await http.post(
        Uri.parse('$_baseUrl$endpoint'),
        headers: headers ?? _getHeaders(),
        body: jsonEncode(body),
      );

      if (response.statusCode == 200 || response.statusCode == 201) {
        final data = jsonDecode(response.body) as Map<String, dynamic>;
        return fromJson(data);
      } else {
        throw Exception('Erro na requisição: ${response.statusCode}');
      }
    } catch (e) {
      developer.log('Erro em POST $endpoint: $e');
      rethrow;
    }
  }

  // PUT request
  Future<T> put<T>(
    String endpoint,
    dynamic body,
    T Function(Map<String, dynamic>) fromJson, {
    Map<String, String>? headers,
  }) async {
    try {
      final response = await http.put(
        Uri.parse('$_baseUrl$endpoint'),
        headers: headers ?? _getHeaders(),
        body: jsonEncode(body),
      );

      if (response.statusCode == 200) {
        final data = jsonDecode(response.body) as Map<String, dynamic>;
        return fromJson(data);
      } else {
        throw Exception('Erro na requisição: ${response.statusCode}');
      }
    } catch (e) {
      developer.log('Erro em PUT $endpoint: $e');
      rethrow;
    }
  }

  // DELETE request
  Future<void> delete(
    String endpoint, {
    Map<String, String>? headers,
  }) async {
    try {
      final response = await http.delete(
        Uri.parse('$_baseUrl$endpoint'),
        headers: headers ?? _getHeaders(),
      );

      if (response.statusCode != 200 && response.statusCode != 204) {
        throw Exception('Erro na requisição: ${response.statusCode}');
      }
    } catch (e) {
      developer.log('Erro em DELETE $endpoint: $e');
      rethrow;
    }
  }
}
