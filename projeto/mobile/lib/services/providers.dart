import 'package:flutter/material.dart';
import 'package:provider/provider.dart';
import 'api_service.dart';
import 'auth_service.dart';
import 'storage_service.dart';
import 'chamado_service.dart';
import 'reference_service.dart';
import 'feedback_service.dart';

class ServiceProviders {
  static List<dynamic> getProviders() {
    return [
      // Core Services
      Provider<ApiService>(create: (_) => ApiService()),
      Provider<StorageService>(create: (_) => StorageService()),
      
      // Auth Service (depends on ApiService and StorageService)
      Provider<AuthService>(
        create: (context) => AuthService(
          apiService: context.read<ApiService>(),
          storageService: context.read<StorageService>(),
        ),
      ),
      
      // Domain Services
      Provider<ChamadoService>(
        create: (context) => ChamadoService(
          apiService: context.read<ApiService>(),
        ),
      ),
      Provider<ReferenceService>(
        create: (context) => ReferenceService(
          apiService: context.read<ApiService>(),
        ),
      ),
      Provider<FeedbackService>(
        create: (context) => FeedbackService(
          apiService: context.read<ApiService>(),
        ),
      ),
    ];
  }
}
