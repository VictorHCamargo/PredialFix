import 'package:flutter/material.dart';
import 'package:provider/provider.dart';
import 'screens/home_screen.dart';
import 'screens/login_screen.dart';
import 'screens/manage_screen.dart';
import 'screens/profile_screen.dart';
import 'screens/request_screen.dart';
import 'screens/rating_screen.dart';
import 'screens/register_screen.dart';
import 'screens/support_screen.dart';
import 'screens/admin_dashboard_screen.dart';
import 'screens/equipamentos_screen.dart';
import 'screens/estoque_screen.dart';
import 'screens/orcamentos_screen.dart';
import 'theme/app_theme.dart';
import 'services/storage_service.dart';
import 'services/api_service.dart';
import 'services/auth_service.dart';
import 'services/chamado_service.dart';
import 'services/reference_service.dart';
import 'services/feedback_service.dart';
import 'services/equipamento_service.dart';
import 'services/estoque_service.dart';
import 'services/orcamento_service.dart';

void main() {
  WidgetsFlutterBinding.ensureInitialized();
  runApp(const AppInitializer());
}

class AppInitializer extends StatefulWidget {
  const AppInitializer({super.key});

  @override
  State<AppInitializer> createState() => _AppInitializerState();
}

class _AppInitializerState extends State<AppInitializer> {
  Future<AppBootstrap>? _bootstrapFuture;

  @override
  void initState() {
    super.initState();
    WidgetsBinding.instance.addPostFrameCallback((_) {
      if (!mounted) return;
      setState(() {
        _bootstrapFuture = _initializeApp();
      });
    });
  }

  Future<AppBootstrap> _initializeApp() async {
    final storageService = StorageService();
    await storageService.init().timeout(const Duration(seconds: 5));

    final apiService = ApiService();
    final authService = AuthService(
      apiService: apiService,
      storageService: storageService,
    );

    final isAuthenticated = await authService.restoreSession().timeout(
      const Duration(seconds: 5),
      onTimeout: () => false,
    );

    return AppBootstrap(
      storageService: storageService,
      apiService: apiService,
      authService: authService,
      initialRoute: isAuthenticated ? '/home' : '/login',
    );
  }

  @override
  Widget build(BuildContext context) {
    return FutureBuilder<AppBootstrap>(
      future: _bootstrapFuture,
      builder: (context, snapshot) {
        if (snapshot.hasData) {
          return MyApp(bootstrap: snapshot.data!);
        }

        if (snapshot.hasError) {
          return MaterialApp(
            title: 'PredialFix',
            theme: AppTheme.appTheme,
            home: Scaffold(
              body: Center(
                child: Padding(
                  padding: const EdgeInsets.all(24),
                  child: Column(
                    mainAxisSize: MainAxisSize.min,
                    children: [
                      const Icon(
                        Icons.error_outline,
                        color: Colors.red,
                        size: 40,
                      ),
                      const SizedBox(height: 16),
                      const Text(
                        'Erro ao iniciar o aplicativo',
                        textAlign: TextAlign.center,
                        style: TextStyle(
                          fontSize: 16,
                          fontWeight: FontWeight.w600,
                        ),
                      ),
                      const SizedBox(height: 16),
                      ElevatedButton(
                        onPressed: () {
                          setState(() {
                            _bootstrapFuture = _initializeApp();
                          });
                        },
                        child: const Text('Tentar novamente'),
                      ),
                    ],
                  ),
                ),
              ),
            ),
          );
        }

        return MaterialApp(
          title: 'PredialFix',
          theme: AppTheme.appTheme,
          home: const Scaffold(
            body: Center(child: CircularProgressIndicator()),
          ),
        );
      },
    );
  }
}

class AppBootstrap {
  final StorageService storageService;
  final ApiService apiService;
  final AuthService authService;
  final String initialRoute;

  const AppBootstrap({
    required this.storageService,
    required this.apiService,
    required this.authService,
    required this.initialRoute,
  });
}

class MyApp extends StatelessWidget {
  final AppBootstrap bootstrap;

  factory MyApp({
    AppBootstrap? bootstrap,
    StorageService? storageService,
    Key? key,
  }) {
    if (bootstrap != null) {
      return MyApp._(bootstrap: bootstrap, key: key);
    }

    if (storageService == null) {
      throw ArgumentError('bootstrap ou storageService deve ser informado');
    }

    final apiService = ApiService();
    return MyApp._(
      key: key,
      bootstrap: AppBootstrap(
        storageService: storageService,
        apiService: apiService,
        authService: AuthService(
          apiService: apiService,
          storageService: storageService,
        ),
        initialRoute: '/login',
      ),
    );
  }

  const MyApp._({required this.bootstrap, super.key});

  @override
  Widget build(BuildContext context) {
    return MultiProvider(
      providers: [
        Provider<ApiService>.value(value: bootstrap.apiService),
        Provider<StorageService>.value(value: bootstrap.storageService),
        Provider<AuthService>.value(value: bootstrap.authService),
        Provider<ChamadoService>(
          create: (context) => ChamadoService(apiService: bootstrap.apiService),
        ),
        Provider<ReferenceService>(
          create: (context) =>
              ReferenceService(apiService: bootstrap.apiService),
        ),
        Provider<FeedbackService>(
          create: (context) =>
              FeedbackService(apiService: bootstrap.apiService),
        ),
        Provider<EquipamentoService>(
          create: (context) =>
              EquipamentoService(apiService: bootstrap.apiService),
        ),
        Provider<EstoqueService>(
          create: (context) => EstoqueService(apiService: bootstrap.apiService),
        ),
        Provider<OrcamentoService>(
          create: (context) =>
              OrcamentoService(apiService: bootstrap.apiService),
        ),
      ],
      child: MaterialApp(
        title: 'PredialFix',
        theme: AppTheme.appTheme,
        initialRoute: bootstrap.initialRoute,
        routes: {
          '/': (context) => const LoginScreen(),
          '/login': (context) => const LoginScreen(),
          '/register': (context) => const RegisterScreen(),
          '/home': (context) => const HomeScreen(),
          '/admin': (context) => const AdminDashboardScreen(),
          '/equipamentos': (context) => const EquipamentosScreen(),
          '/estoque': (context) => const EstoqueScreen(),
          '/orcamentos': (context) => const OrcamentosScreen(),
          '/request': (context) => const RequestScreen(),
          '/manage': (context) => const ManageScreen(),
          '/ratings': (context) => const RatingScreen(),
          '/support': (context) => const SupportScreen(),
          '/profile': (context) => const ProfileScreen(),
        },
      ),
    );
  }
}
