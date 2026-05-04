import 'package:flutter/material.dart';

import 'app_drawer.dart';

class SupportScreen extends StatelessWidget {
  const SupportScreen({super.key});

  @override
  Widget build(BuildContext context) {
    const headerColor = Color(0xFFE63946);

    return Scaffold(
      drawer: const AppDrawer(currentPage: MenuPage.support),
      appBar: AppBar(
        backgroundColor: headerColor,
        title: const Text('Suporte'),
      ),
      body: const Padding(
        padding: EdgeInsets.all(24),
        child: Center(
          child: Text(
            'Esta é a página de Suporte.',
            style: TextStyle(fontSize: 18, fontWeight: FontWeight.bold),
            textAlign: TextAlign.center,
          ),
        ),
      ),
    );
  }
}
