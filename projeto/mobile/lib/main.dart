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

void main() async {
  WidgetsFlutterBinding.ensureInitialized();
  
  // Initialize StorageService
  final storageService = StorageService();
  await storageService.init();
  
  runApp(MyApp(storageService: storageService));
}

class MyApp extends StatefulWidget {
  final StorageService storageService;

  const MyApp({required this.storageService, super.key});

  @override
  State<MyApp> createState() => _MyAppState();
}

class _MyAppState extends State<MyApp> {
  late ApiService _apiService;
  late AuthService _authService;
  String _initialRoute = '/login';
  bool _isLoading = true;

  @override
  void initState() {
    super.initState();
    _initializeApp();
  }

  Future<void> _initializeApp() async {
    _apiService = ApiService();
    _authService = AuthService(
      apiService: _apiService,
      storageService: widget.storageService,
    );

    // Try to restore session
    final isAuthenticated = await _authService.restoreSession();
    
    setState(() {
      _initialRoute = isAuthenticated ? '/home' : '/login';
      _isLoading = false;
    });
  }

  @override
  Widget build(BuildContext context) {
    if (_isLoading) {
      return MaterialApp(
        home: Scaffold(
          body: Center(
            child: CircularProgressIndicator(),
          ),
        ),
      );
    }

    return MultiProvider(
      providers: [
        Provider<ApiService>(create: (_) => _apiService),
        Provider<StorageService>(create: (_) => widget.storageService),
        Provider<AuthService>(create: (_) => _authService),
        Provider<ChamadoService>(
          create: (context) => ChamadoService(apiService: _apiService),
        ),
        Provider<ReferenceService>(
          create: (context) => ReferenceService(apiService: _apiService),
        ),
        Provider<FeedbackService>(
          create: (context) => FeedbackService(apiService: _apiService),
        ),
        Provider<EquipamentoService>(
          create: (context) => EquipamentoService(apiService: _apiService),
        ),
        Provider<EstoqueService>(
          create: (context) => EstoqueService(apiService: _apiService),
        ),
        Provider<OrcamentoService>(
          create: (context) => OrcamentoService(apiService: _apiService),
        ),
      ],
      child: MaterialApp(
        title: 'PredialFix',
        theme: AppTheme.appTheme,
        initialRoute: _initialRoute,
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
