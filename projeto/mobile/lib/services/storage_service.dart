import 'package:shared_preferences/shared_preferences.dart';
import 'dart:convert';
import '../models/user.dart';

class StorageService {
  static const String _tokenKey = 'auth_token';
  static const String _userKey = 'user_data';

  SharedPreferences? _prefs;
  Future<SharedPreferences>? _prefsFuture;

  Future<void> init() async {
    await _ensurePrefs();
  }

  Future<SharedPreferences> _ensurePrefs() {
    final prefs = _prefs;
    if (prefs != null) {
      return Future.value(prefs);
    }

    final pendingPrefs = _prefsFuture;
    if (pendingPrefs != null) {
      return pendingPrefs;
    }

    final future = SharedPreferences.getInstance().then((prefs) {
      _prefs = prefs;
      return prefs;
    }).catchError((Object error) {
      _prefsFuture = null;
      throw error;
    });

    _prefsFuture = future;
    return future;
  }

  // Token
  Future<void> setToken(String token) async {
    final prefs = await _ensurePrefs();
    await prefs.setString(_tokenKey, token);
  }

  Future<String?> getToken() async {
    final prefs = await _ensurePrefs();
    return prefs.getString(_tokenKey);
  }

  // User
  Future<void> setUser(User user) async {
    final prefs = await _ensurePrefs();
    await prefs.setString(_userKey, jsonEncode(user.toJson()));
  }

  Future<User?> getUser() async {
    final prefs = await _ensurePrefs();
    final userJson = prefs.getString(_userKey);
    if (userJson != null) {
      return User.fromJson(jsonDecode(userJson));
    }
    return null;
  }

  // Clear
  Future<void> clearAll() async {
    final prefs = await _ensurePrefs();
    await prefs.clear();
  }

  Future<void> removeToken() async {
    final prefs = await _ensurePrefs();
    await prefs.remove(_tokenKey);
  }

  Future<void> removeUser() async {
    final prefs = await _ensurePrefs();
    await prefs.remove(_userKey);
  }
}
