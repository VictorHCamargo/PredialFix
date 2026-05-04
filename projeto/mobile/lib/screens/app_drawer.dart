import 'package:flutter/material.dart';

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
            Expanded(
              child: ListView.separated(
                padding: EdgeInsets.zero,
                itemCount: _drawerItems.length,
                separatorBuilder: (context, index) => const Divider(height: 1),
                itemBuilder: (context, index) {
                  final item = _drawerItems[index];
                  final selected = item.page == currentPage;
                  return ListTile(
                    title: Text(
                      item.title,
                      style: TextStyle(
                        fontWeight: selected ? FontWeight.w700 : FontWeight.w500,
                        color: selected ? Colors.black : Colors.black87,
                      ),
                    ),
                    selected: selected,
                    selectedTileColor: const Color(0xFFF2F2F2),
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
                style: TextStyle(fontWeight: FontWeight.w600),
              ),
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
