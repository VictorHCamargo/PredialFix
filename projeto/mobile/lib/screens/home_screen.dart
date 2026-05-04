import 'package:flutter/material.dart';

import 'app_drawer.dart';

class HomeScreen extends StatelessWidget {
  const HomeScreen({super.key});

  @override
  Widget build(BuildContext context) {
    const headerColor = Color(0xFFE63946);

    return Scaffold(
      drawer: const AppDrawer(currentPage: MenuPage.home),
      appBar: AppBar(
        backgroundColor: headerColor,
        title: const Text('Home'),
      ),
      body: const Padding(
        padding: EdgeInsets.all(24),
        child: Center(
          child: Text(
            'Bem-vindo à página Home.',
            style: TextStyle(fontSize: 18, fontWeight: FontWeight.bold),
            textAlign: TextAlign.center,
          ),
        ),
      ),
    );
  }
}
