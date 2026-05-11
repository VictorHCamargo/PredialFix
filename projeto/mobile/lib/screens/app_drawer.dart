import 'package:flutter/material.dart';
import '../theme/app_theme.dart';

enum MenuPage { home, createRequest, manage, ratings, support, profile }

class AppDrawer extends StatelessWidget {
  final MenuPage currentPage;
  const AppDrawer({super.key, required this.currentPage});

  static const _drawerItems = [
    _DrawerItem('Home', '/home', MenuPage.home),
    _DrawerItem('Criar Chamado', '/request', MenuPage.createRequest),
    _DrawerItem('Gerenciar', '/manage', MenuPage.manage),
    _DrawerItem('Avaliações', '/ratings', MenuPage.ratings),
    _DrawerItem('Suporte', '/support', MenuPage.support),
    _DrawerItem('Perfil', '/profile', MenuPage.profile),
  ];

  @override
  Widget build(BuildContext context) {
    return Drawer(
      child: SafeArea(
        child: Column(
          children: [
            // Header com informações do usuário
            Container(
              width: double.infinity,
              padding: const EdgeInsets.all(16),
              color: AppTheme.primaryColor,
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  Container(
                    width: 56,
                    height: 56,
                    decoration: BoxDecoration(
                      color: Colors.white.withOpacity(0.25),
                      borderRadius: BorderRadius.circular(28),
                    ),
                    child: const Icon(
                      Icons.person,
                      color: Colors.white,
                      size: 28,
                    ),
                  ),
                  const SizedBox(height: 12),
                  const Text(
                    'Nome do Professor',
                    style: TextStyle(
                      fontSize: 16,
                      fontWeight: FontWeight.w600,
                      color: Colors.white,
                    ),
                  ),
                  const SizedBox(height: 4),
                  const Text(
                    'Docente',
                    style: TextStyle(
                      fontSize: 12,
                      fontWeight: FontWeight.w500,
                      color: Colors.white70,
                    ),
                  ),
                ],
              ),
            ),

            const Divider(height: 1, color: Color(0xFFE0E0E0)),

            // Menu Items
            Expanded(
              child: ListView.builder(
                padding: EdgeInsets.zero,
                itemCount: _drawerItems.length,
                itemBuilder: (context, index) {
                  final item = _drawerItems[index];
                  final selected = item.page == currentPage;

                  return Container(
                    decoration: BoxDecoration(
                      color: selected
                          ? AppTheme.primaryColor.withOpacity(0.1)
                          : Colors.transparent,
                    ),
                    child: ListTile(
                      contentPadding: const EdgeInsets.symmetric(
                        horizontal: 16,
                        vertical: 8,
                      ),
                      title: Text(
                        item.title,
                        style: TextStyle(
                          fontSize: 14,
                          fontWeight: selected
                              ? FontWeight.w600
                              : FontWeight.w500,
                          color: selected
                              ? AppTheme.primaryColor
                              : AppTheme.textPrimaryColor,
                        ),
                      ),
                      onTap: () {
                        Navigator.pop(context);
                        if (selected) return;
                        Navigator.pushReplacementNamed(context, item.route);
                      },
                    ),
                  );
                },
              ),
            ),

            const Divider(height: 1, color: Color(0xFFE0E0E0)),

            // Logout Button
            ListTile(
              contentPadding: const EdgeInsets.symmetric(
                horizontal: 16,
                vertical: 8,
              ),
              title: const Text(
                'Sair',
                style: TextStyle(
                  fontSize: 14,
                  fontWeight: FontWeight.w600,
                  color: AppTheme.errorColor,
                ),
              ),
              onTap: () {
                Navigator.pop(context);
                Navigator.pushNamedAndRemoveUntil(
                  context,
                  '/',
                  (route) => false,
                );
              },
            ),
          ],
        ),
      ),
    );
  }
}

class _DrawerItem {
  final String title;
  final String route;
  final MenuPage page;

  const _DrawerItem(this.title, this.route, this.page);
}
