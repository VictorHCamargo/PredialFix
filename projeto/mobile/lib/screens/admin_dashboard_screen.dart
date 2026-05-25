import 'package:flutter/material.dart';
import 'package:provider/provider.dart';
import '../theme/app_theme.dart';
import '../services/auth_service.dart';
import 'app_drawer.dart';

class AdminDashboardScreen extends StatefulWidget {
  const AdminDashboardScreen({super.key});

  @override
  State<AdminDashboardScreen> createState() => _AdminDashboardScreenState();
}

class _AdminDashboardScreenState extends State<AdminDashboardScreen> {
  late String userRole;

  @override
  void initState() {
    super.initState();
    _loadUser();
  }

  Future<void> _loadUser() async {
    final authService = context.read<AuthService>();
    final user = await authService.getCurrentUser();
    setState(() {
      userRole = user?.role ?? 'admin';
    });
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
      body: SingleChildScrollView(
        child: Padding(
          padding: const EdgeInsets.all(16),
          child: Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              // Cartão de Boas-vindas
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
                      'Bem-vindo ao Painel Administrativo',
                      style: TextStyle(
                        color: Colors.white,
                        fontSize: 18,
                        fontWeight: FontWeight.bold,
                      ),
                    ),
                    const SizedBox(height: 8),
                    Text(
                      'Gerencie usuários, chamados, estoque e equipamentos',
                      style: TextStyle(
                        color: Colors.white.withOpacity(0.9),
                        fontSize: 14,
                      ),
                    ),
                  ],
                ),
              ),
              const SizedBox(height: 24),

              // Títulos das seções
              const Text(
                'Gerenciamento',
                style: TextStyle(
                  fontSize: 16,
                  fontWeight: FontWeight.bold,
                  color: AppTheme.textPrimaryColor,
                ),
              ),
              const SizedBox(height: 12),

              // Grid de opções de admin
              GridView.count(
                crossAxisCount: 2,
                shrinkWrap: true,
                physics: const NeverScrollableScrollPhysics(),
                mainAxisSpacing: 12,
                crossAxisSpacing: 12,
                children: [
                  // Chamados
                  _AdminMenuCard(
                    icon: '📋',
                    title: 'Chamados',
                    subtitle: 'Ver e gerenciar',
                    color: const Color(0xFF6366F1),
                    onTap: () {
                      Navigator.of(context).pushNamed('/manage');
                    },
                  ),

                  // Equipamentos
                  _AdminMenuCard(
                    icon: '⚙️',
                    title: 'Equipamentos',
                    subtitle: 'CRUD completo',
                    color: const Color(0xFF8B5CF6),
                    onTap: () {
                      Navigator.of(context).pushNamed('/equipamentos');
                    },
                  ),

                  // Estoque
                  _AdminMenuCard(
                    icon: '📦',
                    title: 'Estoque Interno',
                    subtitle: 'Gerenciar itens',
                    color: const Color(0xFF10B981),
                    onTap: () {
                      Navigator.of(context).pushNamed('/estoque');
                    },
                  ),

                  // Orçamentos
                  _AdminMenuCard(
                    icon: '💰',
                    title: 'Orçamentos',
                    subtitle: 'Aprovar/visualizar',
                    color: const Color(0xFFF59E0B),
                    onTap: () {
                      Navigator.of(context).pushNamed('/orcamentos');
                    },
                  ),

                  // Locais
                  _AdminMenuCard(
                    icon: '📍',
                    title: 'Locais',
                    subtitle: 'Criar/editar locais',
                    color: const Color(0xFF06B6D4),
                    onTap: () {
                      ScaffoldMessenger.of(context).showSnackBar(
                        const SnackBar(
                          content: Text('Locais - Em breve'),
                        ),
                      );
                    },
                  ),

                  // Tipos de Problema
                  _AdminMenuCard(
                    icon: '🏷️',
                    title: 'Tipos de Problema',
                    subtitle: 'Categorias',
                    color: const Color(0xFFEC4899),
                    onTap: () {
                      ScaffoldMessenger.of(context).showSnackBar(
                        const SnackBar(
                          content: Text('Tipos - Em breve'),
                        ),
                      );
                    },
                  ),
                ],
              ),
              const SizedBox(height: 24),

              // Atalhos úteis
              const Text(
                'Atalhos',
                style: TextStyle(
                  fontSize: 16,
                  fontWeight: FontWeight.bold,
                  color: AppTheme.textPrimaryColor,
                ),
              ),
              const SizedBox(height: 12),

              // Lista de atalhos
              _QuickAccessTile(
                icon: Icons.history,
                title: 'Histórico de Mudanças',
                subtitle: 'Ver todas as alterações de status',
                onTap: () {
                  ScaffoldMessenger.of(context).showSnackBar(
                    const SnackBar(
                      content: Text('Histórico - Em breve'),
                    ),
                  );
                },
              ),
              _QuickAccessTile(
                icon: Icons.assessment,
                title: 'Relatórios',
                subtitle: 'Gerar e visualizar relatórios',
                onTap: () {
                  ScaffoldMessenger.of(context).showSnackBar(
                    const SnackBar(
                      content: Text('Relatórios - Em breve'),
                    ),
                  );
                },
              ),
              _QuickAccessTile(
                icon: Icons.people,
                title: 'Usuários',
                subtitle: 'Gerenciar contas de usuários',
                onTap: () {
                  ScaffoldMessenger.of(context).showSnackBar(
                    const SnackBar(
                      content: Text('Usuários - Em breve'),
                    ),
                  );
                },
              ),
            ],
          ),
        ),
      ),
    );
  }
}

class _AdminMenuCard extends StatelessWidget {
  final String icon;
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
    return GestureDetector(
      onTap: onTap,
      child: Container(
        decoration: BoxDecoration(
          color: color.withOpacity(0.1),
          border: Border.all(color: color.withOpacity(0.3)),
          borderRadius: BorderRadius.circular(12),
        ),
        padding: const EdgeInsets.all(16),
        child: Column(
          mainAxisAlignment: MainAxisAlignment.spaceBetween,
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            Text(
              icon,
              style: const TextStyle(fontSize: 32),
            ),
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

class _QuickAccessTile extends StatelessWidget {
  final IconData icon;
  final String title;
  final String subtitle;
  final VoidCallback onTap;

  const _QuickAccessTile({
    required this.icon,
    required this.title,
    required this.subtitle,
    required this.onTap,
  });

  @override
  Widget build(BuildContext context) {
    return GestureDetector(
      onTap: onTap,
      child: Container(
        margin: const EdgeInsets.only(bottom: 12),
        padding: const EdgeInsets.all(12),
        decoration: BoxDecoration(
          color: Colors.grey[50],
          borderRadius: BorderRadius.circular(8),
          border: Border.all(color: Colors.grey[200]!),
        ),
        child: Row(
          children: [
            Container(
              padding: const EdgeInsets.all(8),
              decoration: BoxDecoration(
                color: AppTheme.primaryColor.withOpacity(0.1),
                borderRadius: BorderRadius.circular(6),
              ),
              child: Icon(icon, color: AppTheme.primaryColor, size: 20),
            ),
            const SizedBox(width: 12),
            Expanded(
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  Text(
                    title,
                    style: const TextStyle(
                      fontSize: 14,
                      fontWeight: FontWeight.w600,
                      color: AppTheme.textPrimaryColor,
                    ),
                  ),
                  Text(
                    subtitle,
                    style: const TextStyle(
                      fontSize: 12,
                      color: AppTheme.textSecondaryColor,
                    ),
                  ),
                ],
              ),
            ),
            Icon(Icons.arrow_forward_ios,
                size: 16, color: Colors.grey[400]),
          ],
        ),
      ),
    );
  }
}
