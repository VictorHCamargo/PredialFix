import 'package:flutter/material.dart';
import 'package:provider/provider.dart';
import '../services/auth_service.dart';
import '../theme/app_theme.dart';
import 'app_drawer.dart';

class AdminDashboardScreen extends StatefulWidget {
  const AdminDashboardScreen({super.key});

  @override
  State<AdminDashboardScreen> createState() => _AdminDashboardScreenState();
}

class _AdminDashboardScreenState extends State<AdminDashboardScreen> {
  String userRole = 'aluno';
  bool _loaded = false;

  bool get _canManageEquipamentos {
    return userRole == 'administrador' ||
        userRole == 'gerente_manutencao' ||
        userRole == 'tecnico_manutencao';
  }

  bool get _canManageEstoqueOrOrcamentos {
    return userRole == 'administrador' || userRole == 'gerente_manutencao';
  }

  @override
  void didChangeDependencies() {
    super.didChangeDependencies();
    if (!_loaded) {
      _loaded = true;
      _loadUser();
    }
  }

  Future<void> _loadUser() async {
    final authService = context.read<AuthService>();
    try {
      final user = await authService.getCurrentUser();
      if (!mounted) return;
      setState(() {
        userRole = user?.role ?? 'aluno';
      });
    } catch (_) {
      if (!mounted) return;
      ScaffoldMessenger.of(context).showSnackBar(
        const SnackBar(content: Text('Erro ao carregar usuário')),
      );
    }
  }

  String _roleLabel(String role) {
    switch (role) {
      case 'administrador':
        return 'Administrador';
      case 'gerente_manutencao':
        return 'Gerente de Manutenção';
      case 'tecnico_manutencao':
        return 'Técnico de Manutenção';
      case 'professor':
        return 'Professor';
      default:
        return 'Aluno';
    }
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: Colors.white,
      drawer: const AppDrawer(),
      appBar: AppBar(
        title: const Text('Painel Administrativo'),
        backgroundColor: AppTheme.primaryColor,
      ),
      body: ListView(
        padding: const EdgeInsets.all(16),
        children: [
          Container(
            padding: const EdgeInsets.all(16),
            decoration: BoxDecoration(
              color: AppTheme.primaryColor,
              borderRadius: BorderRadius.circular(12),
            ),
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                const Text(
                  'Painel Administrativo',
                  style: TextStyle(
                    color: Colors.white,
                    fontSize: 18,
                    fontWeight: FontWeight.bold,
                  ),
                ),
                const SizedBox(height: 8),
                Text(
                  'Perfil: ${_roleLabel(userRole)}',
                  style: TextStyle(
                    color: Colors.white.withValues(alpha: 0.9),
                    fontSize: 14,
                  ),
                ),
              ],
            ),
          ),
          const SizedBox(height: 24),
          const Text(
            'Gerenciamento',
            style: TextStyle(
              fontSize: 16,
              fontWeight: FontWeight.bold,
              color: AppTheme.textPrimaryColor,
            ),
          ),
          const SizedBox(height: 12),
          GridView.count(
            crossAxisCount: 2,
            shrinkWrap: true,
            physics: const NeverScrollableScrollPhysics(),
            mainAxisSpacing: 12,
            crossAxisSpacing: 12,
            children: [
              _AdminMenuCard(
                icon: Icons.assignment_turned_in,
                title: 'Chamados',
                subtitle: 'Ver e gerenciar',
                color: const Color(0xFF6366F1),
                onTap: () => Navigator.of(context).pushNamed('/manage'),
              ),
              if (_canManageEquipamentos)
                _AdminMenuCard(
                  icon: Icons.precision_manufacturing,
                  title: 'Equipamentos',
                  subtitle: 'Cadastro e edição',
                  color: const Color(0xFF8B5CF6),
                  onTap: () =>
                      Navigator.of(context).pushNamed('/equipamentos'),
                ),
              if (_canManageEstoqueOrOrcamentos)
                _AdminMenuCard(
                  icon: Icons.inventory_2,
                  title: 'Estoque',
                  subtitle: 'Gerenciar itens',
                  color: const Color(0xFF10B981),
                  onTap: () => Navigator.of(context).pushNamed('/estoque'),
                ),
              if (_canManageEstoqueOrOrcamentos)
                _AdminMenuCard(
                  icon: Icons.receipt_long,
                  title: 'Orçamentos',
                  subtitle: 'Criar e aprovar',
                  color: const Color(0xFFF59E0B),
                  onTap: () => Navigator.of(context).pushNamed('/orcamentos'),
                ),
            ],
          ),
        ],
      ),
    );
  }
}

class _AdminMenuCard extends StatelessWidget {
  final IconData icon;
  final String title;
  final String subtitle;
  final Color color;
  final VoidCallback onTap;

  const _AdminMenuCard({
    required this.icon,
    required this.title,
    required this.subtitle,
    required this.color,
    required this.onTap,
  });

  @override
  Widget build(BuildContext context) {
    return InkWell(
      borderRadius: BorderRadius.circular(8),
      onTap: onTap,
      child: Container(
        decoration: BoxDecoration(
          color: color.withValues(alpha: 0.1),
          border: Border.all(color: color.withValues(alpha: 0.3)),
          borderRadius: BorderRadius.circular(8),
        ),
        padding: const EdgeInsets.all(16),
        child: Column(
          mainAxisAlignment: MainAxisAlignment.spaceBetween,
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            Icon(icon, color: color, size: 28),
            Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                Text(
                  title,
                  style: const TextStyle(
                    fontSize: 14,
                    fontWeight: FontWeight.bold,
                    color: AppTheme.textPrimaryColor,
                  ),
                ),
                const SizedBox(height: 4),
                Text(
                  subtitle,
                  style: const TextStyle(
                    fontSize: 12,
                    color: AppTheme.textSecondaryColor,
                  ),
                ),
              ],
            ),
          ],
        ),
      ),
    );
  }
}
