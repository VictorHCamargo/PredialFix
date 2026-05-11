import 'package:flutter/material.dart';
import '../theme/app_theme.dart';
import 'register_screen.dart';
import 'home_screen.dart';

class LoginScreen extends StatefulWidget {
  const LoginScreen({super.key});

  @override
  State<LoginScreen> createState() => _LoginScreenState();
}

class _LoginScreenState extends State<LoginScreen> {
  final _cpfController = TextEditingController();
  final _passwordController = TextEditingController();
  final _codeController = TextEditingController();
  bool _isLoading = false;

  @override
  void dispose() {
    _cpfController.dispose();
    _passwordController.dispose();
    _codeController.dispose();
    super.dispose();
  }

  void _handleLogin() {
    if (_cpfController.text.isEmpty ||
        _passwordController.text.isEmpty ||
        _codeController.text.isEmpty) {
      ScaffoldMessenger.of(context).showSnackBar(
        const SnackBar(content: Text('Preencha todos os campos')),
      );
      return;
    }

    setState(() => _isLoading = true);

    // Simula uma requisição de login
    Future.delayed(const Duration(seconds: 2), () {
      if (!mounted) return;
      setState(() => _isLoading = false);
      
      // Navega para Home após login bem-sucedido
      Navigator.of(context).pushReplacementNamed('/home');
    });
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: AppTheme.backgroundColor,
      body: Center(
        child: SingleChildScrollView(
          child: Container(
            margin: const EdgeInsets.all(AppTheme.paddingLarge),
            padding: const EdgeInsets.all(AppTheme.paddingXLarge),
            decoration: AppTheme.getCardDecoration(borderRadius: AppTheme.radiusLarge),
            child: Column(
              mainAxisSize: MainAxisSize.min,
              children: [
                // Título
                const Text(
                  'Portal de Chamados',
                  style: TextStyle(
                    fontSize: 28,
                    fontWeight: FontWeight.bold,
                    color: AppTheme.textPrimaryColor,
                  ),
                ),
                const SizedBox(height: 16),

                // Subtítulo
                const Text(
                  'Bem-Vindo ao PredialFix!',
                  style: TextStyle(
                    fontSize: 16,
                    color: AppTheme.textSecondaryColor,
                  ),
                ),
                const SizedBox(height: AppTheme.paddingLarge),

                // Logo SENAI
                Container(
                  height: 60,
                  decoration: BoxDecoration(
                    color: AppTheme.primaryColor,
                    borderRadius: BorderRadius.circular(AppTheme.radiusMedium),
                  ),
                  child: const Center(
                    child: Text(
                      'SENAI',
                      style: TextStyle(
                        color: Colors.white,
                        fontSize: 24,
                        fontWeight: FontWeight.bold,
                        letterSpacing: 2,
                      ),
                    ),
                  ),
                ),
                const SizedBox(height: AppTheme.paddingXLarge),

                // Texto de instrução
                const Text(
                  'Faça seu Login de Docente.',
                  style: TextStyle(
                    fontSize: 14,
                    color: AppTheme.textSecondaryColor,
                  ),
                ),
                const SizedBox(height: AppTheme.paddingLarge),

                // Campo CPF
                TextField(
                  controller: _cpfController,
                  decoration: AppTheme.getFieldDecoration('Digite seu CPF'),
                  keyboardType: TextInputType.number,
                ),
                const SizedBox(height: 16),

                // Campo Senha
                TextField(
                  controller: _passwordController,
                  obscureText: true,
                  decoration: AppTheme.getFieldDecoration('Digite sua Senha'),
                ),
                const SizedBox(height: 16),

                // Campo Código de Entrada
                TextField(
                  controller: _codeController,
                  decoration: AppTheme.getFieldDecoration('Código de Entrada'),
                ),
                const SizedBox(height: AppTheme.paddingXLarge),

                // Botão Entrar
                SizedBox(
                  width: double.infinity,
                  height: 52,
                  child: ElevatedButton(
                    onPressed: _isLoading ? null : _handleLogin,
                    child: _isLoading
                        ? const SizedBox(
                            height: 20,
                            width: 20,
                            child: CircularProgressIndicator(
                              valueColor: AlwaysStoppedAnimation<Color>(
                                Colors.white,
                              ),
                            ),
                          )
                        : const Text('Entrar'),
                  ),
                ),
                const SizedBox(height: AppTheme.paddingLarge),

                // Link para cadastro
                Row(
                  mainAxisAlignment: MainAxisAlignment.center,
                  children: [
                    const Text(
                      'Não tem conta? ',
                      style: TextStyle(color: AppTheme.textSecondaryColor),
                    ),
                    GestureDetector(
                      onTap: () {
                        Navigator.of(context).push(
                          MaterialPageRoute(
                            builder: (context) => const RegisterScreen(),
                          ),
                        );
                      },
                      child: const Text(
                        'Cadastre-se',
                        style: TextStyle(
                          color: AppTheme.primaryColor,
                          fontWeight: FontWeight.bold,
                        ),
                      ),
                    ),
                  ],
                ),
              ],
            ),
          ),
        ),
      ),
    );
  }
}
