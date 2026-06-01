import '../models/user.dart';
import 'api_service.dart';
import 'storage_service.dart';

class AuthService {
  final ApiService _apiService;
  final StorageService _storageService;

  AuthService({
    required ApiService apiService,
    required StorageService storageService,
  }) : _apiService = apiService,
       _storageService = storageService;

  Future<bool> login(String email, String password) async {
    try {
      final response = await _apiService.login(email, password);

      final token = response['token'] ?? response['access_token'];
      final userData = response['user'];

      if (token != null) {
        _apiService.setToken(token);
        await _storageService.setToken(token);

        if (userData != null) {
          final user = User.fromJson(
            Map<String, dynamic>.from(userData is Map ? userData : {}),
          );
          await _storageService.setUser(user);
        }

        return true;
      }
      return false;
    } catch (_) {
      return false;
    }
  }

  Future<bool> register(
    String nome,
    String email,
    String password,
    String passwordConfirmation,
  ) async {
    try {
      final response = await _apiService.register(
        nome,
        email,
        password,
        passwordConfirmation,
      );

      // Se o backend faz login automático após registro
      final token = response['token'] ?? response['access_token'];
      if (token != null) {
        _apiService.setToken(token);
        await _storageService.setToken(token);

        final userData = response['user'];
        if (userData != null) {
          final user = User.fromJson(
            Map<String, dynamic>.from(userData is Map ? userData : {}),
          );
          await _storageService.setUser(user);
        }
      }
      return true;
    } catch (_) {
      return false;
    }
  }

  Future<void> logout() async {
    try {
      await _apiService.logout();
    } catch (_) {
    } finally {
      _apiService.clearToken();
      await _storageService.clearAll();
    }
  }

  Future<bool> restoreSession() async {
    try {
      final token = await _storageService.getToken();
      if (token != null) {
        _apiService.setToken(token);

        final response = await _apiService.getCurrentUser();
        final userData = response['user'] ?? response;
        final user = User.fromJson(
          Map<String, dynamic>.from(userData is Map ? userData : {}),
        );
        await _storageService.setUser(user);
        return true;
      }
      return false;
    } catch (_) {
      return false;
    }
  }

  Future<User?> getCurrentUser() async {
    return await _storageService.getUser();
  }

  bool isAuthenticated() {
    return _apiService.getToken() != null;
  }
}
