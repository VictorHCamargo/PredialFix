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
  final ApiService _apiService = ApiService();
  final StorageService _storageService = StorageService();
  late final AuthService _authService = AuthService(
    apiService: _apiService,
    storageService: _storageService,
  );

  Future<_AppBootstrap>? _startupFuture;

  @override
  void initState() {
    super.initState();
    WidgetsBinding.instance.addPostFrameCallback((_) {
      if (!mounted) return;
      setState(() {
        _startupFuture = _initializeApp();
      });
    });
  }

  Future<_AppBootstrap> _initializeApp() async {
    try {
      await _storageService.init().timeout(const Duration(seconds: 5));
    } catch (_) {
      // StorageService remains lazy; login can still render if startup storage
      // takes too long during Android cold start.
    }

    final isAuthenticated = await _authService.restoreSession(
      timeout: const Duration(seconds: 5),
    );

    return _AppBootstrap(
      apiService: _apiService,
      storageService: _storageService,
      authService: _authService,
      initialRoute: isAuthenticated ? '/home' : '/login',
    );
  }

  _AppBootstrap _fallbackBootstrap() {
    return _AppBootstrap(
      apiService: _apiService,
      storageService: _storageService,
      authService: _authService,
      initialRoute: '/login',
    );
  }

  @override
  Widget build(BuildContext context) {
    return FutureBuilder<_AppBootstrap>(
      future: _startupFuture,
      builder: (context, snapshot) {
        if (snapshot.hasData) {
          return MyApp(bootstrap: snapshot.data!);
        }

        if (snapshot.hasError) {
          return MyApp(bootstrap: _fallbackBootstrap());
        }

        return const _StartupSplash();
      },
    );
  }
}

class _AppBootstrap {
  final ApiService apiService;
  final StorageService storageService;
  final AuthService authService;
  final String initialRoute;

  const _AppBootstrap({
    required this.apiService,
    required this.storageService,
    required this.authService,
    required this.initialRoute,
  });
}

class _StartupSplash extends StatelessWidget {
  const _StartupSplash();

  @override
  Widget build(BuildContext context) {
    return MaterialApp(
      title: 'PredialFix',
      theme: AppTheme.appTheme,
      home: const Scaffold(
        body: Center(
          child: CircularProgressIndicator(),
        ),
      ),
    );
  }
}

class MyApp extends StatelessWidget {
  final _AppBootstrap bootstrap;

  const MyApp({required this.bootstrap, super.key});

  @override
  Widget build(BuildContext context) {
    final apiService = bootstrap.apiService;

    return MultiProvider(
      providers: [
        Provider<ApiService>.value(value: apiService),
        Provider<StorageService>.value(value: bootstrap.storageService),
        Provider<AuthService>.value(value: bootstrap.authService),
        Provider<ChamadoService>(
          create: (_) => ChamadoService(apiService: apiService),
        ),
        Provider<ReferenceService>(
          create: (_) => ReferenceService(apiService: apiService),
        ),
        Provider<FeedbackService>(
          create: (_) => FeedbackService(apiService: apiService),
        ),
        Provider<EquipamentoService>(
          create: (_) => EquipamentoService(apiService: apiService),
        ),
        Provider<EstoqueService>(
          create: (_) => EstoqueService(apiService: apiService),
        ),
        Provider<OrcamentoService>(
          create: (_) => OrcamentoService(apiService: apiService),
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
