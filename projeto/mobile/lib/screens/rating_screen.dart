import 'package:flutter/material.dart';

import 'app_drawer.dart';

class RatingScreen extends StatelessWidget {
  const RatingScreen({super.key});

  @override
  Widget build(BuildContext context) {
    const headerColor = Color(0xFFE63946);

    return Scaffold(
      drawer: const AppDrawer(currentPage: MenuPage.ratings),
      appBar: AppBar(
        backgroundColor: headerColor,
        title: const Text('Avaliações'),
      ),
      body: const Padding(
        padding: EdgeInsets.all(24),
        child: Center(
          child: Text(
            'Esta é a página de Avaliações.',
            style: TextStyle(fontSize: 18, fontWeight: FontWeight.bold),
            textAlign: TextAlign.center,
          ),
        ),
      ),
    );
  }
}
