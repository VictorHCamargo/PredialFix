import 'package:flutter/material.dart';

import 'app_drawer.dart';

class ProfileScreen extends StatelessWidget {
  const ProfileScreen({super.key});

  @override
  Widget build(BuildContext context) {
    const headerColor = Color(0xFFE63946);

    return Scaffold(
      drawer: const AppDrawer(currentPage: MenuPage.profile),
      appBar: AppBar(
        backgroundColor: headerColor,
        title: const Text('Perfil'),
      ),
      body: const Padding(
        padding: EdgeInsets.all(24),
        child: Center(
          child: Text(
            'Esta é a página de Perfil.',
            style: TextStyle(fontSize: 18, fontWeight: FontWeight.bold),
            textAlign: TextAlign.center,
          ),
        ),
      ),
    );
  }
}
