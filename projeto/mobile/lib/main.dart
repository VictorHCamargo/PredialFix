import 'package:flutter/material.dart';
import 'screens/home_screen.dart';
import 'screens/login_screen.dart';
import 'screens/manage_screen.dart';
import 'screens/profile_screen.dart';
import 'screens/request_screen.dart';
import 'screens/rating_screen.dart';
import 'screens/register_screen.dart';
import 'screens/support_screen.dart';
import 'theme/app_theme.dart';

void main() {
  runApp(const MyApp());
}

class MyApp extends StatelessWidget {
  const MyApp({super.key});

  @override
  Widget build(BuildContext context) {
    return MaterialApp(
      title: 'PredialFix',
      theme: AppTheme.appTheme,
      initialRoute: '/',
      routes: {
        '/': (context) => const LoginScreen(),
        '/register': (context) => const RegisterScreen(),
        '/home': (context) => const HomeScreen(),
        '/request': (context) => const RequestScreen(),
        '/manage': (context) => const ManageScreen(),
        '/ratings': (context) => const RatingScreen(),
        '/support': (context) => const SupportScreen(),
        '/profile': (context) => const ProfileScreen(),
      },
    );
  }
}
