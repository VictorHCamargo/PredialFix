import 'package:flutter/material.dart';
import 'package:provider/provider.dart';
import '../theme/app_theme.dart';
import '../services/auth_service.dart';
import '../models/user.dart';

class AppDrawer extends StatefulWidget {
  const AppDrawer({super.key});

  @override
  State<AppDrawer> createState() => _AppDrawerState();
}

class _AppDrawerState extends State<AppDrawer> {
  User? _user;
  bool _loaded = false;

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
        _user = user;
      });
    } catch (_) {}
  }

  @override
  Widget build(BuildContext context) {
    return Drawer(
      child: SafeArea(
        child: Column(
          children: [
            Container(
              width: double.infinity,
              padding: const EdgeInsets.all(16),
              color: AppTheme.primaryColor,
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  CircleAvatar(
                    radius: 28,
                    backgroundColor: Colors.white,
                    child: Text(
                      (_user?.nome ?? 'U')[0].toUpperCase(),
                      style: const TextStyle(
                        fontSize: 24,
                        fontWeight: FontWeight.bold,
                        color: AppTheme.primaryColor,
                      ),
                    ),
                  ),
                  const SizedBox(height: 12),
                  Text(
                    _user?.nome ?? 'Usuário',
                    style: const TextStyle(
                      fontSize: 16,
                      fontWeight: FontWeight.w600,
                      color: Colors.white,
                    ),
                  ),
                  const SizedBox(height: 4),
                  Text(
                    _user?.email ?? '',
                    style: const TextStyle(fontSize: 12, color: Colors.white70),
                  ),
                ],
              ),
            ),
            const Divider(height: 1),
            Expanded(
              child: ListView(
                padding: EdgeInsets.zero,
                children: [
                  _buildDrawerItem(
                    icon: Icons.home,
                    title: 'Home',
                    onTap: () {
                      Navigator.pop(context);
                      Navigator.pushReplacementNamed(context, '/home');
                    },
                  ),
                  _buildDrawerItem(
                    icon: Icons.add_circle,
                    title: 'Novo Chamado',
                    onTap: () {
                      Navigator.pop(context);
                      Navigator.pushNamed(context, '/request');
                    },
                  ),
                  _buildDrawerItem(
                    icon: Icons.list,
                    title: 'Meus Chamados',
                    onTap: () {
                      Navigator.pop(context);
                      Navigator.pushNamed(context, '/manage');
                    },
                  ),
                  _buildDrawerItem(
                    icon: Icons.admin_panel_settings,
                    title: 'Painel Admin',
                    onTap: () {
                      Navigator.pop(context);
                      Navigator.pushNamed(context, '/admin');
                    },
                  ),
                  _buildDrawerItem(
                    icon: Icons.star,
                    title: 'Avaliar',
                    onTap: () {
                      Navigator.pop(context);
                      Navigator.pushNamed(context, '/ratings');
                    },
                  ),
                  _buildDrawerItem(
                    icon: Icons.support_agent,
                    title: 'Suporte',
                    onTap: () {
                      Navigator.pop(context);
                      Navigator.pushNamed(context, '/support');
                    },
                  ),
                  _buildDrawerItem(
                    icon: Icons.person,
                    title: 'Perfil',
                    onTap: () {
                      Navigator.pop(context);
                      Navigator.pushNamed(context, '/profile');
                    },
                  ),
                ],
              ),
            ),
            const Divider(height: 1),
            _buildDrawerItem(
              icon: Icons.logout,
              title: 'Sair',
              isRed: true,
              onTap: () async {
                final authService = context.read<AuthService>();
                await authService.logout();
                if (!mounted) return;
                Navigator.of(context).pushReplacementNamed('/login');
              },
            ),
          ],
        ),
      ),
    );
  }

  Widget _buildDrawerItem({
    required IconData icon,
    required String title,
    required VoidCallback onTap,
    bool isRed = false,
  }) {
    return ListTile(
      leading: Icon(icon, color: isRed ? Colors.red : AppTheme.primaryColor),
      title: Text(
        title,
        style: TextStyle(
          fontSize: 14,
          fontWeight: FontWeight.w500,
          color: isRed ? Colors.red : AppTheme.textPrimaryColor,
        ),
      ),
      onTap: onTap,
    );
  }
}
