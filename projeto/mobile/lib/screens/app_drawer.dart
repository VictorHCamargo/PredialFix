import 'package:flutter/material.dart';
import '../theme/app_theme.dart';

enum MenuPage { home, createRequest, manage, ratings, support, profile }

class AppDrawer extends StatelessWidget {
  final MenuPage currentPage;
  const AppDrawer({super.key, required this.currentPage});

  static const _drawerItems = [
    _DrawerItem('Home', '/home', MenuPage.home),
    _DrawerItem('Novo Chamado', '/request', MenuPage.createRequest),
    _DrawerItem('Meus Chamados', '/manage', MenuPage.manage),
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
            Container(
              width: double.infinity,
              padding: const EdgeInsets.all(AppTheme.paddingLarge),
              color: AppTheme.primaryColor,
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  Container(
                    width: 56,
                    height: 56,
                    decoration: BoxDecoration(
                      color: Colors.white.withOpacity(0.2),
                      borderRadius: BorderRadius.circular(28),
                    ),
                    child: const Icon(
                      Icons.person,
                      color: Colors.white,
                      size: 32,
                    ),
                  ),
                  const SizedBox(height: 12),
                  const Text(
                    'João Silva',
                    style: TextStyle(
                      fontSize: 16,
                      fontWeight: FontWeight.bold,
                      color: Colors.white,
                    ),
                  ),
                  const SizedBox(height: 4),
                  const Text(
                    'Docente',
                    style: TextStyle(
                      fontSize: 12,
                      color: Colors.white70,
                    ),
                  ),
                ],
              ),
            ),
            const Divider(height: 1),
            Expanded(
              child: ListView.separated(
                padding: EdgeInsets.zero,
                itemCount: _drawerItems.length,
                separatorBuilder: (context, index) => const Divider(
                  height: 1,
                  indent: 0,
                ),
                itemBuilder: (context, index) {
                  final item = _drawerItems[index];
                  final selected = item.page == currentPage;
                  return ListTile(
                    title: Text(
                      item.title,
                      style: TextStyle(
                        fontWeight: selected ? FontWeight.w700 : FontWeight.w500,
                        color: selected ? AppTheme.primaryColor : AppTheme.textPrimaryColor,
                        fontSize: 14,
                      ),
                    ),
                    selected: selected,
                    selectedTileColor: AppTheme.primaryColor.withOpacity(0.1),
                    leading: selected
                        ? Icon(Icons.check_circle,
                            color: AppTheme.primaryColor)
                        : null,
                    onTap: () {
                      Navigator.pop(context);
                      if (selected) return;
                      Navigator.pushReplacementNamed(context, item.route);
                    },
                  );
                },
              ),
            ),
            const Divider(height: 1),
            ListTile(
              title: const Text(
                'Sair',
                style: TextStyle(
                  fontWeight: FontWeight.w600,
                  fontSize: 14,
                ),
              ),
              leading: const Icon(Icons.logout, color: AppTheme.errorColor),
              onTap: () {
                Navigator.pop(context);
                Navigator.pushNamedAndRemoveUntil(context, '/', (route) => false);
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
