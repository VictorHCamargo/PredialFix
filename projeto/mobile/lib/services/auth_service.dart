import 'package:shared_preferences/shared_preferences.dart';
import '../models/user.dart';
import 'api_service.dart';
import 'storage_service.dart';

class AuthService {
  final ApiService _apiService;
  final StorageService _storageService;

  AuthService({required ApiService apiService, required StorageService storageService})
      : _apiService = apiService,
        _storageService = storageService;

  Future<bool> login(String email, String password) async {
    try {
      final response = await _apiService.login(email, password);
      
      if (response.statusCode == 200) {
        final token = response.data['token'] ?? response.data['access_token'];
        final userData = response.data['user'];

        if (token != null) {
          _apiService.setToken(token);
          await _storageService.setToken(token);
          
          if (userData != null) {
            final user = User.fromJson(userData);
            await _storageService.setUser(user);
          }
          
          return true;
        }
      }
      return false;
    } catch (e) {
      print('Login error: $e');
      return false;
    }
  }

  Future<bool> register(String nome, String email, String password, String passwordConfirmation) async {
    try {
      final response = await _apiService.register(nome, email, password, passwordConfirmation);
      
      if (response.statusCode == 201 || response.statusCode == 200) {
        // Se o backend faz login automático após registro
        final token = response.data['token'] ?? response.data['access_token'];
        if (token != null) {
          _apiService.setToken(token);
          await _storageService.setToken(token);
          
          final userData = response.data['user'];
          if (userData != null) {
            final user = User.fromJson(userData);
            await _storageService.setUser(user);
          }
        }
        return true;
      }
      return false;
    } catch (e) {
      print('Register error: $e');
      return false;
    }
  }

  Future<void> logout() async {
    try {
      await _apiService.logout();
    } catch (e) {
      print('Logout error: $e');
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
        if (response.statusCode == 200) {
          final user = User.fromJson(response.data['user'] ?? response.data);
          await _storageService.setUser(user);
          return true;
        }
      }
      return false;
    } catch (e) {
      print('Restore session error: $e');
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
